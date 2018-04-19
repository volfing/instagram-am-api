<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 12.04.2018
 * Time: 14:15
 */

namespace InstagramAmAPI\Request;

use InstagramAmAPI\Model\ModelHelper;
use InstagramAmAPI\Response\ResponseMediaFeed;

/**
 * Class Explore
 * @package InstagramAmAPI\Request
 */
class Explore extends Request
{
    /**
     * Поиск публикакций по хештегу
     * @param $tag
     * @return ResponseMediaFeed
     */
    public function searchByTag($tag)
    {
        $request = new RequestTagFeed($this->client, [
            "tag" => $tag
        ]);
        $response = $request->send();

        if (is_array($response)) {
            $response = $response['graphql']['hashtag']['edge_hashtag_to_media'];
            $count = $response['count'];
            $next_id = $response['page_info']['end_cursor'];
            $medias = [];
            foreach ($response['edges'] as $node) {
                $node = $node['node'];
                $media = ModelHelper::loadMediaFromNode($node);
                $medias[] = $media;
            }
            return new ResponseMediaFeed([
                'next_max_id' => $next_id,
                'count' => $count,
                'items' => $medias
            ]);
        }
        return null;
    }

    /**
     * Поиск публикакций по ID локации
     * @param $locationID
     * @return ResponseMediaFeed
     */
    public function searchByLocationId($locationID)
    {
        $request = new RequestLocationFeed($this->client, [
            "location_id" => $locationID
        ]);
        $response = $request->send();
        if (is_array($response)) {
            $response = $response['graphql']['location']['edge_location_to_media'];
            $count = $response['count'];
            $next_id = $response['page_info']['end_cursor'];
            $medias = [];
            foreach ($response['edges'] as $node) {
                $node = $node['node'];
                $media = ModelHelper::loadMediaFromNode($node);
                $medias[] = $media;
            }
            return new ResponseMediaFeed([
                'next_max_id' => $next_id,
                'count' => $count,
                'items' => $medias
            ]);
        }
        return null;
    }

}