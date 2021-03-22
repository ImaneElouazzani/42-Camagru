<?php
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Camagru</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body style="position: relative;">

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <a class="navbar-brand" href="index.php">Camagru</a>
  <ul class="navbar-nav">
    <?php if(isset($_SESSION['auth'])): ?>
    <li>
    <a class="nav-link" href="logout.php">Logout</a>
    </li>
    <li>
    <a class="nav-link" href="account.php">Profil</a>
    </li>
    <li>
    <a class="nav-link" href="webcam.php"><i class="bi bi-camera"></i></a>
    </li>
    <?php else: ?>
    <li class="nav-item">
      <a class="nav-link" href="register.php">Register</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="login.php">Login</a>
    </li>
    <?php endif; ?>
  </ul>
</nav>