<?php
include_once '../configuration.php';
include_once '../functions/functions.php';
start_session();

if(!isset($_SESSION['login_user']) || $_SESSION['login_user'] == "") {
     header('Location: index.php');
}
else {
     $id = $_SESSION['login_user'];
}

$key = 0;
$user = 0;

if (isset($_GET['eventid'])) {
  $id = makeSafe($_GET['eventid']);
}
$_SESSION['requestuser'] = 0;
if (isset($_GET['requestuser'])) {
    $requestuser = makeSafe($_GET['requestuser']);
    $_SESSION['requestuser'] = $requestuser;
}

$_SESSION['id'] = $id;

$rq = mysqli_connect($host, $username, $password, $db);
$query_string = "SELECT date from events where id='".$id."'";
$query = mysqli_query($rq, $query_string);
$result = mysqli_fetch_row($query);
$event_title=$result[0];

$filename=stripslashes($event_title).".m3u";
$file = "#EXTM3U\r\n";

$query_string = "SELECT requests.*,events.* FROM requests LEFT JOIN events ON requests.eventid=events.id WHERE events.id='".$id."' AND filepath is not NULL";
$query = mysqli_query($rq, $query_string);
$count  = mysqli_num_rows($query);
if($count > 0) {
        while($fetch = mysqli_fetch_array($query)) {
                $records[] = $fetch;
        }
}
$i = 0;

foreach($records as $record) {
    $i = $i + 1;
    $temp_time = explode('.', $record['lengthsecs']);
    $file .= "#EXITINF:".$temp_time[0].",".$record['artist']. " - " . $record['title'] . "\r\n";
    $file .= $record['filepath']. "\r\n";
}

header('Content-Type: audio/x-mpegurl; charset=utf-8');
header('Content-Disposition: attachment; filename="'.$filename.'"');
echo $file;

?>
