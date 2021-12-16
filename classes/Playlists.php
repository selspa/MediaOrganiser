<?php

namespace App;

require_once('ADODB.php');
//include 'vendor/autoload.php';

// This class contains methods and objects to manage playlists

class Playlists {

 // Insert a new playlist in Playlists
  public function insertNewPlaylistInDB($playlistTitle){

     global $db;


     $sql = "INSERT INTO Playlists (playlistID, playlist) VALUES (NULL, ?)";

     $db->Execute($sql,[$db->addQ($playlistTitle)]);

  }

// Select all the playlists from Playlists table
  public function selectPlaylists(){

    global $db;

    $playlists = [];

    $sql = "SELECT playlistID, playlist FROM Playlists";

    $result = $db->Execute($sql);

    if($result->RecordCount() > 0){

        for ($i=0; $i<$result->RecordCount(); $i++) {
          $playlists[] = ['playlistID' => $result->fields['playlistID'], 'playlist' => $result->fields['playlist']];
          $result->MoveNext();
        }

    }

   return $playlists;

  }

// Insert fileID and playlistID in PlaylistsFiles table to associate files to playlists
  public function addFilesToPlaylist($filesID = [], $playlistID){

    global $db;

    $sql = "SELECT fileID, playlistID FROM PlaylistsFiles WHERE playlistID = ?";

    $result = $db->Execute($sql,[$db->addQ($playlistID)]);


    if($result->RecordCount() > 0 && $filesID != []){

      $filesIDInDB = [];

      for ($i=0; $i<$result->RecordCount(); $i++) {
        $filesInDB[] = $result->fields['fileID'];
        $result->MoveNext();
      }

      $uncheckedFiles = array_diff($filesInDB, $filesID);

      //print_r($uncheckedFiles);

      foreach($filesID as $fileID){
        if(!in_array($fileID, $filesInDB)){

        $sql = "INSERT INTO PlaylistsFiles (id, fileID, playlistID, orderID) VALUES (NULL, ?, ?, NULL)";
        $db->Execute($sql,[$db->addQ($fileID), $db->addQ($playlistID)]);

      } elseif(!in_array($fileID, $uncheckedFiles)) {

        $sql = "DELETE FROM PlaylistsFiles WHERE playlistID = ? AND fileID = ?";

        foreach($uncheckedFiles as $id){
          $db->Execute($sql,[$db->addQ($playlistID), $db->addQ($id)]);
        }

      }
      }

    } else {
      print "Error - you need to select a file";
    }

  }

// Select records from MediaFiles, Playlists, PlaylistsFiles, Images and Types tables and generates an array of objects that can be used to display needed info
  public function selectFilesInPlaylist($playlistID, $playlist){

     global $db;

     $sql = "SELECT F.fileID, F.title, F.typeID, F.filepath, F.imageID, P.playlistID, P.playlist, PF.id, PF.fileID, PF.playlistID, I.imageID, I.imagePath, T.typeID, T.type
             FROM MediaFiles as F, Playlists as P, PlaylistsFiles as PF, Images as I, Types as T
             WHERE F.fileID = PF.fileID
             AND P.playlistID = ?
             AND P.playlist = ?
             AND P.playlistID = PF.playlistID
             AND F.imageID = I.imageID
             AND F.typeID = T.typeID";

    $result = $db->Execute($sql,[$db->addQ($playlistID), $db->addQ($playlist)]);

     if($result->RecordCount() > 0){

       $filesPlaylist = [];

       for ($i=0; $i<$result->RecordCount(); $i++) {
        $fileID = $result->fields['fileID'];
        $title = $result->fields['title'];
        $file_path = $result->fields['filepath'];
        $filepath = preg_replace('/\\\\/', '',$file_path);
        $filepath = preg_replace('/&/', '%26',$filepath);
        $playlistID = $result->fields['playlistID'];
        $playlist = $result->fields['playlist'];
        $imagePath = $result->fields['imagePath'];
        $type = $result->fields['type'];


      $filesPlaylist[] = [
                        'fileID' => $fileID,
                        'title' => $title,
                        'filepath' => $filepath,
                        'playlistID' => $playlistID,
                        'playlist' => $playlist,
                        'imagePath' => $imagePath,
                        'type' => $type
                       ];

        $result->MoveNext();

      }

  }

    return $filesPlaylist;

  }

// Delete playlist from Playlists and PlaylistsFiles
  public function deletePlaylist($playlistID, $playlist){

    global $db;

    $sql = "DELETE FROM Playlists WHERE playlistID = ? AND playlist = ?";

    $db->Execute($sql,[$db->addQ($playlistID), $db->addQ($playlist)]);

    $sql = "DELETE FROM PlaylistsFiles WHERE playlistID = ?";

    $db->Execute($sql,[$db->addQ($playlistID)]);

  }

// Update Playlist table and call method addFilesToPlaylist to update files in playlist
  public function updatePlaylist($playlistID, $playlist, $filesID = []){

     global $db;

     $sql = "UPDATE Playlists SET playlist = ? WHERE playlistID = ?";
     $db->Execute($sql,[$db->addQ($playlist), $db->addQ($playlistID)]);

     if($filesID != []){
     $this->addFilesToPlaylist($filesID, $playlistID);
   } else {
     print "You need to select a file";
   }
  }


} // end of class Playlists
