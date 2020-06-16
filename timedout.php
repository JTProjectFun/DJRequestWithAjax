<?php 
include_once 'configuration.php';
include_once 'login.php'; // Includes Login Script
include_once 'functions/functions.php';
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
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<!-- Bootstrap theme -->
		<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<link href="bootstrap/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="theme.css" rel="stylesheet">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="bootstrap/js/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="bootstrap/js/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>

	<body role="document">

		<div class="container theme-showcase" role="main">

			<img class="img-responsive" src="<?php echo $logoURL; ?>" alt="<?php echo $company_name; ?>"/>

			<div class="row">
				<div class="col-md-12">
					<h1 class="text-center">Your session has timed out due to inactivity</h1>
					<h4 class="text-center">Log in again</h4>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
					<div class="account-wall">
						<form class="form-signin" action="/" method="post">
							<p class="text-center login-title">Enter the event key code</p>
							<input type="text" name="eventkey" class="form-control" placeholder="Event Key" required autofocus>
							<button class="btn btn-lg btn-primary btn-block" name="submit" type="submit">Login</button>
						</form>
					</div>
				</div>
			</div>

		</div> <!-- /container -->

		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="bootstrap/js/jquery.1.11.3.min.js"><\/script>')</script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<script src="bootstrap/js/ie10-viewport-bug-workaround.js"></script>

	</body>
</html>