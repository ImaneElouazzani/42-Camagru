<?php
session_start();
if($_SESSION['auth'] == true)
    {
        $_SESSION['flash']['danger'] = 'Vous êtes déjà connecté !';
        header('Location: index.php');
    }
if(isset($_POST['submit']))
{
    require_once '/var/www/html/config/setup.php';
    $req = $bdd->prepare('SELECT * FROM users WHERE (pseudo = :user OR email = :user)');
    $req->execute(['user' => $_POST['pseudo']]);
    $user = $req->fetch();
    if($user['token'] && $user['confirmed_at'] == NULL)
    {
        $_SESSION['flash']['danger'] = "vous n'avez pas encore validé votre compte ";
    }
    elseif(password_verify($_POST['psw'], $user['password']))
    { 
        $_SESSION['auth'] = $user;
        $_SESSION['flash']['success'] = 'Vous êtes maintenant connecté ';
        header('Location: index.php');
        exit();
    }
    else
    {
        $_SESSION['flash']['danger'] = 'Identifiant ou mot de passe incorrect ';

    }
}
require '/var/www/html/inc/header.php';
require '/var/www/html/inc/alert.php';
?>
<form action="" method="POST">
  <div class="container">
    <h1>Login</h1>
    <hr>

    <label for="pseudo"><b>Pseudo or mail</b></label>
    <input type="text" placeholder="Enter Email or pseudo" name="pseudo" id="pseudo" required>

    <label for="psw"><b>Password </b></label>
    <input type="password" placeholder="Enter Password" name="psw" id="psw" required>
    <a href="forget.php">(mdp oublié)</a>

    <button type="submit" name="submit"class="registerbtn">login</button>
    
    <hr>
  </div>
</form>
<?php require '/var/www/html/inc/footer.php';?>