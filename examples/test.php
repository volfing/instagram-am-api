<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 13/04/2018
 * Time: 02:17
 */

$res = httpPost("https://www.instagram.com/smqend/?__a=1", []);

var_dump($res);
$res = json_decode($res, true);
echo "<pre>";
print_r($res);

function httpPost($url, $data)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_AUTOREFERER, true);
    curl_setopt($curl, CURLOPT_HTTPGET, true);
    curl_setopt($curl, CURLOPT_VERBOSE, "");

    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Cookie: rur=FRC; csrftoken=GkiWOYYADwuZraZHOvOQhKszazCNKO1w; mid=WtX_bgAEAAETSbbUXgL140pMFiqr;",
        "Referer: https://www.instagram.com/smqend/",
        "x-csrftoken: GkiWOYYADwuZraZHOvOQhKszazCNKO1w",
        "x-instagram-ajax: 1",
        "x-requested-with: XMLHttpRequest",
        "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36",
        "X-Instagram-GIS: cf1df62fefa628c8e682db0e9f1036b7",
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}