<?php
include_once '../functions/functions.php';
// Reminder: DON'T TRUST COOKIES!

// cookie("client_user", $email);
// cookie("client_id", $userid);
// cookie("clientrealname", $realname);
// SESSION['client_user']=$email;
$email = makeSafe($_COOKIE['client_user']);
$userid = makeSafe($_COOKIE['client_id']);

$realname = makeSafe($_COOKIE['clientrealname']);
?>
