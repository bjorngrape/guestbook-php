<?php

include("db/db.php");

$action = (isset($_GET['action']) ? $_GET['action'] : '');

// Ta bort inlägg och skicka tillbaka användaren

// = om den är satt och lika med delete körs det som står i blocket
if (isset($_GET['action']) && $_GET['action'] == "delete") {

$query = "DELETE FROM messages WHERE id=". $_GET['id'];
$return = $dbh->exec($query);

header ("location:index.php");

} else {

// Felmeddelande för gästbok och tvinga användare att skriva något i fälten
$name    = (!empty($_POST['name']) ? $_POST['name'] : "");
$message = (!empty($_POST['message']) ? $_POST['message'] : "");

$errors = false;
$errorMessages = "";

if (empty ($name)) {
    $errorMessages .= "Du måste skriva ett namn <br />";
    $errors = true;
}

if (empty ($message)) {
    $errorMessages .= "Du måste skriva ett meddelande <br />";
    $errors = true;
}

if ($errors == true) {
    echo $errorMessages;
    echo '<a href="index.php">Gå tillbaka</a>';
    die;
}

// Mata in data i databasens tabeller
$query = "INSERT INTO messages (name, message) VALUES('$name', '$message');";
$return = $dbh->exec($query);

if (!$return) {
    print_r($dbh->errorInfo());
} else {
    header("location:index.php");
}

}

?>