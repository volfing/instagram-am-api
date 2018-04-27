<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 27/04/2018
 * Time: 17:54
 */

namespace InstagramAmAPI\Transport;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

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
    private $proxy;
    private $options;

    public function __construct()
    {
        $this->method = self::METHOD_GET;
    }


    public function init()
    {
    }

    public function setProxy(string $proxy)
    {
        $this->proxy = $proxy;
    }

    public function setHeaders($headers)
    {
        $this->options['headers'] = $headers;
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
        $cookieJar->toArray();
        return $cookieJar;
    }

    public function send()
    {
        $this->client = new Client(['cookies' => true]);
        $this->response = $this->client->request($this->method, $this->url, $this->options);
        return $this->response->getBody();
    }

    public function getRequestInfo()
    {
        return [
            'http_code' => $this->response->getStatusCode()
        ];
    }
}