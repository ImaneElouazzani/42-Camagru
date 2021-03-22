<?php
    session_start();

    require '/var/www/html/inc/header.php';
    require '/var/www/html/config/setup.php';
    require '/var/www/html/inc/alert.php';


    function isCommentText($text)
    {
        if (!is_string($text))
            return false;
        $text = trim($text);
        $len = strlen($text);

        if ($len == 0 || $len > 250)
            return false;
        return true;
    }

    if(isset($_POST['like']))
    {
        $req = $bdd->prepare('SELECT COUNT(*) AS likesCount FROM likes WHERE likes.photo_id = ? AND likes.user_id = ?');
        $req->execute([
            $_POST["photo_id"],
            $_SESSION["auth"]["id"]
        ]);
        $likesCount = $req->fetch(PDO::FETCH_ASSOC)["likesCount"];

        if ($likesCount == 0)
        {
            // insert record into likes table
            $req = $bdd->prepare('UPDATE photos SET likes = likes + 1 where id = ?');
            $req->execute([$_POST["photo_id"]]);
            
            // update likes number
            $req = $bdd->prepare('INSERT INTO likes SET photo_id = ?, user_id = ?');
            $req->execute([
                $_POST["photo_id"],
                $_SESSION["auth"]["id"]
            ]);
        }
    }
    if(isset($_POST['comment']) && isset($_POST['comment-input']) && isCommentText($_POST["comment-input"]))
    {
        $photoId = $_POST["photo_id"];
        $userId = $_SESSION["auth"]["id"];
        $commentText = htmlentities($_POST["comment-input"]);

        $req = $bdd->prepare('INSERT INTO comments (`photo_id`, `user_id`, `comment`) VALUES(?, ?, ?)');
        $req->execute([
            $photoId,
            $userId,
            $commentText
        ]);
        $quest = $bdd->prepare('SELECT notification, email FROM users INNER JOIN photos ON users.id = photos.user_id WHERE photos.id =?');
        $quest->execute([$_POST["photo_id"]]);
        $notif = $quest->fetch();
        if($notif['notification'] == 1)
        {
            mail($notif['email'], 'notification', "Votre photo a été commentée");
        }
    }
    $photoCount = 0;
    $photos = $bdd->query("SELECT COUNT(image) AS nbPhotos FROM photos");
    $nbPhotos = $photos->fetch();
    if (($nbPhotos["nbPhotos"] % 5) == 0)
        $photoCount = ($nbPhotos["nbPhotos"] / 5);
    else
        $photoCount = (int)($nbPhotos["nbPhotos"] / 5) + 1;
    
?>
<div id="container" style="margin: auto"></div>
<ul class="pagination justify-content-center">
    <?php for ($i = 0; $i < $photoCount; $i++): ?>
        <li class="page-item"><a class="page-link" href="/index.php?page=<?php echo $i ?>"><?php echo $i + 1; ?></a></li>
    <?php endfor; ?>
</ul>
<script src="js/home.js"></script>
<?php
require '/var/www/html/inc/footer.php';
?>