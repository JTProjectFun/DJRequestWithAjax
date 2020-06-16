<?php
include_once '../configuration.php';
include_once '../functions/functions.php';
start_session();
$error=''; // Variable To Store Error Message

// If user comes in via a URL obtained from somewhere, e.g. the QR code generator, set their session as 'logged in'
//if (isset($_GET["eventkey"])) {
	// only letters, numbers and underscore allowed in keys, strip everything else
//    $key = makeSafe($_GET['eventkey']);
//    $_POST['submit'] = true;
//    $_POST['eventkey'] = $key;
//}


if (isset($_POST['submit'])) {
    if (!isset($_POST['requestername'])) {
        $error = "You must enter your name";
    }
    if (!isset($_POST['eventkey'])) {
        $error = $error . " Key Code is not valid";
	} else {
		$conn = mysqli_connect($host, $username, $password, $db);
		// only aletters, numbers and underscore allowed in keys, strip everything else
		$key=preg_replace('/[^\w]/', '', $_POST['eventkey']);
        $name = preg_replace("/[^A-Za-z0-9 ]/", "", $_POST['requestername']);
		$date = date("d-m-Y");
		// To protect MySQL injection for Security purpose
		$key = stripslashes($key);
		$date = stripslashes($date);
		$key = mysqli_real_escape_string($conn, $key);
		$date = mysqli_real_escape_string($conn, $date);
                $key = strtolower($key);
		$result = mysqli_query($conn, "SELECT date, willexpire, id FROM events WHERE thekey='$key'");
		$rows = mysqli_num_rows($result);
		if ($rows == 1) {
			$row = mysqli_fetch_row($result);
			$data = $row[0];
			$date = strtotime($data);
			$willexpire = $row[1];
			$thekeyid = $row[2];
			$cur_date = date("U");
			//if (($cur_date - (3600 * (24 + $over_hours)) < $date) || ($willexpire == "0")) {
                $_SESSION['name'] = $name;
				$_SESSION['eventkey'] = $key; 
				$_SESSION['eventkeyid'] = $thekeyid; 
				$_SESSION['timeout'] = time();
				setcookie("eventkey", $key, time() + (60 * 60 * 8), "/"); // 60 * 60 * 8 seconds = 8 hours
                setcookie("name", $name, time() + (60 * 60 * 8), "/"); // 60 * 60 * 8 seconds = 8 hours
				mysqli_close($conn); // Closing Connection
				header("location: requests.php");
			//} else {
			//	$error = "Willexpire: ". $willexpire. " " . $data . " Key Code '" . $key . "' is not valid " .$date . ", sorry. Try again with a valid code.";
			//	mysqli_close($conn); // Closing Connection
			//	header("location: index.php");
			//}
		} else {
			$error =  "Sorry, '" . $key . "' is not a valid event key.";
		}
		// mysqli_close($conn); // Closing Connection
	}
}
?>
