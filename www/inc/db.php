<?php
require('/var/www/html/config/database.php');

// $DB_DSN = "mysql:host=db";
try {
    $bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    // set the PDO error mode to execption
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
