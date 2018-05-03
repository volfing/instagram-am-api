<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 01.05.2018
 * Time: 20:51
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\AuthorizedRequest;

class RequestDirectGetMessageList extends AuthorizedRequest
{
    protected function init($url = "", $params = null)
    {
        $this->instagram_url = "https://i.instagram.com/api/v1/";
        $url = "direct_v2/inbox/";

        parent::init($url, $params);
        $this->setPostData(["persistentBadging" => "true", "use_unified_inbox" => "true"]);
    }
}