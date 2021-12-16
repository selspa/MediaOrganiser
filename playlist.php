<!DOCTYPE html>
<html>
<head>
  <title>Media Organiser</title>
  <link rel="stylesheet" href="css/style.css">

  <link rel="stylesheet" href="css/jquery.multiselect.css">
  <link rel="stylesheet" href="AblePlayer/build/ableplayer.min.css" type="text/css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="AblePlayer/thirdparty/js.cookie.js"></script>
  <script src="AblePlayer/build/ableplayer.min.js"></script>
</head>

<body>

<?php

error_reporting(E_ALL);
ini_set('display_errors','On');

// include file to start database connection
include_once('ADODB.php');
//include class Files
require_once('classes/Files.php');
//include class Files
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

<?php

if(isset($_GET['playlistID']) && isset($_GET['playlist'])){

   $playlistID = $_GET['playlistID'];
   $playlist = $_GET['playlist'];


   $filesPlaylist = $p->selectFilesInPlaylist($playlistID, $playlist);

  // print_r($filesPlaylist);

   $filesID = [];
   $filepaths = [];
   $filesTitles = [];
   $imagePaths = [];


   foreach($filesPlaylist as $filePlaylist){
     $filesID[] = $filePlaylist['fileID'];
     $filepaths[] = $filePlaylist['filepath'];
     $filesTitles[] = $filePlaylist['title'];
     $imagePaths[] = $filePlaylist['imagePath'];

   }

?>
<section id="player">

  <h2>Playlist: <?php print $playlist; ?></h2>



 <audio id="audio1" width="480" preload="auto" data-able-player data-skin="2020" loop>


   </audio>

<ul class="able-playlist" data-player="audio1" data-embedded>

  <?php

      foreach($filesPlaylist as $fileP){

  ?>

         <li data-poster="<?php print $fileP['imagePath']; ?>">
             <span class="able-source"
               data-type="<?php print $fileP['type']; ?>"
               data-src="<?php print $fileP['filepath']; ?>"></span>
             <span class="able-source"
               data-type="<?php print $fileP['type']; ?>"
               data-src="<?php print $fileP['filepath']; ?>"></span>
             <button><?php print $fileP['title']; ?></button>
           </li>
<?php
}
?>

</ul>

  <h2>Playlists</h2>

    <ul class="playlists-list">
      <?php

      foreach($playlists as $p){
        if($p['playlist'] == $playlist){
          print "<li class='current'><a href='playlist.php?playlist=".$p['playlist']."&playlistID=".$p['playlistID']."' >".$p['playlist']."</a></li>";
        } else {
          print "<li><a href='playlist.php?playlist=".$p['playlist']."&playlistID=".$p['playlistID']."' >".$p['playlist']."</a></li>";
        }
    }

       ?>

    </ul>

</section>

<section id="add-files-to-playlist">

<?php

    ?>


       <h2>Add files to playlist</h2>
       <form id="add-files" method="post" action="add-files-to-playlist.php">
         <label for="playlist">Playlist </label><select id="playlist" name='playlist' class="playlist">
            <option name="select" value="">Please select</option>

    <?php


              foreach($playlists as $pl){
                if($pl['playlist'] == $playlist){
                  print "<option name='".$pl['playlist']."' value='".$pl['playlistID']."' selected>".$pl['playlist']."</option>";
                } else {
                  print "<option name='".$pl['playlist']."' value='".$pl['playlistID']."' >".$pl['playlist']."</option>";
                }
            }



    ?>
            </select><br><br>

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

             <input type="submit" value="Add file(s)" name="add-files"></input>
            </form>

    </section>


<?php


} else {


 ?>


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

      <label for="files">Media files </label><select id="files" name='files' class="files" multiple='multiple'>

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

  foreach($playlists as $playlist){
      print "<a href='playlist.php?playlist=".$playlist['playlist']."&playlistID=".$playlist['playlistID']."' >".$playlist['playlist']."</a><br>";
}

   ?>

</section>

<?php

}

 ?>


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
