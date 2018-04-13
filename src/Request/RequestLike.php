<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 12.04.2018
 * Time: 0:02
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\AuthorizedRequest;
use InstagramAmAPI\Client;

class RequestLike extends AuthorizedRequest
{
    private $mediaID;

    /**
     * RequestLike constructor.
     * @param Client $client
     * @param array $data
     */
    public function __construct(Client $client, array $data = [])
    {
        parent::__construct($client, $data);
    }

    protected function init($url = "")
    {

        parent::init("/web/likes/" . $this->mediaID . "/like/");
        $this->setPost(true);
        $this->setPostData($this->data);
    }


    public function like($mediaID)
    {
        $this->mediaID = $mediaID;
        return parent::send();
    }
}