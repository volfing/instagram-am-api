<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 03/05/2018
 * Time: 13:27
 */

namespace InstagramAmAPI\Request;

use InstagramAmAPI\Signatures;

/**
 * Class RequestDirectSendText
 * @package InstagramAmAPI\Request
 */
class RequestDirectSendText extends RequestPrivateApi
{
    protected function init($url = "", $params = null)
    {
        $this->instagram_url = "https://i.instagram.com/api/v1/";
        $url = "direct_v2/threads/broadcast/text/";
        parent::init($url, $params);

        $post_data = [
            'text' => $this->data['text'],
            'recipient_users' => $this->data['users'],
            'client_context' => Signatures::genUUID(),
            'action' => 'send_item',
            '_csrftoken' => $this->client->cookie->getCookie("csrftoken"),
            '_uuid' => Signatures::genUUID(),
        ];
        $this->setPostData($post_data);

    }

}