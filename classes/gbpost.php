<?php

// include("../db/db.php");

class GBPost {
    // Deklara variabler, enbart för klassen då de är private
    private $databaseHandler;
    public $order = "desc";
    private $posts;

    public function __construct($dbh) {

        $this->databaseHandler = $dbh;
    }

    public function fetchAll() {

        $query = "SELECT id, name, message, date_posted FROM messages ORDER BY date_posted $this->order";
        
        $return_array = $this->databaseHandler->query($query);
        $return_array = $return_array->fetchAll(PDO::FETCH_ASSOC);

        $this->posts = $return_array;
        
    }

    public function getPosts() {

        return $this->posts;
    }
}

?>