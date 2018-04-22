<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 12.04.2018
 * Time: 13:24
 */

namespace InstagramAmAPI\Model;

use LazyJsonMapper\LazyJsonMapper;

/**
 * Class Account
 *
 * @property string $id
 * @property bool $is_private
 * @property bool $is_business
 * @property string $gender
 * @property int $numOfFollowers
 * @property int $numOfFollowings
 * @property string $username
 * @property string $full_name
 * @property string $biography
 * @property string $profile_pic_url
 * @property int $media_count
 * @property Media[] $medias
 * @package InstagramAmAPI\Model
 */
class Account extends LazyJsonMapper
{
    const JSON_PROPERTY_MAP = [
        "id" => "string",
        "is_private" => "bool",
        "is_business" => "bool",
        "gender" => "string",
        "numOfFollowers" => "int",
        "numOfFollowings" => "int",
        "media_count" => "int",
        "username" => "string",
        "full_name" => "string",
        "biography" => "string",
        "profile_pic_url" => "string",
        "medias" => "Media[]",
//        "followers" => "Account[]",
//        "followings" => "Account[]",
//        "avatar" => "Photo[]",
    ];
}