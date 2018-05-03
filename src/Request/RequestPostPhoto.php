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
use InstagramAmAPI\Exception\UploadPhotoException;

/**
 * Class RequestPostPhoto
 * Загружает фотографию в профиль
 * @package InstagramAmAPI\Request
 */
class RequestPostPhoto extends AuthorizedRequest
{
    protected function init($url = "", $params = null)
    {
        parent::init($url, $params);

        $this->preparePhoto();
    }

    private function preparePhoto(){
        $this->data["photo"] = $this->squareImage($this->data["photo"]);
    }

    private function squareImage($imageData, $thumbSize = 1000)
    {
        list($width, $height) = getimagesizefromstring($imageData);
        $newImage = imagecreatefromstring($imageData);

        if ($width > $height) {
            $y = 0;
            $x = ($width - $height) / 2;
            $smallestSide = $height;
        } else {
            $x = 0;
            $y = ($height - $width) / 2;
            $smallestSide = $width;
        }

        $thumb = imagecreatetruecolor($thumbSize, $thumbSize);
        imagecopyresampled($thumb, $newImage, 0, 0, $x, $y, $thumbSize, $thumbSize, $smallestSide, $smallestSide);

        ob_start();
        imagejpeg($thumb, null, 100);
        $newImageData =  ob_get_contents();
        ob_end_clean();

        @imagedestroy($newImage);
        @imagedestroy($thumb);

        return $newImageData;
    }

    /**
     * @inheritdoc
     */
    public function send()
    {
        parent::send();

        $uid = number_format(round(microtime(true) * 1000), 0, '', '');

        $uploadRequest = new RequestUploadPhoto($this->client, [
            "upload_id" => $uid,
            "photo" => $this->data["photo"]
        ]);

        $response = $uploadRequest->send();

        if(empty($response["status"]) || $response["status"] != "ok"){
            throw new UploadPhotoException("Unknown error while uploading photo to instagram server");
        }

        $configureRequest = new RequestConfigurePhoto($this->client, [
            "upload_id" => $uid,
            "caption" => $this->data["message"]
        ]);

        $response = $configureRequest->send();

        return $response;
    }


}