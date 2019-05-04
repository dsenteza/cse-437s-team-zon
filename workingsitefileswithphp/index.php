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
          <input type="radio" name="searchType" value="title"> Title
          <input type="radio" name="searchType" value="author"> Author
          <input type="radio" name="searchType" value="isbn"> ISBN
        </div>
      </form>
    <div class="col-md-3"></div>
    </div>
    <!-- header image -->
    <div id="header" class="row">
      <div class="col-md-6 col-sm-12"></div>
      <h1 class="col-md-5 col-sm-12">Team zon</h1>
      <div class="col-md-1 col-sm-12"></div>
    </div>
    <div id="hotBooks">
      <h3>You May Also Like:</h3>
      <div class="row">
        <div class="col-md-1 col-sm-12"></div>
        <div class="col-md-2 hotOnes col-sm-12"><a href="#"></a></div>
        <div class="col-md-2 hotOnes col-sm-12"><a href="#"></a></div>
        <div class="col-md-2 hotOnes col-sm-12"><a href="#"></a></div>
        <div class="col-md-2 hotOnes col-sm-12"><a href="#"></a></div>
        <div class="col-md-2 hotOnes col-sm-12"><a href="#"></a></div>
        <div class="col-md-1 col-sm-12"></div>
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
