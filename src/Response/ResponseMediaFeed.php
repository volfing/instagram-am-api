<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 19/04/2018
 * Time: 14:11
 */

namespace InstagramAmAPI\Response;

/**
 * Class ResponseMediaFeed
 * @property string $next_max_id
 * @property int $count
 * @property \InstagramAmAPI\Model\Media[] $items
 * @package InstagramAmAPI\Response
 */
class ResponseMediaFeed extends Response
{
    const JSON_PROPERTY_MAP = [
        'next_max_id' => 'string',
        'count' => 'int',
        'items' => '\InstagramAmAPI\Model\Media[]',
    ];
}