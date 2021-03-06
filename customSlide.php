<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>CSCI Slideshow</title>
  <link rel="stylesheet" href="css/global.css">
  <link rel="stylesheet" href="superslides-0.6.2/dist/stylesheets/superslides.css">
  <link rel="stylesheet" href="css/customSlide.css">

</head>
<body>
  <div id="slides">
    <ul class="slides-container">
    </ul>
  </div>

  <script src="js/jquery-2.2.3.min.js"></script>
  <script src="superslides-0.6.2/examples/javascripts/jquery.easing.1.3.js"></script>
  <script src="superslides-0.6.2/examples/javascripts/jquery.animate-enhanced.min.js"></script>
  <script src="superslides-0.6.2/dist/jquery.superslides.js" type="text/javascript" charset="utf-8"></script>
  <script>
  window.addEventListener("load", ready);
  var slideshow;
  var slideNumber = 0;

  function ready(){
    setHeight();
    initializeSlider();
    setInterval(getNextSlide, 3000);
  }

  function setHeight(){
    var height = window.innerHeight;
    $(".slides-container").height(height-4);
  }

  function initializeSlider(){
    for(var i=0; i<3; i++){

      $.ajax({
        url : "getSlide.php",
        dataType : 'json',
        type : 'POST',
        data : {},
        index : i,
        success : function (returnData) {
          if(returnData.message == "Success"){
            var nextSlideData = returnData.slide[0];
            var imageTag = ""; //default image tag if there is none
            var iframeTag = "";

            if(nextSlideData.image !== ""){ //there is an image
              imageTag = "<img src='" + nextSlideData.image + "'/>";

            }

            else if(nextSlideData.url !== ""){
              iframeTag = "<iframe src='" + nextSlideData.url + "' ></iframe>";
            }

            else if (nextSlideData.url === "" && nextSlideData.image === "")imageTag="<img src='assets/default.jpg'/>";
            var nextSlide = "<li>" + imageTag + iframeTag + "<div class='content_container'><h1>" + nextSlideData.title + "</h1><p>" + nextSlideData.content + "</p></div></li>";
            $("ul.slides-container").append(nextSlide);
            //due to the asynchronous nature of ajax the next 3 lines always throw exactly 2 errors. Fixing the problem is more of a headache than I realized. It is broken but works
              $("li")[0].setAttribute("class", "prev_slide");
              $("li")[1].setAttribute("class", "active_slide");
              $("li")[2].setAttribute("class", "next_slide");
          }
        }

      });
    }
  }

  function getNextSlide(){
    setHeight();
    if(slideNumber%5===0){
            ajaxSlide =  "<li class='next_slide' id='channel2'><iframe src='http://concerto.coloradomesa.edu/concerto/screen/index.php?mac=00:00:00:00:0B:AD/'></iframe></li>";
            //replaceContent();
            var slidesContainer = $("ul.slides-container");

            var prevSlide = $("ul.slides-container li")[0];
            var activeSlide = $("ul.slides-container li")[1];
            var nextSlide = $("ul.slides-container li")[2];

            
            prevSlide.remove();
            activeSlide.setAttribute("class","prev_slide");
            nextSlide.setAttribute("class","active_slide");
            slidesContainer.append(ajaxSlide);
            // var error = $(activeSlide).find("#errorPageContainer");
            
            checkForErrors(nextSlide);
          }
    else{
    $.ajax({
      url : "getSlide.php",
      dataType : 'json',
      type : 'POST',
      async : false,
      data : {},

      success : function (returnData) {
        if(returnData.message == "Success"){
          console.log(returnData);
          var nextSlideData = returnData.slide[0];


          var imageTag = ""; //default image tag if there is none
          var iframeTag = "";

          if(nextSlideData.image !== ""){ //there is an image
            imageTag = "<img src='" + nextSlideData.image + "'/>";

          }


          else if(nextSlideData.url !== ""){
            iframeTag = "<iframe src='" + nextSlideData.url + "' ></iframe>";
          }

          else if (nextSlideData.url === "" && nextSlideData.image === "")imageTag="<img src='assets/default.jpg'/>";
          var ajaxSlide =  "<li class='next_slide'>" + imageTag + iframeTag + "<div class='content_container'><h1>" + nextSlideData.title + "</h1><p>" + nextSlideData.content + "</p></div></li>";
          var slidesContainer = $("ul.slides-container");

          var prevSlide = $("ul.slides-container li")[0];
          var activeSlide = $("ul.slides-container li")[1];
          var nextSlide = $("ul.slides-container li")[2];

          
          prevSlide.remove();
          activeSlide.setAttribute("class","prev_slide");
          nextSlide.setAttribute("class","active_slide");
          slidesContainer.append(ajaxSlide);
          // var error = $(activeSlide).find("#errorPageContainer");
          
          checkForErrors(nextSlide);


        }

        else {
          //console.log(returnData);
        }

      }

    });
  }
  slideNumber++;
  }

function replaceContent(){
  
  var content = $("#channel2 iframe").contents().find( "body" ).html();
  $(".next_slide").html( content );
  console.log( content );
  $(this).remove();
}

function checkForErrors(nextSlide){

  try {
    var error = $("li.active_slide iframe").contents().find("#main-frame-error").html();
    if(error){
      var errorUrl = $(nextSlide).find("iframe")[0].getAttribute("src");
      console.log("page failed @"+errorUrl);
      $.post( "logError.php", { url: errorUrl} );
    }
  }

  catch(err) {
    console.log(err.message);
    console.log($(nextSlide).find("iframe")[0].getAttribute("src"));
  }
          
}



  </script>
</body>
</html>
