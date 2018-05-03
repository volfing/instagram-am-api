<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 03/05/2018
 * Time: 11:08
 */

namespace InstagramAmAPI\Request;

/**
 * Class RequestPrivateApi
 * @package InstagramAmAPI\Request
 */
class RequestPrivateApi extends \InstagramAmAPI\Request
{
    protected function init($url = "", $params = null)
    {

        parent::init($url, $params);

        $headers = [
            "Cookie" => [
                "rur" => $this->client->cookie->getCookie("rur"),
                "csrftoken" => $this->client->cookie->getCookie("csrftoken"),
                "mid" => $this->client->cookie->getCookie("mid"),
                "sessionid" => $this->client->cookie->getCookie("sessionid"),
                "ds_user_id" => $this->client->cookie->getCookie("ds_user_id"),
                "shbid" => $this->client->cookie->getCookie("shbid"),
                "ds_user" => $this->client->getUsername(),
                "igfl" => $this->client->getUsername(),
                "shbts" => microtime(true),
                "is_starred_enabled" => "yes",
            ],
        ];
        $this->setHeaders($headers);
//
//        $this->addHeader('User-Agent', Client::MOBILE_APP_USER_AGENT);
//        $this->addHeader('Connection', 'Keep-Alive');
//        $this->addHeader('X-FB-HTTP-Engine', Constants::X_FB_HTTP_Engine);
//        $this->addHeader('Accept', '*/*');
//        $this->addHeader('Accept-Encoding', Constants::ACCEPT_ENCODING);
//        $this->addHeader('Accept-Language', Constants::ACCEPT_LANGUAGE);
//
//        $this->addHeader('Content-Type', Constants::CONTENT_TYPE);
//
//
//        $this->addHeader('X-IG-App-ID', Constants::FACEBOOK_ANALYTICS_APPLICATION_ID);
//        $this->addHeader('X-IG-Capabilities', Constants::X_IG_Capabilities);
//        $this->addHeader('X-IG-Connection-Type', Constants::X_IG_Connection_Type);
//        $this->addHeader('X-IG-Connection-Speed', mt_rand(1000, 3700) . 'kbps');
//        // TODO: IMPLEMENT PROPER CALCULATION OF THESE HEADERS.
//        $this->addHeader('X-IG-Bandwidth-Speed-KBPS', '-1.000');
//        $this->addHeader('X-IG-Bandwidth-TotalBytes-B', '0');
//        $this->addHeader('X-IG-Bandwidth-TotalTime-MS', '0');
    }


}