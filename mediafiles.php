<!DOCTYPE html>
<html>
<head>
  <title>Media Organiser</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/jquery.multiselect.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
</head>

<body>

<?php

error_reporting(E_ALL);
ini_set('display_errors','On');

// include file to start database connection
include_once('ADODB.php');
//include class Files
require_once('classes/Files.php');



?>

<header>

   <div id="header">
    <h1>Media Organiser</h1>

    <ul class="main-nav-bar">
      <li><form id="search" action="search.php" method="post"><input id="search" type="text" value="" name="word-to-search" placeholder="Search..."></input><button type="submit" value="search" name="search"><i class="fa fa-search"></i></button></form></li>
      <li class="current"><a href="mediafiles.php">Media Files</a> |</li>
      <li><a href="playlists.php">Playlists</a></li>
    </ul>
  </div>

</header>

<section id="uploader">

   <h2>Upload file</h2>
   <form id="upload" method="post" action="upload-file.php" enctype="multipart/form-data">
      <label for="title" >Title </label><br><input id="title" type="text" value="" name="file_name"></input><br><br>
      <label for="fileToUpload">File </label><br><input id="fileToUpload" type="file" name="fileToUpload" id="fileToUpload"></input><br><br>
      <label for="comment">Comment (max 50 characters) </label><br><textarea id="comment" value="" name="comment" maxlength="50"></textarea><br><br>
      <label for="imageToUpload">Image </label><br><input id="imageToUpload" type="file" name="imageToUpload" id="imageToUpload"></input><br><br>
      <label for="category">Category </label><select id="category" name='category' class="category">
        <option name="select" value="">Please select</option>

<?php
        // query to select categories names
        $sql='SELECT category FROM Categories';
        $result = $db->Execute($sql);

        // check if there are records
        if ($result->Recordcount() > 0) {
          // populate select options
          $categories=[];
          for ($i=0; $i<$result->RecordCount(); $i++) {
           $categories[] = $result->fields["category"];
           if($categories[$i] != "None"){
             print "<option name='$categories[$i]' value='$categories[$i]' >".$categories[$i]."</option>";
           }
           $result->MoveNext();
        }
        }
?>
      </select><br><br>
      <input type="submit" value="Upload file" name="upload"></input>
   </form>

</section>

<section id="files">

  <h2>Uploaded files</h2>

  <?php

  $f = new App\Files;

/*  $uploadedFiles = $f->selectUploadedFiles();

  foreach ($uploadedFiles as $uplFile){
   print $uplFile."<br>";
}*/

$f->selectUploadedFiles();

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
</script>

</html>
