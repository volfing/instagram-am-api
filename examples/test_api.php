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
    try {
        $instagram = new Instagram();
        $username = $_POST['username'];
        $password = $_POST['password'];
        $instagram->setUser($username, $password);

        switch ($_POST['submit']) {
            case "submitLogin":
                $result = $instagram->login();
                break;
            case "submitLike":
                $result = $instagram->media->like($_POST['search_id']);
                break;
            case "submitUnlike":
                $result = $instagram->media->unlike($_POST['search_id']);
                break;
            case "submitFollow":
                $result = $instagram->account->followById($_POST['search_id']);
                break;
            case "submitUnfollow":
                $result = $instagram->account->unFollowById($_POST['search_id']);
                break;
            case "getUserInfoByID":
                $result = $instagram->account->getById($_POST['search_id']);
                break;
            case "getUserInfoByName":
                $result = $instagram->account->getByUsername($_POST['search_id']);
                break;
            case "getUserFeed":
                $result = $instagram->account->loadMediasById($_POST['search_id']);
                break;
            case "loadMediasByUsername":
                $result = $instagram->account->loadMediasByUsername($_POST['search_id']);
                break;
            case "getMediaInfoByID":
                $result = $instagram->media->getById($_POST['search_id']);
                break;
            case "submitMediaComment":
                $result = $instagram->media->comment($_POST['comment'], $_POST['search_id']);
                break;
            case "submitMediaCommentDelete":
                $result = $instagram->media->removeComment($_POST['search_id'], $_POST['comment']);
                break;
            case "findByTag":
                $result = $instagram->explore->searchByTag($_POST['search_id']);
                break;
            case "findByLocation":
                $result = $instagram->explore->searchByLocationId($_POST['search_id']);
                break;
            case "RequestMediaInfoByShortcode":
                $result = $instagram->media->getByShortCode($_POST['search_id']);
                break;
            case "getMediaComments":
                $result = $instagram->media->getComments($_POST['search_id']);
                break;
            case "getMediaLikes":
                $result = $instagram->media->getLikes($_POST['search_id']);
                break;
            case "deleteMedia":
                $result = $instagram->account->deleteMediaById($_POST['search_id']);
                break;
            case "getFollowers":
                $result = $instagram->account->followers($_POST['search_id']);
                break;
            case "getFollowings":
                $result = $instagram->account->followings($_POST['search_id']);
                break;
            case "getTimeline":
                $result = $instagram->account->timelineFeed();
                break;
            case "getSearch":
                $result = $instagram->explore->search($_POST['search_id']);
                break;
            case "getStories":
                $result = $instagram->account->getRecentStories();
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
                Search id/name
                <input type="text" name="search_id" placeholder="Enter search id"
                       value="<?= !empty($_POST['search_id']) ? $_POST['search_id'] : '' ?>">
            </label>
        </div>
        <div class="form-item">
            <label>
                Comment id/text
                <input type="text" name="comment" placeholder="Enter comment"
                       value="<?= !empty($_POST['comment']) ? $_POST['comment'] : '' ?>">
            </label>
        </div>
        <div class="form-item">
            <div>
                <input type="submit" name="submit" value="submitLogin">
                <input type="submit" name="submit" value="submitLike">
                <input type="submit" name="submit" value="submitUnlike">
                <input type="submit" name="submit" value="submitFollow">
            </div>
            <div>
                <input type="submit" name="submit" value="submitUnfollow">
                <input type="submit" name="submit" value="getUserInfoByID">
                <input type="submit" name="submit" value="getUserInfoByName">
                <input type="submit" name="submit" value="getUserFeed">
                <input type="submit" name="submit" value="loadMediasByUsername">
                <input type="submit" name="submit" value="getMediaInfoByID">
                <input type="submit" name="submit" value="submitMediaComment">
                <input type="submit" name="submit" value="submitMediaCommentDelete">
            </div>
            <div>
                <input type="submit" name="submit" value="findByTag">
                <input type="submit" name="submit" value="findByLocation">
                <input type="submit" name="submit" value="RequestMediaInfoByShortcode">
                <input type="submit" name="submit" value="getMediaComments">
                <input type="submit" name="submit" value="getMediaLikes">
                <input type="submit" name="submit" value="deleteMedia">
            </div>
            <div>
                <input type="submit" name="submit" value="getFollowers">
                <input type="submit" name="submit" value="getFollowings">
                <input type="submit" name="submit" value="getTimeline">
                <input type="submit" name="submit" value="getSearch">
                <input type="submit" name="submit" value="getStories">
            </div>
        </div>
    </form>
</div>