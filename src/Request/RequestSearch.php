<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 22/04/2018
 * Time: 13:09
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\NonAuthorizedRequest;

/**
 * Search users, hashtags, locations
 *
 * Class RequestSearch
 * @package InstagramAmAPI\Request
 */
class RequestSearch extends NonAuthorizedRequest
{
    /**
     * @inheritdoc
     */
    protected function init($url = "", $params = null)
    {
        $this->instagram_url = self::INSTAGRAM_URL;
        $url = "web/search/topsearch/";

        $rank_token = 1;
        if (isset($this->data['rank_token']) && !empty($this->data['rank_token'])) {
            $rank_token = $this->data['rank_token'];
        }
        $params = [
            "content" => "blended",
            "query" => $this->data['query'],
            "rank_token" => $rank_token,
        ];
        parent::init($url, $params);

    }

}