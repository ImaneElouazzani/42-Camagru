<?php
    session_start();
    require_once "/var/www/html/config/setup.php";
    function    isStickerValid($stickerName)
    {
        $stickers = array(
            "duck.png" => "assets/duck.png",
            "heart.png" => "assets/heart.png",
            "smiley.png" => "assets/smiley.png"
        );
        foreach($stickers as $sticker => $path)
        {
            if ($stickerName == $sticker)
                return $path;
        }
        return false;
    }

    if (isset($_POST["pic"]) && isset($_POST["sticker"]))
    {
        $userPic = imagecreatefromjpeg($_POST["pic"]);
        $stickerPath = isStickerValid($_POST["sticker"]);
        if ($stickerPath === false)
            die("invalid sticker");
        $stickerPic = imagecreatefrompng($stickerPath);
        imagecopy($userPic, $stickerPic, 300, 97, 0, 0, 100, 100);
        $imageName = $_SESSION["auth"]["pseudo"] . "_" . rand(1000, 10000);
        $imagePath = "gallery/" . $imageName . ".jpeg";
        imagejpeg($userPic, $imagePath);
        $req = $bdd->prepare('INSERT INTO photos(user_id, image) VALUES(:user_id, :image)');
        $req->execute(array('user_id' => $_SESSION['auth']['id'],
                                'image' => $imagePath));
    }