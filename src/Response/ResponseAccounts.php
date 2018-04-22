<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 19/04/2018
 * Time: 14:12
 */

namespace InstagramAmAPI\Response;

/**
 * Class ResponseAccounts
 * @property string $next_max_id
 * @property int $count
 * @property \InstagramAmAPI\Model\Account[] $items
 * @package InstagramAmAPI\Response
 */
class ResponseAccounts extends Response
{
    const JSON_PROPERTY_MAP = [
        'next_max_id' => 'string',
        'count' => 'int',
        'items' => '\InstagramAmAPI\Model\Account[]',
    ];

}