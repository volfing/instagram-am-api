<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 12.04.2018
 * Time: 13:52
 */

namespace InstagramAmAPI\Request;

class Account extends Request
{
    /*
     * Получение информации об instagram аккаунте по его ID
     * return Account
     */
    public function getById($userID)
    {
        $request = new RequestUserInfoById($this->client, ['id' => $userID]);
        $response = $request->send();
        var_dump($response);
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
                $comments = [];
                $photos = [];
                foreach ($media_node["node"]["thumbnail_resources"] as $image) {
                    $photos[] = [
                        "src" => $image["src"],
                        "width" => $image["config_width"],
                        "height" => $image["config_height"],
                    ];
                }
                $data = [
                    "id" => $media_node["node"]["id"],
                    "owner" => $media_node["node"]["owner"]["id"],
                    "dateOfPublish" => $media_node["node"]["taken_at_timestamp"],
                    "numOfComments" => $media_node["node"]["edge_media_to_comment"]["count"],
                    "numOfLikes" => $media_node["node"]["edge_liked_by"]["count"],
                    "type" => $media_node["node"]["__typename"],
                    "message" => $media_node["node"]["edge_media_to_caption"]["edges"][0]["node"]["text"],
                    "comments" => $comments,
                    "photos" => $photos,
                ];
//                TODO: Не работает пока...Надо кое-где поменять типы в json map + не инициализируются пустые массивы с объектами Photo, Comment...
//                $model = new \InstagramAmAPI\Model\Media($data);
                $media[] = $data;
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

    /*
     * Подписка на пользователя по его ID
     * return boolean
     */
    public function followById($userID)
    {
        $reques = new RequestFollow($this->client, ['id' => $userID]);
        return $reques->send();
    }

    /*
     * Подписка на пользователя по его логину
     * return boolean
     */
    public function followByUsername($username)
    {
        return true;
    }

    /**
     * Отписка от пользователя по его ID
     * @param int $userID
     * @return bool|array
     */
    public function unFollowById($userID)
    {
        $request = new RequestUnfollow($this->client, ['id' => $userID]);
        return $request->send();
    }

    /*
     * Отписка от пользователя по его логину
     * return boolean
     */
    public function unFollowByUsername($username)
    {
        return true;
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
    public function loadMediasById($userID, $maxID = null)
    {
        $request = new RequestUserFeed($this->client, ['id' => $userID]);
//        TODO: обработать данные на выходе в аккуратный массив объектов Media
        return $request->send();
    }

    /*
     * Получение списка публикаций пользователя по его логину
     * return Media[]
     */
    public function loadMediasByUsername($username, $maxID = null)
    {
        return;
    }

}