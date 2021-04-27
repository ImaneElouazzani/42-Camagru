<?php
    require '/var/www/html/config/setup.php';
    $req = $bdd->query('SELECT * FROM likes');
    $data = $req->fetchAll();
    echo json_encode($data);
?>