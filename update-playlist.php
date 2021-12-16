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


?>

<header>

   <div id="header">
    <h1>Media Organiser</h1>

    <ul class="main-nav-bar">
      <li><form id="search" action="search.php" method="post"><input id="search" type="text" value="" name="word-to-search" placeholder="Search..."></input><button type="submit" value="search" name="search"><i class="fa fa-search"></i></button></form></li>
      <li><a href="mediafiles.php">Media Files</a> |</li>
      <li class="current"><a href="playlists.php">Playlists</a></li>
    </ul>
  </div>

</header>

<section id="uploaded">
<?php

$f = new App\Files;
$p = new App\Playlists;

$playlists = $p->selectPlaylists();

if(isset($_POST["update-playlist"])){

  foreach ($playlists as $playlist){
    $playlistsTitles[] = $playlist['playlist'];
    }

    $thisTitle = [];
    $titlesMinusThisTitle = [];

    $thisTitle = [$_POST['playlist-title']];

    $titlesMinusThisTitle = array_diff($playlistsTitles, $thisTitle);

      if(isset($_POST['files']) && $_POST['files'] != []){
          $filesID = $_POST['files'];

          $playlistID = $_POST['playlistID'];

        //  print_r($filesID);

          if((isset($_POST['playlist-title']) && $_POST['playlist-title'] != "") && !in_array($_POST['playlist-title'], $titlesMinusThisTitle)){

            $playlistTitle = $_POST['playlist-title'];


          print "PlaylistID is: ".$playlistTitle;
        } else {
          print "Please enter a title for the playlist";
        }


          $p->updatePlaylist($playlistID, $playlistTitle, $filesID);

    ?>

      <section id="add-files-to-playlist">

    <?php

          print "Files added";

          ?>

             <h2>Edit files in playlist</h2>
             <form id="add-files" method="post" action="update-playlist.php">
               <label for="playlist">Playlist </label><input type="text" name="playlist-title" value="<?php print $playlistTitle; ?>"></input><br><br>
               <input type="hidden" id="playlistID" name="playlistID" value="<?php print $playlistID; ?>"><br><br>

                  <label for="files">Media files </label><select id="files" name='files[]' class="files" multiple='multiple'>

          <?php

                 $files = $f->selectMediaFiles();

                 foreach($files as $file){
                   if(in_array($file['fileID'], $filesID)){
                     print "<option name='".$file['title']."' value='".$file['fileID']."' selected>".$file['title']."</option>";
                   } else {
                     print "<option name='".$file['title']."' value='".$file['fileID']."' >".$file['title']."</option>";
                   }
               }

          ?>

                   </select><br><br>

                   <input type="submit" value="Update playlist" name="update-playlist"></input>
                 </form><br><br>

          </section>

          <?php

      } else {
  if(in_array($_POST['playlist-title'], $titlesMinusThisTitle)){
    print "Error - File title already exists, please choose anothe title";
  } elseif(!isset($_POST['playlist-title']) || $_POST['playlist-title'] == ""){
    echo "Error - Field 'File name' cannot be empty<br>";
  }
}

} else {

   echo "An error has occured";
}


 ?>

</section>

<section id="playlists">

  <h2>Playlists</h2>

  <?php
print "<table id='added-playlists'><tr><th>Playlists</th><th>Actions</th></tr>";
  foreach($playlists as $pl){
    print "
     <tr>
         <td><a href='playlist.php?playlist=".$pl['playlist']."&playlistID=".$pl['playlistID']."' >".$pl['playlist']."</a></td>
         <td><a href='edit-playlist.php?playlistID=".$pl['playlistID']."&playlist=".$pl['playlist']."'>Edit </a>  | <a href='delete-playlist.php?playlistID=".$pl['playlistID']."&playlist=".$pl['playlist']."'>Delete</a></td>
     </tr>"
     ;

}
   //print_r($playlists);
print "</table>";

   ?>

</section>

</body>
<script src="js/jquery.multiselect.js"></script>
<script>
$('.files').multiselect({
    columns: 4,
    placeholder: 'Select file(s)'
});
</script>
<script type="text/javascript">
   function playMyAudio(file){
     document.getElementById(file).play();
   }
   function pauseMyAudio(file){
     document.getElementById(file).pause();
   }

</script>

</html>
