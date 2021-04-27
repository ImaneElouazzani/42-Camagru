<?php
session_start();
if($_SESSION['auth'] == true)
    {
        //$_SESSION['flash']['danger'] = 'Vous êtes déjà connecté !';
        header('Location: index.php');
    }
if(isset($_POST['submit']))
{
    require_once 'config/setup.php';
    $req = $bdd->prepare('SELECT * FROM users WHERE email = ? AND confirmed_at IS NOT NULL');
    $req->execute([ $_POST['email']]);
    $user = $req->fetch();
    if($user)
    {
        $reset_token = 'qwertyuiopasdfghjklzxcvbnm1239698745QWERTYIBVNXMHFDAKLOEVCBCJHB';
        $reset_token = substr(str_shuffle($reset_token),0,8);
        $req = $bdd->prepare('UPDATE users SET reset_token = ?, reset_at = NOW() WHERE id=?');
        $req->execute([$reset_token, $user['id']]);
        $_SESSION['flash']['success'] = 'Les instructions du rappel de mot de passe vous ont été envoyées par emails';
        $ip = $_SERVER['SERVER_NAME'];
        mail($_POST['email'], 'Réinitialisation de votre mot de passe', "Reinitialiser votre mot de passe\n\nhttp://".$ip.":3000/reset.php?id={$user['id']}&token=$reset_token");
        header('Location: login.php');
        exit();
    }
    else
    {
        $_SESSION['flash']['danger'] = 'Aucun compte ne correspond à cet email';
    }
}
require '/var/www/html/inc/header.php';
require '/var/www/html/inc/alert.php';
?>
<form action="" method="POST">
  <div class="container">
    <h1>Mot de passe oublié</h1>
    <hr>

    <label for="email"><b>Email</b></label>
    <input type="text" placeholder="Enter Email " name="email" id="email" required>
    
    <button type="submit" name="submit"class="registerbtn">login</button>
    
    <hr>
  </div>
</form>
<?php 
require '/var/www/html/inc/footer.php';?>