<!DOCTYPE html>
<html>
<head>
  <title>Media Organiser</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/jquery.multiselect.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

<?php

error_reporting(E_ALL);
ini_set('display_errors','On');

// include file to start database connection
include_once('ADODB.php');
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


<?php

$f = new App\Files;


if(isset($_GET["title"]) && isset($_GET["fileID"]) && isset($_GET["filepath"]) && isset($_GET["comment"]) && isset($_GET["imagePath"]) && isset($_GET["category"])){

  $title = $_GET["title"];
  $fileID = $_GET["fileID"];
  $filepath = $_GET["filepath"];
  $comment = $_GET["comment"];
  $imagePath = $_GET["imagePath"];
  $category = $_GET["category"];

?>
  <section id="uploader">

     <h2>Upload file</h2>
     <form id="upload" method="post" action="update-file.php" enctype="multipart/form-data">
       <label for="title" >Title </label><br><input id="title" type="text" value="<?php print $title; ?>" name="file_name"></input><br><br>
       <input type="hidden" id="fileID" name="fileID" value="<?php print $fileID; ?>">
       <label for="uploadedFile">File </label><br><input id="uploadedFile" name="uploadedFile" type="text" value="<?php print $filepath; ?>" readonly></input><br><br>
       <input id="fileToUpload" type="file" name="fileToUpload" id="fileToUpload"></input><br><br>
       <label for="comment">Comment (max 50 characters) </label><br><textarea id="comment" value="" name="comment" maxlength="50"><?php print $comment; ?></textarea><br><br>
       <label for="imageToUpload">Image </label><br><input id="uploadedImage" type="text" value="<?php print $imagePath; ?>" readonly></input><br><br>
       <input id="imageToUpload" type="file" name="imageToUpload" id="imageToUpload"></input><br><br>
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
               if($categories[$i]==$category){
                 print "<option name='$categories[$i]' value='$categories[$i]' selected='selected'>".$categories[$i]."</option>";
               } else {
               print "<option name='$categories[$i]' value='$categories[$i]' >".$categories[$i]."</option>";
             }
             }
             $result->MoveNext();
          }
          }
  ?>
        </select><br><br>
        <input type="submit" value="Update file" name="update"></input>
     </form>

  </section>


<?php

} else {

   echo "<p>Nothing to edit</p>";

 ?>

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
             print "<option name='$categories[$i]' value='$categories[$i]' >".$categories[$i]."</option>";
           $result->MoveNext();
        }
        }
?>
      </select><br><br>
      <input type="submit" value="Upload file" name="upload"></input>
   </form>

</section>

<?php
}
 ?>

<section id="files">

  <h2>Uploaded files</h2>

  <?php

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
