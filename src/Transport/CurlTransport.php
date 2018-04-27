<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 25/04/2018
 * Time: 16:48
 */

namespace InstagramAmAPI\Transport;

use InstagramAmAPI\Exception\ForbiddenInstagramException;
use InstagramAmAPI\Exception\InstagramException;
use InstagramAmAPI\Exception\InvalidProxyException;
use InstagramAmAPI\Exception\InvalidRequestMethodException;
use InstagramAmAPI\Exception\NotFoundInstagramException;
use InstagramAmAPI\Exception\TooManyRequestsException;

/**
 * Class CurlTransport
 * @package InstagramAmAPI\Transport
 */
class CurlTransport implements ITransport
{
    private $curl;
    private $url;

    public function __construct()
    {
    }

    /**
     * @param string $proxy
     */
    public function setProxy(string $proxy)
    {
        curl_setopt($this->curl, CURLOPT_PROXY, $proxy);
    }

    /**
     * Устанавливает заголовки
     * @param $headers
     */
    public function setHeaders($headers)
    {

        $result_headers = [];
        foreach ($headers as $key => $value) {
            if (is_array($value)) {
                $full_value = "";
                foreach ($value as $key_inner => $value_inner) {
                    if (!empty($value_inner)) {
                        $full_value .= $key_inner . "=" . $value_inner . "; ";
                    }
                }
                $result_headers[] = $key . ": " . $full_value;
            } else {
                if (!empty($value)) {
                    $result_headers[] = $key . ": " . $value;
                }
            }
        }
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $result_headers);
    }

    /**
     * Устанавливает тип запроса
     *
     * @param bool $flag
     */
    public function setPost($flag = false)
    {
        if ($flag) {
            curl_setopt($this->curl, CURLOPT_POST, true);
        }
    }

    /**
     * Устанавливает данные тела POST запроса
     * @param $post_data
     */
    public function setPostData($post_data)
    {
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($post_data));
    }

    /**
     * Устанавливает url
     * @param $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Устанавливает timeout соединения
     * @param int $timeout
     */
    public function setTimeout($timeout = 15)
    {
        curl_setopt($this->curl, CURLOPT_TIMEOUT, $timeout);
    }

    /**
     * Закрывает соединение
     */
    public function close()
    {
        curl_close($this->curl);
    }

    /**
     * Возвращает массив cookie
     * @return mixed
     */
    public function getCookie()
    {
        $cookieJar = curl_getinfo($this->curl, CURLINFO_COOKIELIST);
        $cookies = [];

        foreach ($cookieJar as $cookie_str) {
            $cookie_parts = explode("	", $cookie_str);
            if (!empty($cookie_parts[6])) {
                $cookies[$cookie_parts[5]] = $cookie_parts[6];
            }
        }
        return $cookies;
    }

    /**
     * Выполняет запрос
     * @return mixed
     * @throws ForbiddenInstagramException
     * @throws InstagramException
     * @throws InvalidRequestMethodException
     * @throws NotFoundInstagramException
     * @throws TooManyRequestsException
     */
    public function send()
    {
        $result = curl_exec($this->curl);

        $http_code = curl_getinfo($this->curl)['http_code'];
        switch ($http_code) {
            case 0:
                throw new InvalidProxyException("InvalidProxy");
            case 200:
//                ok
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
            case 429:
                throw new TooManyRequestsException();
                break;
            default:
                throw new InstagramException("Http code: {$http_code}");
                break;

        }
        return $result;
    }

    public function init()
    {
        $this->curl = curl_init($this->url);
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, "");
        $this->setTimeout(15);
    }

    /**
     * Возвращает данные о запросе
     * @return mixed
     */
    public function getRequestInfo()
    {
        return curl_getinfo($this->curl);
    }
}