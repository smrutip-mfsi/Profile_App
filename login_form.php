<?php
session_start();

if(isset($_SESSION['id']))
{
	header('Location: dashboard.php');
	exit;
}

?>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Login</title>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
		<script   src="https://code.jquery.com/jquery-3.0.0.min.js"   integrity="sha256-JmvOoLtYsmqlsWxa7mDSLMwa6dZ9rrIdtrrVYRnDRH0="   crossorigin="anonymous"></script>
		<script type="text/javascript" src="js/jquery_validation.js"></script>
	</head>
	<body background="images/home_background.jpg">
	<!-- Navigation bar -->
	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<ul class="nav navbar-nav">
				<li><a href="home_default.php">Home</a></li>
				<li><a href="sign_up.php">Sign Up</a></li>
				<li class="active"><a href="login.php">Login</a></li>
			</ul>
		</div>
	</nav>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2">
				<form action="login.php" method="post" id="login_form">
					<fieldset>
					<div class="well">

						<!-- Email id field -->
						<div class="row form-group">
							<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
								<label for="email">Email ID:</label>
							</div>
							<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
								<input type="text" name="email" id="email" 
								class="form-control login_email" placeholder="Email@mail.com">
								<span class="text-danger err_msg" id="err_email"></span>
							</div>
						</div>

						<!-- Password field -->
						<div class="row form-group">
							<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
								<label for="password">Password:</label>
							</div>
							<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
								<input type="password" name="password" id="password" 
								class="form-control login_password" placeholder="********">
								<span class="text-danger err_msg" id="err_password"></span>
							</div>
						</div>
						<div class="text-center">
						<?php
						if(isset($_SESSION['error_array']['login']['msg']))
						{
							echo '<span class="text-danger">'.$_SESSION['error_array']
							['login']['msg']."</span>";
							session_unset();
						}
						?>
						</div>
					</div>
					</fieldset>
					

				<!-- Buttons -->
				<div class="row form-group text-center">
					<button class="btn btn-primary" type="submit" name="login_submit" 
					value="login_submit">Login</button>
				</div>
				</form>
			</div>
		</div>
	</div>
	</body>
</html>
