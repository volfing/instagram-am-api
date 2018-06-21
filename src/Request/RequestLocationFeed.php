<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 17/04/2018
 * Time: 19:49
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\AuthorizedRequest;
use InstagramAmAPI\NonAuthorizedRequest;

/**
 * Class RequestLocationFeed
 * @package InstagramAmAPI\Request
 */
class RequestLocationFeed extends NonAuthorizedRequest
{
    /**
     * @inheritdoc
     */
    protected function init($url = "", $params = null)
    {

        $this->instagram_url = self::GRAPHQL_API_URL;
        $url = "";

        $variables = [
            'id' => $this->data['location_id'],
            'first' => 12,
            'after' => $this->data['after']
        ];
        $variables = array_filter($variables);
        $params = [
            'query_hash' => QueryProperty::QUERY_HASH_LOCATION_FEED,
            'variables' => json_encode($variables)
        ];
        parent::init($url, $params);
        $this->addQuerySignature($params);
    }


}