<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 18/04/2018
 * Time: 03:36
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\AuthorizedRequest;
use InstagramAmAPI\Client;

/**
 * Class RequestPostPhoto
 * Загружает фотографию в профиль
 * @package InstagramAmAPI\Request
 */
class RequestPostPhoto extends AuthorizedRequest
{
    /**
     * @inheritdoc
     */
    protected function init($url = "", $params = null)
    {
//        TODO Нужно доделать.
        $this->instagram_url = self::INSTAGRAM_URL;
        $url = "create/upload/photo/";
        parent::init($url, $params);
        $this->setPost(true);
        $this->addHeader("User-Agent", Client::MOBILE_USER_AGENT);
        $this->addHeader("Referer", "https://www.instagram.com/create/crop/");
        $this->addHeader("Content-Type", "application/octet-stream");

        $filename = 'test_file.jpg';
        file_put_contents($filename, $this->data['photo_data']);
        $this->addAttachment([
            'name' => 'file',
            'contents' => fopen($filename, 'r'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function send()
    {
        $response = parent::send();

        $params = [
            "upload_id" => $response["upload_id"],
            "caption" => $this->data["message"],
        ];
        $configureRequest = new RequestConfigurePhoto($this->client, $params);
        $response = $configureRequest->send();
        return $response;
    }


}