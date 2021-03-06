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
    <a class="col-md-2"href="results.php"><button class="backbutton">Back</button></a>
    <span class="col-md-2"></span>
    <a class="col-md-2" href="https://github.com/dsenteza/cse-437s-team-zon" target="_blank">Github</a>
    <a class="col-md-2" href="about.html">About</a>
    <a class="col-md-2" href="doc.html">Documentation</a>
  </div>
  <?php
  function getDistance($bookstoreAddress)
  {
    $from = "38.647867,-90.3068304"; // Assume the user is at LOPATA HALL
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
  $bookstores = [];
  $i=0;
  $mysqli = new mysqli('localhost', 'l.florence', 'PASSWORD', '437s');
  $stmt = $mysqli->prepare("select asin,title,author,category,image_url,phonenumber,bookstoredatabase.name from booksdatabase4 join bookstoredatabase on (booksdatabase4.bookstore_id=bookstoredatabase.id) where asin = '$_GET[isbn]'");
  $stmt->execute();
  $stmt->bind_result($asin1,$title,$author,$category,$image_url,$phoneNumber,$bookstore);
  while ($stmt->fetch()){
    $bookstores[$i] = $bookstore;
    ++$i;
    if ($author== ""){
    $author = "Unknown";
    }
  }
  if (count($bookstores) == 0){
    header("Location: index.php");
  }
  echo'<!-- book destails -->
  <div class="details row">
  <!-- edge spacer -->
  <div class="col-md-1 col-sm-12"></div>
  <!-- below div will be an image of a book cover -->
  <div id="bookAuth">
  <div class="col-md-2 col-sm-6 bookImg"><img src="'.$image_url.'" width="190px" height="270px"/></div>
  <!-- book description -->
  <div class="col-md-2 col-sm-6">
  <div class="authorInfo">
  <h1 class="book-title">'.htmlspecialchars($title).'</h1>
  <h4><span>Author: '.ucwords(strtolower(htmlspecialchars($author))).'</span></h4>
  <p>'.htmlspecialchars($category).'</p>
  </div>
  <!-- list of bookstores nearby -->
  <div class="bookstores">';
  foreach ($bookstores as $store){
    echo '<div class="store">
    <p class="storeName">'.htmlspecialchars($store).'</p>
    <address>
    '.getBookstoreAddress($store).'
    </address>
    <p>'.$phoneNumber.'</p>
    <p class="distance">'.getDistance(getBookstoreAddress($store)).' miles away</p>
    </div>
    </div>';
  }
  echo '</div>
  </div>
  <!-- map -->
  <div class="col-md-4 col-sm-12 mapSec">
  <p>*call bookstores for prices*</p>
  <div class="map" id="map"></div>
  </div>';
  $suggestions = [];
  $imageurl = [];
  $i1=0;
  $mysqli1 = new mysqli('localhost', 'l.florence', 'PASSWORD', '437s');
  $stmt1 = $mysqli1->prepare("select * from supplementaryitems");
  $stmt1->execute();
  //$stmt1->bind_result($name,$long_name, $num,$manufacturer,$fit,$brand,$vendor,$price,$image_url);
  $result = $stmt1->get_result();
  while ($row = $result->fetch_assoc()){
  //while ($stmt1->fetch()){
    $suggestions[$i1] = htmlspecialchars($row["Name"]);
    $imageurl[$i1] = htmlspecialchars($row["Image URL"]);;
    ++$i1;
  }
  
  $item1 = mt_rand(0,7);
  $item2 = mt_rand(8,15);
  $item3 = mt_rand(16,23);
  $item4 = mt_rand(24,29);
  
  echo'
  <!-- recommendations -->
  <div class="col-md-2 col-sm-12 studyRecs">
  <h2>Recommendations</h2>
  <div class="rec">
  <img src="'.$imageurl[$item1].'" width="100px" height="150px"/>
  <a href="#">'.$suggestions[$item1].'</a>
  </div>
  <div class="rec">
  <img src="'.$imageurl[$item2].'" width="100px" height="150px"/>
  <a href="#">'.$suggestions[$item2].'</a>
  </div>
  <div class="rec">
  <img src="'.$imageurl[$item3].'" width="100px" height="150px"/>
  <a href="#">'.$suggestions[$item3].'</a>
  </div>
  <div class="rec">
  <img src="'.$imageurl[$item4].'" width="100px" height="150px"/>
  <a href="#">'.$suggestions[$item4].'</a>
  </div>
  </div>';
   
      //API URL
    $url = 'http://does.fyi:3456/rpc';
    //create a new cURL resource
    $ch = curl_init($url);
    //setup request to send json via POST
    $data = array(
        'acin' => $asin1,
        'numOfResults' => 3
    );
    $stuff = array(
        "params" => $data,
        "method" => "Cambridge.getBookRecommendations",
        "id" => "1"
    );
    $payload = json_encode($stuff);
    //attach encoded JSON string to the POST fields
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    //set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    //return response instead of outputting
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //execute the POST request
    $result = curl_exec($ch);
    //close cURL resource
    curl_close($ch);
   $result = json_decode($result);
    echo '  <!-- recommendations -->
  <hr>
  <div class="row">
  <h3 id="youmayalsolike">You May Also Like</h3>
    <div class="recsgroup">
      <!-- the below three divs will be book images -->';
    foreach ($result->{'result'} as $book) {
  if (ucwords(strtolower(htmlspecialchars($book[2])))==""){
  $book[2] = "Unknown";
        }
  echo ' <div class="recDiv">
               <a href="details.php?isbn='.$book[0].'">
               <img src="'.$book[3].'" width="190px" height="270px"/>
               <p class = "newclass">'.$book[1].'<br/>'.ucwords(strtolower(htmlspecialchars($book[2]))).'</p>
               </a></div>';
    }
  ?>
    </div>
  </div>
  <script>
  var geocoder;
  function initMap() {
        //define the center of the map and its zooming
        //may adjust it accordingly
        let option = {
          zoom: 13,
          center: {lat: 38.6472923, lng: -90.288}
        },
        map = new google.maps.Map(document.getElementById('map'), option);
        let bookstores = document.getElementsByClassName("storeName");//get the bookstore names from the dom
        let bookstoreMap  = new Map();//Create a Map to get the location of each bookstore
        let phoneNumberMap = new Map();
        // //ADD BOOKSTORES HERE with their locations
        bookstoreMap.set('Washington University Campus Store', "6465 Forsyth Blvd, St. Louis, MO 63105");
        bookstoreMap.set('Subterranean Books', "6275 Delmar Blvd, St. Louis, MO 63130");
        bookstoreMap.set('Left Bank Books', "399 N Euclid Ave, St. Louis, MO 63108");
        bookstoreMap.set('The Wizards Wagon', "6388 Delmar Blvd, University City, MO 63130");
        bookstoreMap.set('Fontbonne Bookstore', "6800 Wydown Blvd, St. Louis, MO 63105");
        phoneNumberMap.set('Washington University Campus Store', "(314) 935-5500");
        phoneNumberMap.set('Subterranean Books', "(314) 862-6100");
        phoneNumberMap.set('Left Bank Books', "(314) 367-6731");
        phoneNumberMap.set('The Wizards Wagon', "(314) 862-4263");
        phoneNumberMap.set('Fontbonne Bookstore', "(314) 889-1420");
        geocoder = new google.maps.Geocoder();
        //infoWindow = new google.maps.InfoWindow;
        //Looping the bookstores and add them to the map
        for (let i = 0; i < bookstores.length; i++) {
          let bookstoreName = bookstores[i].innerText.toString();
          let bookstoreLoc =bookstoreMap .get(bookstoreName);
          let phone = phoneNumberMap.get(bookstoreName);
          console.log(bookstoreName);
          console.log(bookstoreLoc);
          addMarker(bookstoreLoc,bookstoreName, map, phone);
        }
      }
        //Function that add marks to the map
        function addMarker(coords,storeName, markMap, phoneNumber){
          geocoder.geocode({'address': coords}, function(results, status) {
          if (status === 'OK') {
            console.log(results[0]);
            console.log(markMap);
            let marker = new google.maps.Marker({
              map: markMap,
              position: results[0].geometry.location
            });
            console.log(marker);
            let names = storeName
            let url = "https://www.google.com/maps/search/"+storeName.replace(/ /g,'+'); //url for navigation
            let label ='<h3>'+names+'</h3><p class="window">'+coords+'<br>'+phoneNumber+'</p><br><a href='+url+' target="_blank">Directions</a>'; //label for each node
            let infowindow=new google.maps.InfoWindow({content:label});
            marker.addListener('click',function(){
                    infowindow.open(markMap, marker)
                }
            );
          } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
          });
        }
</script>
<!--Calling google api-->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDPTRY89JQZBHOgKto1ciw-ghZ8KSDclvY&callback=initMap"
async defer></script>
</body>
</html>
