<?php
include_once '../configuration.php';
include_once '../customtexts.php';
include_once '../functions/functions.php';
include_once ('generatekey.php');
include_once 'adminconfig.php';
$action = $_REQUEST['action'];
$record="";

switch($action) {
        case "load":
                $tempkey = random_string();
                // Generate a new key. If it's already in the database, keep trying.
                while (checkkey($tempkey)) {
                    $tempkey = random_string();
                }
                $conn = mysqli_connect($host, $username, $password, $db);

                ?>
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-success collapse" role="alert" id="passwordchanged">
            <p><?php echo $passwordsuccessString; ?></p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="alert alert-danger collapse" role="alert" id="passwordmismatch">
            <p>ERROR: <?php echo $passwordmismatchString; ?></p>
        </div>
    </div>
</div>

                <form id="changepassform" class="form-signin" method="post">
                    <input type="hidden" name="action" value="update" />

                    <div class="password" id="changepass">
                        <table>
                            <tr><td>New Password</td><td><input type="password" name="newPass1" id="date" class="form-control"/></td></tr>
                            <tr><td>Confirm New Password</td><td><input type="password" name="newPass2" id="showrequests" class="form-control" /></td></tr>
                        </table>
                        <button class="btn btn-lg btn-primary btn-block" name="submit" type="submit" id="changepassword">Submit</button>
                    </div>
                 </div>
             </form>
        </div>
        <?php
        break;

        case "update":
                $conn = mysqli_connect($host,$username,$password,$db);
                $curPass = isset($_POST['curPass']) ? mysqli_real_escape_string($conn, $_POST['curPass']) : '';
                $newPass1 = isset($_POST['newPass1']) ? mysqli_real_escape_string($conn, $_POST['newPass1']) : '';
                $newPass2 = isset($_POST['newPass2']) ? mysqli_real_escape_string($conn, $_POST['newPass2']) : '';
                $query = mysqli_query($conn, "SELECT password,timedate FROM systemUser WHERE id='$userid'");
error_log("UserId: " . $userid . " curPass: " . $curPass . " newPass1: " . $newPass1 . " newPass2: " . $newPass2 . " userid: " . $userid);
                if (mysqli_error($conn)) {
                    $error=mysqli_error($conn);
                    $response['status'] = 'sqlerror'. $error;
                    header('Content-type: application/json');
                    echo json_encode($response);
                    mysqli_close($conn);
                    break;
                }

                $result = mysqli_fetch_row($query);
                $pass = $result[0];
                $timedate = $result[1];
error_log("Password: " .$pass);
                $salt = strrev(date('U', strtotime($timedate)));
                $hashedPass = sha1($salt.$curPass);
error_log("Hashed Password: " .$hashedPass);

                if ($newPass1 != $newPass2) {
                    error_log("New passwords do not match");
                    $response['status'] = 'PasswordMismatch';
                    header('Content-type: application/json');
                    echo json_encode($response);
                    mysqli_close($conn);
                    break;
                }

$hashedNewPass = sha1($salt.$newPass1);
$query = mysqli_query($conn, "UPDATE systemUser SET password='$hashedNewPass' WHERE id='$userid'");
$query = mysqli_query($conn, "DELETE FROM systemUserKey WHERE id='$userid'");

                $response['status'] = 'success';
                header('Content-type: application/json');
                echo json_encode($response);
                mysqli_close($conn);
        break;

}

?>
