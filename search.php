<!DOCTYPE html>
<html>
<head>
  <title>Media Organiser</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/jquery.multiselect.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>

<?php

error_reporting(E_ALL);
ini_set('display_errors','On');

// include file to start database connection
include_once('ADODB.php');
//include class Files
require_once('classes/Files.php');
//include class Playlists
require_once('classes/Playlists.php');
//include class Search
require_once('classes/Search.php');

$f = new App\Files;
$p = new App\Playlists;
$s = new App\Search;


?>

<header>

   <div id="header">
    <h1>Media Organiser</h1>

    <ul class="main-nav-bar">
      <li><form id="search" action="search.php" method="post"><input id="search" type="text" value="" name="word-to-search" placeholder="Search..."></input><button type="submit" value="search" name="search"><i class="fa fa-search"></i></button></form></li>
      <li><a href="mediafiles.php">Media Files</a> |</li>
      <li><a href="playlists.php">Playlists</a></li>
    </ul>
  </div>

</header>

<section id="search-results">
<?php

    if(isset($_POST['search'])){

     if(isset($_POST['word-to-search']) || $_POST['word-to-search']!=""){

      $word = $_POST['word-to-search'];

?>

    <h2> Search results</h2>
<?php

    $s->searchFiles($word);
?>
<br><br>

<?php
    $s->searchPlaylists($word);

?>

<?php

    } else {

      print "Error - you didn't type anything to search";

    }

  } else {

    print "some error";
  }

?>



</section>

</body>

<script type="text/javascript">
   function playMyAudio(file){
     document.getElementById(file).play();
      }
   function pauseMyAudio(file){
     document.getElementById(file).pause();
   }
   function confirmAction() {
        let confirmAction = confirm("Are you sure to execute this action?");
        if (confirmAction) {
          alert("Action successfully executed");
        } else {
          alert("Action canceled");
        }
      }
</script>

</html>
