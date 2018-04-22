<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 18/04/2018
 * Time: 03:23
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\NonAuthorizedRequest;

/**
 * Class RequestMediaLikes
 * @package InstagramAmAPI\Request
 */
class RequestMediaLikes extends NonAuthorizedRequest
{
    /**
     * @inheritdoc
     */
    protected function init($url = "", $params = null)
    {
        $this->instagram_url = self::GRAPHQL_API_URL;
        $count_items = 24;
        if (!empty($this->data['count'])) {
            $count_items = (int)$this->data['count'];
        }

        $variables = [
            'shortcode' => $this->data['shortcode'],
            'first' => $count_items,
            'after' => $this->data['after'],
        ];
        $variables = array_filter($variables);

        $params = [
            "query_hash" => QueryProperty::QUERY_HASH_MEDIA_LIKES,
            "variables" => json_encode($variables)
        ];
        parent::init($url, $params);
        $this->addHeader("User-Agent", "");
        $this->addQuerySignature($params);
    }

}