<?php
include_once '../functions/functions.php';
start_session();

if(session_destroy()) // Destroying All Sessions
{
if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        if ($name !='requestuser') { // keep requestuser only
                                       setcookie($name, '', time()-1000);
                                       setcookie($name, '', time()-1000, '/');
                                   }
    }
}
header("Location: index.php"); // Redirecting To Home Page
}
?>
