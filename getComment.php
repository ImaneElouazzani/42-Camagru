<?php
    require '/var/www/html/config/setup.php';
        $req = $bdd->prepare('SELECT * FROM comments WHERE photo_id = ?');
        $req->execute([$_GET["photoId"]]);
        $data = $req->fetchAll();
        echo json_encode($data);

?>