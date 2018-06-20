<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 14/04/2018
 * Time: 01:03
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\NonAuthorizedRequest;

/**
 * Class RequestUserFeed
 * @package InstagramAmAPI\Request
 */
class RequestUserFeed extends NonAuthorizedRequest
{
    /**
     * @inheritdoc
     */
    protected function init($url = "", $params = null)
    {
        $this->instagram_url = self::GRAPHQL_API_URL;
        $count_items = 10;
        if (!empty($this->data['count'])) {
            $count_items = (int)$this->data['count'];
        }
        $variables = [
            'id' => $this->data['id'],
            'first' => $count_items,
            'after' => $this->data['after'],
        ];
        $variables = array_filter($variables);

        $params = [
            "query_hash" => QueryProperty::QUERY_HASH_USER_MEDIA_FEED,
            "variables" => json_encode($variables)
        ];


        parent::init("", $params);
        $this->addHeader("User-Agent", "");
        $this->addQuerySignature($params);
    }


}