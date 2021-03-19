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

        let imageUrl = "https://image.tmdb.org/t/p/w500"+element.image;
        let request = new XMLHttpRequest();
        request.open("GET", imageUrl, true);
        request.send();
        request.onload = function() {
            console.log(request.status);
            if(request.status != 200) {
                imageUrl = "notFoundImage.jpg";
            }
        }


        let poster = document.createElement("img");
        poster.onerror = imageNotFound(this);
        poster.style.maxHeight = "300px";
        poster.style.maxWidth = "200px";
        poster.src = imageUrl;

        let title = document.createElement("h4");
        title.innerHTML = element.original_title;

        card_img.appendChild(poster);
        card_body.appendChild(title);
        card.appendChild(card_body);
        card.appendChild(card_img);

        setTimeout(() => movieContainer.appendChild(card), 100 * multiplier);
        multiplier++;
        //movieContainer.appendChild(card);

    });
}
function imageNotFound(image) {
    image.onerror = '';
    image.src = "notFoundImage.jpg";
    return true;
}