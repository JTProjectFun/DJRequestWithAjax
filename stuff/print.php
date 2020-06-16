<?php
include "configuration.php";
include "functions/functions.php";
include "phpqrcode/qrlib.php";
$row = "";
$key = makeSafe($_GET['key']);
$codeContents=$requestAppURL . "/login.php?eventid=" . $key;

$tempfile = "images/qrcode.png";

$conn = mysqli_connect($host,$username,$password,$db);
$query = mysqli_query($conn, "SELECT id FROM events WHERE id='".$key."'");
$rows = mysqli_num_rows($query);
mysqli_close($conn);
        if ($rows == 1) {
?>

<html>
<head><title>Print Event Key Code for key <?php echo $key; ?></title></head>
<body>
<h1><?php echo $company_name; ?> Request System</h1>
Scan the QR code with your smartphone or tablet to automatically log into the request system
<?php
                             QRcode::png($codeContents, $tempfile, QR_ECLEVEL_L, 8); 
                             // display QR code
                             echo '<img src="'.$tempfile.'" />'; 
} 
        else echo "<span>INVALID KEY SUPPLIED</span>";
?>
</body>
</html>
