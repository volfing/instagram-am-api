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
 * @property $account
 * @property $media
 * @property $explore
 * @package InstagramAmAPI
 */
class Instagram
{
    private $client;

    public function __construct()
    {
//        TODO: добавить инициализацию полей account, media, explore
//        $this->account = new RequestAccount();
//        $this->media = new RequestMedia();
//        $this->explore = new RequestExplore();
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

    /**
     * @param $mediaID
     */
    public function like($mediaID)
    {
        $request = new RequestLike($this->client);
        $res = $request->like($mediaID);
        return $res;
    }

}