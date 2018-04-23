<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 23/04/2018
 * Time: 16:33
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\AuthorizedRequest;

/**
 * Class RequestStories
 * @package InstagramAmAPI\Request
 */
class RequestStoriesReels extends AuthorizedRequest
{
    /**
     * @inheritdoc
     */
    protected function init($url = "", $params = null)
    {
        $this->instagram_url = self::GRAPHQL_API_URL;
        $variables = [
            'only_stories' => true
        ];
        $params = [
            'query_hash' => QueryProperty::QUERY_HASH_REELS_TRAY,
            'variables' => json_encode($variables)
        ];
        parent::init($url, $params);
        $this->addQuerySignature($params);
    }


}