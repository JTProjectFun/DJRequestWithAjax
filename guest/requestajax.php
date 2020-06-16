<?php
include_once '../configuration.php';
include_once '../functions/functions.php';
include_once '../customtexts.php';
start_session();

$result=""; // Initialise variables which may have been previously used & would contain data already
$fetch=""; // Initialise variables which may have been previously used & would contain data already
$record=""; // Initialise variables which may have been previously used & would contain data already
$eventid=0;

$action = $_REQUEST['action'];
// don't trust cookies
$key = makeSafe($_COOKIE['eventkey']);
$uniqueid = makeSafe($_COOKIE['guestuser']);
if (isset($_COOKIE['name'])) {
$name = preg_replace("/[^A-Za-z0-9 ]/", "", $_COOKIE['name']);

}
$error = '';
// get eventid from key
                $conn = mysqli_connect($host, $username, $password, $db);
$result = mysqli_query($conn, "SELECT * from events WHERE thekey='".$key."';");
$newresult = $result->fetch_assoc();
$eventid = $newresult['id'];
$showClientRequests = $newresult['showClientRequests'];
                mysqli_close($conn);

switch($action) {
    
    case "search":

 ?>

<div id='livesearchbox'> <?php
if(isset($_REQUEST['term'])){
    $conn = mysqli_connect($host, $username, $password, $db);
 $SEARCHTERM = $_REQUEST['term'];
// Check connection
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
    // Prepare a select statement
    $sql = "SELECT * FROM songs WHERE title LIKE ? OR artist LIKE ? ORDER BY playcount DESC LIMIT 40";
    
    if($stmt = mysqli_prepare($conn, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $param_term, $param_term);
        
        // Set parameters
        $param_term = "%" . $SEARCHTERM . "%";
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            
            // Check number of rows in the result set
            if(mysqli_num_rows($result) > 0){
                // Fetch result rows as an associative array
                $i = 0;
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    $artist=stripslashes($row['artist']);
                    $title=stripslashes($row['title']);
                    $year=stripslashes($row['year']);
                    $playcount=stripslashes($row['playcount']);
                    $lengthsecs=$row['lengthsecs'];  // Need to convert seconds into mins & secs
                    $songlength=gmdate("i:s",$lengthsecs);
                    $filepath=stripslashes($row['filepath']);
                    $trackid=stripslashes($row['ID']);
?>
    <div class="row <?php if ($i%2 ==0) {echo 'even';} ?> ">
        <div class="col-sm-12">
            <h4><?php echo $artist.' - '.$title;?></h4>
        </div>
        <div class="col-sm-6"><?php echo $year;?></div>
        <div class="col-sm-6 text-right"><?php echo $songlength;?></div>
        <div class="col-sm-12 text-right">
            <button class="btn btn-primary" data-toggle="collapse" href="#requestinfopanel<?php echo $trackid;?>" role="button" aria-controls="multiCollapseExample1"><i class="fa fa-ellipsis-v"></i></button>
        </div>
<div class="col-sm-12">
	<div class="info panel collapse" id="requestinfopanel<?php echo $trackid;?>">
                             <div class="row">
                                <div class="col">Comment</div>
                                <div class="col"><input id="comment<?php echo $trackid;?>"></div>
                             </div>
                             <div class="row">
                                <div class="col"><button id="addthis" name="addlivetrack" value="<?php echo $trackid. ";" .$eventid.";".$name?>">ADD THIS</button></div>
                             </div>
                        </div>
</div>
</div>




<?php
                  $i++;
                }
                echo "</table>";
            } else{
                echo "<p>No matches found</p>";
            }
        } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
    }
     
    // Close statement
  mysqli_stmt_close($stmt);
}
 
// close connection
mysqli_close($conn);
?>

</div>
<?php
break;
    
	case "load":
    getRequestStuff(0);

