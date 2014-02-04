<?php
include_once 'db_session_inc.php';
error_reporting(E_ALL);
$_SESSION['name'] = 'My name is Kazi Ahsan.';
$ses_id = session_id();
echo $ses_id; 
//session_destroy();
?>
