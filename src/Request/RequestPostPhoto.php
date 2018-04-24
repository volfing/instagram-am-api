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

        $files = [
            'photo',
            'photo.jpg',
            'application/octet-stream',
            $this->data['photo_data'],
        ];
        $boundary_teil = "DOgoiQ5LLJRxJQ4y";
        $boundary = "----WebKitFormBoundary" . $boundary_teil;
        $body = "
{$boundary}
Content-Disposition: form-data; name=\"upload_id\"

1524012338614
{$boundary}
Content-Disposition: form-data; name=\"photo\"; filename=\"photo.jpg\"
Content-Type: image/jpeg


{$boundary}
Content-Disposition: form-data; name=\"media_type\"

1
{$boundary}--
        ";
        $data = [
            'photo' => $this->data['photo_data']
        ];
        $this->addHeader("User-Agent", Client::MOBILE_USER_AGENT);
        $this->addHeader("Referer", "https://www.instagram.com/create/crop/");
        $this->addHeader("Content-Type", "multipart/form-data; boundary=----WebKitFormBoundary{$boundary_teil}");
        $this->addHeader("Content-Length", strlen($body));
        $this->setPostData($files);
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