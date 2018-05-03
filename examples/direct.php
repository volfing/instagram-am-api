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
        $instagram->login();
        switch ($_POST['submit']) {
            case "getListOfMessages":
                $result = $instagram->direct->getListOfMessages();
                break;
            case "sendText":
                $result = $instagram->direct->sendText($_POST['user_pk'], $_POST['text']);
                break;
            default:
                break;
        }
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
                User pk
                <input type="text" name="user_pk" placeholder="Enter user_pk"
                       value="<?= !empty($_POST['user_pk']) ? $_POST['user_pk'] : '' ?>">
            </label>
        </div>
        <div class="form-item">
            <label>
                Direct message
                <input type="text" name="text" placeholder="Enter message"
                       value="<?= !empty($_POST['te']) ? $_POST['text'] : '' ?>">
            </label>
        </div>
        <div class="form-item">
            <div>
                <input type="submit" name="submit" value="getListOfMessages">
                <input type="submit" name="submit" value="sendText">
            </div>
        </div>
    </form>
</div>