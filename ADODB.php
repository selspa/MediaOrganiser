<?php
include_once('adodb-php/adodb.inc.php');
$db = ADONewConnection('mysqli'); # eg. 'mysql' or 'oci8'
$db->Connect('host', 'username', 'password', 'database');

?>
