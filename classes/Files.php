<?php

namespace App;

require_once('ADODB.php');
//include 'vendor/autoload.php';

// This class contains methods and objects to manage the uploads, editing and deleting media files

class Files {

  public $file_type;
  public $categoryID;
  public $imageID;
  public $typeID;
  public $uploadedFile;

    // Upload files in FTP from a remote device to server
    public function uploadFile($target_dir, $file, $fileTypes = [], $err_message){

      $target_file = $target_dir . basename($file["name"]);

      $fileType = $this->getFileType($file);

       if(in_array($fileType, $fileTypes)){
         if(file_exists($target_file)){
                   echo $file["name"] . ' already exists';
           } else {
             if (move_uploaded_file($file["tmp_name"], $target_file)) {
             echo "The file ". htmlspecialchars( basename( $file["name"])). " has been uploaded. File type is ".$fileType;
             } else {
               echo "Sorry, there was an error uploading your file.";
             }
           }
       } else {
         echo $err_message.$fileType;
       }

    }

   // Get the file type from the uploaded file
    public function getFileType($file){

         $this->file_type = $file["type"];

         return $this->file_type;

    }

   // Get the typeID from Types table and if the type is not in the table add it to it
    public function getTypeID($file){

      global $db;

      $type = $this->getFileType($file);

      $sql = "SELECT typeID, type FROM Types WHERE type = ?; ";

      $result = $db->Execute($sql,[$db->addQ($type)]);

        if ($result->Recordcount() > 0) {
            $this->typeID = $result->fields["typeID"];
        } else {
          $sql = "INSERT INTO Types (typeID, type) VALUES (NULL, ?)";
          $db->Execute($sql,[$db->addQ($type)]);
          $sql2 = "SELECT typeID, type FROM Types WHERE type = ?; ";
          $result = $db->Execute($sql2,[$db->addQ($type)]);

            if ($result->Recordcount() > 0) {
                $this->typeID = $result->fields["typeID"];
            }
        }

        return $this->typeID;

    }


 // Get the categoryID from Categories table based on the category title
    public function getCategoryID($category){

      global $db;

      $sql = "SELECT categoryID, category FROM Categories WHERE category = ? ";

      $result = $db->Execute($sql,[$db->addQ($category)]);

        if ($result->Recordcount() > 0) {
            $this->categoryID = $result->fields["categoryID"];
        }

        return $this->categoryID;

    }

// Get the imageID from table Images table based on image title and add into Images if not already in there
    public function getImageID($imageName, $image_path){

      global $db;

      $sql = "SELECT imageID, image FROM Images WHERE image = ?; ";

      $result = $db->Execute($sql,[$db->addQ($imageName)]);

        if ($result->Recordcount() > 0) {
            $this->imageID = $result->fields["imageID"];
        } else {
          $sql = "INSERT INTO Images (imageID, image, imagePath) VALUES (NULL, ?, ?)";
          $db->Execute($sql,[$db->addQ($imageName), $db->addQ($image_path)]);
          $sql2 = "SELECT imageID, image FROM Images WHERE image = ?; ";
          $result = $db->Execute($sql2,[$db->addQ($imageName)]);

            if ($result->Recordcount() > 0) {
                $this->imageID = $result->fields["imageID"];
            }
        }

        return $this->imageID;

    }

// insert record in table MediaFiles
    public function insertFileSpecsInDB($file, $file_name, $file_path, $image_path, $comment, $category, $imageName){

       global $db;

           if($category != ""){
           $categoryID = $this->getCategoryID($category);
         } else {
           $categoryID = 0;
         }
           if($imageName != ""){
           $imageID = $this->getImageID($imageName, $image_path);
         } else {
           $imageID = 0;
         }

       $typeID = $this->getTypeID($file);

       $sql = "INSERT INTO MediaFiles (fileID, title, filepath, typeID, comment, categoryID, imageID) VALUES (NULL, ?, ?, ?, ?, ?, ?)";

       $db->Execute($sql,[$db->addQ($file_name), $db->addQ($file_path), $db->addQ($typeID), $db->addQ($comment), $db->addQ($categoryID), $db->addQ($imageID)]);

    }

// select the records in MediaFiles table
    public function selectMediaFiles(){

      global $db;

      $mediaFiles = [];

      $sql = "SELECT fileID, title FROM MediaFiles";

      $result = $db->Execute($sql);

      if($result->RecordCount() > 0){

          for ($i=0; $i<$result->RecordCount(); $i++) {
            $mediaFiles[] = ['fileID' => $result->fields['fileID'], 'title' => $result->fields['title']];
            $result->MoveNext();
          }

      }

     return $mediaFiles;

    }

// select all the uploaded files and show them in a table
    public function selectUploadedFiles(){

       global $db;

       print "<table id='uploaded-files'><tr><th>Files</th><th>Type</th><th>Actions</th></tr>";

       $sql = "SELECT F.fileID, F.title, F.filepath, F.typeID, F.comment, F.categoryID, F.imageID, T.typeID, T.type, I.imageID, I.imagePath, C.categoryID, C.category
               FROM MediaFiles as F, Types as T, Images as I, Categories as C
               WHERE F.typeID = T.typeID
               AND F.categoryID = C.categoryID
               AND F.imageID = I.imageID";

       $result = $db->Execute($sql);

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
               <td><a href="edit-file.php?title='.$title.'&fileID='.$fileID.'&filepath='.$filepath.'&comment='.$comment.'&imagePath='.$imagePath.'&category='.$category.'">Edit </a>  | <a href="delete-file.php?title='.$title.'&fileID='.$fileID.'&filepath='.$filepath.'" class="example2">Delete</a></td>
           </tr>'
           ;
          $result->MoveNext();
       }
       }

       print '</table>';

    }