?>
				<form method="post" id="gridder_addform" role="form">				
					<input type="hidden" name="action" value="addnew" />
					<div class="addnewrequest" id="addnewreq">
                        <div class="row">
							<div class="col-md-12">
                                <div class="form-group">
									<label class="control-label" for="name">Your Name:</label>
									<input id="name" name="name" type="text" placeholder="<?php echo $name;?>" value="<?php echo $name;?>" class="form-control gridder_addreq" maxlength="64" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend"><span class="input-group-text">Artist:</span></div>
                                        <input type="text" class="form-control gridder_addreq" id="artist" name="artist" placeholder="" autocomplete="off" maxlength="64"/>
                                        <div class="error collapse" id="artisterror">This field is required.</div>
                                        <div class="error collapse" id="artisterror_tl"><?php echo str_replace("%FIELD%", "artist", $fieldtoolongString); ?></div>
                                    </div>
                                </div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend"><span class="input-group-text">Title:</span></div>
										<input id="title" name="title" type="text" placeholder="" class="form-control gridder_addreq" maxlength="64">
                                        <div class="error collapse" id="titleerror">This field is required.</div>
                                        <div class="error collapse" id="titleerror_tl"><?php echo str_replace("%FIELD%", "title", $fieldtoolongString); ?></div>
                                    </div>
                                </div>
                            </div>
						</div>
						<div class="row">
							<div class="col-md-12">
                            <div><span class="input-group-text">Your Message (up to 140 characters):</span></div>
								<div class="form-group">
                                    <div class="input-group mb-4">
                                		<textarea id="message" name="message" class="form-control" placeholder="" rows="4" maxlength="140"></textarea>
                                    </div>
                                    <div class="error collapse" id="messageerror_tl"><?php echo str_replace("%FIELD%", "message", $fieldtoolongString); ?></div>
                                </div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<div class="newadd" id="submitbutton"><input type="submit" id="gridder_addrecord" value="Submit Request" class="btn btn-primary gridder_addrecord_button" title="Add" /></div>
								</div>
							</div>
						</div>
					</div>
				</form>

            <?php
	break;

	case "populateRequests":
	getRequestStuff(0);
			$requestContent = '<div id="requests-placeholder">';
			if($count <= 0) {
				$requestContent .= '<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> There have been no requests made yet.</div>' . PHP_EOL;
			}
            if ($showRequests == 0) {
                if ($count == 1) {
                    $requestContent .= '<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> There has been one request made so far.</div>' . PHP_EOL;
                }
                if ($count > 1) {
					$requestContent .=  '<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> There have been ' .$count. ' requests made so far.</div>' . PHP_EOL;
				}
			} elseif($count > 0) {
				$i = 0;
				foreach($record as $records) {
					$i = $i + 1;
					$requestContent .= '<div class="card card-block';
                                        if ($i%2 == 0) $requestContent .=" even";
                                        $requestContent .='"><div class="card-body">' . PHP_EOL;
                                        if ($records['name'] == "") { $records['name'] = "HOST";}
					$requestContent .= $records['name'] . ': &lsquo;' . $records['title'] . '&rsquo; <em>by</em> ' . $records['artist'] . PHP_EOL;
					if ((strlen($records['message']) > 0) && ($showMessages == "1")) {
						$requestContent .= '<br/>Message: ' . $records['message'] . PHP_EOL;
					}
					
					$requestContent .= '</div></div>' . PHP_EOL;
				}
			}
			$requestContent .= '</div>';
			// update badge count
			echo '<script type="text/javascript">' . PHP_EOL;
			echo '	$(\'#requests-badge\').text(\'' . $count . '\')' . PHP_EOL;
			echo '</script>' . PHP_EOL;
			// output requests
			echo $requestContent;
	break;

    case "addnewfromsearch":
    //$trackid = $_POST['trackid'];
    //$eventid = $_POST['eventid'];
    error_log("ADD NEW FROM SEARCH");
                $row="";
                $timedate = date("Y-m-d.H:i:s");
                error_log($timedate);
                $ipaddr = $_SERVER['REMOTE_ADDR'];
				// don't trust cookies
				$uniqueid = makeSafe($_COOKIE['guestuser']);
                $conn = mysqli_connect($host, $username, $password, $db);
                $bannedquery = mysqli_query($conn, "SELECT banned FROM guestusers WHERE uniqueid='$uniqueid'");
                $banned = mysqli_fetch_row($bannedquery);
                if ( $banned[0] == "1" ) {
                        $response['status'] = 'banned';
                        header('Content-type: application/json');
                        echo json_encode($response);
                        mysqli_close($conn);
                        break;
                }

                $numquery = mysqli_query($conn, "SELECT numRequests FROM guestusers WHERE uniqueid='$uniqueid'");
                $userRequest = mysqli_fetch_row($numquery);
                
                $totalquery = mysqli_query($conn, "SELECT COUNT(*) FROM requests WHERE eventid='$eventid' AND visible='1'");
                $totalRequest = mysqli_fetch_row($totalquery);
                $maxquery = mysqli_query($conn, "SELECT maxUserRequests, maxRequests FROM events WHERE id='$eventid'");
                $maxRequests = mysqli_fetch_assoc($maxquery);
                $totalRequests = $totalRequest[0];
                $maxUserRequests = $maxRequests['maxUserRequests'];
                $maxRequests = $maxRequests['maxRequests'];
                error_log("max user requests: ".$maxUserRequests);
                
                $userRequests = $userRequest[0];

                if (($userRequests >= $maxUserRequests) && ($maxUserRequests != 0)) {
                        $response['status'] = 'toomanyuser';
                        header('Content-type: application/json');
                        echo json_encode($response);
                        mysqli_close($conn);
                        break;
                }

                if (($totalRequests >= $maxRequests) && ($maxRequests != 0)) {
                        $response['status'] = 'toomany';
                        header('Content-type: application/json');
                        echo json_encode($response);
                        mysqli_close($conn);
                        break;
                }

                $result = mysqli_query($conn, "SELECT timedate FROM requests WHERE uniqueid='".$uniqueid."' ORDER BY timedate DESC LIMIT 1");
                $rows = mysqli_num_rows($result);
                if ($rows == 1) {
                    $row = mysqli_fetch_row($result);
                    $lasttime = date($row[0]);

                    if (strtotime($lasttime) > (strtotime($timedate) - $flood_period)) {
                        $response['status'] = 'flood';
                        header('Content-type: application/json');
                        echo json_encode($response);
                        mysqli_close($conn);
                        break;
                    }
        }
                mysqli_close($conn);
                $result="";
                // don't trust cookies
				$key = makeSafe($_COOKIE['eventkey']);
                if (strlen($key) < 3){   // If eventkey is blank, kick the user out & make em log back in.
					header('Location:logout.php');
					break;
                }
                
                // get songid
                $conn = mysqli_connect($host, $username, $password, $db);
				$name = isset($_POST['name']) ? $_POST['name'] : 'default'; // ? mysqli_real_escape_string($conn, strip_tags($_POST['name'])) : 'default';

                $trackid = isset($_POST['trackid']) ? mysqli_real_escape_string($conn, strip_tags($_POST['trackid'])) : '';
