<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 17/04/2018
 * Time: 19:49
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\NonAuthorizedRequest;

/**
 * Class RequestLocationFeed
 * @package InstagramAmAPI\Request
 */
class RequestLocationFeed extends NonAuthorizedRequest
{
    /**
     * @inheritdoc
     */
    protected function init($url = "", $params = null)
    {
        $url = "explore/locations/" . $this->data['location_id'] . "/";
        $params = [
            "__a" => 1
        ];
        parent::init($url, $params);
        $this->addQuerySignature($params, "/" . $url);
    }


}