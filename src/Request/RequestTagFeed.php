<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 17/04/2018
 * Time: 19:42
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\NonAuthorizedRequest;

/**
 * Class RequestTagFeed
 * @package InstagramAmAPI\Request
 */
class RequestTagFeed extends NonAuthorizedRequest
{
    /**
     * @inheritdoc
     */
    protected function init($url = "", $params = null)
    {
        $this->instagram_url = self::GRAPHQL_API_URL;
        $url = "";

        $variables = [
            'tag_name' => $this->data['tag'],
            'first' => 10,
            'after' => $this->data['after']
        ];
        $variables = array_filter($variables);
        $params = [
            'query_hash' => QueryProperty::QUERY_HASH_TAG_FEED,
            'variables' => json_encode($variables)
        ];
        parent::init($url, $params);
        $this->addQuerySignature($params, "/" . $url);
    }


}