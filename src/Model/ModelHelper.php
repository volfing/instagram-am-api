<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 17/04/2018
 * Time: 20:38
 */

namespace InstagramAmAPI\Model;

/**
 * Класс занимается сбором данных из ответа сервера
 *
 * Class ModelHelper
 * @package InstagramAmAPI\Model
 */
class ModelHelper
{
    /**
     * @param array $media_node
     * @return Media
     */
    public static function loadMediaFromNode($media_node)
    {
        $photos = [];
        if (isset($media_node['thimbnail_resources']) || isset($media_node['display_resources'])) {
            $photo_nodes = [];
            if (isset($media_node['thimbnail_resources'])) {
                $photo_nodes = $media_node['thimbnail_resources'];
            } elseif (isset($media_node['display_resources'])) {
                $photo_nodes = $media_node['display_resources'];
            }
            foreach ($photo_nodes as $image) {
                $photos[] = new Photo([
                    "src" => $image["src"],
                    "width" => $image["config_width"],
                    "height" => $image["config_height"],
                ]);
            }
        }
        $message = "";
        $message_node = $media_node["edge_media_to_caption"]["edges"];
        if (is_array($message_node) && !empty($message_node)) {
            if (isset($message_node[0]["text"])) {
                $message = $message_node[0]["text"];
            } elseif (isset($message_node[0]["node"])) {
                $message = $message_node[0]["node"]["text"];
            }
        }
        $type = "image";
        if (isset($media_node["__typename"])) {
            $type = $media_node["__typename"];
        }

        $owner = self::loadAccount($media_node['owner']);
        $data = [
            "id" => $media_node["id"],
            "owner" => $owner,
            "shortcode" => $media_node["shortcode"],
            "dateOfPublish" => $media_node["taken_at_timestamp"],
            "numOfComments" => $media_node["edge_media_to_comment"]["count"],
            "numOfLikes" => $media_node["edge_media_preview_like"]["count"],
            "type" => $type,
            "message" => $message,
            "photos" => $photos,
        ];

        if (isset($media_node['edge_media_to_comment']) && isset($media_node['edge_media_to_comment']['edges'])) {
            $comments = [];
            foreach ($media_node['edge_media_to_comment']['edges'] as $comment_node) {
                $comment_node = $comment_node['node'];
                $owner = ModelHelper::loadAccount($comment_node['owner']);
                $comments[] = new Comment([
                    'id' => $comment_node['id'],
                    'text' => $comment_node['text'],
                    'created_at' => $comment_node['created_at'],
                    'owner' => $owner,
                ]);
            }
            $data['comments'] = $comments;
        }
        if (isset($media_node['edge_media_preview_like']) && isset($media_node['edge_media_preview_like']['edges'])) {
            $likes = [];
            foreach ($media_node['edge_media_preview_like']['edges'] as $like_node) {
                $like_node = $like_node['node'];
                $likes[] = self::loadAccount($like_node);
            }
            $data['likes'] = $likes;
        }
        $model = new Media($data);
        return $model;
    }

    /**
     * @param $node
     * @return Location
     */
    public static function loadLocation($node)
    {
        $location = new Location([
            'id' => $node['pk'],
            'name' => $node['name'],
            'address' => $node['address'],
            'city' => $node['city'],
            'short_name' => $node['short_name'],
            'longitude' => $node['lng'],
            'latitude' => $node['lat'],
            'external_source' => $node['external_source'],
            'facebook_places_id' => $node['facebook_places_id'],
        ]);
        return $location;
    }

    /**
     * @param array $owner
     * @return Account
     */
    public static function loadAccount($owner)
    {
        $account_data = [];
        $account_data['id'] = $owner['id'];

        if (isset($owner['username'])) {
            $account_data['username'] = $owner['username'];
        }
        if (isset($owner['profile_pic_url'])) {
            $account_data['profile_pic_url'] = $owner['profile_pic_url'];
        }
        if (isset($owner['full_name'])) {
            $account_data['full_name'] = $owner['full_name'];
        }
        if (isset($owner['is_private'])) {
            $account_data['is_private'] = $owner['is_private'];
        }

        $account = new Account($account_data);
        return $account;
    }
}