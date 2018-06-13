<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 11/04/2018
 * Time: 21:00
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\Request as BaseRequest;

/**
 * Class RequestLogin
 * @package InstagramAmAPI\Request
 */
class RequestLogin extends BaseRequest
{
    private $login_url = "/accounts/login/ajax/";

    protected function init($url = "", $params = null)
    {
        parent::init($this->login_url);
        $this->setPost(true);
        $this->setPostData($this->data);
        $headers = [
            "Cookie" => [
                "rur" => $this->client->cookie->getCookie("rur"),
                "csrftoken" => $this->client->cookie->getCookie("csrftoken"),
                "mid" => $this->client->cookie->getCookie("mid")
            ],
            "Referer" => "https://www.instagram.com/",
            "x-csrftoken" => $this->client->cookie->getCookie("csrftoken"),
            "x-instagram-ajax" => $this->client->cookie->getCookie("x_instagram_ajax"),
            "x-requested-with" => "XMLHttpRequest",
            "Content-Type" => "application/x-www-form-urlencoded",
        ];
        $this->setHeaders($headers);
    }

    /**
     * {
     *      "authenticated": false,
     *      "user": true,
     *      "status": "ok"
     * }
     */
    public function send()
    {
        return parent::send();
    }

}