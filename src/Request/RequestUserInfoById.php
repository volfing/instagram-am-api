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
    protected function init($url = "")
    {
        parent::init("/graphql/query/?query_hash=" . QueryProperty::QUERY_HASH_REELS_TRAY . "&variables=%7B\"id\":\"" . $this->data['id'] . "\",\"first\":1%7D");
    }

}