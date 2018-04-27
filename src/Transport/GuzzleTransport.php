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
            switch ($e->getCode()) {
                case 200:
//                ok
                    break;
                case 400:
                    $response = json_decode($e->getResponse()->getBody()->getContents(), true);
                    if ($response['message'] == 'checkpoint_required') {
                        throw new ChallengeRequiredException("ChallengeRequired");
                    }
                    throw new InstagramException("Http code: {$http_code}");
                    break;
                case 403:
                    throw new ForbiddenInstagramException("InvalidInputParams");
                    break;
                case 404:
                    throw new NotFoundInstagramException("NotFound");
                    break;
                case 405:
                    throw new InvalidRequestMethodException("InvalidRequestMethod");
                    break;
                case 407:
                    throw new InvalidProxyException("InvalidProxy");
                    break;
                case 429:
                    throw new TooManyRequestsException("TooManyRequests");
                    break;
                default:
                    throw new InstagramException("Http code: {$http_code}");
                    break;
            }
        }
        $body = $this->response->getBody()->getContents();
        return $body;
    }

    public function getRequestInfo()
    {
        return [
            'http_code' => $this->response->getStatusCode()
        ];
    }
}