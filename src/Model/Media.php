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
 * @property Account $owner
 * @property int $dateOfPublish
 * @property int $numOfComments
 * @property int $numOfLikes
 * @property string $url
 * @property string $shortcode
 * @property string $type
 * @property string $message
 * @property Account[] $likes
 * @property Photo[] $photos
 * @package InstagramAmAPI\Model
 */
class Media extends LazyJsonMapper
{
    const JSON_PROPERTY_MAP = [
        "id" => "string",
        "owner" => "Account",
        "dateOfPublish" => "int",
        "numOfComments" => "int",
        "numOfLikes" => "int",
        "url" => "string",
        "shortcode" => "string",
        "type" => "string",
        "message" => "string",
        "comments" => "Comment[]",
        "likes" => "Account[]",
        "photos" => "Photo[]",
    ];
}