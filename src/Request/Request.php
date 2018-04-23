<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 12.04.2018
 * Time: 13:53
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\Client;

class Request
{
    /** @var  Client $client */
    protected $client;

    /**
     * Request constructor.
     * @param $client
     */
    function __construct(&$client)
    {
        $this->client = $client;
    }
}