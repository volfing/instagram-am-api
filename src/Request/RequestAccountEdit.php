<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 13/06/2018
 * Time: 05:03
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\AuthorizedRequest;

/**
 * Запрос на страницу редактирования данных аккаунта
 * Получает данные:
 *  {
 *      "form_data":
 *      {
 *          "first_name":"",
 *          "last_name":"",
 *          "email":"",
 *          "username":"",
 *          "phone_number":"",
 *          "gender":1,
 *          "birthday":null,
 *          "biography":"",
 *          "external_url":"",
 *          "chaining_enabled":false,
 *          "private_account":false,
 *          "presence_disabled":false,
 *          "business_account":false,
 *          "usertag_review_enabled":false
 *      }
 *  }
 *
 * Class RequestAccountEdit
 * @package InstagramAmAPI\Request
 */
class RequestAccountEdit extends AuthorizedRequest
{
    protected function init($url = "", $params = null)
    {
        $this->instagram_url = "https://www.instagram.com";

        $variables = [
            "__a" => 1
        ];
        $url = "/accounts/edit/";
        parent::init($url, $variables);
        $this->addHeader("Referer", "https://www.instagram.com/accounts/edit/");
        $this->addQuerySignature($variables, $this->instagram_url . $url);
    }

}