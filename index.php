<?

/*echo file_get_contents('https://www.instagram.com/challenge/5955709178/Kzt1xNU0co/');
exit;*/

include __DIR__ . "/autoload.php";

use InstagramAmAPI\Instagram;

$instagrm = new Instagram();
$username = "user_name";
$password = "user_password";
$res = $instagrm->login($username, $password);
var_dump($res);
?>