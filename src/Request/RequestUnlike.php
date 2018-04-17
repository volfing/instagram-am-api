<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 15/04/2018
 * Time: 01:48
 */

namespace InstagramAmAPI\Request;

use InstagramAmAPI\AuthorizedRequest;

/**
 * Class RequestUnlike
 * @package InstagramAmAPI\Request
 */
class RequestUnlike extends AuthorizedRequest
{
    protected function init($url = "", $params = null)
    {
        parent::init("/web/likes/" . $this->data['id'] . "/unlike/");
        $this->setPost(true);
        $this->setPostData([]);
    }

}