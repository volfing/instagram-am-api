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
        $hashtag = $_POST['hashtag'];
//        $instagram->setUser($username, $password);

        if (!empty($_POST['proxy'])) {
            $instagram->setProxy($_POST['proxy']);
        }
        switch ($_POST['submit']) {
            case "submitLogin":
                $result = $instagram->login();
                break;
            case "submitParsing":
                $items = [];
                $proxies = [
                    'http://209.50.56.151:65103',
                    'http://103.87.16.2:80',
                    'http://206.81.5.117:3128',
                    'http://50.17.177.230:3128',
                    'http://212.90.183.149:8080',
                ];
                $instagram->setProxy(array_pop($proxies));
                $max_id = null;
                $max_count = 1000;
                $time_start = microtime(true);
                $f = fopen('log.txt', 'w');
                try {
                    do {
                        try {
                            $result = $instagram->explore->searchByTag($_POST['hashtag'], $max_id);
                            $max_id = $result->next_max_id;
                            $items = array_merge($items, $result->items);
                            if (count($items) > $max_count) {
                                break;
                            }
                        } catch (\Exception $e) {
                            if ((strpos($e->getMessage(), 'rate limited') || (strpos($e->getMessage(), 'cURL error')) !== false) && !empty($proxies)) {
                                $proxy = array_pop($proxies);
                                echo "<p>Changed proxy to {$proxy}</p>";
                                fwrite($f, "<p>Changed proxy to {$proxy}</p>" . PHP_EOL);
                                $instagram->setProxy($proxy);
                                continue;
                            }
                            throw new Exception($e->getMessage());
                        }
                        if (is_null($max_id)) {
                            break;
                        }
                        usleep(100);
                    } while (true);
                } catch (\Exception $e) {
                    echo "<p>Парсинг занял " . (microtime(true) - $time_start) . " секунд.</p>";
                    echo "<p>Parsed " . count($items) . " media items.</p>";
                    var_dump($e->getMessage());
                    echo "<hr><pre>";
                    echo $e->getTraceAsString();
                    echo "</pre>";

                    fwrite($f, "<p>Парсинг занял " . (microtime(true) - $time_start) . " секунд.</p>" . PHP_EOL);
                    fwrite($f, "<p>Parsed " . count($items) . " media items.</p>" . PHP_EOL);
                } finally {
                    echo "<p>Парсинг занял " . (microtime(true) - $time_start) . " секунд.</p>";
                    echo "<p>Success parsed " . count($items) . " media items.</p>";
                    fwrite($f, "<p>Парсинг занял " . (microtime(true) - $time_start) . " секунд.</p>" . PHP_EOL);
                    fwrite($f, "<p>Parsed " . count($items) . " media items.</p>" . PHP_EOL);
                }
                fclose($f);

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
                Proxy
                <input type="text" name="proxy" placeholder="Enter proxy"
                       value="<?= !empty($_POST['proxy']) ? $_POST['proxy'] : '' ?>">
            </label>
        </div>
        <div class="form-item">
            <label>
                Hashtag
                <input type="text" name="hashtag" placeholder="Enter hashtag"
                       value="<?= !empty($_POST['hashtag']) ? $_POST['hashtag'] : '' ?>">
            </label>
        </div>
        <div class="form-item">
            <div>
                <input type="submit" name="submit" value="submitLogin">
                <input type="submit" name="submit" value="submitParsing">
            </div>
        </div>
    </form>
</div>