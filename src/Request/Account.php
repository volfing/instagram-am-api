<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 12.04.2018
 * Time: 13:52
 */

namespace InstagramAmAPI\Request;

use InstagramAmAPI\Exception\BadResponseException;
use InstagramAmAPI\Exception\ForbiddenInstagramException;
use InstagramAmAPI\Exception\InstagramException;
use InstagramAmAPI\Model\AccountEdit;
use InstagramAmAPI\Model\AccountInfo;
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
     * @throws \Exception
     */
    public function getById($userID)
    {
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
        var_dump($response);
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
                "biography" => $user_info["biography"],
                "is_private" => $user_info["is_private"],
                "profile_pic_url" => $user_info["profile_pic_url"],
                "medias" => $medias,
                "numOfFollowers" => $user_info['edge_followed_by']['count'],
                "numOfFollowings" => $user_info['edge_follow']['count'],
                "media_count" => $user_info['edge_owner_to_timeline_media']['count'],

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
        if ($response['result'] == 'requested') {
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
     * @throws \Exception
     */
    public function postMedia($photo_data, $message = "")
    {
        $request = new RequestPostPhoto($this->client,
            [
                'photo' => $photo_data,
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
                $followers[] = ModelHelper::loadAccount($item);
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
                $followings[] = ModelHelper::loadAccount($item);
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
                    'owner' => ModelHelper::loadAccount($item['owner'])
                ];
            }
            return $response;
        }
        throw new BadResponseException("");
    }

    /**
     * @param $reel_ids
     * @return array
     * @throws \Exception
     */
    public function getStoriesFeed($reel_ids)
    {
        throw new \Exception("NonImplementException");
//        TODO: не работает
        return null;
        $request = new RequestStoriesFeed($this->client, [
            'reel_ids' => [$reel_ids]
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

    /**
     * Запрос на получение данных о профиле аккаунта
     * Дата создания аккаунта, настройки приватности, изменение пароля и тд.
     * @return AccountInfo
     */
    public function getSelfInfoPrivacy()
    {
        $request = new RequestAccountInfo($this->client);
        $response = $request->send();
        return $response;
        $data = [
          'is_blocked' => $response['is_blocked'],
          'date_joined' => !empty($response['date_joined']) && !empty($response['date_joined']['data']) ? $response['date_joined']['data']['timestamp'] : null,
          'date_of_birth' => !empty($response['date_of_birth']) && !empty($response['date_of_birth']['data']) ? $response['date_of_birth']['data']['timestamp'] : null,
        ];
        return new AccountInfo($data);
    }

    /**
     * Отдает историю логинов
     *
     * @param null|string $cursor Указатель на начало страницы
     * @return array
     */
    public function getLogins($cursor = null)
    {
        $request = new RequestAccountLogins($this->client, ['cursor' => $cursor]);
        $response = $request->send();
        return $response;
    }

    /**
     * Запрос на страницу редактирования данных аккаунта
     * Получает данные:
     *  {
     *      "form_data":
     *      {
     *          "first_name":"",
     *          "last_name":"",
     *          "email":"",
     *          "username":"",
     *          "phone_number":"",
     *          "gender":1,
     *          "birthday":null,
     *          "biography":"",
     *          "external_url":"",
     *          "chaining_enabled":false,
     *          "private_account":false,
     *          "presence_disabled":false,
     *          "business_account":false,
     *          "usertag_review_enabled":false
     *      }
     *  }
     *
     * @return array
     */
    public function edit()
    {
        $request = new RequestAccountEdit($this->client);
        $response = $request->send();
        return new AccountEdit($response['form_data']);
    }
}