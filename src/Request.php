<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 11/04/2018
 * Time: 20:56
 */

namespace InstagramAmAPI;

use InstagramAmAPI\Exception\InstagramException;
use InstagramAmAPI\Exception\ForbiddenInstagramException;
use InstagramAmAPI\Exception\NotFoundInstagramException;


/**
 * Class Request
 * @property array $data
 * @property array $headers
 * @property $curl
 * @property Client $client
 * @package InstagramAmAPI
 */
class Request
{
    protected $instagram_url = "https://instagram.com";
    protected $curl;
    protected $data;
    private $headers;
    protected $client;

    /**
     * Request constructor.
     * @param Client $client
     * @param array $data
     */
    public function __construct($client, $data = [])
    {
        $this->curl = null;
        $this->data = $data;
        $this->headers = false;
        $this->client = $client;

    }

    /**
     * Создание curl подключения
     *
     * @param string $url
     *
     */
    protected function init($url = "")
    {
        $this->client->cookie->loadCookie();
        $full_url = $this->instagram_url . $url;
        $this->curl = curl_init($full_url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, "");

    }

    /**
     * Установка нужных заголовков
     */
    protected function initHeaders()
    {
        $result_headers = [];
        foreach ($this->headers as $key => $value) {
            if (is_array($value)) {
                $full_value = "";
                foreach ($value as $key_inner => $value_inner) {
                    $full_value .= $key_inner . "=" . $value_inner . "; ";
                }
                $result_headers[] = $key . ": " . $full_value;
            } else {
                $result_headers[] = $key . ": " . $value;
            }
        }
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $result_headers);
    }

    /**
     * @param bool $post_flag
     */
    protected function setPost($post_flag)
    {
        if ($post_flag) {
            curl_setopt($this->curl, CURLOPT_POST, true);
        }
    }


    /**
     * @param array $data
     */
    protected function setPostData($data)
    {
        if (!empty($data)) {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($data));
        }
    }

    /**
     * Действия перед отправкой запроса
     */
    protected function preRequest()
    {
        //        some code
    }


    /**
     * Действия после отправки запроса
     */
    protected function postRequest()
    {
        //        some code
    }


    /**
     * Сохраняем куки
     */
    protected function saveCookie()
    {
        $cookie = curl_getinfo($this->curl, CURLINFO_COOKIELIST);
        $this->client->cookie->saveCurlCookie($cookie);
    }

    /**
     * @param array $headers
     */
    protected function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     * Добавляет заголовок
     *
     * @param $header_name
     * @param $header_value
     */
    protected function addHeader($header_name, $header_value)
    {
        $this->headers[$header_name] = $header_value;
    }

    /**
     * Подписывает запрос
     *
     * @param array $query
     * @param string $url
     * @param null|string $endpoint
     * @return null
     */
    protected function addQuerySignature($query, $url, $endpoint = null)
    {
        if (
            !empty($this->client->cookie->getCookie("rhx_gis"))
            && !empty($this->headers['query_hash'])
            && !empty($this->headers['variables'])
        ) {
            $variables = $this->headers['variables'];
        } elseif (
            !empty($this->client->cookie->getCookie("rhx_gis"))
            && in_array("__a", $query)
            && $endpoint
        ) {
//            TODO: добавить реализацию
//            variables = compat_urllib_parse_urlparse(endpoint).path
        } else {
            return false;
        }
        $signature = md5($this->client->cookie->getCookie('rhx_gis') . ":" . $this->client->cookie->getCookie('csfrtoken') . ":" . $variables);
        if (!empty($signature)) {
            $this->addHeader("X-Instagram-GIS", $signature);
            return true;
        }
        return false;
    }

    /**
     * Шаблонный метод
     * @return array
     * @throws InstagramException
     * @throws ForbiddenInstagramException
     * @throws NotFoundInstagramException
     */
    public function send()
    {
        $this->init();
        $this->preRequest();
        $this->initHeaders();
        $result = curl_exec($this->curl);
        var_dump($result);
        $http_code = curl_getinfo($this->curl)['http_code'];
        switch ($http_code) {
            case 200:
//                ok
                break;
            case 403:
                throw new ForbiddenInstagramException();
                break;
            case 404:
                throw new NotFoundInstagramException();
                break;
            default:
                throw new InstagramException();
                break;

        }
        $this->saveCookie();
        $this->postRequest();
        $result = json_decode($result, true);
        return $result;

    }

    public function __destruct()
    {
        if (!is_null($this->curl)) {
            curl_close($this->curl);
        }
    }

}