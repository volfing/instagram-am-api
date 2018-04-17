<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 12.04.2018
 * Time: 14:14
 */

namespace InstagramAmAPI\Model;


use LazyJsonMapper\LazyJsonMapper;

class Explore extends LazyJsonMapper
{
    const JSON_PROPERTY_MAP = [
        "medias" => "Media[]",
        "mostPopularMedias" => "Media[]",
    ];
}