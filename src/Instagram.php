<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 11/04/2018
 * Time: 20:55
 */

namespace InstagramAmAPI;

use InstagramAmAPI\Exception\BadResponseException;
use InstagramAmAPI\Exception\IncorrectPasswordException;
use InstagramAmAPI\Exception\InvalidUserException;
use InstagramAmAPI\Request\Account;
use InstagramAmAPI\Request\Direct;
use InstagramAmAPI\Request\Explore;
use InstagramAmAPI\Request\Media;
use InstagramAmAPI\Request\RequestLogin;

/**
 * Class Instagram
 * @property Client $client
 * @property Account $account
 * @property Media $media
 * @property Direct $direct
 * @property Explore $explore
 * @package InstagramAmAPI
 */
class Instagram
{
    /** @var  Client $client */
    private $client;

    /**
     * Instagram constructor.
     */
    public function __construct()
    {
        $username = "empty_userndjbse34";
        $password = "empty_password";
        $this->client = new Client($username, $password);
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
     * @throws IncorrectPasswordException
     * @throws InvalidUserException
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
                throw new InvalidUserException("Пользователь не существует.");
            } else {
                throw new IncorrectPasswordException("Неверный пароль.");
            }
        }
        throw new BadResponseException();
    }

    /**
     *
     */
    private function initSubmodules()
    {
        $this->account = new Account($this->client);
        $this->media = new Media($this->client);
        $this->explore = new Explore($this->client);
        $this->direct = new Direct($this->client);
    }

    /**
     * @param string $username
     * @param string $password
     */
    public function setUser($username, $password)
    {
        $this->client->setPassword($password);
        $this->client->setUsername($username);
        $this->initSubmodules();
    }

    /**
     * @param $proxy
     */
    public function setProxy($proxy)
    {
        $this->client->setProxy($proxy);
        $this->initSubmodules();
    }

    /**
     * @return bool
     */
    public function isLogged()
    {
        return $this->client->isLogged();
    }

}