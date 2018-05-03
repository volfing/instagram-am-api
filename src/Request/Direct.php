<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 01.05.2018
 * Time: 20:56
 */

namespace InstagramAmAPI\Request;


class Direct extends Request
{
    public function getListOfMessages($maxID = null){
        $request = new RequestDirectGetMessageList($this->client, []);

        return $request->send();
    }
}