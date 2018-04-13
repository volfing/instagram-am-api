<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 13/04/2018
 * Time: 02:17
 */

$res = httpPost("https://www.instagram.com/accounts/activity/?__a=1", []);

var_dump($res);
$res = json_decode($res, true);
echo "<pre>";
print_r($res);

function httpPost($url, $data)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    //curl_setopt($curl, CURLOPT_HEADER, 0);
    /* $cookie_file = "cookie1.txt";
     curl_setopt ($curl, CURLOPT_COOKIE, session_name() . '=' . session_id());
     curl_setopt($curl, CURLOPT_COOKIESESSION, true);
     curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_file);
     curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_file);*/

    $sessionid = "";
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Cookie: mid=WOUVSAAEAAHPzcLdGn9CdEttOwiG; ig_dru_dismiss=1505395836641; csrftoken=bhpu2l0YVwffk17bQyClrS3S9PmKFws6; ds_user_id=6607879743; shbid=6659; rur=FRC; ig_pr=1; ig_or=landscape-primary; ig_vw=1440; sessionid={$sessionid}; ig_vh=297;",
        "Referer: https://www.instagram.com/coding.jobs/",
//        "x-csrftoken: 9IV0j85cIXJ3a3B3sjNnZSzmS16RD3Pt",
//        "X-Instagram-GIS: 6267e6626592416a5c8d7631f173e1f8",
        "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36",
//        "x-instagram-ajax: 1",
        "x-requested-with: XMLHttpRequest",
//        "Content-Type: application/x-www-form-urlencoded",

    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}