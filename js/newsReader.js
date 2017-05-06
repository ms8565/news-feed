"use strict";

const PROXY_URL = "proxy.php?url=";

let articles = [];
let loadedArticles = [];
let amountToShow = 10;
let amountShown = 0;

let bbcIcon;
let cnnIcon;

class Article{
  constructor(provider, topic, pubDate, title, description, link){
    this.title = title;
    this.description = description;
    this.link = link;
    this.provider = provider;
    this.topic = topic;
    this.pubDate = pubDate;
    this.favorited = false;
  }
  getHTML(){
    let html = "<div class='article'>";
    //html += "<img src='"+providerIcon.src+"' alt='icon' width='"+providerIcon.width+"' height='"+providerIcon.height+"'>";
    html += "<h3><a href='"+this.link+"'>"+this.provider+": " + this.title  + "</a></h3>";
    html += "<p class='description'>"+this.description+"</p>";
    html += "<p class='pubDate'>" + this.pubDate + "</p>";
    //html += "<p><a href='" + this.link + "'>Article Link</a></p>";
    html += "</div>";
    
    return html;
  }
}


let shownProviders = [];
let shownTopics = []

const init = () =>{
  loadUser();
  //createUser("user", "password");

  //Set the window scroll function for generating random strings
  $(window).scroll(function() {
     if($(window).scrollTop() + $(window).height() == $(document).height()) {
        amountToShow+=5;
       displayFeed();
     }
  });
  
  //Get world checkboxes
  for(let i = 0; i < 4; i++){
    let checkbox = document.getElementById('checkbox');
  }
  
  //bbcIcon = document.getElementById('bbc-icon');
  //cnnIcon = document.getElementById('cnn-icon');


  loadRSS('http://feeds.bbci.co.uk/news/world/rss.xml?edition=uk', 'bbc', 'general');
  loadRSS('http://rss.cnn.com/rss/cnn_topstories.rss', 'cnn', 'general');

};

window.onload = init;

 const loadJSONObj = () => {
  var select = document.getElementById('json-select');
  var url = 'json/' + select.options[select.selectedIndex].value;

  var xhr = new XMLHttpRequest();

  xhr.onload = function(){
    try{
      var myJSON = JSON.parse( xhr.responseText );

      var allInfo = myJSON.info;
      var response = "";

      for(var i = 0; i <allInfo.length; i++){
        var info = allInfo[i];

        response +="<div class = 'info'>";
        response +="<h3>"+info.name+"</h3>";
        response +="<p>Age: "+info.age+"</p>";
        response +="<p>Hometown: "+info.hometown+"</p>";
        response += "</div>";

        document.querySelector('#json-content').innerHTML = response;
      }

    }
    catch(error){
      document.querySelector('#json-content').innerHTML = "<h3>Error Loading Data</h3><p>" + error + "</p";
    }
  }

  xhr.open('GET',url,true);
  // try to prevent browser caching by sending a header to the server
  xhr.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2017 00:00:00 GMT");
  xhr.send();
};

const createUser = (username, password) => {
  let data = {function: "createUser", username: username, password: password};
  //loadJSON("POST", data);
};

const loadJSON = (type, requestData) => {
  $.ajax({
      type: type,
      url: "fileIO.php",
      dataType: "json",
      data: requestData,
      success: onJSONLoaded,
      error: function(xhr, status, error) {
        var err = "(" + xhr.responseText + ")"; 
        console.log(err);
          console.log(error);
          console.log(status);
      }
  }); 
};

const loadUser = () => {
  $.ajax({
      type: 'GET',
      url: "fileIO.php?",
      dataType: "json",
      success: onJSONLoaded,
      error: function(xhr, status, error) {
          console.log(error);
          console.log(status);
      }
  }); 
};

const onJSONLoaded = (data) => {
  console.log(data);
};

const loadRSS = (rssUrl, provider, topic) =>{
  $.ajax({
      type: "GET",
      url: PROXY_URL + rssUrl,
      dataType: "xml",
      success: function(data){
        onRSSLoaded(data, provider, topic);
      },
      error: function(xhr, status, error) {
          console.log(error);
          console.log(status);
      }
  });   
};

const onRSSLoaded = (data, provider, topic) =>{
  //$('#rss-content').html("");

  var siteTitle = $(data).find('title').first().text();

  $(data).find('item').each(function(){

    const title = $(this).find('title').text();
    const description = $(this).find('description').text();
    const link = $(this).find('link').text();
    const pubDate = $(this).find('pubDate').text();

    let html = "<div class='article'>";
    //html += "<img src='"+providerIcon.src+"' alt='icon' width='"+providerIcon.width+"' height='"+providerIcon.height+"'>";
    html += "<h3>"+siteTitle+": " + title  + "</h3>";
    html += "<p class='description'>"+description+"</p>";
    html += "<p class='pubDate'>" + pubDate + "</p>";
    html += "<p><a href='" + link + "'>Article Link</a></p>";
    html += "</div>";

    articles.push({article: html, date: Date.parse(pubDate)});
    
    //provider, topic, date, title, content, link)
    let newArticle = new Article(provider, topic, pubDate, title, description, link);
    loadedArticles.push(newArticle);

  });

  displayFeed();
};

const displayFeed = () => {
  console.log('called');
  console.log(articles);
  //$('#rss-content').html("");

  //Sort Articles by date
  articles.sort(function(a, b){
    return b.date - a.date;
  });

  if(amountToShow > articles.length){
    amountToShow = articles.length;
  }

  for(var i = amountShown; i < amountToShow; i++){
    
    $('#rss-content').append(loadedArticles[i].getHTML());
    //$('#rss-content').append(articles[i].article);
  }
  amountShown = amountToShow;
  //$('#rss-content').hide();
  //$('#rss-content').fadeIn();

};
    