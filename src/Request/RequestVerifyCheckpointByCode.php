<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 25.05.2018
 * Time: 11:27
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\NonAuthorizedRequest;

class RequestVerifyCheckpointByCode extends NonAuthorizedRequest
{
    protected function init($url = "", $params = null)
    {
        $this->instagram_url = str_replace("{checkpoint}", $this->data["checkpoint_url"], self::INSTAGRAM_CHECKPOINT_URL);
        $this->setPost(true);
        $this->setPostData([
            "security_code" => $this->data["code"]
        ]);

        parent::init($url, $params);
    }
}