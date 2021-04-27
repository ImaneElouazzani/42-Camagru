<?php
    session_start();
    if($_SESSION['auth'] == true)
    {
        header('Location: index.php');
    }
    if(!empty($_POST))
    {
        require('/var/www/html/config/setup.php');
        $errors = array();
        if(strlen($_POST['pseudo']) != 8 || !ctype_alnum($_POST['pseudo']) || ( $_POST['pseudo'] !== str_replace(' ','',$_POST['pseudo'])) || !is_string($_POST['pseudo']))
        {
            $errors['pseudo'] = "Votre pseudo est invalide ! N.B(pseudo alphanumerique, pas d'espace, egal a 8)!";
        }
        else
        {
            $req = $bdd->prepare('SELECT id FROM users WHERE pseudo = ?');
            $req->execute([htmlentities($_POST['pseudo'])]);
            $user = $req->fetch();
            if ($user) {
                $errors['pseudo'] = "Ce pseudo est déjà pris";
            }
        }
        if(!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL) || !is_string($_POST['mail']))
        {
            $errors['mail'] = "Votre adresse mail n'est pas valide !";
        }
        else
        {
            $req = $bdd->prepare('SELECT id FROM users WHERE email = ?');
            $req->execute([htmlentities($_POST['mail'])]);
            $user = $req->fetch();
            if ($user) {
                $errors['mail'] = "Cette adresse mail est déjà prise";
            }
        }
        if(strlen($_POST['psw']) < 10  || strlen($_POST['psw']) > 64 || !ctype_alnum($_POST['psw']) || ( $_POST['psw'] !== str_replace(' ','',$_POST['psw'])) || !is_string($_POST['psw']))
        {
            $errors['psw'] = "Votre mot de passe n'est pas valide ! N.B(password alphanumerique, pas d'espace, superieur a 10)!";
        }
        if($_POST['psw-repeat'] != $_POST['psw'])
        {
            $errors['psw-repeat'] = "Vos mots de passes ne correspondent pas !";
        }
        $password = password_hash($_POST['psw'], PASSWORD_BCRYPT);
        $token = 'qwertyuiopasdfghjklzxcvbnm1239698745QWERTYIBVNXMHFDAKLOEVCBCJHB';
        $token = substr(str_shuffle($token),0,8);
        
        if(empty($errors))
        {
            $req = $bdd->prepare('INSERT INTO users(`pseudo`, `email`, `password`, `token`) 
                                 VALUES(:pseudo, :email, :password, :token)');
            $req->execute(array('pseudo' => htmlentities($_POST['pseudo']),
                                'email' => htmlentities($_POST['mail']),
                                'password' => $password,
                                'token' => $token));
            $user_id = $bdd->lastInsertId();
            $ip = $_SERVER['SERVER_NAME'];           
            mail($_POST['mail'], 'Confirmation de votre compte', "Cliquez sur ce lien pour valider votre compte\n\nhttp://".$ip.":3000/confirm.php?id=$user_id&token=$token");
            $_SESSION['flash']['success'] = 'Un email de confirmation vous a été envoyé pour valider votre compte';
            header('Location: login.php');
        }
    }
    require '/var/www/html/inc/header.php'; 
?>

<form action="register.php" method="POST">
  <div class="container">
    <h1>Register</h1>
    <hr>

    <label for="Pseudo"><b>Pseudo</b></label>
    <input type="text" placeholder="Enter Pseudo" name="pseudo" id="pseudo" required>
    <p><?php if($errors['pseudo'] != null){echo $errors['pseudo'];}?></p>

    <label for="email"><b>Email</b></label>
    <input type="text" placeholder="Enter Email" name="mail" id="email" required>
    <p>
    <?php if($errors['mail'] != null)
            echo $errors['mail'];
        ?>
    </p>
    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" id="psw" required>
    <p><?php if($errors['psw'] != null){echo $errors['psw'];}?></p>
    
    <label for="psw-repeat"><b>Repeat Password</b></label>
    <input type="password" placeholder="Repeat Password" name="psw-repeat" id="psw-repeat" required>
    <p><?php if($errors['psw-repeat'] != null){echo $errors['psw-repeat'];}?>
    <button type="submit" class="registerbtn">Register</button>
    
    <hr>
  </div>
</form>
<?php
    require '/var/www/html/inc/footer.php';
?>