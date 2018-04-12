<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 12.04.2018
 * Time: 13:49
 */

namespace InstagramAmAPI\Model;


use LazyJsonMapper\LazyJsonMapper;

class Like extends LazyJsonMapper
{
    const JSON_PROPERTY_MAP = [
        "id" => "string",
        "owner" => "Account",
        "date" => "int",
        "media" => "Media",
    ];
}