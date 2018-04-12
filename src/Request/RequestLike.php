<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 12.04.2018
 * Time: 0:02
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\Request;

class RequestLike extends Request
{
    private $mediaID;

    public function prepareRequest()
    {
        $this->curl = curl_init($this->instagram_url . "/web/likes/" . $this->mediaID . "/like/");
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($this->data));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, "");
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            "Cookie: rur=" . $this->client->cookie->getCookie("rur") . "; csrftoken=" . $this->client->cookie->getCookie("csrftoken") . "; mid=" . $this->client->cookie->getCookie("mid") . "; ig_vw=1915; ig_pr=1; ig_vh=937; sessionid=" . $this->client->cookie->getCookie("sessionid") . "; ds_user_id=" . $this->client->cookie->getCookie("ds_user_id"),
            "Referer: https://www.instagram.com/",
            "x-csrftoken: " . $this->client->cookie->getCookie("csrftoken"),
            "x-instagram-ajax: 1",
            "x-requested-with: XMLHttpRequest",
            "Content-Type: application/x-www-form-urlencoded",

        ));
    }

    public function like($mediaID){
        $this->mediaID = $mediaID;

        var_dump($this->client->cookie->getCookie("csrftoken"));

        $this->prepareRequest();
        $result = curl_exec($this->curl);

        var_dump($result);
    }
}