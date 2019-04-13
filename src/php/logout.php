<?php   
session_start();
session_destroy();
header("location:/pawtopia");
$_SESSION = array();
exit();
?>