<?php
include_once '../configuration.php';
include_once 'forgotpassck.php';
include_once '../functions/functions.php';
start_session();
$email="";
$error="";
//if(isset($_SESSION['login_user'])){
//header("location: admin.php");
//}
?>
<?php include ('adminheadertop.php'); ?>
 <?php if ($error != '') { ?>
            <!-- start error modal -->
            <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="errorModalLabel"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> Oops</h4>
                        </div>
                        <div class="modal-body">
                            <p><?php echo $error; ?></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end error modal -->
            <?php } ?>

            <div class="row">
                <div class="col-md-12">
                    <h1 class="text-center">Reset Your <?php echo $company_name; ?> Request System Password</h1>
                </div>
            </div>

    <div class="row">
        <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
            <div class="account-wall">
                <form class="form-signin" action="" method="post">
                    <p class="text-center login-title">Email Address</p>
                    <input id="email" name="email" type="text" class="form-control" style="background-color:#9eff9e"/>
                    <button class="btn btn-lg btn-primary btn-block" name="submit" type="submit"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
