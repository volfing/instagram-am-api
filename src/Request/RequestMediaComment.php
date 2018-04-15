<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 14/04/2018
 * Time: 17:19
 */

namespace InstagramAmAPI\Request;

use InstagramAmAPI\AuthorizedRequest;
use InstagramAmAPI\Exception\InstagramException;

/**
 * Class RequestMediaComment
 * @package InstagramAmAPI\Request
 */
class RequestMediaComment extends AuthorizedRequest
{
    protected function init($url = "", $params = null)
    {
        $id = $this->data['id'];
        $message = $this->data['message'];
        if (mb_strlen($message) > 300) {
            throw new InstagramException("The total length of the comment cannot exceed 300 characters.");
        } elseif ($message == mb_strtoupper($message)) {
            throw new InstagramException("The comment cannot consist of all capital letters.");
        }
//        TODO: добавить больше проверок

        if (preg_match("/\d+_\d+/", $id)) {
            $id = explode("_", $id)[0];
        }
        $this->instagram_url = self::INSTAGRAM_URL;
        $url = "web/comments/" . $id . "/add/";
        parent::init($url);

        $params = [
            "comment_text" => $message
        ];
        $this->setPost(true);
        $this->setPostData($params);
    }

}