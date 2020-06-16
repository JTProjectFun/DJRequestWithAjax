<?php
include_once '../configuration.php';
include_once '../functions/functions.php';
include_once ('generatekey.php');
include_once 'adminconfig.php';
start_session();
$action = $_POST['action'];
$record="";
$id = $_SESSION['login_user'];
switch($action) {
	case "load":
                $tempkey = random_string();
                // Generate a new key. If it's already in the database, keep trying.
                while (checkkey($tempkey)) {
                    $tempkey = random_string();
                }
                $conn = mysqli_connect($host, $username, $password, $db);

		?>

                <form id="changeadminpassword" method="post">
                    <input type="hidden" name="action" value="update" />

                    <div class="password" id="changepass">
                        <table>
                            <tr><td>Current Password</td><td><input type="password" name="curPass" value=""/></td></tr>
                            <tr><td>New Password</td><td><input type="password" name="newPass1" /></td></tr>
                            <tr><td>Confirm New Password</td><td><input type="password" name="newPass2" /></td></tr>
                        </table>

                        <div class="keyadd" id="submitbutton"><input type="submit" id="changepassword" value="submit" class="btn btn-lg gridder_addrecord_button"></input></div>
                        <div class="keyadd" id="cancelbutton">
                            <a href="cancel" id="gridder_cancel" class="btn btn-lg btn-danger gridder_cancel">Cancel</a>
                        </div>
                 </div>
             </form>
        </div>
        <?php
	break;

	case "update":
 foreach ($_POST as $key => $value) {
        error_log("key: " .$key . " value " .$value);
    }




                $conn = mysqli_connect($host,$username,$password,$db);
                $curPass = isset($_POST['curPass']) ? mysqli_real_escape_string($conn, $_POST['curPass']) : '';
                $newPass1 = isset($_POST['newPass1']) ? mysqli_real_escape_string($conn, $_POST['newPass1']) : '';
                $newPass2 = isset($_POST['newPass2']) ? mysqli_real_escape_string($conn, $_POST['newPass2']) : '';
                $query = mysqli_query($conn, "SELECT password,timedate FROM systemUser WHERE id='$userid'");
                if (mysqli_error($conn)) {
                    $error=mysqli_error($conn);
                    $response['status'] = 'sqlerror '. $error;
                    header('Content-type: application/json');
                    echo json_encode($response);
                    mysqli_close($conn);
                    break;
                }

                $result = mysqli_fetch_row($query);
                $pass = $result[0];
                $timedate = $result[1];
                $salt = strrev(date('U', strtotime($timedate)));
                $hashedPass = sha1($salt.$curPass);

                if ($hashedPass != $pass) {
                    $response['status'] = 'PasswordIncorrect';
                    header('Content-type: application/json');
                    echo json_encode($response);
                    mysqli_close($conn);
                    break;
                }

                if ($newPass1 != $newPass2) {
                    $response['status'] = 'NewPasswordsDontMatch';
                    header('Content-type: application/json');
                    echo json_encode($response);
                    mysqli_close($conn);
                    break;
                }

$hashedNewPass = sha1($salt.$newPass1);
 $query = mysqli_query($conn, "UPDATE systemUser SET password='$hashedNewPass' WHERE id='$userid'");

                $response['status'] = 'success';
                header('Content-type: application/json');
                echo json_encode($response);
                mysqli_close($conn);
	break;

}
?>
