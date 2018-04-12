<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 11/04/2018
 * Time: 20:56
 */

namespace InstagramAmAPI;


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
        curl_setopt($this->curl, CURLOPT_HEADER, $this->headers);
    }

    /**
     * @param null|array $data
     */
    protected function setPost($data = null)
    {
        if (!empty($data)) {
            curl_setopt($this->curl, CURLOPT_POST, true);
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
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     * Шаблонный метод
     *
     * @return array
     */
    public function send()
    {
        $this->init();
        $this->preRequest();
        $this->initHeaders();
        $result = curl_exec($this->curl);
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