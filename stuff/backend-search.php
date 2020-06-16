<?php


// check user is logged in
if (!(isset($_COOKIE['eventkey']) && $_COOKIE['eventkey'] != ''))
{
  header("Location: index.php");
}

if (isset($_SESSION['timeout'])){
    if ($_SESSION['timeout'] + $session_timeout * 60 < time()) {
        if(session_destroy()) // Destroying All Sessions
        {
            header("Location: timedout.php"); // Redirecting To Timed-Out Page
        }
    }
}

if (!isset($_COOKIE['guestuser'])) {
    header("Location: index.php");
}
else{
    


/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$link = mysqli_connect("localhost", "vdjsongs", "vdjsongs", "vdjsongs");
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
 
if(isset($_REQUEST['term'])){
    // Prepare a select statement
    $sql = "SELECT * FROM songs WHERE title LIKE ? OR artist LIKE ? ORDER BY playcount DESC LIMIT 40";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $param_term, $param_term);
        
        // Set parameters
        $param_term = "%" . $_REQUEST['term'] . "%";
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            
            // Check number of rows in the result set
            if(mysqli_num_rows($result) > 0){
                // Fetch result rows as an associative array
                echo "<table><tr><th>Artist</th><th>Title</th><th>Year</th><th>Length</th></tr>";
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    $artist=stripslashes($row['artist']);
                    $title=stripslashes($row['title']);
                    $year=stripslashes($row['year']);
                    $playcount=stripslashes($row['playcount']);
                    $lengthsecs=$row['lengthsecs'];  // Need to convert seconds into mins & secs
                    $songlength=gmdate("i:s",$lengthsecs);
                    $filepath=stripslashes($row['filepath']);
                    $trackid=stripslashes($row['ID']);
                    echo "<tr><td>".$artist."</td><td>".$title."</td><td>".$year."</td><td>".$songlength."</td><td><a href='?action=add&eventid=".$eventid."&trackid=".$trackid."'>Add this track</a></td></tr>";
                    
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
mysqli_close($link);
?>
