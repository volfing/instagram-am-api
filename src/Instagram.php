<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 11/04/2018
 * Time: 20:55
 */

namespace InstagramAmAPI;

use InstagramAmAPI\Request\Account;
use InstagramAmAPI\Request\Explore;
use InstagramAmAPI\Request\Media;
use InstagramAmAPI\Request\RequestLogin;

/**
 * Class Instagram
 * @property Client $client
 * @property Account $account
 * @property Media $media
 * @property Explore $explore
 * @package InstagramAmAPI
 */
class Instagram
{
    private $client;

    public function __construct()
    {
        $this->initSubmodules();
    }


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
     * @return array|bool
     */
    private function _login($login, $password, $force)
    {
        $this->client = new Client($login, $password);
        $this->initSubmodules();
        if ($this->client->isLogged() && !$force) {
            return true;
        }
        $request = new RequestLogin($this->client, [
            "username" => $login,
            "password" => $password
        ]);
        $response = $request->send();
        return $response;

    }

    private function initSubmodules()
    {
        $this->account = new Account($this->client);
        $this->media = new Media($this->client);
        $this->explore = new Explore($this->client);
    }

}