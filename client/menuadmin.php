<?php
include_once '../configuration.php';
include_once 'adminconfig.php';
include_once '../functions/functions.php';

// Don't want the menu to appear on these pages:
if ((curPageName()=="forgotpass.php") || (curPageName()=="resetpassword.php")) {
    return;
}

echo '<nav class="navbar navbar-static-top">';
echo '<div class="container-fluid">';
echo '<ul class="nav navbar-nav nav-pills">';
echo '<li><a href="admin.php">Your Events</a></li>';
echo '</ul>';

echo '</div>';
echo '</nav>';

?>

