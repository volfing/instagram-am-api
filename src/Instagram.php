<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 11/04/2018
 * Time: 20:55
 */

namespace InstagramAmAPI;

use InstagramAmAPI\Exception\BadResponseException;
use InstagramAmAPI\Exception\NotAuthException;
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
     * @param bool $force
     * @return bool
     */
    public function login($force = false)
    {
        return $this->_login($force);
    }

    /**
     * @param $force
     * @return array|bool
     * @throws BadResponseException
     * @throws NotAuthException
     */
    private function _login($force)
    {
        if ($this->client->isLogged() && !$force) {
            return true;
        }
        $request = new RequestLogin($this->client, [
            "username" => $this->client->getUsername(),
            "password" => $this->client->getPassword()
        ]);
        $response = $request->send();
        if (is_array($response)) {
            if ($response['authenticated']) {
                return true;
            }
            if (!$response['user']) {
                throw new NotAuthException("Пользователь не существует.");
            } else {
                throw new NotAuthException("Неверный пароль.");
            }
        }
        throw new BadResponseException();
    }

    private function initSubmodules()
    {
        $this->account = new Account($this->client);
        $this->media = new Media($this->client);
        $this->explore = new Explore($this->client);
    }

    public function setUser($username, $password)
    {
        $this->client = new Client($username, $password);
        $this->initSubmodules();

    }

}