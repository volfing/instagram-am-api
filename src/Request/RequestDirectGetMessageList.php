<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 01.05.2018
 * Time: 20:51
 */

namespace InstagramAmAPI\Request;

/**
 * Class RequestDirectGetMessageList
 * @package InstagramAmAPI\Request
 */
class RequestDirectGetMessageList extends RequestPrivateApi
{
    protected function init($url = "", $params = null)
    {
        $url = "direct_v2/inbox/";

        parent::init($url, $params);
        $this->setPostData(["persistentBadging" => "true", "use_unified_inbox" => "true"]);
    }
}