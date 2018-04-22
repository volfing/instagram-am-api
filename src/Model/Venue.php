<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 22/04/2018
 * Time: 14:53
 */

namespace InstagramAmAPI\Model;


use LazyJsonMapper\LazyJsonMapper;

/**
 * Class Venue
 *
 * @property float $latitude
 * @property float $longitude
 * @property string $address
 * @property string $external_id
 * @property string $external_id_source
 * @property string $name
 * @property int $minimum_age
 * @package InstagramAmAPI\Model
 */
class Venue extends LazyJsonMapper
{
    const JSON_PROPERTY_MAP = [
        'latitude' => 'float',
        'longitude' => 'float',
        'address' => 'string',
        'external_id' => 'string',
        'external_id_source' => 'string',
        'name' => 'string',
        'minimum_age' => 'int',
    ];

}