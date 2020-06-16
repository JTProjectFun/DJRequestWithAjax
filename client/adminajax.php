<?php
include_once '../configuration.php';
include_once '../functions/functions.php';
include_once ('generatekey.php');
include_once 'adminconfig.php';
start_session();
$action = $_REQUEST['action'];
$record=array();
$requestuser=array();
$id = $_SESSION['client_id'];
error_log("session client_id=".$id);

if (isset($_COOKIE['client_id'])) {
    // If I told you once, I told you a million times. DON'T TRUST COOKIES!
    $client_id = makeSafe($_COOKIE['client_id']);
error_log("found cookie client_id=".$client_id);

}

// Database Schema Note
//+-----------------+-------------+------+-----+---------+----------------+
//| Field           | Type        | Null | Key | Default | Extra          |
//+-----------------+-------------+------+-----+---------+----------------+
//| id              | int(11)     | NO   | PRI | NULL    | auto_increment |  ID of the event. autogenerated
//| timedate        | varchar(32) | YES  |     | NULL    |                |  Timestamp event inserted into database
//| thekey          | varchar(16) | NO   | UNI | NULL    |                |  Unique event 'key' used in login URL
//| date            | varchar(32) | YES  |     | NULL    |                |  Event date
//| showRequests    | int(1)      | YES  |     | NULL    |                |
//| willexpire      | int(1)      | YES  |     | NULL    |                |
//| maxRequests     | int(11)     | YES  |     | 0       |                |
//| maxUserRequests | int(11)     | YES  |     | 0       |                |
//| userid          | int(11)     | YES  |     | NULL    |                |
//| showMessages    | int(1)      | YES  |     | 1       |                |
//| hours           | int(11)     | YES  |     | NULL    |                |
//| showClientRequests | int(1)   | NO   |     | 1       |                |
//+-----------------+-------------+------+-----+---------+----------------+

