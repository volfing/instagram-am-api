<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 19/04/2018
 * Time: 15:17
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\AuthorizedRequest;

/**
 * Class RequestTimelineFeed
 * @package InstagramAmAPI\Request
 */
class RequestTimelineFeed extends AuthorizedRequest
{
    protected function init($url = "", $params = null)
    {
        $this->instagram_url = self::GRAPHQL_API_URL;
        $variables = [
            'fetch_media_item_count' => $this->data['count'],
            'fetch_media_item_cursor' => $this->data['max_id'],
            'fetch_comment_count' => 4,
            'fetch_like' => 10,
            'has_stories' => false,
        ];
        $variables = array_filter($variables);
        $params = [
            'query_hash' => QueryProperty::QUERY_HASH_USER_TIMELINE_FEED,
            'variables' => json_encode($variables)
        ];
        parent::init($url, $params);
        $this->addQuerySignature($params);
    }

}