<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 18/04/2018
 * Time: 04:02
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\AuthorizedRequest;

/**
 * Class RequestDeletePhoto
 * @package InstagramAmAPI\Request
 */
class RequestDeletePhoto extends AuthorizedRequest
{
    /**
     * @inheritdoc
     */
    protected function init($url = "", $params = null)
    {
        $this->instagram_url = self::INSTAGRAM_URL;
        $url = "create/" . $this->data['media_id'] . "/delete/";
        parent::init($url, $params);
        $this->setPost(true);
    }

}