<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 14/04/2018
 * Time: 01:03
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\AuthorizedRequest;

/**
 * Class RequestUserFeed
 * @package InstagramAmAPI\Request
 */
class RequestUserFeed extends AuthorizedRequest
{
    /**
     * @inheritdoc
     */
    protected function init($url = "")
    {
        parent::init("/graphql/query/?query_hash=" . QueryProperty::QUERY_HASH_USER . "&variables=%7B\"id\":\"" . $this->data['id'] . "\",\"first\":100%7D");
    }


}