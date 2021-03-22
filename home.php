<?php
    session_start();
    require_once '/var/www/html/config/setup.php';
    $currentUserId = $_SESSION["auth"]["id"];
    $page = $_GET["page"];
    $offest = $page * 5;
    
    $query = "SELECT COUNT(*) FROM likes WHERE photos.id = likes.photo_id AND likes.user_id = ?";

    $req = $bdd->prepare("SELECT user_id, image, photos.id, likes, date AS date, users.pseudo, ($query) AS likesCount
                        FROM photos
                        INNER JOIN users
                        ON users.id = photos.user_id
                        ORDER BY date DESC
                        LIMIT $offest, 5");
    
    $req->execute([ $currentUserId ]);
    $data = $req->fetchAll(PDO::FETCH_ASSOC);
    for ($i = 0; $i < count($data); $i++) {
        if (isset($currentUserId)) {
            $data[$i]["is_connected"] = true;
            if ($data[$i]['user_id'] == $currentUserId){
                $data[$i]['is_deletable'] = true;
            }
            else {
                $data[$i]['is_deletable'] = false;
            }
        }
        else $data[$i]["is_connected"] = false;
    }
    echo json_encode($data);

?>