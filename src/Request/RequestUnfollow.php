<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 14/04/2018
 * Time: 01:34
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\AuthorizedRequest;

/**
 * Class RequestUnfollow
 * @package InstagramAmAPI\Request
 */
class RequestUnfollow extends AuthorizedRequest
{
    protected function init($url = "", $params = null)
    {
        parent::init("/web/friendships/" . $this->data['id'] . "/unfollow/");
        $this->setPost(true);
    }

}