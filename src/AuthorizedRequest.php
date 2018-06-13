<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 12/04/2018
 * Time: 14:14
 */

namespace InstagramAmAPI;

/**
 * Класс авторизованного запроса
 * Куки отправляются с сессией
 *
 * Class AuthorizedRequest
 * @package InstagramAmAPI
 */
class AuthorizedRequest extends Request
{
    /**
     * @inheritdoc
     */
    public function __construct(Client $client, array $data = [])
    {
        parent::__construct($client, $data);
    }

    /**
     * @inheritdoc
     */
    protected function init($url = "", $params = null)
    {
        parent::init($url, $params);
        $headers = [
            "Cookie" => [
                "rur" => $this->client->cookie->getCookie("rur"),
                "csrftoken" => $this->client->cookie->getCookie("csrftoken"),
                "mid" => $this->client->cookie->getCookie("mid"),
                "sessionid" => $this->client->cookie->getCookie("sessionid"),
                "ds_user_id" => $this->client->cookie->getCookie("ds_user_id"),
                "shbid" => $this->client->cookie->getCookie("shbid")
            ],
            "Referer" => "https://www.instagram.com/",
            'Authority' => 'www.instagram.com',
            'Origin' => 'https://www.instagram.com',
            "x-csrftoken" => $this->client->cookie->getCookie("csrftoken"),
            "x-instagram-ajax" => $this->client->cookie->getCookie("x_instagram_ajax"),
            "x-requested-with" => "XMLHttpRequest",
            "Content-Type" => "application/x-www-form-urlencoded",
            "User-Agent" => $this->client->getUserAgent()
        ];
        $this->setHeaders($headers);
    }


}