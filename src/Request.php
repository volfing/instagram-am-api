<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 11/04/2018
 * Time: 20:56
 */

namespace InstagramAmAPI;

use InstagramAmAPI\Storage\CookieManager;

/**
 * Class Request
 * @property array $data
 * @property $curl
 * @property Client $client
 * @package InstagramAmAPI
 */
class Request
{
    protected $instagram_url = "https://instagram.com";
    protected $curl;
    protected $data;
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
        $this->client = $client;
    }


    public function prepareRequest()
    {

        $this->curl = curl_init($this->instagram_url);
        curl_setopt($this->curl, CURLOPT_HEADER, false);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, "");

    }

    /**
     * @return Response
     */
    public function send()
    {
        $this->prepareRequest();
        $result = curl_exec($this->curl);
        $cookie = curl_getinfo($this->curl, CURLINFO_COOKIELIST);
        $this->client->cookie->saveCurlCookie($cookie);

    }

    public function __destruct()
    {
        if (!is_null($this->curl)) {
            curl_close($this->curl);
        }
    }


}