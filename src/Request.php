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

    const INSTAGRAM_URL = 'https://www.instagram.com/';
    const API_URL = 'https://www.instagram.com/query/';
    const GRAPHQL_API_URL = 'https://www.instagram.com/graphql/query/';

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
     * @param null|array $params
     */
    protected function init($url = "", $params = null)
    {
        $this->client->cookie->loadCookie();
        $full_url = $this->instagram_url . $url;

        if (!is_null($params)) {
            $full_url .= "?";
            foreach ($params as $param_key => $param_value) {
                $params[$param_key] = $param_key . "=" . $param_value;
            }
            $full_url .= implode("&", $params);
        }

        $this->curl = curl_init($full_url);
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 15);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, "");

        if (!empty($this->client->getProxy())) {
            curl_setopt($this->curl, CURLOPT_PROXY, $this->client->getProxy());
            curl_setopt($this->curl, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        }

    }

    /**
     * Первый запрос к сервису для получения csrftoken & rhx_gis
     *
     * @throws ForbiddenInstagramException
     * @throws InstagramException
     * @throws NotFoundInstagramException
     */
    private function initRequest()
    {
        $this->client->cookie->loadCookie();
        $this->curl = curl_init(self::INSTAGRAM_URL);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, "");
        $result = curl_exec($this->curl);
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
        $rhx_gis = $this->extractRhxGis($result);
        $this->saveCookie();
        if (empty($rhx_gis)) {
            throw new InstagramException("Unable to get rhx_gis from init request.");
        }
        if (empty($this->client->cookie->getCookie('csrftoken'))) {
            throw new InstagramException("Unable to get csrftoken from init request.");
        }
        $this->client->cookie->setCookie("rhx_gis", $rhx_gis);
        $this->client->cookie->saveCookie();
        curl_close($this->curl);
    }

    /**
     * Установка нужных заголовков
     */
    protected function initHeaders()
    {
        if (empty($this->headers)) {
            return;
        }
        /** Удаляем пустые заголовки */
        $this->headers = array_filter($this->headers);

        $result_headers = [];
        foreach ($this->headers as $key => $value) {
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
            $this->setPost(true);
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
     * Ищет rhx_gis на странице
     *
     * @param $html_body
     * @return bool
     */
    protected function extractRhxGis($html_body)
    {
        $success_search = preg_match_all("/\"rhx_gis\"\:\"([a-z0-9]+)\"/", $html_body, $matched);
        if ($success_search) {
            return $matched[1][0];
        }
        return false;
    }

    /**
     * Подписывает запрос
     *
     * @param array $query
     * @param null|string $endpoint
     * @return null
     */
    protected function addQuerySignature($query, $endpoint = null)
    {
        if (
            !empty($this->client->cookie->getCookie("rhx_gis"))
            && !empty($query['query_hash'])
            && !empty($query['variables'])
        ) {
            $variables = $query['variables'];
        } elseif (
            !empty($this->client->cookie->getCookie("rhx_gis"))
            && isset($query["__a"])
            && $endpoint
        ) {
            $variables = str_replace($this->instagram_url, "", $endpoint);
            $variables = str_replace("?__a=1", "", $variables);
        } elseif (
            !empty($this->client->cookie->getCookie("rhx_gis"))
            && isset($query["q"])
            && $endpoint
        ) {
            $variables = $query["q"];
        } else {
            return false;
        }
        $str_for_hash = $this->client->cookie->getCookie('rhx_gis') . ":" . $variables;
        $signature = md5($str_for_hash);
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
        $this->client->cookie->loadCookie();
        if (empty($this->client->cookie->getCookie("csrftoken"))) {
            $this->initRequest();
        }

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
                break;
            case 403:
                throw new ForbiddenInstagramException("Http code: {$http_code}");
                break;
            case 404:
                throw new NotFoundInstagramException("Http code: {$http_code}");
                break;
            default:
                throw new InstagramException("Http code: {$http_code}");
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