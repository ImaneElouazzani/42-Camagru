<?php
session_start();
require '/var/www/html/inc/functions.php';
require_once '/var/www/html/config/setup.php';
logged_only();
require '/var/www/html/inc/header.php'; 
require '/var/www/html/inc/alert.php';

if(isset($_POST['change']))
{
    $errors = array();
    if($_POST['pseudo']!= $_SESSION['auth']['pseudo'])
    {
      $req = $bdd->prepare('SELECT id FROM users WHERE pseudo = ?');
      $req->execute([$_POST['pseudo']]);
      $user_x = $req->fetch();
      if(!strlen($_POST['pseudo']) == 8  || !ctype_alnum($_POST['pseudo']) || ( $_POST['pseudo'] !== str_replace(' ','',$_POST['pseudo'])) || !is_string($_POST['pseudo']))
      {
          $errors['pseudo'] = "Votre pseudo est invalide ! N.B(pseudo alphanumerique, pas d'espace, egal a 8)!";
      }
      elseif($user_x)
      {
          $errors['pseudo'] = "Ce pseudo est déjà pris";
      }
      else
      {
          $req = $bdd->prepare('UPDATE users SET pseudo = :pseudo WHERE id = :id');
          $req->execute(array('pseudo' => htmlentities($_POST['pseudo']), 'id' => $_SESSION['auth']['id']));
          $_SESSION['auth']['pseudo'] = htmlentities($_POST['pseudo']);
      }
    }
    if($_POST['email']!= $_SESSION['auth']['email'])
    {
      $req = $bdd->prepare('SELECT id FROM users WHERE email = ?');
      $req->execute([$_POST['email']]);
      $user_x = $req->fetch();
      if(!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL) || !is_string($_POST['mail']))
      {
          $errors['email'] = "Votre adresse mail n'est pas valide !";
      }
      elseif($user_x)
      {
          $errors['email'] = "Cette adresse mail est déjà prise";
      }
      else
      {
        $req = $bdd->prepare('UPDATE users SET email = :email WHERE id = :id');
        $req->execute(array('email' => htmlentities($_POST['email']), 'id' => $_SESSION['auth']['id']));
        $_SESSION['auth']['email'] = htmlentities($_POST['email']);
      }
    }
    if($_POST['psw'])
    {
      if(!strlen($_POST['psw']) == 10 || !ctype_alnum($_POST['psw']) || ( $_POST['psw'] !== str_replace(' ','',$_POST['psw'])) || !is_string($_POST['psw']))
      {
          $errors['psw'] = "Votre mot de passe n'est pas valide ! N.B(password alphanumerique, pas d'espace, egal a 10)!";
      }
      elseif($_POST['psw-repeat'] != $_POST['psw'])
      {
          $errors['psw-repeat'] = "Vos mots de passes ne correspondent pas !";
      }
      else
      {
          $password = password_hash($_POST['psw'], PASSWORD_BCRYPT);
          $req = $bdd->prepare('UPDATE users SET password = :password WHERE id = :id');
          $req->execute(array('password' => $password, 'id' => $_SESSION['auth']['id']));
          $_SESSION['auth']['psw'] = $password;
          $_SESSION['flash']['success'] = 'Votre mot de passe est bien modifié';
      }
    }
    if(isset($_POST['notif']))
    {
      // set 1
      $req = $bdd->prepare('UPDATE users SET notification = 1 WHERE id = :id');
      $req->execute(array('id' => $_SESSION['auth']['id']));
      $_SESSION['auth']['notification'] = 1;
    }
    else
    {
      // set 0
      $req = $bdd->prepare('UPDATE users SET notification = 0 WHERE id = :id');
      $req->execute(array('id' => $_SESSION['auth']['id']));
      $_SESSION['auth']['notification'] = 0;
    }
}
?>
<h1>Hello <?= $_SESSION['auth']['pseudo']?></h1>
<form action="account.php" method="POST">
  <div class="container">
    <h1>Changer vos infos personnels</h1>
    <hr>

    <label for="pseudo"><b>Changer votre pseudo par ici</b></label>
    <input type="text" value=<?= $_SESSION['auth']['pseudo'] ?> name="pseudo" id="pseudo">
    <p><?php if($errors['pseudo'] != null){echo $errors['pseudo'];}?></p>

    <label for="email"><b>Changer votre email par ici</b></label>
    <input type="text" value=<?= $_SESSION['auth']['email'] ?> name="email" id="email">
    <p><?php if($errors['email'] != null){echo $errors['email'];}?></p>

    <label for="psw"><b>Changer votre password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" id="psw">

    <label for="psw-repeat"><b> Password</b></label>
    <input type="password" placeholder="Repeat Password" name="psw-repeat" id="psw-repeat">

    <div class="form-check checkbox-rounded checkbox-living-coral-filled mb-2">
      <input type="checkbox" class="form-check-input filled-in" id="roundedExample2" name="notif" <?= $_SESSION["auth"]["notification"] == 1 ? "checked" : "" ?> />
      <label class="form-check-label">recevoir les notifications</label>
    </div>
   
    <button type="submit" class="registerbtn" name="change">Changer</button>
    
    <hr>
  </div>
</form>
<?php 
require '/var/www/html/inc/footer.php';?>