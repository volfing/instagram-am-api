<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 13/06/2018
 * Time: 05:10
 */

namespace InstagramAmAPI\Model;


use LazyJsonMapper\LazyJsonMapper;

/**
 * Class AccountEdit
 *
 * @property string $first_name Имя
 * @property string $last_name Фамилия
 * @property string $email Емейл
 * @property string $username Логин
 * @property string $phone_number Номер телефона
 * @property int $gender Пол
 * @property int $birthday Дата рождения
 * @property string $biography Биография
 * @property string $external_url Внешняя ссылка
 * @property bool $private_account Приватный аккаунт
 * @property bool $business_account Бизнес аккаунт
 * @property bool $chaining_enabled
 * @property bool $presence_disabled Разрешен ли просмотр статуса онлайн
 * @property bool $usertag_review_enabled Разрешен ли просмотр юзертегов
 *
 * @package InstagramAmAPI\Model
 */
class AccountEdit extends LazyJsonMapper
{
    const JSON_PROPERTY_MAP = [
        'first_name' => 'string',
        'last_name' => 'string',
        'email' => 'string',
        'username' => 'string',
        'phone_number' => 'string',
        'biography' => 'string',
        'external_url' => 'string',

        'gender' => 'int',
        'birthday' => 'int',

        'private_account' => 'bool',
        'business_account' => 'bool',
        'chaining_enabled' => 'bool',
        'presence_disabled' => 'bool',
        'usertag_review_enabled' => 'bool',
    ];
}