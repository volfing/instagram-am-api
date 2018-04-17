<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 14/04/2018
 * Time: 17:28
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\AuthorizedRequest;

/**
 * Class RequestMediaDeleteComment
 * @package InstagramAmAPI\Request
 */
class RequestMediaDeleteComment extends AuthorizedRequest
{
    protected function init($url = "", $params = null)
    {
        $id = $this->data['id'];
        $comment_id = $this->data['comment_id'];

        if (preg_match("/\d+_\d+/", $id)) {
            $id = explode("_", $id)[0];
        }

        $this->instagram_url = self::INSTAGRAM_URL;
        $url = "web/comments/" . $id . "/delete/" . $comment_id . "/";
        parent::init($url, $params);
        $this->setPost(true);
        $this->setPostData([]);
    }

}