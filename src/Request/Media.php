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
        return true;
    }

    /**
     * Комментарий под публикацией
     * @param string $message
     * @param string $mediaID
     * @return int $commentID
     */
    public function comment($message, $mediaID)
    {
        return;
    }

    /**
     * Удаление комментария под публикацией
     * @param string $message
     * @param string $mediaID
     * @return bool
     */
    public function removeComment($message, $mediaID)
    {
        return true;
    }

    /**
     * Получение публикации по ее $mediaID
     * @param $mediaID
     * @return Media
     */
    public function getById($mediaID)
    {
        return;
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