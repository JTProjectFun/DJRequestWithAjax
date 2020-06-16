<?php
include_once '../configuration.php';
include_once 'login.php'; // Includes Login Script
include_once '../functions/functions.php';
start_session();

if((isset($_SESSION['eventkey']) && $_SESSION['eventkey'] ='')){
header("location: requests.php");
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
		<!-- Custom styles for this template -->
		<link href="../theme.css" rel="stylesheet">

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
					<h1 class="text-center">Log in to the <?php echo $company_name; ?> request system</h1>
				</div>
			</div>

			<div class="row">
				<div class="col">
					<div class="account-wall">
						<form class="form-signin" action="" method="post">
							<p class="text-center login-title">Enter the event key code</p>
							<input type="text" name="eventkey" class="form-control" placeholder="Event Key" required autofocus>
                            <input type="text" name="requestername" class="form-control" placeholder="Your Name" required>
							<button class="btn btn-lg btn-primary btn-block" name="submit" type="submit"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> Login</button>
						</form>
					</div>
				</div>
			</div>

			<div class="row top-buffer">
				<div class="col-md-12">
					<button class="btn btn-primary btn-howto" type="button" data-toggle="collapse" data-target="#howToText" aria-expanded="false" aria-controls="howToText">
					  How to use this service
					</button>          
				</div>

                <div class="collapse" id="howToText">
						<div class="panel-group top-buffer" id="accordion" role="tablist" aria-multiselectable="true">
							<div class="panel panel-default">
								<div class="panel-heading" data-toggle="collapse" data-parent="#accordion" data-target="#loggingIn">
									<h4 class="panel-title">
										<a class="accordion-toggle">
											Logging in
										</a>
									</h4>
								</div>
								<div id="loggingIn" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingLoggingIn">
									<div class="panel-body">
										To log in, enter the key code provided. Requests cannot be made without logging in.<br/>
										If the key code entered incorrectly it will not be possible to log in.
									</div>
								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading" data-toggle="collapse" data-parent="#accordion" data-target="#usingTheSystem">
									<h4 class="panel-title">
										<a class="accordion-toggle">
											Using the system
										</a>
									</h4>
								</div>
								<div id="usingTheSystem" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingUsingTheSystem">
									<div class="panel-body">
										Once logged in, click the 'Add New Request' button.<br/>
                                        Search for songs by entering text in the search box. Click on ADD THIS to have the song added to the request list.<br/>
										A form will be shown where your name, the song artist and title can be added.<br/>
										If there are problems with the information used on the form, error popups will be shown.<br/>
										Upon a request being submitted successfully, a popup will be displayed to say so.<br/>
										In some circumstances a list of all requests for this event will be displayed.
									</div>
								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading" data-toggle="collapse" data-parent="#accordion" data-target="#loggingOut">
									<h4 class="panel-title">
										<a class="accordion-toggle">
											Logging out
										</a>
									</h4>
								</div>
								<div id="loggingOut" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingLoggingOut">
									<div class="panel-body">
										To log out, click the 'log out' button at the top of the requests page. Requests cannot be made once logged out. To make further requests, log in with the correct key code again.
									</div>
								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading" data-toggle="collapse" data-parent="#accordion" data-target="#timeOut">
									<h4 class="panel-title">
										<a class="accordion-toggle">
											Time out
										</a>
									</h4>
								</div>
								<div id="timeOut" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTimeOut">
									<div class="panel-body">
										All logged in users are automatically logged out after 2 hours. To make requests again, simply log in again with the correct key code.
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div> <!-- /container -->

		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="bootstrap/js/jquery.1.11.3.min.js"><\/script>')</script>
		<script src="../bootstrap/js/bootstrap.min.js"></script>

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
