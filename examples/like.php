<?

/*echo file_get_contents('https://www.instagram.com/challenge/5955709178/Kzt1xNU0co/');
exit;*/

include __DIR__ . "/../autoload.php";

use InstagramAmAPI\Instagram;

$auth_message = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $instagram = new Instagram();
    $username = $_POST['username'];
    $password = $_POST['password'];
    $instagram->setUser($username, $password);
    $instagram->login();
    $mediaIDS = array(
        "1742667399201505216",
        "1741205546457866496",
        "1733248101509917377",
        "1730328513117689203",
        "1726698797793153939",
        "1723881243403931202",
        "1720208695646566280",
        "1717403789231472235",
        "1714569002586272529",
        "1714478104846419701",
    );

    foreach ($mediaIDS as $id) {
        $result = $instagram->media->like($id);
        if ($result) {
            echo "<p>like</p>";
        } else {
            echo "<p>error</p>";
        }
        echo "<br>";
        usleep(20);
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
                Media ID
                <input type="text" name="media_id" placeholder="Enter media ID"
                       value="<?= !empty($_POST['media_id']) ? $_POST['media_id'] : '' ?>">
            </label>
        </div>
        <div class="form-item">
            <input type="submit" name="submit" value="LIKE">
        </div>
    </form>
</div>
