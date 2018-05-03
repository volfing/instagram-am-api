<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 03/05/2018
 * Time: 13:34
 */

namespace InstagramAmAPI;

/**
 * Class Signatures
 * @package InstagramAmAPI
 */
class Signatures
{

    /**
     * @return string
     */
    public static function genUUID()
    {
        $uuid = sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );

        return $uuid;

    }

}