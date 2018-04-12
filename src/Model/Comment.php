<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 12.04.2018
 * Time: 13:47
 */

namespace InstagramAmAPI\Model;


use LazyJsonMapper\LazyJsonMapper;

class Comment extends LazyJsonMapper
{
    const JSON_PROPERTY_MAP = [
        "id" => "string",
        "owner" => "Account",
        "media" => "Media",
        "date" => "int",
        "message" => "string",
        "numOfLikes" => "int",
        "numOfSubComments" => "int",
        "subComments" => "Comment[]",
    ];
}