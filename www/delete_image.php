<?php
require_once '/var/www/html/config/setup.php';
if (isset($_POST["photo_id"])) {
    $req = $bdd->prepare("DELETE  FROM likes WHERE photo_id = ?");
    $req->execute([$_POST['photo_id']]);
    $req = $bdd->prepare("DELETE  FROM comments WHERE photo_id = ?");
    $req->execute([$_POST['photo_id']]);
    $req = $bdd->prepare("DELETE  FROM photos WHERE id = ?");
    $req->execute([$_POST['photo_id']]);
}
?>