switch($action) {
	case "load":
                $tempkey = random_string();
                // Generate a new key. If it's already in the database, keep trying.
                while (checkkey($tempkey)) {
                    $tempkey = random_string();
                }
                $tempkey = strtolower($tempkey);

                $conn = mysqli_connect($host, $username, $password, $db);

              	$query = mysqli_query($conn, "select events.*, requestusers.* from events LEFT JOIN requestusers on requestusers.requestuserid=events.userid WHERE requestuserid='$id' ORDER BY date DESC;");
		$count  = mysqli_num_rows($query);
		if($count > 0) {
			while($fetch = mysqli_fetch_array($query)) {
				$record[] = $fetch;
			}
		}
		$query = mysqli_query($conn, "SELECT requestuserid, realname from requestusers");
		$usercount  = mysqli_num_rows($query);
		if($usercount > 0) {
			while($fetch = mysqli_fetch_array($query)) {
				$requestuser[] = $fetch;
			}
		}
		?>

        <table class="table table bordered table-striped table-condensed as_gridder">
            <tr class="grid_header">
                <th>Title</th>
                <th>Active Date</th>
                <th>Key</th>
                <th>Show Requests</th>
                <th>Show Messages</th>
                <th>Max Requests per user</th>
                <th>Manage</th>
                <th>Print</th>
            </tr>

            <?php
            if($count <= 0) {
            ?>
            <tr id="norecords">
                <td colspan="7" align="center">No records found</td>
            </tr>
            <?php } else {
            $i = 0;
            foreach($record as $records) {
            $i = $i + 1;
            ?>
            <tr>
                <td class="date"><div class="grid_content sno"><span><?php echo $records['eventtitle']; ?></span></div></td>
                <td class="date"><div class="grid_content sno"><span><?php echo $records['date']; ?></span></div></td>
                <td class="key"><div class="grid_content sno"><span class="thekey"><?php echo $records['thekey']; ?></span></div></td>
                <td class="showreq">
                    <div class="gridder_content">
                        <span></span>
                        <input type="checkbox" class="toggle" name="
                        <?php echo "showRequests|".$records['id']; ?>"
                        <?php if ($records['showRequests'] == 1) { echo ' checked '; } ?> />
                    </div>
                </td>
                <td class="showreq">
                    <div class="gridder_content">
                        <span></span>
                        <input type="checkbox" class="toggle" name="
                        <?php echo "showMessages|".$records['id']; ?>"
                        <?php if ($records['showMessages'] == 1) { echo ' checked '; } ?> />
                    </div>
                </td>

                <td class="maxrequests">
                    <div class="grid_content editable">
                        <span><?php echo $records['maxUserRequests']; ?></span>
                        <input type="text" class="gridder_input" name="<?php echo "maxUserRequests|".$records['id']; ?>" value="<?php echo $records['maxUserRequests']; ?>" />
                    </div>
                </td>

                <td class="manage">
                    <div class="gridder_content">
                        <a href="requests.php?eventkey=<?php echo $records['id'] ;?>">manage</a>
                    </div>
                </td>
                <td class="print">
                    <a href="../print.php?eventkey=<?php echo $records['thekey'] ; ?>">Print</a>
                </td>
            </tr>
            <?php
                }
            }
            ?>
            </table>
        <?php
	break;

	case "addnew":
                $conn = mysqli_connect($host,$username,$password,$db);
                $timedate = date("Y-m-d.H:i:s");
		$thekey 		= isset($_POST['thekey']) ? mysqli_real_escape_string($conn, $_POST['thekey']) : '';
		$thekey 		= strtolower($thekey);
		$date 		= isset($_POST['date']) ? mysqli_real_escape_string($conn, $_POST['date']) : '';
		$showRequests = isset($_POST['showRequests']) ? mysqli_real_escape_string($conn, $_POST['showRequests']) : '';
		$willexpire		= isset($_POST['willexpire']) ? mysqli_real_escape_string($conn, $_POST['willexpire']) : '';
		$maxuserrequests		= isset($_POST['maxUserRequests']) ? mysqli_real_escape_string($conn, $_POST['maxUserRequests']) : '';
		$maxrequests		= isset($_POST['maxRequests']) ? mysqli_real_escape_string($conn, $_POST['maxRequests']) : '';
                if ($showRequests == "on") { $showRequests = "1"; } else { $showRequests = "0"; }
                if ($willexpire == "on") { $willexpire = "1"; } else { $willexpire = "0"; }
                $useridq = mysqli_query($conn, "SELECT id FROM `systemUser` WHERE username='$id'");
                $userids = mysqli_fetch_row($useridq);
                $userid = $userids[0];
		mysqli_query($conn, "INSERT INTO `events` (timedate, thekey, date, showRequests, willexpire,userid,maxUserRequests,maxRequests) VALUES ('$timedate', '$thekey', '$date', '$showRequests', '$willexpire', '$userid','$maxuserrequests','$maxrequests')");
                if (mysqli_error($conn)) {
                    $error=mysqli_error($conn);
                    $response['status'] = 'sqlerror'. $error;
                    header('Content-type: application/json');
                    echo json_encode($response);
                    break;
                }
                $response['status'] = 'sqlerror'. $error;
                header('Content-type: application/json');
                echo json_encode($response);
                mysqli_close($conn);
	break;

	case "update":
                $conn = mysqli_connect($host,$username,$password,$db);
		$value 	= $_POST['value'];
		$crypto = $_POST['crypto'];
		$explode = explode('|', $crypto);
		$columnName = $explode[0];
		$rowId = $explode[1];

		$query = mysqli_query($conn, "UPDATE `events` SET `$columnName` = '$value' WHERE id = '$rowId' ");
                if (mysqli_error($conn)) {
                    $error=mysqli_error($conn);
                    $response['status'] = 'sqlerror'. $error;
                    header('Content-type: application/json');
                    echo json_encode($response);
                    break;
                }
                $response['status'] = 'success';
                header('Content-type: application/json');
                echo json_encode($response);
                mysqli_close($conn);
	break;

	case "delete":
                $conn = mysqli_connect($host,$username,$password,$db);
		$value 	= $_POST['value'];
               // $query = mysqli_query($conn, "SELECT thekey FROM `events` WHERE id = '$value' limit 1");
                $data = mysqli_fetch_row($query);
                $delkey = $data[0];
		//$query = mysqli_query($conn, "DELETE FROM `events` WHERE id = '$value' ");
		//$query = mysqli_query($conn, "DELETE FROM `requests` WHERE thekey = '$delkey' ");
		//$query = mysqli_query($conn, "DELETE FROM `users` WHERE thekey = '$delkey' ");
                if (mysqli_error($conn)) {
                    $error=mysqli_error($conn);
                    $response['status'] = 'sqlerror'. $error;
                    header('Content-type: application/json');
                    echo json_encode($response);
                    break;
                }
                $response['status'] = 'sqlerror'. $error;
                header('Content-type: application/json');
                echo json_encode($response);
                mysqli_close($conn);
	break;
}
?>