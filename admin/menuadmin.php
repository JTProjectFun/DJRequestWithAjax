<?php
include_once '../configuration.php';
include_once 'adminconfig.php';
include_once '../functions/functions.php';

// Don't want the menu to appear on these pages:
if ((curPageName()=="forgotpass.php") || (curPageName()=="resetpassword.php")) {
    return;
}

echo '<div class="container-fluid">';
echo '<nav class="navbar navbar-light">';
echo '<ul class="nav navbar-nav nav-pills">';
echo '<li><a href="user.php">Request Users</a></li>';
echo '<li><a href="admin.php">Events</a></li>';
echo '<li><a  href="password.php">Change Password</a></li>';
echo '</ul>';
echo '<ul class="pull-right"><a href="logout.php"  class="btn btn-lg btn-danger" role="button">Log Out <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a></ul>';
echo '</div>';
echo '</nav>';

?>

