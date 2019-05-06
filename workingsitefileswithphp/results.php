<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <!-- we could add a varable call to pull in the query that was searched -->
  <title>Team Amazon Book Searching Site</title>
  <!-- link to css file -->
  <link rel="stylesheet" href="css/style.css">
  <!-- FontAwesome styling -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <!-- Bootstrap Link for Styling -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
  <body>
<?php
session_start();
//echo $_SESSION['searchType'];
//echo $_SESSION['searchTerm'];
//echo $_SESSION['results'];
?>
    <!-- navigation bar -->
    <div class="navBar row">
      <a class="col-md-2"href="index.php"><img src="img/TeamAmazonLogo.png" height="60px" width="60px" alt="Team Amazon Logo"></a>
      <span class="col-md-4"></span>
      <a class="col-md-2" href="https://github.com/dsenteza/cse-437s-team-zon" target="_blank">Github</a>
      <a class="col-md-2" href="about.html">About</a>
      <a class="col-md-2" href="doc.html">Documentation</a>
    </div>
    <!-- search bar -->
    <div class="searchBar">
      <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
        <i class="fas fa-search"></i>
        <input type="text" name="searchTerm" placeholder="Search By Title, Keyword, or ISBN" value = "<?php echo $_SESSION['searchTerm']; ?>">
        <input type="submit" name="search" value="Search">
        <div class="radio">
          <input type="radio" name="searchType" value="title" <?php echo (strcmp(htmlentities($_SESSION['searchType']),'title')===0) ? 'checked' : ''?>> Title
          <input type="radio" name="searchType" value="author" <?php echo (strcmp(htmlentities($_SESSION['searchType']),'author')===0)? 'checked' : ''?>> Author
          <input type="radio" name="searchType" value="isbn" <?php echo (strcmp(htmlentities($_SESSION['searchType']),'isbn')===0)? 'checked' : ''?>> ASIN
        </div>
      </form>
     </div>
<?php
if(isset($_POST['searchType'])){
 $_SESSION['searchType'] =  $_POST['searchType'];
 $_SESSION['searchTerm'] = $_POST['searchTerm'];
 header("Location: results.php");
}
//Function that returns the distance based on bookstore address
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
        "Subterranean Books"=>"6275 Delmar Blvd, St. Louis, MO 63130", "The Wizards Wagon"=>"6388 Delmar Blvd, University City, MO 63130","Fontbonne Bookstore"=>"6800 Wydown Blvd, St. Louis, MO 63105","Left Bank Books"=>"399 N Euclid Ave,St. Louis, MO 63108");
    return $bookstoreAddress[$bookstoreName];
}

//session_start();
//session_start();
//establish connection
$mysqli = new mysqli('localhost', 'l.florence', 'PASSWORD', '437s');

if(isset($_SESSION['searchType'])){
if(strpos($_SESSION['searchTerm'],'&')!==false){
  $radio = $_SESSION['searchTerm'];
}else{
  $radio = $mysqli->real_escape_string(htmlentities($_SESSION['searchTerm']));
}
}
if(isset($_POST['searchType'])){
if(strpos($_POST['searchTerm'],'&')!==false){
  $radio = $_POST['searchTerm'];
$_SESSION['searchType'] = $_POST['searchType'];
$_SESSION['searchTerm'] = $_POST['searchTerm'];
}else{
$_SESSION['searchType'] = $_POST['searchType'];
$_SESSION['searchTerm'] = $_POST['searchTerm'];
  $radio = $mysqli->real_escape_string(htmlentities($_POST['searchTerm']));
}
}

