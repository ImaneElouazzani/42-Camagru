<?php
session_start();
if($_SESSION['auth'] == true)
    {
        //$_SESSION['flash']['danger'] = 'Vous êtes déjà connecté !';
        header('Location: index.php');
    }
function getParams($params) {
    $query = '';

    foreach ($params as $key => $value) {
        $key = htmlspecialchars($key);
        $value = htmlspecialchars($value);
        $query .= "$key=$value&";
    }

    return $query;
}

if(!empty($_GET['id']) && !empty($_GET['token']))
{
    require '/var/www/html/config/setup.php';
    $req = $bdd->prepare('SELECT * FROM users WHERE id = ? AND reset_token = ? AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)');
    $req->execute([$_GET['id'], $_GET['token']]);
    $user = $req->fetch();
    if($user)
    {
        if(!empty($_POST) && strlen($_POST['psw']) > 10  && strlen($_POST['psw']) < 64 && ctype_alnum($_POST['psw']) && ($_POST['psw'] == str_replace(' ','',$_POST['psw'])) && is_string($_POST['psw']) && $_POST['psw'] == $_POST['psw-repeat'])
        {
            $password = password_hash($_POST['psw'], PASSWORD_BCRYPT);
            $bdd->prepare('UPDATE users SET password = ?, reset_at = NULL, reset_token = NULL')->execute([$password]);
            $_SESSION['flash']['success'] = "Votre mot de passe a été modifié";
            $_SESSION['auth'] = $user;
            header('Location: account.php');
            exit();
        }
        else{
            $_SESSION['flash']['danger'] = "Mot de passe invalide";
        }
    }
    else
    {
        $_SESSION['flash']['danger'] = "ce token n'est plus valide";
        header('Location: login.php');
        exit();
    }
}
else
{
    header('Location: login.php');
    exit();
}
require '/var/www/html/inc/header.php';
require '/var/www/html/inc/alert.php';
?>
<form action="reset.php?<?= getParams($_GET) ?>" method="POST">
  <?= $out ?>
  <div class="container">
    <h1>Nouveau mot de passe</h1>
    <hr>

    <label for="psw"><b>Password </b></label>
    <input type="password" placeholder="Enter Password" name="psw" id="psw" required>

    <label for="psw-repeat"><b>Repeat Password </b></label>
    <input type="password" placeholder="Repeat Password" name="psw-repeat" id="psw-repeat" required>

    <button type="submit" name="submit"class="registerbtn">Change</button>
    
    <hr>
  </div>
</form>
<?php require '/var/www/html/inc/footer.php';?>