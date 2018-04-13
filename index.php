<?

/*echo file_get_contents('https://www.instagram.com/challenge/5955709178/Kzt1xNU0co/');
exit;*/

include __DIR__ . "/autoload.php";

use InstagramAmAPI\Instagram;

$auth_message = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $instagram = new Instagram();
    $username = $_POST['username'];
    $password = $_POST['password'];
    $instagram->setUser($username, $password);
    $res = $instagram->login();
    if ($res['authenticated']) {
        $auth_message = "Success auth.";
    } elseif ($res['user']) {
        $auth_message = "Wrong password.";
    } else {
        $auth_message = "User not found.";
    }
}
?>
<style>
    .content {
        text-align: center;
    }
    .form-item {
        padding: 10px;
    }
</style>
<div class="content">
    <h1>Авторизация в Instagram.</h1>
    <h3><?= $auth_message ?></h3>
    <form method="post" action="/">
        <div class="form-item">
            <label>
                Username
                <input type="text" name="username" placeholder="Enter username" value="<?=!empty($_POST['username']) ? $_POST['username'] : '' ?>">
            </label>
        </div>
        <div class="form-item">
            <label>
                Password
                <input type="password" name="password" placeholder="Enter password" value="<?=!empty($_POST['password']) ? $_POST['password'] : '' ?>">
            </label>
        </div>
        <div class="form-item">
            <input type="submit" name="submit" value="Log in">
        </div>
    </form>
</div>
