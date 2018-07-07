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
        $username = $_POST['login'];
        $password = $_POST['password'];
        $instagram->setUser($username, $password);

        switch ($_POST['submit']) {
            case "submitLogin":
                $result = $instagram->login();
                break;
            case "submitSelfinfo":
                $max_count = 1000;
                $time_start = microtime(true);
                try {
                    for ($i = 0; $i < $max_count; $i++) {
                        $result = $instagram->account->getSelfInfo();
                        var_dump($result);
                        echo "<hr>";
                        usleep(100);
                    }
                } catch (\Exception $e) {
                    throw new Exception($e->getMessage());
                }

                break;

            default:
                break;
        }
//        echo "<pre>";
//        print_r($result);
//        echo "</pre><br>";
    } catch
    (\InstagramAmAPI\Exception\InstagramException $e) {
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
                Login
                <input type="text" name="login" placeholder="Enter login"
                       value="<?= !empty($_POST['login']) ? $_POST['login'] : '' ?>">
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
            <div>
                <input type="submit" name="submit" value="submitLogin">
                <input type="submit" name="submit" value="submitSelfinfo">
            </div>
        </div>
    </form>
</div>