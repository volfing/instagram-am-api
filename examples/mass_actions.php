<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 13.05.2018
 * Time: 7:31
 */

include __DIR__ . "/../autoload.php";

use InstagramAmAPI\Instagram;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $instagram = new Instagram();
    $username = $_POST['username'];
    $password = $_POST['password'];
    $instagram->setUser($username, $password);
    $instagram->setProxy("https://105.156.104.251:8080");
    $authResponse = $instagram->login();
	$medias = array();

	try {
        $response = $instagram->explore->searchByTag($_POST["hashtag"]);

        if (!empty($response) && !empty($response->getItems())) {
            $medias = $response->getItems();
        }

        if (!empty($response->getNextMaxId())) {
            $response = $instagram->explore->searchByTag($_POST["hashtag"], $response->getNextMaxId());

            if (!empty($response) && !empty($response->getItems())) {
                $medias = array_merge($medias, $response->getItems());
            }
        }

        echo "Number of found medias: " . count($medias) . "<br>";

        foreach ($medias as $media) {
            $res = $instagram->media->like($media->getId());

            echo "Like {$media->getId()}: " . (!empty($res) ? "success" : "fail") . "<br>";
        }
    }catch(InstagramAmAPI\Exception\BadResponseException $exception){
		echo $exception->getMessage();
	}catch (InstagramAmAPI\Exception\TooManyRequestsException $exception){
		echo $exception->getMessage();
	}catch(InstagramAmAPI\Exception\InstagramException $exception){
		echo $exception->getMessage();
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
				Hashtag
				<input type="text" name="hashtag" placeholder="Enter hashtag"
					   value="<?= !empty($_POST['hashtag']) ? $_POST['hashtag'] : '' ?>">
			</label>
		</div>
        <div class="form-item">
            <input type="submit" name="submit" value="Start search and liking">
        </div>
    </form>
</div>
