<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 17/04/2018
 * Time: 19:42
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\NonAuthorizedRequest;

/**
 * Class RequestTagFeed
 * @package InstagramAmAPI\Request
 */
class RequestTagFeed extends NonAuthorizedRequest
{
    /**
     * @inheritdoc
     */
    protected function init($url = "", $params = null)
    {
        $url = "explore/tags/" . $this->data['tag'] . "/";
        $params = [
            "__a" => 1
        ];
        parent::init($url, $params);
        $this->addQuerySignature($params, "/" . $url);
    }


}