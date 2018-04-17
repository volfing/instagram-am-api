<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 13/04/2018
 * Time: 01:35
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\AuthorizedRequest;

/**
 * Class RequestUserInfo
 * @package InstagramAmAPI\Request
 */
class RequestUserInfo extends AuthorizedRequest
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

        $variables = [
            "__a" => 1
        ];
        $url = "/" . $this->data['username'] . "/";
        parent::init($url, $variables);

        $this->addHeader("Referer", "https://www.instagram.com/" . $this->data['username'] . "/");
        $this->addQuerySignature($variables, $this->instagram_url . $url);
    }


}