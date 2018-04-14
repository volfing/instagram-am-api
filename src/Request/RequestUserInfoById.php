<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 14/04/2018
 * Time: 00:46
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\AuthorizedRequest;

class RequestUserInfoById extends AuthorizedRequest
{
    protected function init($url = "", $params = null)
    {
        $this->instagram_url = self::GRAPHQL_API_URL;
        $params = [
            "q" => "ig_user(" . $this->data['id'] . ") {id, username, full_name, profile_pic_url, biography, external_url, is_private, is_verified, media {count}, followed_by {count}, follows {count} }"
        ];
        parent::init($url, $params);
    }

}