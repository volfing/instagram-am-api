<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 18/04/2018
 * Time: 00:58
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\NonAuthorizedRequest;

/**
 * Class RequestMediaInfoByShortcode
 * @package InstagramAmAPI\Request
 */
class RequestMediaInfoByShortcode extends NonAuthorizedRequest
{
    protected function init($url = "", $params = null)
    {

        $url = "p/" . $this->data['shortcode'] . "/";
        $params = [
            "__a" => 1,
            "__b" => 1,
        ];
        parent::init($url, $params);
        $this->addQuerySignature($params, "/" . $url);
    }


}