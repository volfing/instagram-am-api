<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 22/04/2018
 * Time: 13:49
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

        switch ($_POST['submit']) {
            case "submitLogin":
                $result = $instagram->login();
                break;
            case "submitSearchLocation":
                $result = $instagram->explore->searchLocation($_POST['latitude'], $_POST['longitude']);
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
    <form method="post">
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
                latitude
                <input type="text" name="latitude" placeholder="Enter latitude"
                       value="<?= !empty($_POST['latitude']) ? $_POST['latitude'] : '' ?>">
            </label>
        </div>
        <div class="form-item">
            <label>
                longitude
                <input type="text" name="longitude" placeholder="Enter longitude"
                       value="<?= !empty($_POST['longitude']) ? $_POST['longitude'] : '' ?>">
            </label>
        </div>
        <div class="form-item">
            <div>
                <input type="submit" name="submit" value="submitLogin">
                <input type="submit" name="submit" value="submitSearchLocation">
            </div>
        </div>
    </form>
</div>