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
 * @package InstagramAmAPI
 */
class Request
{
    protected $instagram_url = "https://instagram.com";
    protected $curl;
    protected $data;
    protected $storage = [];
    protected $cookie_file;

    public function __construct($data = [])
    {
        $this->curl = null;
        $this->data = $data;
        $this->storage = new CookieHelper();
    }


    public function prepareRequest()
    {

    }

    /**
     * @return Response
     */
    public function send()
    {

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