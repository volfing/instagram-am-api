<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 12.04.2018
 * Time: 14:07
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\Model\Comment;
use InstagramAmAPI\Model\Like;
use InstagramAmAPI\Model\Photo;

class Media extends Request
{
    /**
     * Лайк публикации по ее ID
     * @param string $mediaID
     * @return bool
     */
    public function like($mediaID)
    {
        $request = new RequestLike($this->client);
        $res = $request->like($mediaID);
        if ($res['status'] == 'ok') {
            return true;
        }
        return false;
    }

    /**
     * Удаление лайка с публикации по ее ID
     * @param string $mediaID
     * @return bool
     */
    public function unlike($mediaID)
    {
        $request = new RequestUnlike($this->client, [
            'id' => $mediaID
        ]);
        $res = $request->send();
        if ($res['status'] == 'ok') {
            return true;
        }
        return false;
    }

    /**
     * Комментарий под публикацией
     * @param string $message
     * @param string $mediaID
     * @return int $commentID
     */
    public function comment($message, $mediaID)
    {
        $request = new RequestMediaComment($this->client, [
            'message' => $message,
            'id' => $mediaID
        ]);
        $result = $request->send();
        return $result['id'];
    }

    /**
     * Удаление комментария под публикацией
     * @param string $mediaID
     * @param string $commentID
     * @return bool
     */
    public function removeComment($mediaID, $commentID)
    {
        $request = new RequestMediaDeleteComment($this->client, [
            'id' => $mediaID,
            'comment_id' => $commentID
        ]);
        $result = $request->send();
        if ($result['status'] == 'ok') {
            return true;
        }
        return false;
    }

    /**
     * Получение публикации по ее $mediaID
     * @param $mediaID
     * @return Media|array
     */
    public function getById($mediaID)
    {
//        TODO: Не работает
        $request = new RequestMediaInfo($this->client, [
            'id' => $mediaID
        ]);
        $response = $request->send();
        return $response;
    }

    public function getByShortCode($mediaShortcode)
    {
        $request = new RequestMediaInfoByShortcode($this->client, [
            'shortcode' => $mediaShortcode
        ]);
        $response = $request->send();
        if (is_array($response)) {
            $response = $response['graphql']['shortcode_media'];
            $photos = [];
            foreach ($response['display_resources'] as $display_resource) {
                $photos[] = new Photo([
                    'src' => $display_resource['src'],
                    'width' => $display_resource['config_width'],
                    'height' => $display_resource['config_height'],
                ]);
            }
            $message = "";
            if (isset($response['edge_media_to_caption']['edges'][0])) {
                $message = $response['edge_media_to_caption']['edges'][0]['node']['text'];
            }

            $media = new \InstagramAmAPI\Model\Media([
                'id' => $response['id'],
                "owner" => $response['owner']['id'],
                "dateOfPublish" => $response['taken_at_timestamp'],
                "numOfComments" => $response['edge_media_to_comment']['count'],
                "numOfLikes" => $response['edge_media_preview_like']['count'],
                "type" => $response['__typename'],
                "message" => $message,
//        "comments" => $comments,
                "photos" => $photos,
            ]);
            return $media;
        }
        return $response;
    }

    /**
     * Получение списка комментариев к $mediaID
     * @param string $mediaID
     * @param null|string $maxID
     * @return Comment[]
     */
    public function getComments($mediaID, $maxID = null)
    {
        return;
    }

    /**
     * Получение списка лайков к $mediaID
     * @param string $mediaID
     * @param null|string $maxID
     * @return Like[]
     */
    public function getLikes($mediaID, $maxID = null)
    {
        return;
    }

}