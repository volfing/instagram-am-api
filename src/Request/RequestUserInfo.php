<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 13/04/2018
 * Time: 01:35
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\NonAuthorizedRequest;

/**
 * Class RequestUserInfo
 * @package InstagramAmAPI\Request
 */
class RequestUserInfo extends NonAuthorizedRequest
{

    /**
     * @inheritdoc
     */
    protected function init($url = "", $params = null)
    {
        if (empty($this->data['username'])) {
            throw new \Exception("Empty username.");
        }
        $this->instagram_url = "https://www.instagram.com";

        $url = "/" . $this->data['username'] . "/";
        parent::init($url);
    }
}