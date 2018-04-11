<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 11/04/2018
 * Time: 20:55
 */

namespace InstagramAmAPI;

use InstagramAmAPI\Request\RequestLogin;

/**
 * Class Instagram
 * @package InstagramAmAPI
 */
class Instagram
{
    /**
     * @param $login
     * @param $password
     * @param bool $force
     * @return bool
     */
    public function login($login, $password, $force = false)
    {
        return $this->_login($login, $password, $force);
    }

    /**
     * @param $login
     * @param $password
     * @param $force
     * @return bool
     */
    private function _login($login, $password, $force)
    {
        $request = new RequestLogin([
            "username" => $login,
            "password" => $password
        ]);
        $request->setCookieFile($login);
        $responce = $request->send();
        return $responce;

    }

}