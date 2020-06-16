<?php
include_once '../configuration.php';
include_once '../functions/functions.php';
include_once 'generatekey.php';
start_session();
$action = $_REQUEST['action'];
$records = "";
$id=0;
switch($action) {
	case "load":

                $conn = mysqli_connect($host, $username, $password, $db);
		$query 	= mysqli_query($conn, "SELECT * FROM `settings`");
		$count  = mysqli_num_rows($query);
		if($count > 0) {
			while($fetch = mysqli_fetch_assoc($query)) {
				$record[] = $fetch;
			}
		}
                mysqli_close($conn);
		?>
<div class="container">
        <table class="table table bordered table-striped table-condensed settings_table">
 <tr class="grid_header">
<th>Setting</th>
<th>Value</th>
<th>Description</th>
</tr>
            <?php
            if($count <= 0) {
            ?>
            <tr id="norecords">
                <td colspan="7" align="center">No settings found!  Oh dear oh dear oh dear </td>
            </tr>
            <?php } else {
            foreach($record as $records) {
                ?>
                <tr>
                    <td class="setting_title">Session Timeout (minutes)</td>
                    <td class="date"><div class="grid_content editable"><span><?php echo $records['session_timeout']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("session_timeout|".$records['id']); ?>" value="<?php echo $records['session_timeout']; ?>" /></div></td>
                    <td>How long a user's session should time out after</td>
                </tr>
                <tr>
                    <td class="setting_title">Flood Period (seconds)</td>
                    <td class="date"><div class="grid_content editable"><span><?php echo $records['flood_period']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("flood_period|".$records['id']); ?>" value="<?php echo $records['flood_period']; ?>" /></div></td>
                    <td>The minimum period between a user's request posts, to discourage spamming the system</td>
                </tr>
                <tr>
                    <td class="setting_title">Max Requests Per User</td>
                    <td class="date"><div class="grid_content editable"><span><?php echo $records['maxUserRequestDefault']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("maxUserRequestDefault|".$records['id']); ?>" value="<?php echo $records['maxRequests']; ?>" /></div></td>
                    <td>How many requests one user can make for an event. If set to zero, there will be no limit</td>
                </tr>
                    <td class="setting_title">Max Requests Per Event</td>
                    <td class="date"><div class="grid_content editable"><span><?php echo $records['maxRequestDefault']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("maxRequestDefault|".$records['id']); ?>" value="<?php echo $records['maxRequests']; ?>" /></div></td>
                    <td>The total number of requests which may be made at an event. If set to zero, there will be no limit</td>
                </tr>
                <tr>
                    <td class="setting_title">Overrun by (hours)</td>
                    <td class="date"><div class="grid_content editable"><span><?php echo $records['over_hours']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("over_hours|".$records['id']); ?>" value="<?php echo $records['over_hours']; ?>" /></div></td>
                    <td>How many hours into the next day an event key can be valid for, e.g. for a key to expire at 2am the following morning, set this to 2</td>
                </tr>
                <tr>
                    <td class="setting_title">Show Requests by default</td>
                    <td class="showreq">
                        <div class="gridder_content">
                            <span></span>
                            <input type="checkbox" class="toggle" name="
                            <?php echo encrypt("show_requests|".$records['id']); ?>"
                            <?php if ($records['show_requests'] == 1) { echo ' checked '; } ?> />
                        </div>
                    </td>
                    <td>If set, requests submitted will be listed. If not set, only the number of requests submitted will be shown to the user</td>
                </tr>
                <tr>
                    <td class="setting_title">Keys expire by default</td>
                    <td class="expire">
                        <div class="gridder_content">
                            <span></span>
                            <input type="checkbox" class="toggle" name="
                            <?php echo encrypt("willexpire|".$records['id']); ?>"
                            <?php if ($records['willexpire'] == 1) { echo ' checked '; } ?> />
                        </div>
                    </td>
                    <td>If set, an event key will automatically be locked out (expired) after its valid date has passed</td>
                </tr>
                <tr>
                    <td class="setting_title">Keep Users For (Days)</td>
                        <div class="gridder_content">
                            <span></span>
                    <td class="date"><div class="grid_content editable"><span><?php echo $records['maxUserAge']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("maxUserAge|".$records['id']); ?>" value="<?php echo $records['maxUserAge']; ?>" />
                    </td>
                    <td>The length of time in days for event key users to stay in the database. Any older than this value will be deleted automatically</td>
                </tr>
                <?php
                }
            }
            ?>
            </table>
</div>
        <?php
	break;

	case "update":
                $conn = mysqli_connect($host, $username, $password, $db);
		$value 	= $_POST['value'];
		$crypto = decrypt($_POST['crypto']);
		$explode = explode('|', $crypto);
		$columnName = $explode[0];
		$query = mysqli_query($conn, "UPDATE `settings` SET `$columnName` = '$value'");
                mysqli_close($conn);
	break;
}
?>
