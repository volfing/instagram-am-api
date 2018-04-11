<?

/*echo file_get_contents('https://www.instagram.com/challenge/5955709178/Kzt1xNU0co/');
exit;*/

$res = httpPost("https://www.instagram.com/accounts/login/ajax/", array(
	"username" => "user_name",
	"password" => "user_password",
	"queryParams" => []
));

echo($res);

function httpPost($url, $data)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	//curl_setopt($curl, CURLOPT_HEADER, 0);
   /* $cookie_file = "cookie1.txt";
    curl_setopt ($curl, CURLOPT_COOKIE, session_name() . '=' . session_id());
	curl_setopt($curl, CURLOPT_COOKIESESSION, true);
	curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_file);
	curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_file);*/
	
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		"Cookie: rur=FTW; csrftoken=9IV0j85cIXJ3a3B3sjNnZSzmS16RD3Pt; mid=Ws47hAAEAAEbFgiJlzua3to_2TBO; ig_vw=1915; ig_pr=1; ig_vh=937",
		"Referer: https://www.instagram.com/",
		"x-csrftoken: 9IV0j85cIXJ3a3B3sjNnZSzmS16RD3Pt",
		"x-instagram-ajax: 1",
		"x-requested-with: XMLHttpRequest",
		"Content-Type: application/x-www-form-urlencoded",

	));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}
?>