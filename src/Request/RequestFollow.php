<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 14/04/2018
 * Time: 01:09
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\AuthorizedRequest;

/**
 * Class RequestFollow
 * @package InstagramAmAPI\Request
 */
class RequestFollow extends AuthorizedRequest
{
    protected function init($url = "")
    {
        parent::init("/web/friendships/" . $this->data['id'] . "/follow/");
        $this->setPost(true);
    }


}