<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 11/04/2018
 * Time: 21:11
 */

namespace InstagramAmAPI\Storage;

/**
 * Класс для работы с куками
 *
 * Class CookieHelper
 * @package InstagramAmAPI\Storage
 */
class CookieHelper
{
    private $cookie_file;
    private $cookie_data;

    public function __construct()
    {
        $this->cookie_file = null;
        $this->cookie_data = [];
    }

    /**
     * Задать название файла с куками
     *
     * @param $username
     */
    public function setCookieFile($username)
    {
        if (!is_dir("sessions")) {
            mkdir("sessions");
        }
        if (!is_dir("sessions/" . $username)) {
            mkdir("sessions/" . $username);
        }
        $this->cookie_file = "sessions/" . $username . "/cookie.txt";
    }

    /**
     * Записать значение в куку
     *
     * @param $name
     * @param $value
     */
    public function setCookie($name, $value)
    {
        $this->cookie_data[$name] = $value;
    }

    /**
     * Получить значение куки
     *
     * @param $name
     * @return mixed
     */
    public function getCookie($name)
    {
        if (isset($this->cookie_data[$name])) {
            return $this->cookie_data[$name];
        }
        return null;
    }

    /**
     * Загрузить данные из кук
     */
    public function loadCookie()
    {
        $this->cookie_data = json_decode(file_get_contents($this->cookie_file), true);
    }

    /**
     * Сохранить данные в куки
     */
    public function saveCookie()
    {
        $f = fopen($this->cookie_file, "w");
        fwrite($f, json_encode($this->cookie_data));
        fclose($f);
    }

    public function getCookieString()
    {
        return json_encode($this->cookie_data);
    }
}