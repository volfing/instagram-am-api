<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 12.04.2018
 * Time: 13:52
 */

namespace InstagramAmAPI\Request;

use InstagramAmAPI\Model\Media;
use InstagramAmAPI\Model\ModelHelper;
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
                $media_node = $media_node["node"];
                $model = ModelHelper::loadMediaFromNode($media_node);
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

    /**
     * Публикация фотографии в instagram
     * @param string $photo_data
     * @param string $message
     * @return int $mediaID
     */
    public function postMedia($photo_data, $message = "")
    {
        $request = new RequestPostPhoto($this->client,
            [
                'photo_data' => $photo_data,
                'message' => $message,
            ]);
        $response = $request->send();
        return $response;
    }

    /**
     * Удаление публикации по ее id
     * @param $mediaID
     * @return bool
     */
    public function deleteMediaById($mediaID)
    {
        $request = new RequestDeletePhoto($this->client,
            [
                'media_id' => $mediaID
            ]);
        $response = $request->send();
        if ($response['did_delete'] == 1) {
            return true;
        }
        return null;
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
                $model = ModelHelper::loadMediaFromNode($media_node);
                $media[] = $model;
            }
            return $media;
        }
        return null;
    }

    /**
     * Получение списка публикаций пользователя по его логину
     * @param $username
     * @param int $count
     * @param null $maxID
     * @return array|Media[]
     */
    public function loadMediasByUsername($username, $count = 10, $maxID = null)
    {
        $user = $this->getByUsername($username);
        $user_id = $user['id'];
        return $this->loadMediasById($user_id, $count, $maxID);
    }

}