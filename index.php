<?php

include('./core/init.inc.php');

function validEmail( $email )						// checks for valid email addresses
{
	if( filter_var( $email, FILTER_VALIDATE_EMAIL ) )
	{
		$allowed = array( 'nd.edu', 'gmail.com' );		// only support nd.edu emails
		$domain = array_pop( explode( '@', $email ) );
		if( in_array( $domain, $allowed ) )
		{
			return TRUE;
		}
	}
	else
	{
		return FALSE;
	}
}

function validPass( $password )						// checks for valid password
{
	if( preg_match( "/^.*(?=.{8,}).*$/", $password ) )
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

$errors = array();
	
if( isset($_POST['email']) && isset($_POST['passWord']) )		// check for correct login
{
	if( empty($_POST['email']) )
	{
		$errors[] = 'Please provide your email address. ';
	}
	if( empty($_POST['passWord']) )
	{
		$errors[] = 'Please provide your password. ';
	}
	if( !empty($_POST['email']) && !empty($_POST['passWord']) && !validCredentials($_POST['email'], sha1($_POST['passWord'])) )
	{
		$errors[] = 'Username and/or password is incorrect. ';
	}
	if( empty($errors) && !isActive($_POST['email']) )
	{
		$errors[] = 'This profile has not yet been activated.';
	}
	if( empty($errors) )
	{
		$_SESSION['user'] = $_POST['email'];
		header("Location: main.php?user={$_SESSION['user']}");
		die();
	}
}

$regErrors = array();

if( isset($_POST['regEmail']) && isset($_POST['regPassWord']) && isset($_POST['repeatPassWord']) )	// check for correct registration
{
	if( empty($_POST['regEmail']) || !validEmail( $_POST['regEmail'] ) )
	{
                $regErrors[] = 'Please provide a valid email address.';
        }
        if( empty($_POST['regPassWord']) || empty($_POST['repeatPassWord']) || !validPass($_POST['regPassWord']) )
	{
		$regErrors[] = 'Please provide a valid password (8 characters/digits or more).';
        }
        if( $_POST['regPassWord'] !== $_POST['repeatPassWord'] )
	{
                $regErrors[] = 'Passwords do not match.';
        }
	if( userExists($_POST['regEmail']) )
	{
                $regErrors[] = 'An account already exists for that email address.';
        }
	if( empty($_POST['firstName']) || empty($_POST['lastName']) )
	{
		$regErrors[] = 'Please enter your first and last name.';
	}
	if( empty($_POST['height']) || empty($_POST['weight']) || empty($_POST['age']) || empty($_POST['calories']) )
	{
		$regErrors[] = 'Please complete all fitness information.';
	}
	else if( !is_numeric( $_POST['height'] ) || !is_numeric( $_POST['weight'] ) || !is_numeric( $_POST['age'] ) || !is_numeric( $_POST['calories'] ) )
	{
		$regErrors[] = 'Fitness information requires numeric inputs.';
	}	

        if( empty($regErrors) )										// no errors, add the account
	{
		addUser( $_POST['regEmail'], sha1($_POST['regPassWord']), $_POST['firstName'], $_POST['lastName'], $_POST['age'], $_POST['height'], $_POST['weight'], $_POST['calories'] );
		header('Location: index.php?success=1');
		die();
	}
}

/*
if( isset($_SESSION['user']) )
{
	header('Location: main.php');
	die();
}
*/

?>

<!DOCTYPE html>

<head>
	<meta charset="utf-8" />
	<!-- Set the viewport width to device width for mobile -->

	<title>Ripped to Shreds</title>
	
	<link rel="icon" type="img/ico" href="favicon.ico">
	<link rel="stylesheet" href="./stylesheets/foundation.css">
	
	<script src="javascripts/modernizr.foundation.js"></script>
</head>

<body>
	<div class="row"> 
		<div class="one column">
		</div>	
		<div class="five columns" align="center" style="background-color:#CCCCFF; margin:2.5% 0%;">
			<h3>Ripped to Shreds</h3>
		</div>
		<div class="five columns" align="center" style="background-color:#FFCCCC; margin:2.5% 0%;">
			<h3>Workout Management</h3>
		</div>
		<div class="one column">
		</div>
		<hr />
	</div>

	<div class="row">
		<div class="one column">
		</div>
		<div class="ten columns" align="center">
			<div class="panel">
				Welcome to <b>Ripped to Shreds</b>, an application that allows you to easily keep track 
				of the workouts and progress of yourself and others! Please log in or register to continue.
			</div>
		</div>
		<div class="one column">
		</div>
	</div>
	
	<div class="row">
		<div class="one column">
		</div>
		<div class="five columns" style="background-color:#FFCCCC; margin-bottom:2.5%;">
			<h4>Login</h4>
			<?php 
			if( $_GET['activated'] == 1 )
			{
			?>
				<p>Account verified! Login below.</p>
			<?php
			}
			else
			{
			?>
				<p>For returning users</p>
			<?php
			}
			?>
			<form method="POST">
				<div class="row">
					<div class="three columns">
						<label class="right inline">Email</label>
					</div>
					<div class="nine columns">
						<input type="text" name="email" value="<?php echo htmlentities($_POST['email']);?>" />
					</div>
				</div>
				<div class="row">
					<div class="three columns">
						<label class="right inline">Password</label>
					</div>
					<div class="nine columns">
						<input type="password" name="passWord" /> 
					</div>
				</div>
				<br>
				<button type="submit" class="button">Sign in</button>
				<?php 
				if( !empty($errors) )
				{
				?>
					<a href="" class="button alert" name="loginErrors" data-reveal-id="loginModal">Errors</a>
				<?php 
				}
				?>
			</form>
		</div>
		<div class="five columns" style="background-color:#CCCCFF; margin-bottom:2.5%;">
			<h4>Register</h4>
			<p>Please fill out all pages</p>
			<form method="POST">
				<dl class="tabs">
					<dd class="active"><a href="#reg1">Page 1</a></dd>
					<dd><a href="#reg2">Page 2</a></dd>
					<dd class="hide-for-small"><a href="#reg3">Page 3</a></dd>
				</dl>
				<ul class="tabs-content">
					<li class="active" id="reg1Tab">
						<div class="row">
							<div class="three columns">
								<label class="right inline">Email</label>
							</div>
							<div class="nine columns">
								<input type="text" name="regEmail" value="<?php echo htmlentities($_POST['regEmail']);?>" />
							</div>
						</div>
						<div class="row">
							<div class="three columns">
								<label class="right inline">Password</label>
							</div>
							<div class="nine columns">
								<input type="password" name="regPassWord" />
							</div>
						</div>
						<div class="row">
							<div class="three columns">
								<label class="right inline">Password</label>
							</div>
							<div class="nine columns">
								<input type="password" name="repeatPassWord" />
							</div>
						</div>
					</li>
					<li id="reg2Tab">
						<div class="row">
							<div class="three columns">
								<label class="right inline">First Name</label>
							</div>
							<div class="nine columns">
								<input type="text" name="firstName" value="<?php echo htmlentities($_POST['firstName']);?>" />
							</div>
						</div>
						<div class="row">
							<div class="three columns">
								<label class="right inline">Last Name</label>
							</div>
							<div class="nine columns">
								<input type="text" name="lastName" value="<?php echo htmlentities($_POST['lastName']);?>" />
							</div>
						</div>	
						<div class="row">
							<div class="three columns">
								<label class="right inline">Age</label>
							</div>
							<div class="six columns">
								<input type="text" name="age" value="<?php echo htmlentities($_POST['age']);?>" />
							</div>
							<div class="three columns">
								<span class="postfix">years</span>
							</div>
						</div>		
					</li>	
					<li id="reg3Tab">
						<div class="row">
							<div class="three columns">
								<label class="right inline">Height</label>
							</div>
							<div class="six columns">
								<input type="text" name="height" value="<?php echo htmlentities($_POST['height']);?>" />
							</div>
							<div class="three columns">
								<span class="postfix">inches</span>
							</div>
						</div>
						<div class="row">
							<div class="three columns">
								<label class="right inline">Weight</label>
							</div>
							<div class="six columns">
								<input type="text" name="weight" value="<?php echo htmlentities($_POST['weight']);?>" />
							</div>
							<div class="three columns">
								<span class="postfix">lbs</span>
							</div>
						</div>	
						<div class="row">
							<div class="three columns">
								<label class="right inline">Diet</label>
							</div>
							<div class="six columns">
								<input type="text" name="calories" value="<?php echo htmlentities($_POST['calories']);?>" />
							</div>
							<div class="three columns">
								<span class="postfix">Kcals/day</span>
							</div>
						</div>
					</li>
				</ul>
				
				<button type="submit" class="button">Register</button>
				<?php 
				if( !empty($regErrors) )
				{
				?>
					<a href="" class="button alert" name="registerErrors" data-reveal-id="registerModal">Errors</a>
				<?php 
				}
				else if( $_GET['success'] == 1 )
				{
				?>
					<a href="" class="button success" name="registerSuccess" data-reveal-id="registerModal">Success</a>
				<?php
				}
				?>
			</form>	
		</div>
		<div class="one column">
		</div>	
		<hr />
	</div>

	<div id="registerModal" class="reveal-modal [expand, xlarge, large, medium, small]">
		<?php 
		if( !empty($regErrors) ) 
		{
		?>
			<h5>Registration Errors</h5>
			<ul style="list-style-type:none; color:red; text-align:left;">
			<?php
			foreach( $regErrors as $error )
			{
				echo "<li>{$error}</li>";
			}
			?>
			</ul>
		<?php
		}
		else
		{
		?>
			<h5>Successful Registration!</h5>
			<p>Check your email for a verification link to get started!</p>
		<?php
		}
		?>

		<a class="close-reveal-modal">&#215;</a>
	</div>

	<div id="loginModal" class="reveal-modal [expand, xlarge, large, medium, small]">
		<h5>Login Errors</h5>
		<ul style="list-style-type:none; color:red; text-align:left;">	
		<?php
		foreach( $errors as $error )
		{
			echo "<li>{$error}</li>";
		}
		?>
		</ul>
		<a class="close-reveal-modal">&#215;</a>
	</div>

	<!-- Included JS Files (Compressed) -->
	<script src="javascripts/jquery.js"></script>
	<script src="javascripts/foundation.min.js"></script>
  
	<!-- Initialize JS Plugins -->
	<script src="javascripts/app.js"></script>

	<script>
		$(window).load(function(){
		$("#featured").orbit();
		});
	</script> 

	<footer align="center">
		Copyright 2013 - The Fireballs
	</footer>

</body>

</html>
