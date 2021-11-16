<?php

class Movies {
    function __construct() {
        require_once "config.php";
        $this->init();
    }
    function result($type, $data) {
        echo json_encode(["result" => $type, "data" => $data]);
        exit();
    }
    public function init() {
        array_map(function($e) { 
            if(!isset($_POST[$e]) || empty($_POST[$e]))
                $this->result(false, "Missing parameters!");
        }, ['title', 'page']);

        $page = $_POST['page'];
        $title = $_POST['title'];

        $query = "https://api.themoviedb.org/3/search/movie?api_key=".API_KEY."&language=en-US&query=".$title."&page=".$page."&include_adult=false";
        $movies = json_decode(file_get_contents($query));
        if($movies->total_results === 0)
            $this->result(false, "No movie found!");

        $this->result(true, $movies);
    }
}

new Movies;

?>