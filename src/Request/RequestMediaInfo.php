<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 14/04/2018
 * Time: 17:08
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\AuthorizedRequest;

/**
 * Class RequestMediaInfo
 * @package InstagramAmAPI\Request
 */
class RequestMediaInfo extends AuthorizedRequest
{
    protected function init($url = "", $params = null)
    {
        $this->instagram_url = self::API_URL;
        $params = [
            "q" => "ig_shortcode(" . $this->data['id'] . ") {caption, code, comments {count}, "
                . "date,dimensions {height, width}, comments_disabled,usertags {nodes {x, y, user {id, username, "
                . "full_name, profile_pic_url} }}, location {id, name, lat, lng}, display_src, id, "
                . "is_video, is_ad, likes {count}, owner {id, username, full_name, profile_pic_url, "
                . "is_private, is_verified}, __typename, thumbnail_src, video_views, video_url }"
        ];
        parent::init($url, $params);
    }

}