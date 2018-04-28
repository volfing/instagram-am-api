<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 11/04/2018
 * Time: 20:56
 */

namespace InstagramAmAPI;

use InstagramAmAPI\Exception\InstagramException;
use InstagramAmAPI\Exception\ForbiddenInstagramException;
use InstagramAmAPI\Exception\NotFoundInstagramException;
use InstagramAmAPI\Transport\GuzzleTransport;
use InstagramAmAPI\Transport\ITransport;


/**
 * Class Request
 * @property array $data
 * @property array $headers
 * @property ITransport $transport
 * @property Client $client
 * @package InstagramAmAPI
 */
class Request
{
    protected $instagram_url = "https://www.instagram.com";

    const INSTAGRAM_URL = 'https://www.instagram.com/{params}';
    const API_URL = 'https://www.instagram.com/query/{params}';
    const GRAPHQL_API_URL = 'https://www.instagram.com/graphql/query/{params}';
    const I_V1_API_URL = 'https://i.instagram.com/api/v1/';

    /** @var  ITransport */
    protected $transport;
    protected $data;
    private $headers;
    protected $client;

    /**
     * Request constructor.
     * @param Client $client
     * @param array $data
     */
    public function __construct($client, $data = [])
    {
        $this->transport = new GuzzleTransport();
        $this->data = $data;
        $this->headers = false;
        $this->client = $client;
    }

    /**
     * @param ITransport $transport
     */
    public function setTransport(ITransport $transport)
    {
        $this->transport = $transport;
    }

    /**
     * Создание curl подключения
     *
     * @param string $url
     * @param null|array $params
     */
    protected function init($url = "", $params = null)
    {
        $this->client->cookie->loadCookie();
        $full_url = $this->instagram_url . $url;

        if (!empty($params)) {
            foreach ($params as $param_key => $param_value) {
                $params[$param_key] = $param_key . "=" . $param_value;
            }

            $params = implode("&", $params);
        } else {
            $params = "";
        }

        if (strpos($full_url, "{params}") !== false) {
            $full_url = str_replace("{params}", "?" . $params, $full_url);
        } else {
            $full_url .= "?" . $params;
        }

        $this->transport->setUrl($full_url);
        $this->transport->init();
        if (!empty($this->client->getProxy())) {
            $this->transport->setProxy($this->client->getProxy());
        }

    }

    /**
     * Первый запрос к сервису для получения csrftoken & rhx_gis
     *
     * @throws ForbiddenInstagramException
     * @throws InstagramException
     * @throws NotFoundInstagramException
     */
    private function initRequest()
    {
        $this->client->cookie->loadCookie();
        $url = str_replace("{params}", "", self::INSTAGRAM_URL);
        $this->transport->setUrl($url);
        $this->transport->init();
        $result = $this->transport->send();
        $rhx_gis = $this->extractRhxGis($result);
        $this->saveCookie();
        if (empty($rhx_gis)) {
            throw new InstagramException("Unable to get rhx_gis from init request.");
        }
        if (empty($this->client->cookie->getCookie('csrftoken'))) {
            throw new InstagramException("Unable to get csrftoken from init request.");
        }
        $this->client->cookie->setCookie("rhx_gis", $rhx_gis);
        $this->client->cookie->saveCookie();
        $this->transport->close();
    }

    /**
     * Установка нужных заголовков
     */
    protected function initHeaders()
    {
        if (empty($this->headers)) {
            return;
        }
        /** Удаляем пустые заголовки */
        $this->headers = array_filter($this->headers);

        $this->transport->setHeaders($this->headers);
    }

    /**
     * @param bool $post_flag
     */
    protected function setPost($post_flag)
    {
        if ($post_flag) {
            $this->transport->setPost(true);
        }
    }


    /**
     * @param array $data
     */
    protected function setPostData($data)
    {
        if (!empty($data)) {
            $this->setPost(true);
            $this->transport->setPostData($data);
        }
    }

    /**
     * Действия перед отправкой запроса
     */
    protected function preRequest()
    {
        //        some code
    }


    /**
     * Действия после отправки запроса
     */
    protected function postRequest()
    {
        //        some code
    }


    /**
     * Сохраняем куки
     */
    protected function saveCookie()
    {
        $cookie = $this->transport->getCookie();
        $this->client->cookie->saveCookieFromArray($cookie);
    }

    /**
     * @param array $headers
     */
    protected function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     * Добавляет заголовок
     *
     * @param $header_name
     * @param $header_value
     */
    protected function addHeader($header_name, $header_value)
    {
        $this->headers[$header_name] = $header_value;
    }

    /**
     * Ищет rhx_gis на странице
     *
     * @param $html_body
     * @return bool
     */
    protected function extractRhxGis($html_body)
    {
        $success_search = preg_match_all("/\"rhx_gis\"\:\"([a-z0-9]+)\"/", $html_body, $matched);
        if ($success_search) {
            return $matched[1][0];
        }
        return false;
    }

    /**
     * Подписывает запрос
     *
     * @param array $query
     * @param null|string $endpoint
     * @return null
     */
    protected function addQuerySignature($query, $endpoint = null)
    {
        if (
            !empty($this->client->cookie->getCookie("rhx_gis"))
            && !empty($query['query_hash'])
            && !empty($query['variables'])
        ) {
            $variables = $query['variables'];
        } elseif (
            !empty($this->client->cookie->getCookie("rhx_gis"))
            && isset($query["__a"])
            && $endpoint
        ) {
            $variables = str_replace($this->instagram_url, "", $endpoint);
            $variables = str_replace("?__a=1", "", $variables);
        } elseif (
            !empty($this->client->cookie->getCookie("rhx_gis"))
            && isset($query["q"])
            && $endpoint
        ) {
            $variables = $query["q"];
        } else {
            return false;
        }
        $str_for_hash = $this->client->cookie->getCookie('rhx_gis') . ":" . $variables;
        $signature = md5($str_for_hash);
        if (!empty($signature)) {
            $this->addHeader("X-Instagram-GIS", $signature);
            return true;
        }
        return false;
    }

    /**
     * Шаблонный метод
     * @return array
     * @throws InstagramException
     * @throws ForbiddenInstagramException
     * @throws NotFoundInstagramException
     */
    public function send()
    {
        $this->client->cookie->loadCookie();
        if (empty($this->client->cookie->getCookie("csrftoken"))) {
            $this->initRequest();
        }

        $this->init();
        $this->preRequest();
        $this->initHeaders();
        $result = $this->transport->send();
        $this->saveCookie();
        $this->postRequest();
        $result = json_decode($result, true);
        return $result;

    }

    public function __destruct()
    {
        if (!is_null($this->transport)) {
            $this->transport->close();
        }
    }

}