<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 12.04.2018
 * Time: 13:24
 */

namespace InstagramAmAPI\Model;

use LazyJsonMapper\LazyJsonMapper;

class Account extends LazyJsonMapper
{
    const JSON_PROPERTY_MAP = [
        "id" => "string",
        "is_private" => "bool",
        "is_business" => "bool",
        "gender" => "string",
        "numOfFollowers" => "int",
        "numOfFollowings" => "int",
        "username" => "string",
        "full_name" => "string",
        "biography" => "string",
        "profile_pic_url" => "string",
//        "medias" => "Media[]",
//        "followers" => "Account[]",
//        "followings" => "Account[]",
//        "avatar" => "Photo[]",
    ];
}