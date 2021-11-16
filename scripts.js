const image_link = "https://image.tmdb.org/t/p/w500"; // for image link
const movieList = [];       // stored movie request result page by page
let is_requesting = false; 
let currentTitle = '';      // current search value
let pageCount = 0;          // number of pages
let currentPageNumber = 1;  // current page number

const movie_con = document.getElementById('movies-container'); // card/movie container
const input_field = document.getElementById('title');               // movie search field
const templates = {
    movie_template: document.getElementById("movie-template").content.querySelector(".movie"),
};

document.addEventListener("DOMContentLoaded", (e) => {
    currentTitle = JSON.parse(window.localStorage.getItem('title'));
    search();
});

// load more contant by scrolling down
window.onscroll = function(e) {
    if((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 100) {
        if(currentPageNumber < pageCount) {
            search();
        }
    }
};
window.onkeyup = function(e) {
    if(e.keyCode === 13) { // search on enter
        e.preventDefault();
        prepare();
    }
}

// set params to default
function prepare() {
    // check if empty
    if(input_field.value == "") {
        alert("Search field is empty!");
    } else {
        // set search value
        currentTitle = input_field.value;
        window.localStorage.setItem('title', JSON.stringify(currentTitle)); // save on local 

        // reset value
        currentPageNumber = 1;
        pageCount = 0;
        movie_con.innerHTML='';
        movieList.length = 0;

        // send request
        search();
    }
}

// get movies by name and page
async function search() {
    if(!is_requesting) {
        is_requesting = true;
        const response = await sendRequest({title: currentTitle, page: currentPageNumber});
        if(currentPageNumber < pageCount)
            currentPageNumber++;
        if(response.result === false) {
            alert(response.data);
        } else if(response.data !== []) {
            pageCount = response.data.total_pages;
            movieList.push(response.data.results);
            generateMovies();
        }
        is_requesting = false;
    }
}

// send request with parameters
function sendRequest(params) {
    return new Promise((res,rej) => {
        jQuery.ajax({
            type: "POST",
            url: "api.php",
            data: params,
            success: function(result) {
                res(JSON.parse(result))
            },
            error: function(error) {
                console.log(error);
            }
        })
    });
}

// generate movies elements
function generateMovies() {
    Array.from(movieList[currentPageNumber-1]).map(m => {
        const movie = templates.movie_template.cloneNode(true);
        movie.querySelector(".card-title").innerHTML = m.title;
        movie.querySelector(".original-title").innerHTML = m.original_title;
        movie.querySelector(".img").src =  "poster_path" in m && m.poster_path !== null ? `https://image.tmdb.org/t/p/w500${m.poster_path}` : "notfoundimage1.jpg";
        movie.querySelector(".description").innerHTML = m.overview;
        movie.querySelector(".rating").innerHTML = m.vote_average.length === 1 ? `${m.vote_average}.0` : m.vote_average;
        movie_con.append(movie);
    });
}