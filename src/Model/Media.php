<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 12.04.2018
 * Time: 13:45
 */

namespace InstagramAmAPI\Model;


use LazyJsonMapper\LazyJsonMapper;

/**
 * Class Media
 * @property $id
 * @property $owner
 * @property $dateOfPublish
 * @property $numOfComments
 * @property $numOfLikes
 * @property $type
 * @property $message
 * @package InstagramAmAPI\Model
 */
class Media extends LazyJsonMapper
{
    const JSON_PROPERTY_MAP = [
        "id" => "string",
        "owner" => "int",
        "dateOfPublish" => "int",
        "numOfComments" => "int",
        "numOfLikes" => "int",
        "type" => "string",
        "message" => "string",
//        "comments" => "Comment[]",
        "photos" => "Photo[]",
    ];
}