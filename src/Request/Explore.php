<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 12.04.2018
 * Time: 14:15
 */

namespace InstagramAmAPI\Request;

use InstagramAmAPI\Exception\BadResponseException;
use InstagramAmAPI\Model\ModelHelper;
use InstagramAmAPI\Model\Venue;
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
     * @throws BadResponseException
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
        throw new BadResponseException("");
    }

    /**
     * Поиск публикакций по ID локации
     * @param $locationID
     * @return ResponseMediaFeed
     * @throws BadResponseException
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
        throw new BadResponseException("");
    }

    /**
     * Search venues
     * {
     *      "venues": [
     *          {
     *              "lat": 55.755833333333,
     *              "lng": 37.617777777778,
     *              "address": "Moscow",
     *              "external_id": "107881505913202",
     *              "external_id_source": "facebook_places",
     *              "name": "Moscow",
     *              "minimum_age": 0
     *          }
     *      ]
     * }
     *
     * @param $latitude
     * @param $longitude
     * @return Venue[]
     * @throws BadResponseException
     */
    public function searchLocation($latitude, $longitude)
    {
        $request = new RequestSearchLocation($this->client, [
            'query' => '',
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
        $response = $request->send();
        if (is_array($response)) {
            $response = $response['venues'];
            $venues = [];
            foreach ($response as $item) {
                $venue = new Venue([
                    'latitude' => $item['lat'],
                    'longitude' => $item['lng'],
                    'address' => $item['address'],
                    'external_id' => $item['external_id'],
                    'external_id_source' => $item['external_id_source'],
                    'minimum_age' => $item['minimum_age'],
                ]);
                $venues[] = $venue;

            }
            return $venues;
        }
        throw new BadResponseException("");
    }

    public function search($query, $rank_token = 1)
    {
        $request = new RequestSearch($this->client, [
            'query' => $query,
            'rank_token' => $rank_token
        ]);
        $response = $request->send();
        return $response;
    }


}