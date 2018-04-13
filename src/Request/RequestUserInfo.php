<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 13/04/2018
 * Time: 01:35
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\AuthorizedRequest;

/**
 * Class RequestUserInfo
 * @package InstagramAmAPI\Request
 */
class RequestUserInfo extends AuthorizedRequest
{

    /**
     * @inheritdoc
     */
    protected function init($url = "")
    {
        if (empty($this->data['username'])) {
            throw new \Exception("Empty username.");
        }
        $this->instagram_url = "https://www.instagram.com";
        parent::init("/" . $this->data['username'] . "/?__a=1");
//      TODO: Узнать от куда берется X-Instagram-GIS
        $x_instagram_gis = "";
        $this->setHeaders([
            "Cookie: mid=" . $this->client->cookie->getCookie("mid") . "; ig_dru_dismiss=" . $this->client->cookie->getCookie("ig_dru_dismiss") . "; csrftoken=" . $this->client->cookie->getCookie("csrftoken") . "; ds_user_id=" . $this->client->cookie->getCookie("ds_user_id") . "; shbid=" . $this->client->cookie->getCookie("shbid") . "; rur=" . $this->client->cookie->getCookie("rur") . "; ig_pr=1; ig_or=landscape-primary; ig_vw=1440; sessionid=" . $this->client->cookie->getCookie("sessionid") . "; ig_vh=297",
            "Referer: " . "https://www.instagram.com/" . $this->data['username'] . "/",
            "x-requested-with: XMLHttpRequest",
            "X-Instagram-GIS: " . $x_instagram_gis,
            "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36",
        ]);
    }


}