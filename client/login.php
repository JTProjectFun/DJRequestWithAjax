<?php
include_once '../configuration.php';
include_once '../functions/functions.php';
start_session();
$error=''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
if (empty($_POST['email']) || empty($_POST['password'])) {
$error = "Email address or Password is invalid";
}
else
{
    // Define $email and $password
    $email=$_POST['email'];
    $pass=$_POST['password'];
    $conn = mysqli_connect($host, $username, $password, $db);

    // To protect MySQL injection for Security purpose
    $email = stripslashes($email);
    $pass = stripslashes($pass);
    $email = mysqli_real_escape_string($conn, $email);
    $pass = mysqli_real_escape_string($conn, $pass);
    $query = mysqli_query($conn, "SELECT realname, requestuserid, createdTime, password from requestusers WHERE email='$email'");
    $rows = mysqli_num_rows($query);

    if ($rows == 1) {
                         $query = mysqli_query($conn, "SELECT realname, requestuserid, createdTime, password FROM requestusers WHERE email='$email'");
                         $result = mysqli_fetch_assoc($query);
                         
                         $realname = $result['realname'];
                         $userid = $result['requestuserid'];
                         $timedate = $result['createdTime'];
                         $gotpass = $result['password'];
                         $salt =  strrev(date('U', strtotime($timedate)));
                         $hashedPass = sha1($salt.$pass);
                         if ($gotpass == $hashedPass) {
                            setcookie("clientrealname", $realname);
                            $_SESSION['client_id']=$userid; // Initializing Session
                            header("location: admin.php"); // Redirecting To Other Page
                         } 
			 else { 
                                $error = "Whoops. Email address or Password is invalid";
			}

                    } else {
                                $error = "Whoops. Email address or Password is invalid";
                           }
        mysqli_close($conn); // Closing Connection
    }
}
?>
