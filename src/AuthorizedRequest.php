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
            "Cookie" => $this->client->cookie->getCookies(),
            "Referer" => "https://www.instagram.com/",
            'Authority' => 'www.instagram.com',
            'Origin' => 'https://www.instagram.com',
            "x-csrftoken" => $this->client->cookie->getCookie("csrftoken"),
            "x-instagram-ajax" => $this->client->cookie->getCookie("x_instagram_ajax"),
            "x-requested-with" => "XMLHttpRequest",
            "Content-Type" => "application/x-www-form-urlencoded",
            "User-Agent" => $this->client->getUserAgent(),
            "upgrade-insecure-requests" => 1,
        ];
        $this->setHeaders($headers);
    }


}