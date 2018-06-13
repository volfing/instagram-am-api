<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 14/04/2018
 * Time: 15:39
 */

namespace InstagramAmAPI\Exception;

/**
 * Class Exception
 * @package InstagramAmAPI\Exception
 */
class InstagramException extends \Exception
{
    public $body = null;
}