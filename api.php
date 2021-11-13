<?php

if(isset($_POST['title'])) {
    if(!empty($_POST['title'])) {
        require_once 'config.php';

        call_user_func_array("searchForMovies", [$_POST['title'], $_POST['page']]);
    }
}


function searchForMovies($title, $page) {
    $result = [];

    $query = "https://api.themoviedb.org/3/search/movie?api_key=".API_KEY."&language=en-US&query=".$title."&page=".$page."&include_adult=false";
    $movies = json_decode(file_get_contents($query));
    if($movies->total_results === 0) {
        $result['error'] = "No result!";
        echo json_encode($result);
        exit();
    }

    $result['total_pages'] = $movies->total_pages;
    $result['total_results'] = $movies->total_results;
    $result['currentPage'] = $movies->page;

    $temp_movies = [];
    
    foreach($movies->results as $key => $movie) {
        $temp = [];
        $temp['image'] = $movie->poster_path;
        $temp['original_title'] = $movie->original_title;
        $temp['title'] = $movie->title;
        $temp['release_date'] = isset($movie->release_date) ? $movie->release_date : null;
        $temp['description'] = $movie->overview;
        $temp['rating'] = strval($movie->vote_average);

        array_push($temp_movies,$temp);
    }
    $result['movies'] = $temp_movies;
    //print_r($result[0]);
    echo json_encode($result, true);
    exit();
}
//echo "<button click=ondelete([{$row['asd']}])>"
?>