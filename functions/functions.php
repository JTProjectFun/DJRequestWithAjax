<?php

// Function for creating a random string

function random_string()
{
    $character_set_array = array();
    $character_set_array[] = array('count' => 4, 'characters' => 'abcdefghijklmnopqrstuvwxyz');
    $character_set_array[] = array('count' => 2, 'characters' => '0123456789');
    $temp_array = array();
    foreach ($character_set_array as $character_set) {
        for ($i = 0; $i < $character_set['count']; $i++) {
            $temp_array[] = $character_set['characters'][rand(0, strlen($character_set['characters']) - 1)];
        }
    }
    shuffle($temp_array);
    $key = implode('', $temp_array);
    return $key;
}

// Function to check a key
function checkkey($key)
{
    global $host, $username, $password, $db;
    $conn = mysqli_connect($host, $username, $password, $db);
    $query = mysqli_query($conn,"select * from requestkeys where thekey='$key'");
    $rows = mysqli_num_rows($query);
    mysqli_close($conn); // Closing Connection
    if ($rows == 1 ) {
   return 1;
                     } else {
   return 0;
    }
}

// Function for encryption
function encrypt($data) {
	return base64_encode(base64_encode(base64_encode(strrev($data))));
}

// Function for decryption
function decrypt($data) {
	return strrev(base64_decode(base64_decode(base64_decode($data))));
}

function db_connect() {
  // Define connection as a static variable, to avoid connecting more than once 
    global $connection;

$init = parse_ini_file(dirname(__DIR__).'/config.php');
    if(!isset($connection)) {
        $connection = mysqli_connect($init['host'],$init['username'],$init['password'],$init['db']);
    }

    // If connection was not successful, handle the error
    if($connection === false) {
        // Handle error - notify administrator, log to a file, show an error screen, etc.
        return mysqli_connect_error(); 
    }
    return $connection;
}

function makeSafe($TheText) {
    // Probably the tip of the iceberg, but it's a start
    $TheText = preg_replace('/[^\w]/', '', $TheText);
    return $TheText;
}

function db_query($query) {

  // Connect to the database
    $connection = db_connect();
    // Query the database
    $result = mysqli_query($connection,$query);
    // Close the database connection
//    mysqli_close($connection);
    return $result;

}

function db_select($query) {
    $rows = array();
    // Query the database
    $sel_result = db_query($query);

    if($sel_result === false) {
        return false;
    }

    while ($row = mysqli_fetch_assoc($sel_result)) {
        $rows[] = $row;
    }
    return $rows;

}

function start_session() {
  if(version_compare(phpversion(), "5.4.0") != -1){
      if (session_status() == PHP_SESSION_NONE) {
          session_start();
      }
  } else {
            if(session_id() == '') {
                session_start();
            }
    }
}

function curPageName() {
    return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}

function getRequestStuff($theclient){
    global $eventid, $record, $host, $username, $password, $db, $count, $showMessages, $showRequests, $showClientRequests, $maxUserRequests, $totalLength;
$record = array();
$total = array();
$fetch = array();
$result = array();

    $conn = mysqli_connect($host, $username, $password, $db);
    $mess = mysqli_query($conn, "SELECT showMessages,showRequests,showClientRequests, maxUserRequests FROM events WHERE id='". $eventid."'");
    $showMes = mysqli_fetch_row($mess);
    $showMessages = $showMes[0];
    $showRequests = $showMes[1];
    $maxUserRequests = $showMes[3];
    $showClientRequests = $showMes[2];
    
    $sql = "SELECT * FROM requests LEFT JOIN categories on categories.categoryid=requests.category WHERE eventid='".$eventid."' AND visible=1";
    if ($showClientRequests != 1 && $theclient != 1) { error_log("ignoring client requests"); $sql = $sql." AND name!=''"; }
    $sql = $sql." ORDER BY timedate DESC";
    $result = mysqli_query($conn, $sql);
    $count = $result->num_rows;

    if($count > 0) {
                       while($fetch = mysqli_fetch_assoc($result)) {
                                                                       $record[] = $fetch;
                                                                   }
                        }
    $sql = "SELECT SUM(lengthsecs) FROM requests WHERE eventid='".$eventid."' AND visible='1'";
    $result = mysqli_query($conn, $sql);
    $total = mysqli_fetch_row($result);
    $totalLength = $total[0];
    mysqli_close($conn);
}

?>
