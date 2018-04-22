<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 22/04/2018
 * Time: 13:07
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\AuthorizedRequest;

/**
 * Search location_id by latitude and longitude
 *
 * Class RequestSearchLocation
 * @package InstagramAmAPI\Request
 */
class RequestSearchLocation extends AuthorizedRequest
{
    /**
     * @inheritdoc
     */
    protected function init($url = "", $params = null)
    {
        $this->instagram_url = self::INSTAGRAM_URL;
        $url = "location_search/";
        parent::init($url, $params);
        $post_data = [
            'search_query' => $this->data['query'],
            'latitude' => $this->data['latitude'],
            'longitude' => $this->data['longitude'],
        ];
        $this->setPostData($post_data);
    }

}