<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 11/04/2018
 * Time: 21:00
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\Request as BaseRequest;

/**
 * Class RequestLogin
 * @package InstagramAmAPI\Request
 */
class RequestLogin extends BaseRequest
{
    private $login_url = "/accounts/login/ajax/";

    protected function init($url = "")
    {
        parent::init($this->login_url);
        $this->setPost(true);
        $this->setPostData($this->data);
        $this->setHeaders([
            "Cookie: rur=" . $this->client->cookie->getCookie("rur") . "; csrftoken=" . $this->client->cookie->getCookie("csrftoken") . "; mid=" . $this->client->cookie->getCookie("mid") . "; ig_vw=1915; ig_pr=1; ig_vh=937;",
            "Referer: https://www.instagram.com/",
            "x-csrftoken: " . $this->client->cookie->getCookie("csrftoken"),
            "x-instagram-ajax: 1",
            "x-requested-with: XMLHttpRequest",
            "Content-Type: application/x-www-form-urlencoded",
        ]);
    }

    public function send()
    {
        $emptyRequest = new BaseRequest($this->client);
        $emptyRequest->send();
        return parent::send();
    }


}