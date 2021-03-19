<?php

if(isset($_POST['title'])) {
    if(!empty($_POST['title'])) {
        require_once 'config.php';

        call_user_func_array("search", [$_POST['title'], $_POST['page']]);
    }
}

function search($title, $page) {
    $query = "https://api.themoviedb.org/3/search/movie?api_key=".API_KEY."&language=en-US&query=".$title."&page=".$page."&include_adult=true";
    $movies = json_decode(file_get_contents($query));
    if($movies->total_results === 0) {
        echo json_encode("false");
        exit();
    }

    $result = [];
    $result['pageCount'] = $movies->total_pages;
    $result['movieCount'] = $movies->total_results;
    $result['currentPage'] = $movies->page;

    $temp_movies = [];
    
    foreach($movies->results as $key => $movie) {
        $temp = [];
        $temp['image'] = $movie->poster_path;
        $temp['original_title'] = $movie->original_title;

        array_push($temp_movies,$temp);
    }
    $result['movies'] = $temp_movies;

    echo json_encode($result, true);
}

?>