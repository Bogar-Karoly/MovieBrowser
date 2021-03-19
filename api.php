<?php

if(isset($_POST['function'])) {
    $function = $_POST['function'];

    require_once 'config.php';

    if(!empty($function)) {
        switch($function) {
            case $function === "search": call_user_func($function, $_POST['title']); break;
            case $function === "nextPage": call_user_func_array($function, [$_POST['title'], $_POST['page']]); break;
            default: return false; break;
        }
    }
}

function search($title) {
    $query = "https://api.themoviedb.org/3/search/movie?api_key=".API_KEY."&language=en-US&query=".$title."&page=1&include_adult=true";
    $movies = json_decode(file_get_contents($query));
    if($movies->total_results == 0) {
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

function nextPage($title, $page) {
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


/*
$dt = 'Tom%20%26%20Jerry';
$title = $_POST['title'];
//$title = json_decode($_POST['title']);

$query = "https://api.themoviedb.org/3/search/movie?api_key=44cb09a225e850bc9a5952bb01fd6c95&language=en-US&query=".$title."&page=1&include_adult=true";
$response = file_get_contents($query);

$data = json_decode($response);
//print_r($data);

$arr = [];

for($i = 1; $i <= $data->total_pages; $i++) {
    $result = json_decode(file_get_contents("https://api.themoviedb.org/3/search/movie?api_key=44cb09a225e850bc9a5952bb01fd6c95&language=en-US&query=".$title."&page=".$i."&include_adult=true"));
    $temp_arr = [];
    foreach($result->results as $key => $value) {
        $temp_arr['image'] = $value->poster_path;
        $temp_arr['original_title'] = $value->original_title;
    }
    array_push($arr, $temp_arr);
}
print_r(json_encode($arr));
*/

?>