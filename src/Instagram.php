<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 11/04/2018
 * Time: 20:55
 */

namespace InstagramAmAPI;

use InstagramAmAPI\Exception\BadResponseException;
use InstagramAmAPI\Exception\CheckpointCodeAlreadySent;
use InstagramAmAPI\Exception\CheckpointCodeExpired;
use InstagramAmAPI\Exception\CheckpointIncorrectCode;
use InstagramAmAPI\Exception\IncorrectPasswordException;
use InstagramAmAPI\Exception\InstagramException;
use InstagramAmAPI\Exception\InvalidUserException;
use InstagramAmAPI\Request\Account;
use InstagramAmAPI\Request\Explore;
use InstagramAmAPI\Request\Media;
use InstagramAmAPI\Request\RequestCheckpointCode;
use InstagramAmAPI\Request\RequestCheckpointMethods;
use InstagramAmAPI\Request\RequestCheckpointReplay;
use InstagramAmAPI\Request\RequestLogin;
use InstagramAmAPI\Request\RequestVerifyCheckpointByCode;

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

    public function getCheckpointMethods($url){
        if(strpos($url, "instagram.com") !== false){
            return null;
        }

        $result = null;

        try{
            $request = new RequestCheckpointMethods($this->client, [
                "checkpoint_url" => $url
            ]);

            $result = $request->send();
        }catch (InstagramException $e){
            $result = json_decode($e->body, true);
        }

        if(!empty($result["challenge"]["navigation"]["replay"]) && $result["challenge"]["challengeType"] != "SelectVerificationMethodForm"){
            $exception = new CheckpointCodeAlreadySent("Checkpoint code already sent");
            $exception->resendLink = $result["challenge"]["navigation"]["replay"];

            throw $exception;
        }

        if(!empty($result["challenge"]["extraData"]["content"][3]["fields"][0]["values"])){
            $result = $result["challenge"]["extraData"]["content"][3]["fields"][0]["values"];
        }else{
            $result = null;
        }

        return $result;
    }

    public function sendCheckpointCode($choice, $url){
        $request = new RequestCheckpointCode($this->client, [
            "checkpoint_url" => $url,
            "choice" => $choice
        ]);

        $request->withoutDecode(true);

        $result = $request->send();

        return $result;
    }

    public function verifyCheckpointByCode($url, $code){
        $request = new RequestVerifyCheckpointByCode($this->client, [
            "checkpoint_url" => $url,
            "code" => $code
        ]);

        try{
            $response = $request->send();
        }catch (InstagramException $exception){
            $response = json_decode($exception->body, true);

            if(!empty($response["challenge"]["errors"][0]) && strpos($response["challenge"]["errors"][0], "expired") !== false){
                $replay_link = $response["challenge"]["navigation"]["replay"];

                $this->checkpointReplayCode($replay_link);

                throw new CheckpointCodeExpired("Code has expired. New code was sent.");
            }

            if(!empty($response["challenge"]["errors"][0]) && strpos($response["challenge"]["errors"][0], "check the code") !== false){
                throw new CheckpointIncorrectCode("Please check the code we sent you and try again");
            }

            throw $exception;
        }


        return $response;
    }

    public function checkpointReplayCode($replay_url){
        $replay = new RequestCheckpointReplay($this->client, [
            "checkpoint_url" => $replay_url
        ]);

        return $replay->send();
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