// Delete file from MediaFiles and PlaylistsFiles
    public function deleteFile($title, $fileID, $filepath){

      global $db;

      $sql = "DELETE FROM MediaFiles WHERE title = ? AND fileID = ?";

      $db->Execute($sql,[$db->addQ($title), $db->addQ($fileID)]);

      $sql2 ="SELECT fileID FROM PlaylistsFiles WHERE fileID = ?";

      $result = $db->Execute($sql2,[$db->addQ($fileID)]);

          if($result->RecordCount() > 0){

          $sql2 = "DELETE FROM PlaylistsFiles WHERE fileID = ?";

          $db->Execute($sql2,[$db->addQ($fileID)]);

        }

      $file = $filepath = preg_replace('/\\\\/', '',$filepath);
      unlink($file);

    }

// Update records in MediaFiles table
    public function updateFileSpecsInDB($file = [], $fileID, $file_name, $file_path, $image_path, $comment, $category, $imageName){

       global $db;

           if($category != ""){
           $categoryID = $this->getCategoryID($category);
         } else {
           $categoryID = 0;
         }

           if($imageName != ""){
           $imageID = $this->getImageID($imageName, $image_path);
         } else {
           $imageID = 0;
         }

       if($file != []){
       $typeID = $this->getTypeID($file);
       $sql = "UPDATE MediaFiles SET title = ?, filepath = ?, typeID = ?, comment = ?, categoryID = ?, imageID = ? WHERE fileID = ?";
       $db->Execute($sql,[$db->addQ($file_name), $db->addQ($file_path), $db->addQ($typeID), $db->addQ($comment), $db->addQ($categoryID), $db->addQ($imageID), $db->addQ($fileID)]);
     } else {
       $sql = "UPDATE MediaFiles SET title = ?, comment = ?, categoryID = ?, imageID = ? WHERE fileID = ?";
       $db->Execute($sql,[$db->addQ($file_name), $db->addQ($comment), $db->addQ($categoryID), $db->addQ($imageID), $db->addQ($fileID)]);
     }


    }


} //end of class Files
