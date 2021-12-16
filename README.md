# MediaOrganiser #

With this Media Organiser you can upload audio files, create playlists, add the files that you uploaded to them and edit or delete playlists and files.


## Folders ##

**AblePlayer/**
It's a media player that I found online, it's very powerfull. If you want to know more about it, in this folder ther is a README.md where they explain how to use these files.


**adodb-php/**
It's a PHP library to execute SQL


**classes/**
It contains the classes Files, Playlists and Search which contains methods and objects to manage files, playlists and search


**css/**
Contains some style, very basic one, for the Media Organiser. It also contains the style for jquery.multiselect.css that I used to display a multiselect list of files with checkboxes


**js/**
Contains jquery.multiselect.js that I used to display a multiselect list of files with checkboxes

**uploads/**
Simply needed as in the code is used as file path to upload files



## Files ##

**ADODB.php**
Includes ADODB library and starts the connection with the database

**MediaOrganiser.sql**
SQL file that can be run in phpMyAdmin (or any other administration tool for MySQL) to create the tables nedeed for this code to run and save information

**index.php**
Very minimal with search and links to Media Files and Playlists

**mediafiles.php**
It shows a form where you can ulpoad an audio file and a list of the files already uploaded. The form to upload files it's going to action **upload-file.php**. 
If you click on a link to edit a file, this is going to take you to **edit-file.php** which display another form that is going to action **update-file.php**. 
If you click on a link to delete a file, this is going to load **delete-file.php** and redirect to **mediafiles.php**

**playlists.php**
It shows a form where you can add a new playlist, another form wher you can add files to an existing playlist and a list of playlists. 
The form to add a new playlist is going to action **add-playlist.php**. The form to add files to playlist is going to action **add-files-to-playlist.php**
If you click on a link with the playlist title, this is going to take you to **playlist.php** and it will display a media player (Able Player) with a list of playlists
If you click on a link to edit a playlist, this is going to take you to **edit-playlist.php** which display another form that is going to action **update-playlist.php**. 
If you click on a link to delete a playlist, this is going to load **delete-playlist.php** and redirect to **playlists.php**

**search.php**
It is actionated when you search something using the search field on the nav-bar

