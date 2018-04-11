<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 11/04/2018
 * Time: 20:56
 */

namespace InstagramAmAPI;

use InstagramAmAPI\Storage\CookieHelper;

/**
 * Class Request
 * @property array $data
 * @property $curl
 * @property CookieHelper $storage
 * @package InstagramAmAPI
 */
class Request
{
    protected $instagram_url = "https://instagram.com";
    protected $curl;
    protected $data;
    protected $storage = [];

    public function __construct($data = [])
    {
        $this->curl = null;
        $this->data = $data;
        $this->storage = new CookieHelper();
    }


    public function prepareRequest()
    {

        $this->storage->loadCookie();
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
        foreach ($cookie as $cookie_str) {
            $cookie_parts = explode("	", $cookie_str);
            $this->storage->setCookie($cookie_parts[5], $cookie_parts[6]);
        }
        $this->storage->saveCookie();

    }

    public function __destruct()
    {
        if (!is_null($this->curl)) {
            curl_close($this->curl);
        }
    }

    /**
     * @param mixed $cookie_file
     */
    public function setCookieFile($cookie_file)
    {
        $this->storage->setCookieFile($cookie_file);

    }


}