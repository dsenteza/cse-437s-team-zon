<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Details</title>
    <!-- link to css file -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Bootstrap Link for Styling -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  </head>
  <body>
<?php
session_start();
?>
    <!-- navigation bar -->
    <div class="navBar row">
      <a class="col-md-2"href="index.php"><img src="img/TeamAmazonLogo.png" height="60px" width="60px" alt="Team Amazon Logo"></a>
      <span class="col-md-4"></span>
      <a class="col-md-2" href="https://github.com/dsenteza/cse-437s-team-zon" target="_blank">Github</a>
      <a class="col-md-2" href="about.html">About</a>
      <a class="col-md-2" href="doc.html">Documentation</a>
    </div>
    <?php

    function getDistance($bookstoreAddress)
{
    $from = "St. Louis, MO 63105"; // Assume the user is at LOPATA HALL
    $to = $bookstoreAddress;
    $from = urlencode($from);
    $to = urlencode($to);
    $apiKey = "AIzaSyDPTRY89JQZBHOgKto1ciw-ghZ8KSDclvY"; //Our google map api key
    $data = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&key=$apiKey&language=en-EN&sensor=false");
    $data = json_decode($data);
    $time = 0;
    $distance = 0;
    foreach ($data->rows[0]->elements as $road) {
        $time += $road->duration->value;
        $distance += $road->distance->value;
    }
    $miles=$distance*0.000621371;
    return ((int)($miles*10))/10;
}
//A helper function that returns the bookstore address based on the bookstore name
function getBookstoreAddress($bookstoreName){
    //Here, define the bookstore name (i.e. the name used in our website) - its address(Google the address and pasted here)
    //e.g."WashU bookstore"=>"6465 Forsyth Blvd, St. Louis, MO 63105"
    $bookstoreAddress = array("Washington University Campus Store"=>"6465 Forsyth Blvd, St. Louis, MO 63105",
        "Subterranean Books"=>"6275 Delmar Blvd, St. Louis, MO 63130", "The Wizards Wagon"=>"6388 Delmar Blvd, University City, MO 63130","Fontbonne Bookstore"=>"6800 Wydown Blvd, St. Louis, MO 63105","Left Bank Books"=>"399 N Euclid Ave, St. Louis, MO 63108");
    return $bookstoreAddress[$bookstoreName];
}

//session_start();
//session_start();
//establish connection
$mysqli = new mysqli('localhost', 'l.florence', 'PASSWORD', '437s');

    $stmt = $mysqli->prepare("select asin,title,author,category,image_url,bookstoredatabase.name from booksdatabase4 join bookstoredatabase on (booksdatabase4.bookstore_id=bookstoredatabase.id) where asin = '$_GET[isbn]'");

    $stmt->execute();

    $stmt->bind_result($asin1,$title,$author,$category,$image_url,$bookstore);

    $stmt->fetch();

    echo'<!-- book destails -->
    <div class="details row">
    <!-- edge spacer -->
      <div class="col-md-1"></div>
      <!-- below div will be an image of a book cover -->
      <div class="col-md-2 bookImg"><img src="'.$image_url.'" width="190px" height="270px"/></div>
      <!-- book description -->
      <div class="col-md-2">
        <div class="authorInfo">
          <h1>'.htmlspecialchars($title).'</h1>
          <h4><span>Author: </span>'.htmlspecialchars($author).'</h4>
          <p>'.htmlspecialchars($category).'</p>
        </div>
        <!-- list of bookstores nearby -->
        <div class="bookstores">
          <div class="store">
            <p class="storeName">'.htmlspecialchars($bookstore).'</p>
            <address>
              '.getBookstoreAddress($bookstore).'
            </address>
            <p class="distance">'.getDistance(getBookstoreAddress($bookstore)).' miles away</p>
          </div>
        </div>
      </div>
      <!-- map -->
      <div class="col-md-4 mapSec">
        <p>*call bookstores for prices*</p>
        <div class="map" id="map"></div>
      </div>
      <!-- recommendations -->
      <div class="col-md-2 studyRecs">
        <h2>Recommendations</h2>
        <div class="rec">
          <div class="recImg">
            <!--put recommendation image here-->
          </div>
          <a href="#">Rec Name</a>
        </div>
        <div class="rec">
          <div class="recImg">
            <!--put recommendation image here-->
          </div>
          <a href="#">Rec Name</a>
        </div>
        <div class="rec">
          <div class="recImg">
            <!--put recommendation image here-->
          </div>
          <a href="#">Rec Name</a>
        </div>
        <div class="rec">
          <div class="recImg">
            <!--put recommendation image here-->
          </div>
          <a href="#">Rec Name</a>
        </div>
    </div>';
    ?>
    <!-- recommendations -->
    <hr>
    
    <div class="row">
      <h3>You May Also Like</h3>
      <div class="recsgroup">
      <!-- the below three divs will be book images -->
      <div class="col-md-2 recs"></div>
      <div class="col-md-2 recs"></div>
      <div class="col-md-2 recs"></div>
      </div>
    </div>
    <p class="location" style="display:none">Subterranean Books</p>
<p class="location"  style="display:none">Washington University Campus Bookstore</p>
<p class="location"  style="display:none">Left Bank Books</p>
    <script>
    function initMap() {
        //define the center of the map and its zooming
        //may adjust it accordingly
        let option = {
                zoom: 13,
                center: {lat: 38.6472923, lng: -90.288}

            },
            map = new google.maps.Map(document.getElementById('map'), option);
        let bookstores = document.getElementsByClassName("location");//get the bookstore names from the dom
        let bookstoreMap  = new Map();//Create a Map to get the location of each bookstore
        //ADD BOOKSTORES HERE with their locations
        bookstoreMap.set('Washington University Campus Bookstore', {lat: 38.6472923, lng: -90.3113771});
        bookstoreMap.set('Subterranean Books', {lat:38.6558431,lng:-90.3047509});
        bookstoreMap.set('Left Bank Books', {lat:38.6483598,lng:-90.261127});

        //Looping the bookstores and add them to the map
        for (let i = 0; i < bookstores.length; i++) {
            let bookstoreName = bookstores[i].innerText.toString();
            let bookstoreLoc =bookstoreMap .get(bookstoreName);
            addMarker(bookstoreLoc,bookstoreName);

        }
        //Function that add marks to the map
        function addMarker(coords,storeName){
            let marker = new google.maps.Marker({position:coords,map:map});
            let names = storeName
            let url = "https://www.google.com/maps/search/"+storeName.replace(/ /g,'+'); //url for navigation
            let label ='<h3>'+names+'</h3><a href='+url+' target="_blank">Direction</a>'; //label for each node
            let infowindow=new google.maps.InfoWindow({content:label});
            marker.addListener('click',function(){
                    infowindow.open(map, marker)
                }
            );

        }
    }
</script>
<!--Calling google api-->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDPTRY89JQZBHOgKto1ciw-ghZ8KSDclvY&callback=initMap"
        async defer></script>
  </body>
</html>
