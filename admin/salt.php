<?php
$timedate="1970-01-01 01:00:00";
//$timedate="2015-04-19 23:44:05";
echo "<pre>".$timedate."</pre>";
echo "<pre>". strtotime($timedate) . "</pre>";
$salt = strrev(date('U', strtotime($timedate)));

$password="password";
$newpassword = sha1($salt.$password);

echo "<pre>salt: " . $salt . "</pre><pre>password: " . $newpassword . "</pre>";

?>
