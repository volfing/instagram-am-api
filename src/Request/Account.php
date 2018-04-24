<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 12.04.2018
 * Time: 13:52
 */

namespace InstagramAmAPI\Request;

use InstagramAmAPI\Exception\BadResponseException;
use InstagramAmAPI\Exception\InstagramException;
use InstagramAmAPI\Model\ModelHelper;
use InstagramAmAPI\Response\ResponseAccounts;
use InstagramAmAPI\Response\ResponseMediaFeed;

/**
 * Class Account
 * @package InstagramAmAPI\Request
 */
class Account extends Request
{

    /**
     * @return \InstagramAmAPI\Model\Account
     */
    public function getSelfInfo()
    {
        return $this->getByUsername($this->client->getUsername());
    }

    /**
     * Получение информации об instagram аккаунте по его ID
     * @param int $userID
     * @return array|Account
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
     * @return \InstagramAmAPI\Model\Account
     * @throws BadResponseException
     */
    public function getByUsername($username)
    {
        $request = new RequestUserInfo($this->client, [
            "username" => $username
        ]);
        $response = $request->send();
        if (is_array($response)) {
            $medias = [];
            $user_info = $response["graphql"]["user"];
            $response = $user_info["edge_owner_to_timeline_media"]["edges"];
            foreach ($response as $media_node) {
                $media_node = $media_node["node"];
                $model = ModelHelper::loadMediaFromNode($media_node);
                $medias[] = $model;
            }
            return new \InstagramAmAPI\Model\Account([
                "id" => $user_info["id"],
                "username" => $user_info["username"],
                "profile_pic_url" => $user_info["profile_pic_url"],
                "medias" => $medias,

            ]);
        }
        throw new BadResponseException("");
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
        $request = new RequestFollow($this->client, ['id' => $userID]);
        $response = $request->send();
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
     * @throws BadResponseException
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
        throw new BadResponseException("");
    }

    /**
     * Получение списка публикаций пользователя по его ID
     * @param int $userID
     * @param int $count
     * @param null|string $maxID
     * @return ResponseMediaFeed
     * @throws BadResponseException
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
            $response = $response["data"]["user"]["edge_owner_to_timeline_media"];
            $next_max_id = null;
            if ($response['page_info']['has_next_page']) {
                $next_max_id = $response['page_info']['end_cursor'];
            }
            $count = $response['count'];
            $response = $response['edges'];
            $media = [];
            foreach ($response as $media_node) {
                $media_node = $media_node["node"];
                $model = ModelHelper::loadMediaFromNode($media_node);
                $media[] = $model;
            }
            return new ResponseMediaFeed([
                'next_max_id' => $next_max_id,
                'count' => $count,
                'items' => $media
            ]);
        }
        throw new BadResponseException("");
    }

    /**
     * Получение списка публикаций пользователя по его логину
     * @param $username
     * @param int $count
     * @param null $maxID
     * @return ResponseMediaFeed
     */
    public function loadMediasByUsername($username, $count = 10, $maxID = null)
    {
        $user = $this->getByUsername($username);
        $user_id = $user->id;
        return $this->loadMediasById($user_id, $count, $maxID);
    }

    /**
     * Получение списка подписчиков пользователя
     *
     * @param $user_id
     * @param null $max_id
     * @param int $count
     * @return ResponseAccounts
     * @throws InstagramException
     */
    public function followers($user_id, $max_id = null, $count = 50)
    {
        $request = new RequestUserFollowers($this->client, [
            'id' => $user_id,
            'after' => $max_id,
            'count' => $count,
        ]);
        $response = $request->send();
        if (is_array($response)) {
            $followers = [];
            $response = $response['data']['user']['edge_followed_by'];
            $next_max_id = null;
            if ($response['page_info']['has_next_page']) {
                $next_max_id = $response['page_info']['end_cursor'];
            }
            $count = $response['count'];
            $response = $response['edges'];
            foreach ($response as $item) {
                $item = $item["node"];
                $followers[] = new \InstagramAmAPI\Model\Account([
                    'id' => $item['id'],
                    'username' => $item['username'],
                    'full_name' => $item['full_name'],
                    'profile_pic_url' => $item['profile_pic_url'],
                ]);
            }
            return new ResponseAccounts([
                'next_max_id' => $next_max_id,
                'count' => $count,
                'items' => $followers,
            ]);
        }
        throw new BadResponseException("");
    }

