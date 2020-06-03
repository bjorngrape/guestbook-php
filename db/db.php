<?php

// PHP settings

$host = "localhost";
$user = "root";
$pass = "";
$db = "guestbook";

// skapa anslutning

try {
    $dsn = "mysql:host=$host;dbname=$db;";
    $dbh = new PDO($dsn, $user, $pass);
    }
catch(PDOException $e)
    {
    // vid error
    echo "Error! $e->getMessage() <br />";
    die();
    }

?>