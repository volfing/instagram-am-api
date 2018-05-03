<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 01.05.2018
 * Time: 20:56
 */

namespace InstagramAmAPI\Request;

/**
 * Class Direct
 * @package InstagramAmAPI\Request
 */
class Direct extends Request
{
    /**
     * @param null $maxID
     * @return array
     */
    public function getListOfMessages($maxID = null){
        $request = new RequestDirectGetMessageList($this->client, []);

        return $request->send();
    }

    public function sendText($users, $text)
    {
        $request = new RequestDirectSendText($this->client,
            [
               'users' => [$users],
               'text' => $text
            ]);
        $response = $request->send();
        var_dump($response);
        return $response;
    }

    public function sendPhoto()
    {

    }
}