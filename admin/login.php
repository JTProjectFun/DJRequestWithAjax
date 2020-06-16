<?php
include_once '../configuration.php';
include_once '../functions/functions.php';
start_session();
$error=''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
if (empty($_POST['username']) || empty($_POST['password'])) {
$error = "Username or Password is invalid";
}
else
{
    // Define $username and $password
    $user=$_POST['username'];
    $pass=$_POST['password'];
    $conn = mysqli_connect($host, $username, $password, $db);

    // To protect MySQL injection for Security purpose
    $user = stripslashes($user);
    $pass = stripslashes($pass);
    $user = mysqli_real_escape_string($conn, $user);
    $pass = mysqli_real_escape_string($conn, $pass);
    // Establishing Connection with Server by passing server_name, user_id and password as a parameter
    // SQL query to fetch information of registered users and finds user match.
    //    $query = mysqli_query($conn, "select * from systemUser where password='$pass' AND username='$user' AND enabled='1'");
    //    $rows = mysqli_num_rows($query);

    $query = mysqli_query($conn, "SELECT userlevel, realname, id, timedate, password from systemUser WHERE username='$user' AND enabled='1'");
    $rows = mysqli_num_rows($query);

    if ($rows == 1) {
                         $query = mysqli_query($conn, "SELECT userlevel, realname, id, timedate, password FROM systemUser WHERE username='$user'");
                         $result = mysqli_fetch_row($query);
                         $userlevel = $result[0];
                         $realname = $result[1];
                         $userid = $result[2];
                         $timedate = $result[3];
                         $gotpass = $result[4];
                         $salt =  strrev(date('U', strtotime($timedate)));
                         $hashedPass = sha1($salt.$pass);
                         if ($gotpass == $hashedPass) {
                                                        setcookie("adminuser", $user);
                                                        setcookie("adminuserid", $userid);
                                                        setcookie("adminlevel", $userlevel);
                                                        setcookie("adminrealname", $realname);
                                                        $_SESSION['login_user']=$user; // Initializing Session
                                                        header("location: admin.php"); // Redirecting To Other Page
                         } 
			 else { 
                                $error = "Whoops. Username or Password is invalid";
			}

                    } else {
                                $error = "Whoops. Username or Password is invalid";
                           }
        mysqli_close($conn); // Closing Connection
    }
}
?>
