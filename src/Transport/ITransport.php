<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 25/04/2018
 * Time: 16:46
 */

namespace InstagramAmAPI\Transport;

/**
 * Interface ITransport
 * @package InstagramAmAPI\Transport
 */
interface ITransport
{

    public function init();

    public function setProxy(string $proxy);

    public function setHeaders($headers);

    public function setPost($flag = false);

    public function setPostData($post_data);

    public function setUrl($url);

    public function setTimeout($timeout = 15);

    public function close();

    public function getCookie();

    public function send();

    public function getRequestInfo();

    public function addAttachment($attachment);
}