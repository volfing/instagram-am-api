<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 01.05.2018
 * Time: 12:55
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\AuthorizedRequest;
use InstagramAmAPI\Client;

class RequestUploadPhoto extends AuthorizedRequest
{
    protected function init($url = "", $params = null)
    {
        $url = "/create/upload/photo/";
        $post_data = [
                [
                    'name'     => 'photo',
                    'filename' => 'photo.jpg',
                    'Content-Type'=> "image/jpeg",
                    'contents' => $this->data["photo"],
                ],
                [
                    "name" => "media_type",
                    "contents" => 1
                ],
                [
                    "name" => "upload_id",
                    "contents" => $this->data['upload_id']
                ]
        ];

        parent::init($url);

        $this->addHeader("Content-Type", "");
        $this->addHeader('Referer', 'https://www.instagram.com/create/style/');
        $this->setMultipartData($post_data);
    }
}