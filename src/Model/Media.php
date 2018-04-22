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
 * @property string $id
 * @property int $owner
 * @property int $dateOfPublish
 * @property int $numOfComments
 * @property int $numOfLikes
 * @property string $type
 * @property string $message
 * @property Photo[] $photos
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
        "comments" => "Comment[]",
        "likes" => "Like[]",
        "photos" => "Photo[]",
    ];
}