<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 12.04.2018
 * Time: 13:53
 */

namespace InstagramAmAPI\Request;


class Request
{
    protected $client;

    function __construct(&$client)
    {
        $this->client = $client;
    }
}