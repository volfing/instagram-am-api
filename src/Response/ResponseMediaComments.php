<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 19/04/2018
 * Time: 14:19
 */

namespace InstagramAmAPI\Response;

/**
 * Class ResponseMediaComments
 * @property string $next_max_id
 * @property int $count
 * @property \InstagramAmAPI\Model\Comment[] $items
 * @package InstagramAmAPI\Response
 */
class ResponseMediaComments extends Response
{
    const JSON_PROPERTY_MAP = [
        'next_max_id' => 'string',
        'count' => 'int',
        'items' => '\InstagramAmAPI\Model\Comment[]',
    ];
}