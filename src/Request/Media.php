<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 12.04.2018
 * Time: 14:07
 */

namespace InstagramAmAPI\Request;


class Media extends Request
{
    /*
     * Лайк публикации по ее ID
     * return boolean
     */
    public function like($mediaID){
        return true;
    }

    /*
     * Удаление лайка с публикации по ее ID
     * return boolean
     */
    public function unlike($mediaID){
        return true;
    }

    /*
     * Комментарий под публикацией
     * return int $commentID
     */
    public function comment($message, $mediaID){
        return;
    }

    /*
     * Удаление комментария под публикацией
     * return boolean
     */
    public function removeComment($message, $mediaID){
        return true;
    }

    /*
     * Получение публикации по ее $mediaID
     * return Media
     */
    public function getById($mediaID){
        return;
    }

    /*
     * Получение списка комментариев к $mediaID
     * return Comment[]
     */
    public function getComments($mediaID, $maxID = null){
        return;
    }

    /*
     * Получение списка лайков к $mediaID
     * return Like[]
     */
    public function getLikes($mediaID, $maxID = null){
        return;
    }

}