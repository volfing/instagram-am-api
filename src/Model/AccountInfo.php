<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 13/06/2018
 * Time: 04:48
 */

namespace InstagramAmAPI\Model;


use LazyJsonMapper\LazyJsonMapper;

/**
 * Class AccountInfo
 *
 * @property bool $is_blocked Заблокирован
 * @property int $date_joined Дата создания аккаунта
 * @property int $date_of_birth Дата рождения
 * @package InstagramAmAPI\Model
 */
class AccountInfo extends LazyJsonMapper
{
    const JSON_PROPERTY_MAP = [
        'is_blocked' => 'bool',
        'date_joined' => 'int',
        'date_of_birth' => 'bool',
    ];
}