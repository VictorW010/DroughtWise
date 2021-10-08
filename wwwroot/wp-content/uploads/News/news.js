const app = document.getElementById('root');
const head = document.createElement('div');
head.setAttribute('class','head');
app.appendChild(head);
// const container = document.createElement('div');
// container.setAttribute('class','container');
// const farmerD = document.createElement("div");
// farmerD.setAttribute('class','NewHeads');

for(let i=0;i<3;i++)
{
// farmerD.appendChild(fDiv);
// app.appendChild(fDiv);
// var container = document.createElement('div');
// container.setAttribute('class','container');
// app.appendChild(container);
// app.appendChild(fDiv);
var request = new XMLHttpRequest();
var strL =["farm+australia","drought","climate+australia"];


// var urlNew = "https://gnews.io/api/v4/search?q="+strL[i]+"&country=au&token=7e3358ab4990ad84cea1a7196e3e00f8&lang=en&sortby=publishedAt&order=asc&pageSize=50";
// var urlNew = "https://gnews.io/api/v4/search?q="+strL[i]+"&token=165a6378c0d83ca1317dd427f919a742&lang=en&sortby=publishedAt&order=asc&pageSize=50&country=au";
// var urlNew = "https://gnews.io/api/v4/search?q="+strL[i]+"&country=au&token=85ad83253b57715fdd0e952a5da66a71&lang=en&sortby=publishedAt&order=asc&pageSize=50";
var urlNew = "https://gnews.io/api/v4/search?q="+strL[i]+"&country=au&token=0e5328b13b094f109868d1058e71470f&lang=en&sortby=publishedAt&order=asc&pageSize=50";



// request.open('GET',"https://newsapi.org/v2/everything?q="+strL[i]+"&google_domain=google.com&sortby=publishedAt&order=asc&pageSize=50&apiKey=d450a4cd89c942319a541371bb3e4d5d", true);
request.open('GET', urlNew, true);

// request.open('GET',"https://api.currentsapi.services/v1/search?q=farm+australia&google_domain=google.com&sortby=publishedAt&order=asc&pageSize=50&token=VtMRVIu9WuMkDq56T450kZOiNLQwvcMUoyzrPSRwBclwom5x&lang=en", true);
// request.setRequestHeader("Access-Control-Allow-Origin", "*");
// request.setRequestHeader("Access-Control-Allow-Credentials", "true");
// request.setRequestHeader("Access-Control-Allow-Methods", "GET,HEAD,OPTIONS,POST,PUT");


// request.addEventListener("readystatechange", function () {
// 	if (this.readyState === this.DONE) {
// 		console.log(this.responseText);
// 	}
// });

// request.open("GET", "http://api.mediastack.com/v1/search?q="+strL[i]+"&google_domain=google.com&sortby=publishedAt&order=asc&pageSize=50");
// request.setRequestHeader("x-rapidapi-host", "google-news1.p.rapidapi.com");
// request.setRequestHeader("x-rapidapi-key", "0d984acd18mshd35585f73582c7dp1ef769jsne6d9ec940405");


request.onload = function(){
    //begin to access JSON data here
    var farmerD = document.createElement("div");
    farmerD.setAttribute('class','NewHeads');
    console.log("i= " + i);
    const strNew = ["Farming", "Drought", "Climate"];
    var fDiv = document.createTextNode(strNew[i] + " News");
    farmerD.appendChild(fDiv);
    var container = document.createElement('div');
    container.setAttribute('class','container');
    app.appendChild(farmerD);
    app.appendChild(container);
    var count = 0;
    var data = JSON.parse(this.response).articles;
    
    console.log(data);
    console.log(request.status);
    if((request.status >= 200 && request.status <= 500)||request.status == 0){
        Object.values(data).forEach(movie =>{    
        // data.forEach(movie =>{
            // console.log(movie.source)
            var h2 = document.createElement('h5');
            h2.textContent = movie.title;
            // var p = document.createElement('p');
            // movie.description = movie.description.substring(0,500);
            // p.textContent = `${movie.description}...`;
            tempS = strL[i].split("+")[0];
            
            if((movie.title.includes(tempS)||movie.content.includes(tempS))&&count<3)
            // if(count<3)
            {
            var idName = tempS + count;
            console.log(idName);
            count++;
            var card = document.createElement('div');
            card.setAttribute('class','cardNews');
            // card.setAttribute('id',idName);
            // console.log("id = " + index);
            // var e1 = document.getElementsByClassName("card")[index];
            // console.log(e1);
            // e1.addEventListener("click", function(){console.log("change");}, false);

            var cardHeader = document.createElement('div');
            cardHeader.setAttribute('class','card_header1');
            cardHeader.style.backgroundImage = "url('" + movie.image+ "')";
            cardHeader.style.backgroundSize = "100% 100%";
            cardHeader.style.backgroundPosition = "center";
            cardHeader.style.backgroundRepeat= "repeat-y";
            var author = document.createElement('small');
            author.setAttribute('class','author');
            if(movie.author === null|| movie.author  === undefined || movie.author === ''){
                author.textContent = `-- ${movie.source.name} --`;
            }else{
                if(movie.author.includes(","))
                    movie.author = movie.author.split(",")[0];
                author.textContent = `-- ${movie.author} --`;
                
            }
            var date = document.createElement('small');
            date.setAttribute('class','date');
            var strDate = movie.publishedAt.split("T")[0];
            date.textContent = `${strDate}`;
            var cardBody =document.createElement('div');
            cardBody.setAttribute('class','card_body');
            card.onclick= function(){
                window.location.href= movie.url;
                // console.log(movie.url);
            }
            container.appendChild(card);
            card.appendChild(cardHeader);
            // cardHeader.appendChild(h2);
            // cardHeader.appendChild(author);
            // cardHeader.appendChild(date);
            card.appendChild(cardBody);
            cardBody.appendChild(h2);
            cardBody.appendChild(author);
            cardBody.appendChild(date);
            // cardBody.appendChild(btn);
            }    
            }
        )
    }else{
        console.log('error')
    }
};

// var interval = setInterval(function(){request.send();}, 1000);
request.send();
// clearInterval(interval);
// console.log("request"+request);
// request.send();
}