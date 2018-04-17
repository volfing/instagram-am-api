<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 12.04.2018
 * Time: 13:52
 */

namespace InstagramAmAPI\Request;

use InstagramAmAPI\Model\Media;
use InstagramAmAPI\Model\Photo;

/**
 * Class Account
 * @package InstagramAmAPI\Request
 */
class Account extends Request
{
    /**
     * Получение информации об instagram аккаунте по его ID
     * @return Account|array
     */
    public function getById($userID)
    {
//        TODO: не работает...
        $request = new RequestUserInfoById($this->client, ['id' => $userID]);
        $response = $request->send();

        return $response;
    }

    /**
     * Получение информации об instagram аккаунте по логину
     * @param string $username
     * @return Account|array|null
     */
    public function getByUsername($username)
    {
        $request = new RequestUserInfo($this->client, [
            "username" => $username
        ]);
        $response = $request->send();
        if (is_array($response)) {
            $media = [];
            foreach ($response["graphql"]["user"]["edge_owner_to_timeline_media"]["edges"] as $media_node) {
//                TODO: Комментарии надо дополнительным запросов доставать.
                $photos = [];
                foreach ($media_node["node"]["thumbnail_resources"] as $image) {
                    $photos[] = new Photo([
                        "src" => $image["src"],
                        "width" => $image["config_width"],
                        "height" => $image["config_height"],
                    ]);
                }
                $message = $media_node["node"]["edge_media_to_caption"]["edges"];
                if (is_array($message) && !empty($message)) {
                    $message = $message[0]["node"]["text"];
                }
                $data = [
                    "id" => $media_node["node"]["id"],
                    "owner" => $media_node["node"]["owner"]["id"],
                    "dateOfPublish" => $media_node["node"]["taken_at_timestamp"],
                    "numOfComments" => $media_node["node"]["edge_media_to_comment"]["count"],
                    "numOfLikes" => $media_node["node"]["edge_liked_by"]["count"],
                    "type" => $media_node["node"]["__typename"],
                    "message" => $message,
                    "photos" => $photos,
                ];
                $model = new Media($data);
                $media[] = $model;
            }
            return [
                "id" => $response["graphql"]["user"]["id"],
                "username" => $response["graphql"]["user"]["username"],
                "profile_pic_url" => $response["graphql"]["user"]["profile_pic_url"],
                "media" => $media,

            ];
        }
        return null;
    }

    /**
     * Подписка на пользователя по его ID
     *
     * {
     *      "result": "following",
     *      "status": "ok"
     * }
     *
     * @param $userID
     * @return bool
     */
    public function followById($userID)
    {
        $response = new RequestFollow($this->client, ['id' => $userID]);
        if ($response['result'] == 'following') {
            return true;
        }
        return false;
    }

    /**
     * Подписка на пользователя по его логину
     * @param $username
     * @return bool
     */
    public function followByUsername($username)
    {
        $user = $this->getByUsername($username);
        $user_id = $user['id'];
        return $this->followById($user_id);
    }

    /**
     * Отписка от пользователя по его ID
     * @param int $userID
     * @return bool|array
     */
    public function unFollowById($userID)
    {
        $request = new RequestUnfollow($this->client, ['id' => $userID]);
        $response = $request->send();
        if ($response['status'] == 'ok') {
            return true;
        }
        return false;
    }

    /**
     * Отписка от пользователя по его логину
     * @param $username
     * @return bool
     */
    public function unFollowByUsername($username)
    {
        $user = $this->getByUsername($username);
        $user_id = $user['id'];
        return $this->unFollowById($user_id);
    }

    /*
     * Публикация фотографии в instagram
     * return int $mediaID
     */
    public function postMedia($message, $photo)
    {
        return;
    }

    /*
     * Удаление публикации по ее id
     * return boolean
     */
    public function deleteMediaById($mediaID)
    {
        return true;
    }

    /**
     * Получение списка публикаций пользователя по его ID
     * @return Media[]
     */
    public function loadMediasById($userID, $count = 10, $maxID = null)
    {
        $request = new RequestUserFeed($this->client, [
            'id' => $userID,
            'count' => $count,
            'after' => $maxID,
        ]);
        $response = $request->send();
        if (is_array($response)) {
            $media = [];
            foreach ($response["data"]["user"]["edge_owner_to_timeline_media"]["edges"] as $media_node) {
                $media_node = $media_node["node"];
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
                $data = [
                    "id" => $media_node["id"],
                    "owner" => $media_node["owner"]["id"],
                    "dateOfPublish" => $media_node["taken_at_timestamp"],
                    "numOfComments" => $media_node["edge_media_to_comment"]["count"],
                    "numOfLikes" => $media_node["edge_media_preview_like"]["count"],
                    "type" => $media_node["__typename"],
                    "message" => $message,
                    "photos" => $photos,
                ];
                $model = new Media($data);
                $media[] = $model;
            }
            return $media;
        }
        return null;
    }

    /**
     * Получение списка публикаций пользователя по его логину
     * @param $username
     * @param null $maxID
     * @return array|Media[]
     */
    public function loadMediasByUsername($username, $maxID = null)
    {
        $user = $this->getByUsername($username);
        $user_id = $user['id'];
        return $this->loadMediasById($user_id);
    }

}