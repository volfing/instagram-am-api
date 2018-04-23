<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 23/04/2018
 * Time: 20:30
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\AuthorizedRequest;

/**
 * Class RequestStoriesFeed
 * @package InstagramAmAPI\Request
 */
class RequestStoriesFeed extends AuthorizedRequest
{
    protected function init($url = "", $params = null)
    {
        $this->instagram_url = self::GRAPHQL_API_URL;
        $variables = [
            'reel_ids' => json_encode($this->data['reel_ids']),
            'tag_names' => $this->data['tag_names'],
            'location_ids' => $this->data['location_ids'],
            'precomposed_overlay' => false,
        ];
        $variables = array_filter($variables);
        $params = [
            'query_hash' => QueryProperty::QUERY_HASH_STORIES_FEED,
            'variables' => json_encode($variables)
        ];
        parent::init($url, $params);
        $this->addQuerySignature($params);
    }

}