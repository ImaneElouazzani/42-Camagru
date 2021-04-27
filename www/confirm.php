<?php
session_start();
    if($_SESSION['auth'] == true)
    {
        //$_SESSION['flash']['danger'] = 'Vous êtes déjà connecté !';
        header('Location: index.php');
    }
    $user_id = $_GET['id'];
    $token   = $_GET['token'];
    require 'config/setup.php';
    $req = $bdd->prepare('SELECT * FROM users WHERE id = ?');
    $req->execute([$user_id]);
    $user = $req->fetch();
    if($user && $user['token'] == $token)
    {
        $req = $bdd->prepare('UPDATE users SET token = NULL, confirmed_at = NOW() WHERE id = ?');
        $req->execute([$user_id]);
        $_SESSION['flash']['success'] = 'Votre compte a été bien validé';
        header('Location: login.php');
    }
    else
    {
        $_SESSION['flash']['danger'] = "ce token n'est plus valide";
        header('Location: login.php');
    }