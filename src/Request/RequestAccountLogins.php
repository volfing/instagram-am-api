<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 13/06/2018
 * Time: 04:57
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\AuthorizedRequest;

/**
 * Class RequestAccountLogins
 * @package InstagramAmAPI\Request
 */
class RequestAccountLogins extends AuthorizedRequest
{
    protected function init($url = "", $params = null)
    {
        $this->instagram_url = "https://www.instagram.com";

        $variables = [
            "__a" => 1
        ];
        if (!empty($this->data['cursor'])) {
            $variables['cursor'] = $this->data['cursor'];
        }
        $url = "/accounts/access_tool/logins/";
        parent::init($url, $variables);
        $this->addHeader("Referer", "https://www.instagram.com/accounts/access_tool/logins/");
        $this->addQuerySignature($variables, $this->instagram_url . $url);
    }

}