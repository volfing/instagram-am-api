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
        foreach ($media_node["thumbnail_resources"] as $image) {
            $photos[] = new Photo([
                "src" => $image["src"],
                "width" => $image["config_width"],
                "height" => $image["config_height"],
            ]);
        }
        $message = $media_node["edge_media_to_caption"]["edges"];
        if (is_array($message) && !empty($message)) {
            if (isset($message[0]["text"])) {
                $message = $message[0]["text"];
            } elseif (isset($message[0]["node"])) {
                $message = $message[0]["node"]["text"];
            }
        }
        $type = "image";
        if (isset($media_node["__typename"])) {
            $type = $media_node["__typename"];
        }

        $data = [
            "id" => $media_node["id"],
            "owner" => $media_node["owner"]["id"],
            "dateOfPublish" => $media_node["taken_at_timestamp"],
            "numOfComments" => $media_node["edge_media_to_comment"]["count"],
            "numOfLikes" => $media_node["edge_media_preview_like"]["count"],
            "type" => $type,
            "message" => $message,
            "photos" => $photos,
        ];
        $model = new Media($data);
        return $model;
    }
}