if($mysqli->connect_errno) {
  printf("Connection Failed: %s\n", $mysqli->connect_error);
  exit;
}
        if(isset($_SESSION['searchTerm']) AND strcmp(htmlentities($_SESSION['searchType']),'isbn')===0 AND $_SESSION['searchTerm'] != ''){
    $stmt = $mysqli->prepare("select asin,title,author,category,bookstoredatabase.name, image_url from booksdatabase4 join bookstoredatabase on (booksdatabase4.bookstore_id=bookstoredatabase.id) where asin = '$radio'");

    $stmt->execute();

    $stmt->bind_result($asin1,$title,$author,$category,$bookstore,$image_url);

    $stmt->fetch();

   //display the important data i.e. book_name, author, bookstore etc.
    if ($asin1==$radio){

    if(ucwords(strtolower(htmlspecialchars($author)))==""){
    $author = "Unknown";
    }
   echo '<div class="row searchResult">
     <!-- rectangles will be images -->
     <!-- <img src="'.htmlspecialchars($image_url).'" height="90px" width="60px" -->
     <div class="col-md-2"></div>
     <div class="col-md-1 bookCover">
     <img src="'.htmlspecialchars($image_url).'" height="120px" width="80px"/>
     </div>
     <div class="col-md-5 bookDesc">
      <a href="details.php?isbn='. htmlspecialchars($asin1) .'"><h2>';
     echo htmlspecialchars($title);
     echo "</h2></a><p>";
     echo "Author: " . ucwords(strtolower(htmlspecialchars($author)));
     echo "<br>";
     echo "Category: " . htmlspecialchars($category);
     echo "<br>";
     // echo "Bookstore: " .htmlspecialchars($bookstore);
     // echo '<br>';
     echo "ASIN: " .htmlspecialchars($asin1);
     echo '</p>
   </div>
   <div class="col-md-2 distance">
     <p>';
	echo'</p>
   </div>
   <div class="col-md-2"></div>
 </div>';

	}
  else{
    echo "<br/><br/><h3 style='text-align:center'>Invalid ISBN.  Please try again.</h3>";
  }

        }
        elseif(isset($_SESSION['searchTerm']) AND strcmp(htmlentities($_SESSION['searchType']),'title')===0){
    //search for titles provided by cambridge analytica    
    //API URL
    $url = 'http://does.fyi:3456/rpc';

    //create a new cURL resource
    $ch = curl_init($url);

    //setup request to send json via POST
    $data = array(
        'title' => $_SESSION['searchTerm'],
        'numOfResults' => "5"
    );

    $stuff = array(
        "params" => $data,
        "method" => "Cambridge.searchTitles",
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

    foreach ($result->{'result'} as $book) {
 if(ucwords(strtolower(htmlspecialchars($book[4])))==""){
    $book[4] = "Unknown";
    }
        echo '<div class="row searchResult">
     <!-- rectangles will be images -->
     <!-- <img src="'.htmlspecialchars($book[2]).'" height="90px" width="60px" -->
     <div class="col-md-2"></div>
     <div class="col-md-1 bookCover">
     <img src="'.htmlspecialchars($book[2]).'" height="120px" width="80px"/>
     </div>
     <div class="col-md-5 bookDesc">
      <a href="details.php?isbn='. htmlspecialchars($book[0]) .'"><h2>';
     echo htmlspecialchars($book[3]);
     echo "</h2></a><p>";
     echo "Author: " .  ucwords(strtolower(htmlspecialchars($book[4])));
     echo "<br>";
     echo "Category: " . htmlspecialchars($book[6]);
     echo "<br>";
     // echo "Bookstore: " .htmlspecialchars($bookstore);
     // echo '<br>';
     echo "ASIN: " .htmlspecialchars($book[0]);
     echo '</p>
   </div>
   <div class="col-md-2 distance">
     <p>';
  echo'</p>
   </div>
   <div class="col-md-2"></div>
 </div>';
    }


}
     elseif(isset($_SESSION['searchTerm']) AND strcmp(htmlentities($_SESSION['searchType']),'author')===0){
    //API URL
    $url = 'http://does.fyi:3456/rpc';

    //create a new cURL resource
    $ch = curl_init($url);

    //setup request to send json via POST
    $data = array(
        'author' => $_SESSION['searchTerm'],
        'numOfResults' => 5
    );

    $stuff = array(
        "params" => $data,
        "method" => "Cambridge.searchAuthors",
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
    $it = 0;

        foreach ($result->{'result'} as $book) {
          if ($it>5){
            break;
          }

	 if(ucwords(strtolower(htmlspecialchars($book[4])))==""){
    		$book[4] = "Unknown";
          }
        echo '<div class="row searchResult">
     <!-- rectangles will be images -->
     <!-- <img src="'.htmlspecialchars($book[2]).'" height="90px" width="60px" -->
     <div class="col-md-2"></div>
     <div class="col-md-1 bookCover">
     <img src="'.htmlspecialchars($book[2]).'" height="120px" width="80px"/>
     </div>
     <div class="col-md-5 bookDesc">
      <a href="details.php?isbn='. htmlspecialchars($book[0]) .'"><h2>';
     echo htmlspecialchars($book[3]);
     echo "</h2></a><p>";
     echo "Author: " . ucwords(strtolower(htmlspecialchars($book[4])));
     echo "<br>";
     echo "Category: " . htmlspecialchars($book[6]);
     echo "<br>";
     // echo "Bookstore: " .htmlspecialchars($bookstore);
     // echo '<br>';
     echo "ASIN: " .htmlspecialchars($book[0]);
     echo '</p>
   </div>
   <div class="col-md-2 distance">
     <p>';
  echo'</p>
   </div>
   <div class="col-md-2"></div>
 </div>';
 $it++;
    }
}
     elseif(isset($_SESSION['searchTerm']) AND strcmp(htmlentities($_SESSION['searchType']),'category')===0){
   //select all of the relevant data to display to the user
   $stmt = $mysqli->prepare("select asin,title,author,category,bookstoredatabase.name as name, image_url from booksdatabase4 join bookstoredatabase on (booksdatabase4.bookstore_id=bookstoredatabase.id) where LOWER(category) like LOWER('$radio') limit 5");

   $stmt->execute();

   $result = $stmt->get_result();
     while ($row = $result->fetch_assoc()){
    
    if(ucwords(strtolower(htmlspecialchars($row["author"])))==""){
                $row["author"] = "Unknown";
          }
   echo '<div class="row searchResult">
     <!-- rectangles will be images -->
     <!-- <img src="'.htmlspecialchars($row["image_url"]).'" height="90px" width="60px" -->
     <div class="col-md-2"></div>
     <div class="col-md-1 bookCover">
     <img src="'.htmlspecialchars($row["image_url"]).'" height="120px" width="80px"/>
     </div>
     <div class="col-md-5 bookDesc">
     <a href="details.php?isbn='. htmlspecialchars($row["asin"]) .'"><h2>';
     echo htmlspecialchars($row["title"]);
     echo "</h2></a><p>";
     echo "Author: " .  ucwords(strtolower(htmlspecialchars($row["author"])));
     echo "<br>";
     echo "Category: " . htmlspecialchars($row["category"]);
     echo "<br>";
     // echo "Bookstore: " .htmlspecialchars($row["name"]);
     //  echo '<br>';
     echo "ASIN: " .htmlspecialchars($row["asin"]);
     echo '</p>
   </div>
   <div class="col-md-2 distance">
     <p>';
        echo'</p>
   </div>
   <div class="col-md-2"></div>
 </div>';
 }
	}
     else{
     //do nothing
     }
// $_SESSION['searchType'] = '';
// $_SESSION['searchTerm'] = '';
?>
</body>
</html>
