<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Team Amazon Book Searching Site</title>
    <!-- link to css file -->
    <link rel="stylesheet" href="css/style.css">
    <!-- FontAwesome styling -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <!-- Bootstrap Link for Styling -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- link for title font -->
    <link href="https://fonts.googleapis.com/css?family=Baloo+Chettan" rel="stylesheet">
  </head>
  <body>
    <!-- navigation bar -->
    <div class="navBar row">
      <a class="col-md-2"href="index.php"><img src="img/TeamAmazonLogo.png" height="60px" width="60px" alt="Team Amazon Logo"></a>
      <span class="col-md-4"></span>
      <a class="col-md-2" href="https://github.com/dsenteza/cse-437s-team-zon" target="_blank">Github</a>
      <a class="col-md-2" href="about.html">About</a>
      <a class="col-md-2" href="doc.html">Documentation</a>
    </div>
    <!-- search bar -->
    <div class="searchBar row">
      <div class="col-md-3"></div>
      <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" class="col-md-6">
        <i class="fas fa-search"></i>
        <input type="text" name="searchTerm" placeholder="Search By Title, Keyword, or ISBN">
        <input type="submit" name="search" value="Search">
        <div class="radio">
          <input type="radio" name="searchType" checked="true" value="title"> Title
          <input type="radio" name="searchType" value="author"> Author
          <input type="radio" name="searchType" value="isbn"> ASIN
        </div>
      </form>
    <div class="col-md-3"></div>
    </div>
    <!-- header image -->
    <div id="header" class="row">
      <div class="col-md-6 col-sm-12"></div>
      <h1 class="col-md-5 col-sm-12" id="zon">Team zon</h1>
      <div class="col-md-1 col-sm-12"></div>
    </div>
    <div id="hotBooks">
    <br/>
    <h3>Trending Books:</h3>
      <br/>
	<div class="recsgroup1">
	<?php
  $suggestions = [];
  $imageurl = [];
  $author = [];
  $i1=0;
  $asin = [];
  $mysqli1 = new mysqli('localhost', 'l.florence', 'PASSWORD', '437s');

  $stmt1 = $mysqli1->prepare("SELECT asin,author,title,image_url FROM booksdatabase4 ORDER BY RAND()
LIMIT 5");

  $stmt1->execute();

  $result = $stmt1->get_result();
  
  while ($row = $result->fetch_assoc()){
    $suggestions[$i1] = htmlspecialchars($row["title"]);
    $imageurl[$i1] = htmlspecialchars($row["image_url"]);
    if (ucwords(strtolower(htmlspecialchars($row["author"]))) == ""){
	$author[$i1] = "Unknown";	
    }else{
	$author[$i1] = ucwords(strtolower(htmlspecialchars($row["author"])));
    }
    $asin[$i1] = htmlspecialchars($row["asin"]);
    ++$i1;
  }
echo' 
      <div><a href="details.php?isbn='.$asin[0].'"><img src="'.$imageurl[0].'" width="190px" height="270px"/>
      <p class = "newclass">'.$suggestions[0].'<br/>'.$author[0].'</p></a></div>
      <div class = ""><a href="details.php?isbn='.$asin[1].'"><img src="'.$imageurl[1].'" width="190px" height="270px"/>
      <p class = "newclass">'.$suggestions[1].'<br/>'.$author[1].'</p></a></div>
      <div class = ""><a href="details.php?isbn='.$asin[2].'"><img src="'.$imageurl[2].'" width="190px" height="270px"/>
      <p class = "newclass">'.$suggestions[2].'<br/>'.$author[2].'</p></a></div>
      <div class = ""><a href="details.php?isbn='.$asin[3].'"><img src="'.$imageurl[3].'" width="190px" height="270px"/>
      <p class = "newclass">'.$suggestions[3].'<br/>'.$author[3].'</p></a></div>
      <div class = ""><a href="details.php?isbn='.$asin[4].'"><img src="'.$imageurl[4].'" width="190px" height="270px"/>
      <p class = "newclass">'.$suggestions[4].'<br/>'.$author[4].'</p></a></div>
';
	?>
      </div>
<?php
session_start();
if(isset($_POST['searchType'])){
 $_SESSION['searchType'] =  $_POST['searchType'];
 $_SESSION['searchTerm'] = $_POST['searchTerm'];
 header("Location: results.php");
}
?>
</body>
</html>
