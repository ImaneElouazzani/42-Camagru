<?php
    require "/var/www/html/config/database.php";

    try {
      $bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
      // set the PDO error mode to execption
      $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
      catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    $create_db = "CREATE DATABASE IF NOT EXISTS db_camagru";
    $use_db = "USE db_camagru;";

    $user_table = "CREATE TABLE IF NOT EXISTS users (
        id int  AUTO_INCREMENT NOT NULL,
        pseudo varchar(30) UNIQUE NOT NULL ,
        email varchar(250) UNIQUE NOT NULL ,
        password varchar(255) NOT NULL,
        token varchar(255) DEFAULT NULL,
        confirmed_at DATETIME DEFAULT NULL,
        reset_token VARCHAR(8) DEFAULT NULL,
        reset_at DATETIME NULL,
        notification boolean default 1 NOT NULL,
        PRIMARY KEY (id)
      )";
    
    $photos_table = "CREATE TABLE IF NOT EXISTS photos (
        id int AUTO_INCREMENT NOT NULL,
        user_id int NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id),
        image VARCHAR (255) NOT NULL,
        likes int DEFAULT 0,
        date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
      )";
    $comments_table = "CREATE TABLE IF NOT EXISTS comments (
        photo_id INTEGER NOT NULL,
        user_id INTEGER NOT NULL,
        comment VARCHAR (255) NOT NULL,
        date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (photo_id) REFERENCES photos (id),
        FOREIGN KEY (user_id) REFERENCES users (id)
      )";
    $likes_table = "CREATE TABLE IF NOT EXISTS likes (
        id INT PRIMARY KEY AUTO_INCREMENT,
        photo_id INT NOT NULL,
        user_id INT NOT NULL,
        FOREIGN KEY(photo_id) REFERENCES photos(id),
        FOREIGN KEY(user_id) REFERENCES users(id)
      )";

    $bdd->query($create_db);
    $bdd->query($use_db);
    $bdd->query($user_table);
    $bdd->query($photos_table);
    $bdd->query($comments_table);
    $bdd->query($likes_table);
?>