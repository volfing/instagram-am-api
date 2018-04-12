<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 11/04/2018
 * Time: 20:55
 */

namespace InstagramAmAPI;

use InstagramAmAPI\Request\RequestLike;
use InstagramAmAPI\Request\RequestLogin;

/**
 * Class Instagram
 * @property Client $client
 * @package InstagramAmAPI
 */
class Instagram
{
    private $client;
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
        $this->client = new Client($login, $password);
        $request = new RequestLogin($this->client, [
            "username" => $login,
            "password" => $password
        ]);
        $response = $request->send();
        return $response;

    }

    public function like($mediaID){
        $request = new RequestLike($this->client);
        $res = $request->like($mediaID);
    }

}