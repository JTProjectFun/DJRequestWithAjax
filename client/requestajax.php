<?php
include_once '../configuration.php';
include_once 'adminconfig.php';
include_once '../functions/functions.php';
include_once('generatekey.php');

start_session();

$record=array();

$action = $_REQUEST['action'];

if (isset($_SESSION['eventid'])) {
$eventid = $_SESSION['eventid'];
}

if(!isset($_SESSION['client_id']) || $_SESSION['client_id'] == "") {
//     header('Location: index.php');
	return;
}

else {
     $userid = $_SESSION['client_id'];
     error_log("got client_id (userid):".$userid);
}

// Get categories
    $conn = mysqli_connect($host, $username, $password, $db);
    $sqlcats = "SELECT * from categories";
    $query = mysqli_query($conn, $sqlcats);
    $count  = mysqli_num_rows($query);
		if($count > 0) {
            while($fetch = mysqli_fetch_array($query)) {
			$categorylist[] = $fetch;
			}
		}
    mysqli_close($conn);

switch($action) {
    
        case "search":

 ?>

<div id='livesearchbox'> <?php
if(isset($_REQUEST['term'])){
    $conn = mysqli_connect($host, $username, $password, $db);
$SEARCHTERM=$_REQUEST['term']; 
// Check connection
if($link === false){
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
                ?>
                              
                        <?php
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
                    <div class="searchresult panel <?php if ($i%2 ==0) echo "even";?>">
                        <div class="row">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-8 text-center"><h4>
                                <?php echo $artist." - ".$title; ?></h4>
                            </div>
                            <div class="col-sm-2"></div>
                           <div class="col-sm-2"></div>
                            <div class="col-sm-8 text-center">
                                <?php echo $year; ?> - 
                                <?php echo $songlength; ?>
                            </div>
                            <div class="col-sm-2"></div><br/>
                            <div class="col-sm-12 text-center">
				<button class="btn btn-primary" data-toggle="collapse" href="#requestinfopanel<?php echo $trackid;?>" role="button" aria-controls="multiCollapseExample1"><i class="fa fa-ellipsis-v"></i></button>
                            </div>
                        </div>
		    	<div class="info panel collapse" id="requestinfopanel<?php echo $trackid;?>"><br/>
                            <div class="row">
                                <div class="col-md-12 text-center">Select Category:
                                     <select id="categorysel<?php echo $trackid; ?>">
                                        <?php foreach ($categorylist as $cat) {
                                        echo "<option value='".$cat['categoryid']."'>".$cat['categoryname']."</option>";
                                        }
                                        ?>
                                     </select>
                                </div>
                             </div><br/>
                             <div class="row">
                                <div class="col-md-12 text-center">Comment:<textarea id="comment<?php echo $trackid; ?>"></textarea></div>
                             </div></br>
                             <div class="row">
                                <div class="col text-center"><button id="addthis" name="addlivetrack" value="<?php echo $trackid. ";" .$eventid.";".$name?>">ADD THIS</button></div>
                             </div>
                        </div>
                    </div>

		    </div>
                 <?php           
                  $i++;
                }
                
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
getRequestStuff(1);
?>
<div id="addnewrequest">
    <form method="post" id="gridder_addform" role="form">				
        <input type="hidden" name="action" value="addnew" />
        <div class="addnewrequest" id="addnewreq">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input id="name" name="name" type="hidden" placeholder="" value="host" class="form-control gridder_addreq" maxlength="64">
                        <input id="eventid" name="eventid" type="hidden" placeholder="" value="<?php echo $eventid; ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label" for="artist">Song Artist:</label>
                        <input id="artist" name="artist" type="text" placeholder="" class="form-control gridder_addreq" maxlength="64">
                        <div class="error collapse" id="artisterror">This field is required.</div>
                        <div class="error collapse" id="artisterror_tl"><?php echo str_replace("%FIELD%", "artist", $fieldtoolongString); ?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label" for="title">Song Title:</label>
                        <input id="title" name="title" type="text" placeholder="" class="form-control gridder_addreq" maxlength="64">
                        <div class="error collapse" id="titleerror">This field is required.</div>
                        <div class="error collapse" id="titleerror_tl"><?php echo str_replace("%FIELD%", "title", $fieldtoolongString); ?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="control-label" for="message">Your Message (up to 140 characters):</label>
                        <textarea id="message" name="message" class="form-control" placeholder="" rows="4" maxlength="140"></textarea>
                        <div class="error collapse" id="messageerror_tl"><?php echo str_replace("%FIELD%", "message", $fieldtoolongString); ?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="control-label" for="cat_input">Category:</label>
                        <select class="form-control" id="cat_input" name="category" ><?php foreach ($categorylist as $cat){ echo '<option value="'.$cat['categoryid'].'">'.$cat['categoryname'].'</option>'; }
            ?></select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="newadd" id="submitbutton"><input type="submit" id="gridder_addrecord" value="Submit Request" class="btn btn-primary gridder_addrecord_button" title="Add" /></div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>

<?php
break;

	case "populaterequests":
    getRequestStuff(1);

        ?>
<div id="requests-placeholder">
</div>
<?php
// update badge count
$totalLong = gmdate('H', $totalLength) . "h ". gmdate("i", $totalLength). "m " . gmdate("s", $totalLength). "s";
			echo '<script type="text/javascript">' . PHP_EOL;
			echo '	$(\'#requests-badge\').text(\'' . $count . '\')' . PHP_EOL;
            echo '	$(\'#requests-length\').text(\'' . $totalLong . '\')' . PHP_EOL;
			echo '</script>' . PHP_EOL;
            ?>
<div class="">
        <div class="">

            <?php
            if($count <= 0) {
            ?>
            <div id="row">
                <div class="col-md-12">No requests found</div>
            </div>
            <?php } else {
            $i = 0;
            foreach($record as $records) {
            $i = $i + 1;
            if ($records['categoryname'] == "") { $records['categoryname']="NOT SET"; }
            ?>
                        
           <div class="row <?php if ($i%2 == 0) echo "odd"; else echo "even"; ?>">
           	<div class="col-sm-12 text-center"><h2><?php if ($records['name'] == "") echo "You"; else echo $records['name']; ?></h2></div>
           
                <div class="col-sm-12 text-center">
                    <div class="grid_content <?php if ($records['songid']) { echo 'sno'; } else echo 'editable'; ?>">
                        <span><?php echo $records['artist']; ?></span>
                        <input type="text" class="gridder_input" name="artist|<?php echo $records['requestid'];?> " value="<?php echo $records['artist'];?>" />
                    </div>
                </div>           
                <div class="col-sm-12 text-center">
                    <div class="grid_content <?php if ($records['songid']) { echo 'sno">'; } else echo 'editable">'; ?>
                       <span><?php echo $records['title'];?></span>
                        <?php echo '<input type="text" class="gridder_input" name="title|'.$records['requestid'].'" value="'.$records['title'].'" />'; ?>
                    </div>
                </div> 
                <div class="col-sm-12 text-center">
                   <div class="grid_content editcategory">
                        <span><?php echo $records['categoryname']; ?></span>
                        <select class="cat_input" value="<?php echo $records['categoryname']; ?>" name="<?php echo 'category|'.$records['requestid'].'">';

                        foreach ($categorylist as $cat){ 
                            echo '<option ';
                            if ($records['categoryid'] == $cat['categoryid']) { echo 'selected ' ;}
                            echo 'value="'.$cat['categoryid'].'">'.$cat['categoryname'].'</option>'; }
                        ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-12 text-center">
                    <div class="grid_content editable">
                        <span><?php if ($records['message']) echo $records['message']; else echo "No Message"; ?></span>
                        <input type="text" class="gridder_input" name="<?php echo "message|".$records['requestid']; ?>" value="<?php echo $records['message']; ?>" />
                    </div>
                </div>
                <div class="col-sm-12 text-center">
                    <a href="<?php echo $records['requestid']; ?>" class="gridder_delete">
                        <img src="../images/delete.png" alt="Delete" title="Delete" />
                    </a>
                </div>
            </div></div>
            <?php
                }
            }
            ?>
            </div>
</div>
        <?php
	break;
	    
    case "addnewfromsearch":
        $row="";
        $timedate = date("Y-m-d.H:i:s");
        $ipaddr = $_SERVER['REMOTE_ADDR'];
        // don't trust cookies
        $uniqueid = makeSafe($_COOKIE['requestuser']);
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
        
        // get songid
        $conn = mysqli_connect($host, $username, $password, $db);
        $category = isset($_POST['category']) ? $_POST['category'] : '0'; 

        $trackid = isset($_POST['trackid']) ? mysqli_real_escape_string($conn, strip_tags($_POST['trackid'])) : '';
        $comment = isset($_POST['comment']) ? mysqli_real_escape_string($conn, strip_tags($_POST['comment'])) : '';

        $result = mysqli_query($conn, "select artist,title, filepath, lengthsecs from `songs` where ID='".$trackid."';");
        $newresult = mysqli_fetch_assoc($result);
        
        $title = addslashes($newresult['title']);
        $artist = addslashes($newresult['artist']);
        $filepath = addslashes($newresult['filepath']);             
        $lengthsecs = $newresult['lengthsecs'];        
        // check songid not already in request for this eventid
        $result = mysqli_query($conn, "select songid FROM requests WHERE eventid=".$eventid." AND songid=".$trackid." AND visible='1'");
        $exists = mysqli_num_rows($result);
        if ($exists > 0) {
            $response['status'] = 'alreadyrequested';
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
        
        $qstring = "INSERT INTO requests (lengthsecs, category, timedate,eventid, name, artist, title, message, ipaddr, uniqueid, songid, visible, filepath)";
        $qstring = $qstring.' VALUES ("'.$lengthsecs.'", "'.$category.'","'.$timedate.'","'.$eventid.'","'.$name.'","'.$artist.'","'.$title.'","'.$comment.'","'.$ipaddr.'","'.$uniqueid.'","'.$trackid.'","1","'.$filepath.'")';
        $result = mysqli_query($conn, $qstring);
        if (mysqli_error($conn)) {
            $error=mysqli_error($conn);
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
    
	case "addnew":
                $conn = mysqli_connect($host,$username,$password,$db);
                $timedate = date("Y-m-d.H:i:s");
                $ipaddr = $_SERVER['REMOTE_ADDR'];
                $key = isset($_POST['key']) ? mysqli_real_escape_string($conn, $_POST['key']) : '';
		$name 		= isset($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : '';
		$artist         = isset($_POST['artist']) ? mysqli_real_escape_string($conn, $_POST['artist']) : '';
		$title 		= isset($_POST['title']) ? mysqli_real_escape_string($conn, $_POST['title']) : '';
		$message 	= isset($_POST['message']) ? mysqli_real_escape_string($conn, $_POST['message']) : '';
		$category 	= isset($_POST['category']) ? mysqli_real_escape_string($conn, $_POST['category']) : '';
        $eventID 	= isset($_POST['eventid']) ? mysqli_real_escape_string($conn, $_POST['eventid']) : '';
		mysqli_query($conn, "INSERT INTO `requests` (visble, eventid, timedate, userid, artist, title, message, ipaddr, uniqueid, category) VALUES ('1','$eventID', '$timedate', '$userid', '$artist', '$title', '$message', '$ipaddr','$user','$category')");
        if (mysqli_error($conn)) {
                   
            $error=mysqli_error($conn);
            error_log("SQL ERROR: ".$error);
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
                $conn = mysqli_connect($host,$username,$password,$db);
		$value 	= $_POST['value'];
		$crypto = $_POST['crypto'];
		$explode = explode('|', $crypto);
		$columnName = $explode[0];
		$rowId = $explode[1];
		$query = mysqli_query($conn, "UPDATE `requests` SET `$columnName` = '$value' WHERE requestid = '$rowId' ");
		if (mysqli_error($conn)) {
                    $error=mysqli_error($conn);
                    $response['status'] = 'sqlerror'. $error;
                    header('Content-type: application/json');
                    echo json_encode($response);
                    mysqli_close($conn);
                    break;
                }


                $response['status'] = 'success';
                header('Content-type: application/json');
                echo json_encode($response);
                mysqli_close($conn);

	break;

    case "toggle":
                $conn = mysqli_connect($host,$username,$password,$db);
		$value 	= $_POST['value'];
		$crypto = $_POST['crypto'];
		$explode = explode('|', $crypto);
		$columnName = $explode[0];
		$rowId = $explode[1];
                if ($value == "on") { $value = 1; } else { $value = 0; }
		$query = mysqli_query($conn, "UPDATE `requests` SET `$columnName` = '$value' WHERE id = '$rowId' ");
                mysqli_close($conn);

    break;

	case "delete":
                $conn = mysqli_connect($host,$username,$password,$db);
		$value 	= $_POST['value'];
		$query = mysqli_query($conn, "UPDATE `requests` set visible=0 WHERE requestid = '$value' ");
                mysqli_close($conn);
	break;
}
?>
