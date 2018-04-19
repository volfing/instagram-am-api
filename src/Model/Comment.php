<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 12.04.2018
 * Time: 13:47
 */

namespace InstagramAmAPI\Model;


use LazyJsonMapper\LazyJsonMapper;

/**
 * Class Comment
 * @property string $id
 * @property int $owner
 * @property int $date
 * @property string $message
 * @property int $numOfLikes
 * @property int $numOfSubComments
 * @package InstagramAmAPI\Model
 */
class Comment extends LazyJsonMapper
{
    const JSON_PROPERTY_MAP = [
        "id" => "string",
        "owner" => "int",
//        "media" => "Media",
        "date" => "int",
        "message" => "string",
        "numOfLikes" => "int",
        "numOfSubComments" => "int",
//        "subComments" => "Comment[]",
    ];
}