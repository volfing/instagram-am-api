<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 27/04/2018
 * Time: 17:54
 */

namespace InstagramAmAPI\Transport;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use InstagramAmAPI\Exception\BadResponseException;
use InstagramAmAPI\Exception\ChallengeRequiredException;
use InstagramAmAPI\Exception\ForbiddenInstagramException;
use InstagramAmAPI\Exception\InstagramException;
use InstagramAmAPI\Exception\InvalidProxyException;
use InstagramAmAPI\Exception\InvalidRequestMethodException;
use InstagramAmAPI\Exception\NotFoundInstagramException;
use InstagramAmAPI\Exception\TooManyRequestsException;

/**
 * Class GuzzleTransport
 * @package InstagramAmAPI\Transport
 */
class GuzzleTransport implements ITransport
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';

    /** @var  Client */
    private $client;
    /** @var  Response */
    private $response;
    private $url;
    private $method = 'POST';
    private $options;

    public function __construct()
    {
        $this->method = self::METHOD_GET;
        $this->options = [];
    }


    public function init()
    {
    }

    public function setProxy(string $proxy)
    {
        $this->options['proxy'] = $proxy;
    }

    public function setHeaders($headers)
    {
        $result_headers = [];
        foreach ($headers as $key => $value) {
            if (is_array($value)) {
                $full_value = [];
                foreach ($value as $key_inner => $value_inner) {
                    if (!empty($value_inner)) {
                        $full_value[] .= $key_inner . "=" . $value_inner . "; ";
                    }
                }
                $result_headers[$key] = $full_value;
            } else {
                if (!empty($value)) {
                    $result_headers[$key] = $value;
                }
            }
        }
        $this->options['headers'] = $result_headers;
    }

    public function setPost($flag = false)
    {
        if ($flag) {
            $this->method = self::METHOD_POST;
        }
    }

    public function setPostData($post_data)
    {
        $this->options['form_params'] = $post_data;
    }

    public function setMultipartData($post_data)
    {
        $this->options['multipart'] = $post_data;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function setTimeout($timeout = 15)
    {
        $this->options['timeout'] = $timeout;
    }

    public function close()
    {
    }

    public function getCookie()
    {
        $cookieJar = $this->client->getConfig('cookies');
        $cookiesJarArray = $cookieJar->toArray();
        $cookies = [];
        foreach ($cookiesJarArray as $cookie) {
            $cookies[$cookie['Name']] = $cookie['Value'];
        }
        return $cookies;
    }

    /**
     * @return bool|string
     * @throws ChallengeRequiredException
     * @throws ForbiddenInstagramException
     * @throws InstagramException
     * @throws InvalidProxyException
     * @throws InvalidRequestMethodException
     * @throws NotFoundInstagramException
     * @throws TooManyRequestsException
     */
    public function send()
    {
        try {
            $this->client = new Client(['cookies' => true]);
            $this->response = $this->client->request($this->method, $this->url, $this->options);

        } catch (RequestException $e) {
            $http_code = $e->getCode();
            $exception_message = '';
            $response = $e->getResponse();
            if (!is_null($response)) {
                $exception_message = $response->getBody()->getContents();
            }
            switch ($e->getCode()) {
                case 200:
//                ok
                    break;
                case 400:
                    $response = json_decode($e->getResponse()->getBody()->getContents(), true);
                    if ($response['message'] == 'checkpoint_required') {
                        $exception = new ChallengeRequiredException("ChallengeRequired. " . $exception_message);
                        $exception->challengeInfo = $response;

                        throw $exception;
                    }
                    $exception = new InstagramException($e->getMessage());
                    $exception->body = $e->getResponse()->getBody();

                    throw $exception;
                    break;
                case 403:
                    $contents = $e->getResponse()->getBody()->getContents();
                    if ($contents == "Please wait a few minutes before you try again.") {
                        throw new TooManyRequestsException("TooManyRequests. " . $exception_message);
                    }
                    $response_array = json_decode($contents, true);
                    if (!empty($response_array) && !empty($response_array['message']) && ($response_array['message'] == "Please wait a few minutes before you try again.")) {
                        throw new TooManyRequestsException("TooManyRequests. " . $exception_message);
                    }
                    throw new ForbiddenInstagramException("InvalidInputParams. " . $exception_message);
                    break;
                case 404:
                    $contents = $e->getResponse()->getBody()->getContents();
                    if ($contents == 'This action was blocked. Please try again later.') {
                        throw new TooManyRequestsException("TooManyRequests. " . $exception_message);
                    }
                    if ($contents == 'This action was blocked. Please try again later.') {
                        throw new TooManyRequestsException("TooManyRequests. " . $exception_message);
                    }
                    throw new NotFoundInstagramException("NotFound. " . $exception_message);
                    break;
                case 405:
                    throw new InvalidRequestMethodException("InvalidRequestMethod. " . $exception_message);
                    break;
                case 407:
                    throw new InvalidProxyException("InvalidProxy. " . $exception_message);
                    break;
                case 429:
                    throw new TooManyRequestsException("TooManyRequests. " . $exception_message);
                    break;
                default:
                    throw new InstagramException($e->getMessage() . " " . $exception_message);
                    break;
            }
        }
        if (!empty($this->response)) {
            return $this->response->getBody()->getContents();
        }
        throw new BadResponseException("BadResponse. Empty response.");
    }

    public function getRequestInfo()
    {
        return [
            'http_code' => $this->response->getStatusCode()
        ];
    }

    /**
     * Добавляет вложение (обычно файл) в тело запроса
     * @param $attachment
     */
    public function addAttachment($attachment)
    {
        $attachment = array_filter($attachment);
        if (!empty($attachment)) {
            $this->options['multipart'][] = $attachment;
        }
    }
}