$comment = isset($_POST['message']) ? mysqli_real_escape_string($conn, strip_tags($_POST['message'])) : '';
                $result = mysqli_query($conn, "select artist,title, filepath, lengthsecs from `songs` where ID='".$trackid."';");
                $newresult = mysqli_fetch_assoc($result);
                
                $title = addslashes($newresult['title']);
                $artist = addslashes($newresult['artist']);
                $filepath = addslashes($newresult['filepath']);
                $lengthsecs = $newresult['lengthsecs'];
                error_log("filepath=".$filepath);
                               
                // check songid not already in request for this eventid
                $result = mysqli_query($conn, "select songid FROM requests WHERE eventid=".$eventid." AND songid=".$trackid);
                $exists = mysqli_num_rows($result);
                if ($exists > 0) {
                    $response['status'] = 'alreadyrequested';
                    header('Content-type: application/json');
                    echo json_encode($response);
                    break;                 
                }    
                
                if (strlen($name) < 1) {
                    $response['status'] = 'nametooshort';
                    header('Content-type: application/json');
                    echo json_encode($response);
                    break;
                }

                if (strlen($artist) < 1) {
                    $response['status'] = 'artisttooshort';
                    header('Content-type: application/json');
                    echo json_encode($response);
                    break;
                }

                if (strlen($title) < 1) {
                    $response['status'] = 'titletooshort';
                    header('Content-type: application/json');
                    echo json_encode($response);
                    break;
                }
                
                $qstring = "INSERT INTO requests (lengthsecs, timedate,eventid, name, artist, title, message, ipaddr, uniqueid, songid, visible, filepath, category)";
                $qstring = $qstring.' VALUES ("'.$lengthsecs.'","'.$timedate.'","'.$eventid.'","'.$name.'","'.$artist.'","'.$title.'","'.$comment.'","'.$ipaddr.'","'.$uniqueid.'","'.$trackid.'","1","'.$filepath.'","4")';
                $result = mysqli_query($conn, $qstring);
                if (mysqli_error($conn)) {
                    $error=mysqli_error($conn);
                    error_log("SQL Error: ".$error);
                    $response['status'] = 'sqlerror'. $error;
                    header('Content-type: application/json');
                    echo json_encode($response);
                    break;
                }

                $result = mysqli_query($conn, "UPDATE guestusers set numRequests=numRequests+1 WHERE uniqueid='$uniqueid'");
                if (mysqli_error($conn)) {
                    $error=mysqli_error($conn);
                    error_log("SQL Error: ".$error);
                    $response['status'] = 'sqlerror'. $error;
                    header('Content-type: application/json');
                    echo json_encode($response);
                    break;
                }

                mysqli_close($conn);

                $response['status'] = 'success';
                header('Content-type: application/json');
                echo json_encode($response);
	break;
    
	case "addnew":
                $row="";
                $timedate = date("Y-m-d.H:i:s");
                $ipaddr = $_SERVER['REMOTE_ADDR'];
				// don't trust cookies
				$uniqueid = makeSafe($_COOKIE['guestuser']);
                $conn = mysqli_connect($host, $username, $password, $db);
                $bannedquery = mysqli_query($conn, "SELECT banned FROM guestusers WHERE uniqueid='$uniqueid'");
                $banned = mysqli_fetch_row($bannedquery);
                if ( $banned[0] == "1" ) {
                        $response['status'] = 'banned';
                        header('Content-type: application/json');
                        echo json_encode($response);
                        mysqli_close($conn);
                        break;
                }

                $numquery = mysqli_query($conn, "SELECT numRequests FROM guestusers WHERE uniqueid='$uniqueid'");
                $maxquery = mysqli_query($conn, "SELECT maxUserRequests, maxRequests FROM events WHERE id='$eventid'");
                $totalquery = mysqli_query($conn, "SELECT COUNT(*) FROM requests WHERE eventid='$eventid' AND visible='1'");
                $maxRequests = mysqli_fetch_row($maxquery);
                $totalRequest = mysqli_fetch_row($totalquery);
                $totalRequests = $totalRequest[0];
                $maxUserRequests = $maxRequests[0];
                $maxRequests = $maxRequests[1];
                $userRequest = mysqli_fetch_row($numquery);
                $userRequests = $userRequest[0];

                if (($userRequests > $maxUserRequests) && ($maxUserRequests != 0)) {
                        $response['status'] = 'toomanyuser';
                        header('Content-type: application/json');
                        echo json_encode($response);
                        mysqli_close($conn);
                        break;
                }

                if (($totalRequests > $maxRequests) && ($maxRequests != 0)) {
                        $response['status'] = 'toomany';
                        header('Content-type: application/json');
                        echo json_encode($response);
                        mysqli_close($conn);
                        break;
                }

                $result = mysqli_query($conn, "SELECT timedate FROM requests WHERE uniqueid='".$uniqueid."' ORDER BY timedate DESC LIMIT 1");
                $rows = mysqli_num_rows($result);
                if ($rows == 1) {
                    $row = mysqli_fetch_row($result);
                    $lasttime = date($row[0]);

                    if (strtotime($lasttime) > (strtotime($timedate) - $flood_period)) {
                        $response['status'] = 'flood';
                        header('Content-type: application/json');
                        echo json_encode($response);
                        mysqli_close($conn);
                        break;
                    }
        }
                mysqli_close($conn);
                $result="";
                // don't trust cookies
				$key = makeSafe($_COOKIE['eventkey']);
                if (strlen($key) < 3){   // If eventkey is blank, kick the user out & make em log back in.
					header('Location:logout.php');
					break;
                }
                $conn = mysqli_connect($host, $username, $password, $db);
				$name = isset($_POST['name']) ? mysqli_real_escape_string($conn, strip_tags($_POST['name'])) : '';
                
				$artist = isset($_POST['artist']) ? mysqli_real_escape_string($conn, strip_tags($_POST['artist'])) : '';
				$title = isset($_POST['title']) ? mysqli_real_escape_string($conn, strip_tags($_POST['title'])) : '';
				$message = isset($_POST['message']) ? mysqli_real_escape_string($conn, strip_tags($_POST['message'])) : '';
                $name = strip_tags($_POST['name']);
                $artist = strip_tags($_POST['artist']);
                $title = strip_tags($_POST['title']);
                $message = strip_tags($_POST['message']);

                if (strlen($name) < 1) {
                    $response['status'] = 'nametooshort';
                    header('Content-type: application/json');
                    echo json_encode($response);
                    break;
                }

                if (strlen($artist) < 1) {
                    $response['status'] = 'artisttooshort';
                    header('Content-type: application/json');
                    echo json_encode($response);
                    break;
                }

                if (strlen($title) < 1) {
                    $response['status'] = 'titletooshort';
                    header('Content-type: application/json');
                    echo json_encode($response);
                    break;
                }

		$qs = "INSERT INTO requests (timedate, eventid, name, artist, title, message, ipaddr, uniqueid, visible, category)";
		$qs = $qs ." VALUES ('".$timedate."', '".$eventid."', '".$name."', '".$artist."', '".$title."', '".$message."', '".$ipaddr."', '".$uniqueid."', '1','4')";
        error_log("QUERY STRING:".$qs);
                $result = mysqli_query($conn, $qs);
                if (mysqli_error($conn)) {
                    
                    $error=mysqli_error($conn);
                    error_log("SQL ERROR: ".$error);
                    $response['status'] = 'sqlerror'. $error;
                    header('Content-type: application/json');
                    echo json_encode($response);
                    break;
                }

                $result = mysqli_query($conn, "UPDATE guestusers set numRequests=numRequests+1 WHERE uniqueid='$uniqueid'");
                if (mysqli_error($conn)) {
                    $error=mysqli_error($conn);
                    $response['status'] = 'sqlerror'. $error;
                    header('Content-type: application/json');
                    echo json_encode($response);
                    break;
                }

                mysqli_close($conn);

                $response['status'] = 'success';
                header('Content-type: application/json');
                echo json_encode($response);
	break;

	case "update":
	break;

	case "delete":
	break;
}
?>