    /**
     * Получение списка подписок пользователя
     *
     * @param $user_id
     * @param null $max_id
     * @param int $count
     * @return ResponseAccounts
     * @throws BadResponseException
     */
    public function followings($user_id, $max_id = null, $count = 50)
    {
        $request = new RequestUserFollowings($this->client, [
            'id' => $user_id,
            'after' => $max_id,
            'count' => $count,
        ]);
        $response = $request->send();

        if (is_array($response)) {
            $followings = [];
            $response = $response['data']['user']['edge_follow'];
            $next_max_id = null;
            if ($response['page_info']['has_next_page']) {
                $next_max_id = $response['page_info']['end_cursor'];
            }
            $count = $response['count'];
            $response = $response['edges'];
            foreach ($response as $item) {
                $item = $item["node"];
                $followings[] = new \InstagramAmAPI\Model\Account([
                    'id' => $item['id'],
                    'username' => $item['username'],
                    'full_name' => $item['full_name'],
                    'profile_pic_url' => $item['profile_pic_url'],
                ]);
            }
            return new ResponseAccounts([
                'next_max_id' => $next_max_id,
                'count' => $count,
                'items' => $followings,
            ]);
        }
        throw new BadResponseException("");
    }

    /**
     * @param int $count
     * @param null|string $max_id
     * @return ResponseMediaFeed
     * @throws BadResponseException
     */
    public function timelineFeed($count = 12, $max_id = null)
    {
        $request = new RequestTimelineFeed($this->client, [
            'count' => $count,
            'max_id' => $max_id,
        ]);
        $response = $request->send();
        if (is_array($response)) {
            $response = $response['data']['user']['edge_web_feed_timeline'];
            $next_max_id = null;
            if ($response['page_info']['has_next_page']) {
                $next_max_id = $response['page_info']['end_cursor'];
            }
            $response = $response['edges'];
            $medias = [];
            foreach ($response as $node) {
                $node = $node['node'];
                $medias[] = ModelHelper::loadMediaFromNode($node);
            }
            return new ResponseMediaFeed([
                'next_max_id' => $next_max_id,
                'count' => $count,
                'items' => $medias,
            ]);
        }
        throw new BadResponseException("");
    }

    /**
     * @return array
     * @throws BadResponseException
     */
    public function getStoriesReels()
    {
        $request = new RequestStoriesReels($this->client);
        $response = $request->send();
        if (is_array($response)) {
            $response = $response['data']['user']['feed_reels_tray']['edge_reels_tray_to_reel']['edges'];
            $stories = [];
            foreach ($response as $item) {
                $item = $item['node'];
                $stories[] = [
                    'id' => $item['id'],
                    'expiring_at' => $item['expiring_at'],
                    'latest_reel_media' => $item['latest_reel_media'],
                    'ranked_position' => $item['ranked_position'],
                    'seen' => $item['seen'],
                    'seen_ranked_position' => $item['seen_ranked_position'],
                    'owner' => [
                        'id' => $item['owner']['id'],
                        'username' => $item['owner']['username'],
                    ]
                ];
            }
            return $response;
        }
        throw new BadResponseException("");
    }

    /**
     * @param $reel_ids
     * @return array
     */
    public function getStoriesFeed($reel_ids)
    {
//        TODO: не работает
        return null;
        $request = new RequestStoriesFeed($this->client, [
            'reel_ids' => [ $reel_ids ]
        ]);
        $response = $request->send();
        return $response;

    }

    /**
     * @param null $max_id
     * @param null $search
     * @return ResponseAccounts
     */
    public function getSelfFollowers($max_id = null, $search = null)
    {
        $user = $this->getByUsername($this->client->getUsername());
        $user_id = $user->id;
        return $this->followers($user_id, $max_id);
    }

    /**
     * @param null $max_id
     * @param null $search
     * @return ResponseAccounts
     */
    public function getSelfFollowings($max_id = null, $search = null)
    {
        $user = $this->getByUsername($this->client->getUsername());
        $user_id = $user->id;
        return $this->followings($user_id, $max_id);
    }
}