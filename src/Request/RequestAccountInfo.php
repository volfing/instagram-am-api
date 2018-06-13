<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 13/06/2018
 * Time: 04:29
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\AuthorizedRequest;

/**
 * Запрос на получение данных о профиле аккаунта
 * Дата создания аккаунта, настройки приватности, изменение пароля и тд.
 *
 * Class RequestAccountInfo
 * @package InstagramAmAPI\Request
 */
class RequestAccountInfo extends AuthorizedRequest
{
    protected function init($url = "", $params = null)
    {
        $this->instagram_url = "https://www.instagram.com";

        $variables = [
            "__a" => 1
        ];
        $url = "/accounts/access_tool/";
        parent::init($url, $variables);
        $this->addHeader("Referer", "https://www.instagram.com/accounts/access_tool/");
        $this->addQuerySignature($variables, $this->instagram_url . $url);
    }

}