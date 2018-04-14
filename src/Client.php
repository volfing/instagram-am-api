<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 12/04/2018
 * Time: 13:24
 */

namespace InstagramAmAPI;

use InstagramAmAPI\Storage\CookieManager;

/**
 * Класс для работы с клиентскими настройками
 *
 * Class Client
 * @package InstagramAmAPI
 */
class Client
{
    const USER_AGENT = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36";

    private $proxy;
    private $username;
    private $password;
    public $cookie;

    /**
     * Client constructor.
     * @param $username
     * @param $password
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->cookie = new CookieManager();
        $this->cookie->setCookieFile($username);
        $this->cookie->loadCookie();
    }

    /**
     * @param string $proxy
     */
    public function setProxy($proxy)
    {
        $this->proxy = $proxy;
    }

    /**
     * @return string
     */
    public function getProxy()
    {
        return $this->proxy;
    }

    /**
     * Проверка на авторизацию
     *
     * @return bool
     */
    public function isLogged()
    {
        return !empty($this->cookie->getCookie('sessionid'));
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Возвращает id аутентифицированного пользователя
     *
     * @return mixed|null
     */
    public function authenticatedUserId()
    {
        if (!empty($this->cookie->getCookie("ds_user_id"))) {
            return $this->cookie->getCookie("ds_user_id");
        }
        return null;
    }

    public function getUserAgent()
    {
        return self::USER_AGENT;
    }


}