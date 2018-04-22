<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 22/04/2018
 * Time: 23:41
 */

namespace InstagramAmAPI\Model;


use LazyJsonMapper\LazyJsonMapper;

/**
 * Class Location
 * @property string $id
 * @property string $name
 * @property string $address
 * @property string $city
 * @property string $short_name
 * @property float $longitude
 * @property float $latitude
 * @property string $external_source
 * @property string $facebook_places_id
 * @package InstagramAmAPI\Model
 */
class Location extends LazyJsonMapper
{
    const JSON_PROPERTY_MAP = [
        'id' => 'string',
        'name' => 'string',
        'address' => 'string',
        'city' => 'string',
        'short_name' => 'string',
        'longitude' => 'float',
        'latitude' => 'float',
        'external_source' => 'string',
        'facebook_places_id' => 'string',
    ];

}