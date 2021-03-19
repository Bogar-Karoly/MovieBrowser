let movieList = [];
let currentTitle = '';
let pageCount = 0;
let currentPageNumber = 1;

const movieContainer = document.getElementById('movies-container');

window.onscroll = function(event) {
    if((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
        if(currentPageNumber < pageCount ) {
            currentPageNumber++;
            movieRequest();
        } else if( currentPageNumber > pageCount) {
            console.log("Error: current page number is higher than the maximum of pages!");
        }
    }
};

function startSearch() {
    if(document.getElementById('title').value == "") {
        console.log("Search field is empty!");
    } else if(document.getElementById('title').value != "") {

        currentTitle = document.getElementById('title').value;
        currentPageNumber = 1;
        pageCount = 0;

        clearMovies();
        movieRequest();
    }
}
function movieRequest() {
    jQuery.ajax({
        type: 'post',
        url: 'api.php',
        data: {title: currentTitle, page: currentPageNumber},
        success: function(data) {
            console.log('success');

            let result = JSON.parse(data);

            if(currentTitle == "") {
                currentTitle = document.getElementById('title').value;
            }
            pageCount = result.pageCount;

            //console.log(result);

            addMovies(result.movies);
            showMovies();
        },
        error: function(data) {
            console.log(data);
        }
    });
}

function addMovies(movies) {
    movieList.push(movies);
}

function clearMovies() {
    let movies = document.getElementById('movies-container');
    movies.innerHTML='';
    movieList = [];
}

function showMovies() {
    let multiplier = 0;
    Array.from(movieList[currentPageNumber-1]).forEach(element => {
        let card = document.createElement("div");
        card.classList.add("card");
        card.classList.add("mb-3");
        card.classList.add("card-ani");

        let card_body = document.createElement("div");
        card_body.classList.add("card-body");

        let card_img = document.createElement("div");
        card_img.classList.add("card-img-top");

        let poster = document.createElement("img");
        poster.style.height = "300px";
        poster.style.width = "200px";

        if(element.image == null) {
            poster.src = "notfoundimage1.jpg";
        } else {
            poster.src = "https://image.tmdb.org/t/p/w500"+element.image;
        }

        let title = document.createElement("h4");
        title.innerHTML = element.original_title;

        card_img.appendChild(poster);
        card_body.appendChild(title);
        card.appendChild(card_body);
        card.appendChild(card_img);

        setTimeout(() => movieContainer.appendChild(card), 100 * multiplier);
        multiplier++;
    });
}