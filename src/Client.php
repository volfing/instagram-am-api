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


}