<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 25.05.2018
 * Time: 8:25
 */

include __DIR__ . "/../autoload.php";

use InstagramAmAPI\Instagram;

$checkpoint_methods = [];
$checkpoint_url = empty($_POST["checkpoint_url"]) ? "" : $_POST["checkpoint_url"];

$instagram = new Instagram();
$username = !empty($_POST['username']) ? $_POST['username'] : "";
$password = !empty($_POST['password']) ? $_POST['password'] : "";
$instagram->setUser($username, $password);

$result = null;

if(!empty($_POST["choice"]) && !empty($checkpoint_url)){
	$result = $instagram->sendCheckpointCode($_POST["choice"], $checkpoint_url);

	if(!empty($result["status"]) && $result["status"] == "ok"){
		$result = "Code was be sent";
	}
}elseif(isset($_POST["username"])){
    try{
        $result = $instagram->login();
    }catch(\InstagramAmAPI\Exception\ChallengeRequiredException $e){
		if(!empty($e->challengeInfo["checkpoint_url"])){
			try{
                $result = $instagram->getCheckpointMethods($e->challengeInfo["checkpoint_url"]);

                if(is_array($result)){
                    $checkpoint_methods = $result;
                }
			}catch(\InstagramAmAPI\Exception\CheckpointCodeAlreadySent $exception){
				if(!empty($_POST["security_code"])){
					$result = $instagram->verifyCheckpointByCode($e->challengeInfo["checkpoint_url"], $_POST["security_code"]);

					if(!empty($result["status"]) && $result["status"] == "ok"){
						$result = "Success logged";
					}
				}else{
                    $result = "Checkpoint code already sent, please input code to field";
				}
			}

			$checkpoint_url = $e->challengeInfo["checkpoint_url"];
		}
	}catch (\InstagramAmAPI\Exception\InstagramException $e) {
        echo "<p>Поймали исключение. {$e->getMessage()}</p>";
        echo "<pre>";
        echo $e->getTraceAsString();
        echo "</pre>";
    }
}

echo "<pre>";
var_dump($result);
echo "</pre>";

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
		<?if(!empty($checkpoint_methods)):?>
			<div class="form-item">
				<div>Select verify method: </div>
				<?foreach ($checkpoint_methods as $method):?>
					<div>
						<input <?=$method['selected'] ? 'checked' : ''?> type="radio" name="choice" value="<?=$method['value']?>" id="choice<?=$method['value']?>">
						<label for="choice<?=$method['value']?>"><?=$method['label']?></label>
					</div>
				<?endforeach;?>
			</div>
			<input type="hidden" name="checkpoint_url" value="<?=$checkpoint_url?>">
		<?endif;?>
        <div class="form-item">
            <label>
                Security code
                <input type="text" name="security_code" placeholder="Enter security code"
                       value="<?= !empty($_POST['security_code']) ? $_POST['security_code'] : '' ?>">
            </label>
        </div>
        <div class="form-item">
            <input type="submit" value="Login">
        </div>
    </form>
</div>