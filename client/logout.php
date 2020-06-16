<?php
include_once '../functions/functions.php';
start_session();
if(session_destroy()) // Destroying All Sessions
{
header("Location: index.php"); // Redirecting To Home Page
}
?>
