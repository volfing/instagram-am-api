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
        $this->storage->loadCookie();
        $this->curl = curl_init($this->instagram_url . "/web/likes/" . $this->mediaID . "/like/");
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($this->data));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, "");
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            "Cookie: rur=FTW; csrftoken=" . $this->storage->getCookie("csrftoken") . "; mid=" . $this->storage->getCookie("mid") . "; ig_vw=1915; ig_pr=1; ig_vh=937",
            "Referer: https://www.instagram.com/",
            "x-csrftoken: " . $this->storage->getCookie("csrftoken"),
            "x-instagram-ajax: 1",
            "x-requested-with: XMLHttpRequest",
            "Content-Type: application/x-www-form-urlencoded",

        ));
    }

    public function like($mediaID){
        $this->mediaID = $mediaID;

        var_dump($this->storage->getCookie("csrftoken"));

        $this->prepareRequest();
        $result = curl_exec($this->curl);

        var_dump($result);
    }
}