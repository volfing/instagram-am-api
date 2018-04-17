<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 12.04.2018
 * Time: 14:15
 */

namespace InstagramAmAPI\Request;

/**
 * Class Explore
 * @package InstagramAmAPI\Request
 */
class Explore extends Request
{
    /**
     * Поиск публикакций по хештегу
     * @param $tag
     * @return Explore|array
     */
    public function searchByTag($tag){
        $request = new RequestTagFeed($this->client, [
            "tag" => $tag
        ]);
        return $request->send();
    }

    /**
     * Поиск публикакций по ID локации
     * @param $locationID
     * @return Explore|array
     */
    public function searchByLocationId($locationID){
        $request = new RequestLocationFeed($this->client, [
            "location_id" => $locationID
        ]);
        return $request->send();
    }

}