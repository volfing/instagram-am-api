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
        "is_private" => "boolean",
        "is_business" => "boolean",
        "gender" => "string",
        "numOfFollowers" => "int",
        "numOfFollowings" => "int",
        "username" => "string",
        "biography" => "string",
        "medias" => "Media[]",
        "followers" => "Account[]",
        "followings" => "Account[]",
        "avatar" => "Photo[]",
    ];
}