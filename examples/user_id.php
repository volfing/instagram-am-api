<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 14/04/2018
 * Time: 00:52
 */


include __DIR__ . "/../autoload.php";

use InstagramAmAPI\Instagram;

$auth_message = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $instagram = new Instagram();
    $username = $_POST['username'];
    $password = $_POST['password'];
    $instagram->setUser($username, $password);

    $result = $instagram->account->getById($_POST['username_search']);
    echo "<pre>";
    print_r($result);
    echo "</pre><br>";
    usleep(20);

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
                Username search
                <input type="text" name="username_search" placeholder="Enter Username for search"
                       value="<?= !empty($_POST['username_search']) ? $_POST['username_search'] : '' ?>">
            </label>
        </div>
        <div class="form-item">
            <input type="submit" name="submit" value="getUserInfo">
        </div>
    </form>
</div>