<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 25.05.2018
 * Time: 8:40
 */

namespace InstagramAmAPI\Request;

use InstagramAmAPI\NonAuthorizedRequest;

class RequestCheckpointMethods extends NonAuthorizedRequest
{
    protected function init($url = "", $params = null){
        $this->setPost(true);
        $this->instagram_url = str_replace("{checkpoint}", $this->data["checkpoint_url"], self::INSTAGRAM_CHECKPOINT_URL);
        parent::init($url, $params);
    }
}