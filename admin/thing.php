<?php

$timedate =  "1970-01-01 01:00:00";
$pass = "password";

$salt =  strrev(date('U', strtotime($timedate)));
$hashedPass = sha1($salt.$pass);

echo "salt: " . $salt . "\n";
echo "your hashed password of 'password' is " .$hashedPass . "\n";

?>
