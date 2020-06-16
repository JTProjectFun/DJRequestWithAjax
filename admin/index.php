<?php
include_once '../configuration.php';
include_once 'login.php'; // Includes Login Script
include_once '../functions/functions.php';
start_session();

if(isset($_SESSION['login_user'])){
header("location: admin.php");
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <title><?php echo $company_name; ?> Request System Login</title>
                <!-- Bootstrap core CSS -->
                <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
                <!-- Bootstrap theme -->
                <link href="../bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
                <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
                <link href="../bootstrap/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

                <!-- Custom styles for this template -->
                <link href="../theme.css" rel="stylesheet">

                <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
                <!--[if lt IE 9]>
                        <script src="../bootstrap/js/html5shiv/3.7.2/html5shiv.min.js"></script>
                        <script src="../bootstrap/js/respond/1.4.2/respond.min.js"></script>
                <![endif]-->

    </head>

    <body role="document">
    <div class="container theme-showcase" role="main">
        <img class="img-fluid" src="<?php echo $logoURL; ?>" alt="<?php echo $company_name; ?>"/>
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
                    <h1 class="text-center">Log in to the admin of the <?php echo $company_name; ?> request system</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
                    <div class="account-wall">
                        <form class="form-signin" action="" method="post">
                            <p class="text-center login-title">Username</p>
                            <input type="text" name="username" class="form-control" placeholder="" required autofocus>
                            <p class="text-center login-title">Password</p>
                            <input type="password" name="password" class="form-control" placeholder="" required>
                            <button class="btn btn-lg btn-primary btn-block" name="submit" type="submit"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> Login</button>
                        </form>
                    </div>
                                <a href="forgotpass.php">Forgot Your Password?</a>
                </div>
            </div>
</div> <!-- /container -->

<?php include 'javaincludes.php'; ?>
<?php if ($error != '') { ?>
        <!-- Fire error modal -->
        <script>
        $(function(){
            $('#errorModal').modal('show');
        });  
        </script>
<?php } ?>
</body>
</html>
