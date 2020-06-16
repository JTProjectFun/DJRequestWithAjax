<?php
include_once '../configuration.php';
include_once '../functions/functions.php';
include_once 'generatekey.php';
include_once 'adminconfig.php';
start_session();
$record=array();
$action = $_REQUEST['action'];
if (isset($_SESSION['listuser'])) {
    $luser = $_SESSION['listuser'];
}

// Database schema reference
//+-----------------+--------------+------+-----+---------+-------+
//| Field           | Type         | Null | Key | Default | Extra |
//+-----------------+--------------+------+-----+---------+-------+
//| requestuserid   | int(11)      | NO   | PRI | NULL    |       |
//| uniqueid        | varchar(16)  | YES  |     | NULL    |       |
//| ipaddr          | varchar(20)  | YES  |     | NULL    |       |
//| banned          | int(11)      | YES  |     | 0       |       |
//| createdTime     | varchar(32)  | YES  |     | NULL    |       |
//| numRequests     | int(11)      | YES  |     | 0       |       |
//| logintimes      | int(11)      | YES  |     | 0       |       |
//| password        | varchar(64)  | YES  |     | NULL    |       |
//| requestusername | varchar(128) | YES  |     | NULL    |       |
//| email           | varchar(128) | YES  |     | NULL    |       |
//| realname        | varchar(128) | YES  |     | NULL    |       |
//+-----------------+--------------+------+-----+---------+-------+


