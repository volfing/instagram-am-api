<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 01.05.2018
 * Time: 20:54
 */


include __DIR__ . "/../autoload.php";

use InstagramAmAPI\Instagram;

$auth_message = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    try {
        $instagram = new Instagram();
        $username = $_POST['username'];
        $password = $_POST['password'];
        $instagram->setUser($username, $password);
        $result = $instagram->direct->getListOfMessages();
        echo "<pre>";
        print_r($result);

        echo "</pre><br>";
    } catch (\InstagramAmAPI\Exception\InstagramException $e) {
        echo "<p>Поймали исключение. {$e->getMessage()}</p>";
        echo $e->getTraceAsString();
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
    <form method="post" enctype="multipart/form-data">
        <div class="form-item">
            <label>
                Username
                <input type="text" name="username" placeholder="Enter username"
                       value="<?= !empty($_POST['username']) ? $_POST['username'] : '' ?>">
            </label>
        </div>
        <div class="form-item">
            <label>
                Password
                <input type="password" name="password" placeholder="Enter password"
                       value="<?= !empty($_POST['password']) ? $_POST['password'] : '' ?>">
            </label>
        </div>
        <div class="form-item">
            <label>
                Photo caption
                <input type="text" name="caption" placeholder="Enter photo caption"
                       value="<?= !empty($_POST['caption']) ? $_POST['caption'] : '' ?>">
            </label>
        </div>
        <div class="form-item">
            <label>
                Photo
                <input type="file" name="photo">
            </label>
        </div>
        <div class="form-item">
            <div>
                <input type="submit" name="submit" value="upload">
            </div>
        </div>
    </form>
</div>