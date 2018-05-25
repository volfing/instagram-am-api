<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 25.05.2018
 * Time: 9:32
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\NonAuthorizedRequest;

class RequestCheckpointCode extends NonAuthorizedRequest
{
    protected function init($url = "", $params = null)
    {
        $this->setPost(true);
        $this->instagram_url = str_replace("{checkpoint}", $this->data["checkpoint_url"], self::INSTAGRAM_CHECKPOINT_URL);
        $this->setPostData([
            "choice" => (int)$this->data["choice"]
        ]);

        parent::init($url, $params);
    }
}