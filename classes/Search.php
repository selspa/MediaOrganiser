<?php

namespace App;

require_once('ADODB.php');

// This class contains methods and objects to to search

class Search {

 // Searches files based on word typed in searh form
   public function searchFiles($search){

     global $db;

     print "<table id='uploaded-files'><tr><th>Files</th><th>Type</th><th>Actions</th></tr>";

     $sql = "SELECT F.fileID, F.title, F.filepath, F.typeID, F.comment, F.categoryID, F.imageID, T.typeID, T.type, I.imageID, I.imagePath, C.categoryID, C.category
             FROM MediaFiles as F, Types as T, Images as I, Categories as C
             WHERE F.typeID = T.typeID
             AND F.categoryID = C.categoryID
             AND F.imageID = I.imageID
             AND F.title LIKE ?";

     $result = $db->Execute($sql,[$db->addQ("%".$search."%")]);

     if($result->RecordCount() > 0){

       for ($i=0; $i<$result->RecordCount(); $i++) {
        $title = $result->fields['title'];
        $type = $result->fields['type'];
        $file_path = $result->fields['filepath'];
        $filepath = preg_replace('/\\\\/', '',$file_path);
        $filepath = preg_replace('/&/', '%26',$filepath);
        $fileID = $result->fields['fileID'];
        $comment = $result->fields['comment'];
        $imageID = $result->fields['imageID'];
        $imagePath = $result->fields['imagePath'];
        $category = $result->fields['category'];

        print '
         <tr>
             <td>'.$title.' <audio src="'.$filepath.'" id="'.$title.'"></audio><button onClick="playMyAudio(\''.$title.'\')"><i class="fa fa-play"></i></button><button onClick="pauseMyAudio(\''.$title.'\')"><i class="fa fa-pause"></i></button></td>
             <td>'.$type.'</td>
             <td><a href="edit-file.php?title='.$title.'&fileID='.$fileID.'&filepath='.$filepath.'&comment='.$comment.'&imagePath='.$imagePath.'&category='.$category.'">Edit </a>  | <a href="delete-file.php?title='.$title.'&fileID='.$fileID.'&filepath='.$filepath.'" onclick="return confirmAction()">Delete</a></td>
         </tr>'
         ;
        $result->MoveNext();
     }
     } else {
       print "<tr><td>No record found</td></tr>";
     }

    print "</table>";

   }

// Searches playlists based on word typed in searh form
   public function searchPlaylists($search){

       global $db;

        print "<table id='added-playlists'><tr><th>Playlists</th><th>Actions</th></tr>";

       $playlists = [];

       $sql = "SELECT playlistID, playlist FROM Playlists WHERE playlist LIKE ?";

       $result = $db->Execute($sql,[$db->addQ("%".$search."%")]);

       if($result->RecordCount() > 0){

           for ($i=0; $i<$result->RecordCount(); $i++) {
             $playlists[] = ['playlistID' => $result->fields['playlistID'], 'playlist' => $result->fields['playlist']];
             $result->MoveNext();
           }

     foreach($playlists as $playlist){
       //  print "<a href='playlist.php?playlist=".$playlist['playlist']."&playlistID=".$playlist['playlistID']."' >".$playlist['playlist']."</a><br>";

         print "
          <tr>
              <td><a href='playlist.php?playlist=".$playlist['playlist']."&playlistID=".$playlist['playlistID']."' >".$playlist['playlist']."</a></td>
              <td><a href='edit-playlist.php?playlistID=".$playlist['playlistID']."&playlist=".$playlist['playlist']."'>Edit </a>  | <a href='delete-playlist.php?playlistID=".$playlist['playlistID']."&playlist=".$playlist['playlist']."'>Delete</a></td>
          </tr>"
          ;
     }

   } else {
     print "<tr><td>No record found</td></tr>";
   }

   //$f->selectUploadedFiles();
     print "</table>";

     }




} // end of class Search
