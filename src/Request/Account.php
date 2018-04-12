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
    public function getById($userID){
        return;
    }

    /*
     * Получение информации об instagram аккаунте по логину
     * return Account
     */
    public function getByUsername($username){
        return;
    }

    /*
     * Подписка на пользователя по его ID
     * return boolean
     */
    public function followById($userID){
        return true;
    }

    /*
     * Подписка на пользователя по его логину
     * return boolean
     */
    public function followByUsername($username){
        return true;
    }

    /*
     * Отписка от пользователя по его ID
     * return boolean
     */
    public function unFollowById($userID){
        return true;
    }

    /*
     * Отписка от пользователя по его логину
     * return boolean
     */
    public function unFollowByUsername($username){
        return true;
    }

    /*
     * Публикация фотографии в instagram
     * return int $mediaID
     */
    public function postMedia($message, $photo){
        return;
    }

    /*
     * Удаление публикации по ее id
     * return boolean
     */
    public function deleteMediaById($mediaID){
        return true;
    }

    /*
     * Получение списка публикаций пользователя по его ID
     * return Media[]
     */
    public function loadMediasById($userID, $maxID = null){
        return;
    }

    /*
     * Получение списка публикаций пользователя по его логину
     * return Media[]
     */
    public function loadMediasByUsername($username, $maxID = null){
        return;
    }

}