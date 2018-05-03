<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 03/05/2018
 * Time: 13:59
 */

namespace InstagramAmAPI\Request;

/**
 * Авторизация через API
 *
 * Class RequestPrivateLogin
 * @package InstagramAmAPI\Request
 */
class RequestPrivateLogin extends RequestPrivateApi
{
    protected function init($url = "", $params = null)
    {
        $url = "accounts/login/";
        parent::init($url, $params);

        $post_data = [
            'phone_id' => '',
            '_csrftoken' => '',
            'username' => '',
            'password' => '',
            'adid' => '',
            'guid' => '',
            'device_id' => '',
            'login_attempt_count' => 0,
        ];
        $this->setPostData($post_data);
    }


}