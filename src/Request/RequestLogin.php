<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 11/04/2018
 * Time: 21:00
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\Request;

/**
 * Class RequestLogin
 * @package InstagramAmAPI\Request
 */
class RequestLogin extends Request
{
    private $login_url = "/accounts/login/ajax/";

    public function prepareRequest()
    {
        $this->curl = curl_init($this->instagram_url . $this->login_url);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($this->data));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_file);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            "Cookie: rur=FTW; csrftoken=9IV0j85cIXJ3a3B3sjNnZSzmS16RD3Pt; mid=Ws47hAAEAAEbFgiJlzua3to_2TBO; ig_vw=1915; ig_pr=1; ig_vh=937",
            "Referer: https://www.instagram.com/",
            "x-csrftoken: 9IV0j85cIXJ3a3B3sjNnZSzmS16RD3Pt",
            "x-instagram-ajax: 1",
            "x-requested-with: XMLHttpRequest",
            "Content-Type: application/x-www-form-urlencoded",

        ));
    }


    public function send()
    {
        $this->prepareRequest();
        $result = curl_exec($this->curl);
        $result = json_decode($result, true);
        return $result;

    }

}