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

class Movie {
    private $poster;
    private $original_title;
    private $translated_title;
    private $release_date;
    private $description;
    private $rating;

    public function getPoster() {return $this->poster;}
    public function getOriginalTitle() {return $this->original_title;}
    public function getTranslatedTitle() {return $this->translated_title;}
    public function getReleaseDate() {return $this->release_date;}
    public function getDescription() {return $this->description;}
    public function getRating() {return $this->rating;}

    public function __construct($data) {
        $this->poster = $data->poster_path;
        $this->original_title = $data->original_title;
        $this->translated_title = $data->title;
        $this->release_date = isset($data->release_date) ? $data->release_date : null;
        $this->description = $data->overview;
        $this->rating = strval($data->vote_average);
    }


}
?>