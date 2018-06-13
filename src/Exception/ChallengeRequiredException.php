<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 23/04/2018
 * Time: 02:16
 */

namespace InstagramAmAPI\Exception;

/**
 * Class ChallengeRequiredException
 * @package InstagramAmAPI\Exception
 */
class ChallengeRequiredException extends InstagramException
{
    public $challengeInfo = null;
}