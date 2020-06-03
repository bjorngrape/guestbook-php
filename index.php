<?php
    include("db/db.php");
    include("classes/gbpost.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gästbok</title>
    <!-- <title>Alpacahead</title> -->
</head>
<body>

<h1><a href="index.php">Gästbok</a></h1>
<!-- Kul effekt! <marquee behavior="" direction=""></marquee> -->

<form method="GET" action="index.php">
<input type="search" name="search_query">
<input type="submit" value="Sök">
</form>

<hr />

<?php
    if (isset($_GET['search_query'])) {
        $searchQuery = $_GET['search_query'];

        // Starta ordning:
        $order = "desc";
        if(isset($_GET['order']) && $_GET['order'] == "ascending") {
            $order = "asc";
        }

        // Ändrar vi från '%$searchQuery% till '%:searchQuery% så kan vi binda :searchQuery till en variabel senare
        $query = "SELECT id, name, message, date_posted FROM messages WHERE name LIKE :searchQuery OR message LIKE :searchQuery ORDER BY date_posted $order;";
        
        $sth = $dbh->prepare($query);
        $queryParam = '%' . $searchQuery . '%';
        $sth->bindParam(':searchQuery', $searchQuery);

        $return = $sth->execute();

        // $rows = $dbh->query($query);

        if (!$return) {
            print_r($dbh->errorInfo());
            die;
        }

        echo "<h2>Sökresultat</h2> Vi hittade ". $sth->rowCount() ." inlägg på sökordet $searchQuery<hr />";
        echo 
        '<a href="index.php">Hem</a><br>
        Sortering: <a href="index.php?search_query=' . $searchQuery . '&order=ascending">Stigande</a> | <a href="index.php?search_query=' . $searchQuery . '&order=descending">Fallande</a><br /><hr />';

        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {

            echo "<b>Name:</b>". $row["name"]."<br>";
            echo "<b>Message:</b>". $row["message"]."<br>";
            echo "<b>Date posted:</b>". $row["date_posted"]."<br>";

            // Här hårdkodar vi _GET som vi sedan tar hand om i handlePosts.php
            echo "<a href=\'handlePost.php?action=delete&id=" . $row["id"] . "'>Delete</a>";
            echo "<hr />";
        }
?>

<?php

    } else {
?>

Sortering: <a href="index.php?order=ascending">Stigande</a> | <a href="index.php?order=descending">Fallande</a><br /><hr />

<?php

// Ersätter kod för sortering

$Posts = new GBPost($dbh);

// argument för sortering i vår query
if(isset($_GET['order']) && $_GET['order'] == "ascending") {
    $Posts->order = "asc";
}

$Posts->fetchAll();

// Loop som hämtar alla rader i Posts
foreach( $Posts->getPosts() as $post ) {
    echo "<b>Name:</b>". $post["name"]."<br>";
    echo "<b>Message:</b>". $post["message"]."<br>";
    echo "<b>Date posted:</b>". $post["date_posted"]."<br>";
    echo "<a href=\"handlePost.php?action=delete&id=". $post['id'] ."'>Delete</a>";
    echo "<br />";
// edit-knapp
    // echo "<a href=\'handlePost.php?action=edit&id=" . $post['id'] . "'>Edit</a>";
    echo "<hr />";

}

// Behövs det här för sortering?

    /* 
    // variabel som agerar som sql-kommando
    $order = "desc";

    // SQL-query
    $query = "SELECT id, name, message, date_posted FROM messages ORDER BY date_posted $order";
    $rows = $dbh->query($query);

    while ($row = $rows->fetch(PDO::FETCH_ASSOC)) {

         echo "<b>Name:</b>". $row["name"]."<br>";
         echo "<b>Message:</b>". $row["message"]."<br>";
         echo "<b>Date posted:</b>". $row["date_posted"]."<br>";
         echo "<a href='handlePost.php?action=delete&id=" . $row["id"] . "'>Delete</a>";
         echo "<hr />";
        // print_r($row);
    }
    */


?>

<form method="POST" action="handlePost.php">

    Namn: <br />
    <input type="text" name="name" required><br />
    <br />
    Meddelande: <br />
    <textarea name="message" id="textarea" cols="60" rows="10" required></textarea><br />
    <input type="submit" value="Skicka">

</form>
    
<?php
    }
?>

</body>
</html>