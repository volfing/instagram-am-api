<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 25.05.2018
 * Time: 9:49
 */

namespace InstagramAmAPI\Exception;


class CheckpointCodeAlreadySent extends InstagramException
{
    public $resendLink = null;
}