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

$f = new App\Files;
$p = new App\Playlists;

$playlists = $p->selectPlaylists();

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

<section id="new-playlist">

   <h2>Add new playlist</h2>
   <form id="new-playlist-form" method="post" action="add-playlist.php">
      <label for="new-playlist-title">Playlist title </label><br><input id="new-playlist-title" type="text" value="" name="new-playlist-title"></input><br><br>
      <input type="submit" value="Add playlist" name="add-playlist"></input>
   </form>

</section>

<section id="add-files-to-playlist">

   <h2>Add files to playlist</h2>
   <form id="add-files" method="post" action="add-files-to-playlist.php">
     <label for="playlist">Playlist </label><select id="playlist" name='playlist' class="playlist">
        <option name="select" value="">Please select</option>

<?php

          foreach($playlists as $playlist){
              print "<option name='".$playlist['playlist']."' value='".$playlist['playlistID']."' >".$playlist['playlist']."</option>";
        }

?>

      </select><br><br>

      <label for="files">Media files </label><select id="files" name='files[]' class="files" multiple="multiple">

 <?php

           $files = $f->selectMediaFiles();

           foreach($files as $file){
               print "<option name='".$file['title']."' value='".$file['fileID']."' >".$file['title']."</option>";
         }


 ?>

       </select><br><br>

      <input type="submit" value="Add file(s)" name="add-files"></input>
   </form>

</section>

<section id="playlists">

  <h2>Playlists</h2>

  <?php

    print "<table id='added-playlists'><tr><th>Playlists</th><th>Actions</th></tr>";

  foreach($playlists as $playlist){

      print "
       <tr>
           <td><a href='playlist.php?playlist=".$playlist['playlist']."&playlistID=".$playlist['playlistID']."' >".$playlist['playlist']."</a></td>
           <td><a href='edit-playlist.php?playlistID=".$playlist['playlistID']."&playlist=".$playlist['playlist']."'>Edit </a>  | <a href='delete-playlist.php?playlistID=".$playlist['playlistID']."&playlist=".$playlist['playlist']."'>Delete</a></td>
       </tr>"
       ;
}

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
