<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 14/04/2018
 * Time: 00:46
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\AuthorizedRequest;

class RequestUserInfoById extends AuthorizedRequest
{
    protected function init($url = "", $params = null)
    {
        $this->instagram_url = self::I_V1_API_URL;
        $this->instagram_url .= "users/" .  $this->data['id'] . "/info/";

        parent::init($url);
    }

}