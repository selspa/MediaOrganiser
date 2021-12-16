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

// include vendor/autoload to use Kint to debug
/*include 'vendor/autoload.php';

// commands for Kint
d($GLOBALS, $_SERVER);
Kint::trace();*/


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

<section id="uploaded">
<?php

$f = new App\Files;
$file = [];
$image = [];
$comment = "";
$category = "";

if(isset($_POST["update"])){

 // query to retrieve all the titles to check if they try to insert the same title
  $sql = "SELECT title FROM MediaFiles";

  $result = $db->Execute($sql);

  if($result->RecordCount() > 0){
      $titles = [];
      for ($i=0; $i<$result->RecordCount(); $i++) {
        $titles[] = $result->fields['title'];
        $result->MoveNext();
      }

  }

  $thisTitle = [];
  $titlesMinusThisTitle = [];

  $thisTitle = [$_POST['file_name']];

  $titlesMinusThisTitle = array_diff($titles, $thisTitle);

if((isset($_POST['file_name']) || $_POST['file_name'] != "") && !in_array($_POST['file_name'], $titlesMinusThisTitle)){
    $file_name = $_POST['file_name'];
    echo "File name: ".$file_name."<br>";

    $fileID = $_POST['fileID'];


   if(isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] == 0){

       // set files variables
       $target_dir = "uploads/";
       $fileTypes = ["audio/aac", "audio/mp3", "audio/mpeg", "audio/wav", "audio/x-ms-wma", "audio/ogg", "video/mp4", "video/avi"];
       $err_message = "Sorry, you can only upload audio or video files ";
       $file = $_FILES["fileToUpload"];
       $fileName = $_FILES["fileToUpload"]["name"];
       $file_path = $target_dir.$fileName;
       $oldFilePath = $_POST['uploadedFile'];

       $f->uploadFile($target_dir, $file, $fileTypes, $err_message );

       unlink($oldFilePath);

      // $file_type = $f->getFileType($file);
       $typeID = $f->getTypeID($file);

       if(isset($_POST['comment']) || $_POST['comment']!=""){
         $comment = $_POST['comment'];
         echo "<br>Comment: ".$comment."<br>";
       } else {
         $comment = "";
         echo "<br>Empty comment<br>";
       }


       if(isset($_FILES['imageToUpload']) && $_FILES['imageToUpload']['error'] == 0){

        // set images variables to pass to method uploadFile
        $target_image_dir = "uploads/";
        $imageTypes = ["image/jpeg", "image/png", "image/gif"];
        $err_message_image = "Sorry, you can only upload image files ";
        $image = $_FILES["imageToUpload"];
        $imageName = $_FILES["imageToUpload"]["name"];
        $image_path = $target_image_dir.$imageName;

           print $image_path;


      $f->uploadFile($target_image_dir, $image, $imageTypes, $err_message_image);
     $f->getImageID($imageName, $image_path);


           } else {

             $image = [];
             $imageName = "";
             $image_path = "";

             if($_FILES['imageToUpload']['error'] == 1){
                echo 'File exceed the maximum size of 200 Mb';
             }
           }

          if(isset($_POST['category']) || $_POST['category'] != ""){
                $category = $_POST['category'];
                echo "<br>Category: ".$category;
              } else {
                $category = "";
                echo "<br>Empty category";
              }

       } else {

         $file = [];
         $file_path = "";

         if(isset($_POST['comment']) || $_POST['comment']!=""){
           $comment = $_POST['comment'];
           echo "<br>Comment: ".$comment."<br>";
         } else {
           $comment = "";
           echo "<br>Empty comment<br>";
         }


         if(isset($_FILES['imageToUpload']) && $_FILES['imageToUpload']['error'] == 0){

          // set images variables to pass to method uploadFile
          $target_image_dir = "uploads/";
          $imageTypes = ["image/jpeg", "image/png", "image/gif"];
          $err_message_image = "Sorry, you can only upload image files ";
          $image = $_FILES["imageToUpload"];
          $imageName = $_FILES["imageToUpload"]["name"];
          $image_path = $target_image_dir.$imageName;

             print $image_path;


        $f->uploadFile($target_image_dir, $image, $imageTypes, $err_message_image);
       $f->getImageID($imageName, $image_path);


             } else {

               $image = [];
               $imageName = "";
               $image_path = "";

               if($_FILES['imageToUpload']['error'] == 1){
                  echo 'File exceed the maximum size of 200 Mb';
               }
             }

            if(isset($_POST['category']) || $_POST['category'] != ""){
                  $category = $_POST['category'];
                  echo "<br>Category: ".$category;
                } else {
                  $category = "";
                  echo "<br>Empty category";
                }


       }

 $f->updateFileSpecsInDB($file, $fileID, $file_name, $file_path, $image_path, $comment, $category, $imageName);

} else {
  if(in_array($_POST['file_name'], $titlesMinusThisTitle)){
    print "Error - File title already exists, please choose anothe title";
  } elseif(!isset($_POST['file_name']) || $_POST['file_name'] == ""){
    echo "Error - Field 'File name' cannot be empty<br>";
  }
}

} else {

   echo "An error has occured";
}


 ?>

</section>

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
