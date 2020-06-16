<?php
include_once '../configuration.php';
include_once '../functions/functions.php';
$error=''; // Variable To Store Error Message
$ak = makeSafe($_GET['ak']);
$id = makeSafe($_GET['userid']);


$tm = time()-86400;
$conn = mysqli_connect($host, $username, $password, $db);
$id=mysqli_real_escape_string($conn, $id);
$ak=mysqli_real_escape_string($conn, $ak);

    $query = mysqli_query($conn, "SELECT id FROM systemUserKey WHERE pkey='$ak' AND id='$id'  AND time > '$tm' AND status='pending'");
    $rows = mysqli_num_rows($query);
    if ($rows <> 1) {
                         echo "Wrong activation";
                         exit;
                    }
//password resetting form will go here

?>
<?php include 'adminheadertop.php'; ?>
<div class="container theme-showcase" role="main">
    <div class="row">
        <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
            <div class="account-wall">
                 <h1 class="text-center"><a href="">Reset Your Password</a></h1>

                 <div id="resetpass">
                     <!-- ajax content -->
                 </div>
            </div>
        </div>
    </div>
</div>


<?php include 'javaincludes.php'; ?>
<script type="text/javascript">
        // Function for loading the grid
        function LoadGrid() {
                var gridder = $('#resetpass');
                var UrlToPass = 'action=load';
                gridder.html('loading..');
                $.ajax({
                        url : 'resetpasswordajax.php',
                        type : 'POST',
                        data : UrlToPass,
                        success: function(responseText) {
                                gridder.html(responseText);
                        }
                });
        }


        LoadGrid(); // Load the grid on page loads

        // Same as the above blur() when user hits the 'Enter' key
        $('body').delegate('.gridder_input', 'keypress', function(e){
                if(e.keyCode == '13') {
                        var ThisElement = $(this);
                        ThisElement.hide();
                        ThisElement.prev('span').show().html($(this).val()).prop('title', $(this).val());
                        var UrlToPass = 'action=update&value='+ThisElement.val()+'&crypto='+ThisElement.prop('name');
                        $.ajax({
                                url : 'passwordajax.php',
                                type : 'POST',
                                data : UrlToPass
                        });
                }
        });
        // Pass the values to ajax page to add the values
        $('body').delegate('#changepassword', 'click', function(){
                // Do insert validation here
                // Pass the form data to the ajax page
                var data = $('#changepassform').serialize();
                $.ajax({
                        url : 'resetpasswordajax.php',
                        type : 'POST',
                        data : data,
                        success: function(data) {
                            if(data.status.indexOf("sqlerror") >=0) {
                                alert ("SQL error.");
                                return false;
                            }

                            if (data.status == "PasswordMismatch") {
                                 setTimeout(function(){ $('#passwordmismatch').show(); }, 100);
                                 setTimeout(function(){ $('#passwordmismatch').fadeOut('fast'); }, 8000);
                                 return false;
                            }

                            if (data.status == "success") {
                                 setTimeout(function(){ $('#passwordchanged').show(); }, 100);
                                 setTimeout(function(){ $('#passwordchanged').fadeOut('fast'); }, 8000);
                                 window.setTimeout(function () {
                                     location.href = "index.php";
                                 }, 5000);
                                 return false;
                            }

                                LoadGrid();
                        }
                });
                return false;
        });
</script>
</body>
</html>

