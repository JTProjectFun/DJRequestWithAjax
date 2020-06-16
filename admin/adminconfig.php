<?php
include_once '../functions/functions.php';
// Reminder: DON'T TRUST COOKIES!
$user = makeSafe($_COOKIE['adminuser']);
$userid = makeSafe($_COOKIE['adminuserid']);
$userlevel = makeSafe($_COOKIE['adminlevel']);
$adminrealname = makeSafe($_COOKIE['adminrealname']);
?>
