<?php
    session_start();
    require_once '/var/www/html/config/setup.php';
    $path = "gallery/";
    $req = $bdd->prepare('SELECT image FROM photos WHERE user_id = ?');
    $req->execute([$_SESSION['auth']['id']]);
    while(($data = $req->fetch())) {
        echo $data['image']. "\n";
    }
?>