switch($action) {
	case "load":
                $rq = mysqli_connect($host, $username, $password, $db);
                        $query_string = "SELECT * FROM requestusers;";
                        $query = mysqli_query($rq, $query_string);
		$count  = mysqli_num_rows($query);
		if($count > 0) {
			while($fetch = mysqli_fetch_array($query)) {
				$record[] = $fetch;
			}
		}
		?>

               <form id="gridder_addform" method="post">
                    <input type="hidden" name="action" value="addnew" />
                    <div class="addnewuser" id="addnew">
                        <table class=" table bordered table-striped table-condensed">
            <tr class="grid_header">
                <th>Client Name</th>
                <th>Client Username</th>
                <th>Client Email</th>
                <th>Password</th>
            </tr>
            <tr>

                <td><input type="text" name="realname" class="gridderadd" value="real name"/></td>
              
                <td><input type="text" class="gridderadd" name="requestusername" value="username" /></td>
                <td><input type="text" class="gridderadd" name="email" value="email address" /></td>
                <td><input type="text" class="gridderadd" name="password" value="password" /></td>
                        </table>
                        <div class="keyadd" id="submitbutton"><input type="submit" id="gridder_addrecord" value="submit" class="btn btn-lg gridder_addrecord_button" $
                        <div class="keyadd" id="cancelbutton">
                            <a href="cancel" id="gridder_cancel" class="btn btn-lg btn-danger gridder_cancel" role="button">Cancel</a>
                        </div>
                 </div>
             </form>
        <table class="table bordered table-striped table-condensed as_gridder">
            <tr>
                <th class="id"><div class="grid_heading">id</div></th>
                <th class="name"><div class="grid_heading">Username</div></th>
                <th class="name"><div class="grid_heading">Real Name</div></th>
                <th class="email"><div class="grid_heading">email</div></th>
                <th class="date"><div class="grid_heading">Date &amp; Time Added</div></th>
                <th class="date"><div class="grid_heading">UserString</div></th>
                <th class="date"><div class="grid_heading">Key</div></th>
                <th class="date"><div class="grid_heading">IP Address</div></th>
                <th class="date"><div class="grid_heading">Requests Made</div></th>
                <th class="del"><div class="grid_heading">Delete</div></th>
                <th class="del"><div class="grid_heading">BAN</div></th>
            </tr>

            <?php
            if($count <= 0) {
            ?>
            <tr id="norecords">
                <td colspan="11" align="center">No records found</td>
            </tr>
            <?php } else {
            $i = 0;
            foreach($record as $records) {
            $i = $i + 1;
            ?>
            <tr class="<?php if($i%2 == 0) { echo 'even'; } else { echo 'odd'; } ?>">
                <td class="id">
                    <div class="grid_content sno">
                        <span><?php echo $records['requestuserid']; ?></span>
                    </div>
                </td>
                <td class="name">
                    <div class="grid_content editable">
                        <span><?php echo $records['requestusername']; ?></span>
                        <input type="text" class="gridder_input" name="<?php echo "requestusername|".$records['requestuserid']; ?>" value="<?php echo $records['requestusername']; ?>" />
                    </div>
                </td>
                <td class="name">
                    <div class="grid_content editable">
                        <span><?php echo $records['realname']; ?></span>
                        <input type="text" class="gridder_input" name="<?php echo "realname|".$records['requestuserid']; ?>" value="<?php echo $records['realname']; ?>" />
                    </div>
                </td>
                <td class="email">
                    <div class="grid_content editable">
                        <span><?php echo $records['email']; ?></span>
                        <input type="text" class="gridder_input" name="<?php echo "email|".$records['requestuserid']; ?>" value="<?php echo $records['email']; ?>" />
                    </div>
                </td>
                <td class="date">
                    <div class="grid_content sno">
                        <span><?php echo $records['createdTime']; ?></span>
                    </div>
                </td>
                <td class="date">
                    <div class="grid_content sno">
                        <span><a href="requests.php?requestuser=<?php echo $records['uniqueid']; ?>"> <?php echo $records['uniqueid']; ?></a></span>
                    </div>
                </td>
                <td class="date">
                    <div class="grid_content sno">
                        <span><a href="requests.php?eventkey=<?php echo $records['requestuserid']; ?>"> <?php echo $records['thekey']; ?></a></span>
                    </div>
                </td>
                <td class="date">
                    <div class="grid_content sno">
                        <span><?php echo $records['ipaddr']; ?></span>
                    </div>
                </td>
                <td class="date">
                    <div class="grid_content sno">
                        <span><?php echo $records['numRequests']; ?></span>
                    </div>
                </td>
                <td>
                    <a href="<?php echo $records['requestuserid']; ?>" class="gridder_delete">
                        <img src="../images/delete.png" alt="Delete" title="Delete" />
                    </a>
                </td>
                <td>
                    <a href="<?php echo $records['requestuserid']; ?>" class="gridder_ban">
                        <img src="../images/delete.png" alt="Ban this user" title="BAN" />
                    </a> 
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
				$date 		= isset($_POST['date']) ? mysqli_real_escape_string($conn, $_POST['date']) : '';
                $realname = isset($_POST['realname']) ? mysqli_real_escape_string($conn, $_POST['realname']) : '';
                $requestusername = isset($_POST['requestusername']) ? mysqli_real_escape_string($conn, $_POST['requestusername']) : '';
                $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
                $userpassword = isset($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : '';
        		mysqli_query($conn, "INSERT INTO `requestusers` (createdTime, realname, requestusername, email, password) VALUES ('$timedate', '$realname', '$requestusername', '$email', '$userpassword')");
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
                $value  = $_POST['value'];
                $crypto = $_POST['crypto'];
                $explode = explode('|', $crypto);
                $columnName = $explode[0];
                $rowId = $explode[1];
                $query = mysqli_query($conn, "UPDATE `requestusers` SET `$columnName` = '$value' WHERE id = '$rowId' ");
                mysqli_close($conn);

        break;

	case "delete":
                $conn = mysqli_connect($host,$username,$password,$db);
		$value 	= $_POST['value'];
		$query = mysqli_query($conn, "DELETE FROM `requestusers` WHERE requestuserid = '$value' ");
        $query = mysqli_query($conn, "DELETE FROM `requests` WHERE userid = '$value' ");
                mysqli_close($conn);
	break;

    
	case "ban":
                $conn = mysqli_connect($host,$username,$password,$db);
		$value 	= $_POST['value'];
		$query = mysqli_query($conn, "DELETE FROM `requests` WHERE uniqueid = '$value' ");
		$query = mysqli_query($conn, "UPDATE `requestusers` SET banned=1 WHERE uniqueid = '$value' ");
                mysqli_close($conn);
	break;
}
?>
