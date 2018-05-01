<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 25/04/2018
 * Time: 01:09
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\AuthorizedRequest;

/**
 * Class RequestConfigurePhoto
 * @package InstagramAmAPI\Request
 */
class RequestConfigurePhoto extends AuthorizedRequest
{
    /**
     * @inheritdoc
     */
    protected function init($url = "", $params = null)
    {
        $url = "/create/configure/";
        $post_data = [
            'upload_id' => $this->data['upload_id'],
            'caption' => $this->data['caption']
        ];
        parent::init($url, $params);

        $this->addHeader('Referer', 'https://www.instagram.com/create/details/');
        $this->setPostData($post_data);
    }

}