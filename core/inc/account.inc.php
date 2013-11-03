<?php

// checks if the given username exists in the database
function userExists( $email )
{
	global $accountDB;

	$query = "SELECT COUNT(*) 
		FROM Account 
		WHERE EMAIL = :x";
	$parsed = oci_parse( $accountDB, $query );
	oci_bind_by_name( $parsed, ":x", $email ); 
	oci_execute( $parsed );
		
	$total = oci_result( $parsed );

	return ( $total == 1 ) ? true: false;		// account exists if we have an instance of that email
}

// checks if the given username and password combination is valid
function validCredentials( $email, $pass )
{
	global $accountDB;

	$query = "SELECT * 
		FROM Account
		WHERE EMAIL = :x AND PASSWORD = :y";
	$parsed = oci_parse( $accountDB, $query );
	oci_bind_by_name( $parsed, ":x", $email ); 
	oci_bind_by_name( $parsed, ":y", $pass );
	oci_execute( $parsed );
	
	$count = 0;					// count the tuples returned

	while( $row = oci_fetch_array( $parsed, OCI_ASSOC ) )
	{
		$count++;
	}	

	return ( $count == 1 ) ? true: false;		// correct credentials if we have an instance of that combination
}

// check to see if the user has activated their account
function isActive( $email ) 
{
	global $accountDB;

	$query = "SELECT *
		FROM Account";
		//WHERE EMAIL = :x";

	$countQuery = "SELECT *
			FROM Account";
			//WHERE EMAIL = 'nlarosa@nd.edu'";

	$parsed = oci_parse( $accountDB, $countQuery );

	//oci_define_by_name( $parsed, 'NUMBER_OF_ROWS', $number_of_rows);

	//oci_bind_by_name( $parsed, ":x", $email ); 
	oci_execute( $parsed, OCI_DEFAULT );
	oci_fetch( $parsed );
	$number_of_rows = oci_num_rows( $parsed );
	oci_commit( $accountDB );

	print_r( $number_of_rows );
		
	//$total = oci_num_rows( $parsed );

	//print_r( $total );
	
	return ( $total == 0 ) ? true: false;		// the account is verified if it does not have a tuple
}

// activates the account related to the given activation ID
function activateAccount( $actID )
{
	global $accountDB;	

	$query = "DELETE
		FROM Verifications
		WHERE CODE = :x";
	$parsed = oci_parse( $accountDB, $query );
	oci_bind_by_name( $parsed, ":x", $actID ); 
	oci_execute( $parsed );
}

// adds a user to the database
function addUser( $email, $pass, $first, $last, $age, $height, $weight, $cals )
{
	global $accountDB;

	$charset = array_flip(array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9)));		// establishing range for random code generators
	$actID = implode('', array_rand($charset, 10));
	$body = "

Hello there!

Thank you for registering with Ripped to Shreds!

Please follow the link below to activate your account and get started:

http://orchestra.cselab.nd.edu/~nlarosa/RippedToShreds/activate.php?code={$actID}

We look forward to you joining our community! 

Sincerely,

The Ripped To Shreds Team
	
";

	mail( $email, 'Activation Email for Ripped to Shreds Account', $body, 'From: nlarosa@nd.edu' );	// sends the email to user

	$query = "INSERT INTO Account 
		VALUES (:a, :b, :c, :d, :e, :f, :g, :h)";
	$parsed = oci_parse( $accountDB, $query );
	oci_bind_by_name( $parsed, ":a", $first ); 
	oci_bind_by_name( $parsed, ":b", $last ); 
	oci_bind_by_name( $parsed, ":c", $height ); 	
	oci_bind_by_name( $parsed, ":d", $weight ); 
	oci_bind_by_name( $parsed, ":e", $cals ); 
	oci_bind_by_name( $parsed, ":f", $pass ); 
	oci_bind_by_name( $parsed, ":g", $age ); 
	oci_bind_by_name( $parsed, ":h", $email ); 
	oci_execute( $parsed );

	$query = "INSERT INTO Verifications (EMAIL, CODE) 
		VALUES (:x, :y)";
	$parsed = oci_parse( $accountDB, $query );
	oci_bind_by_name( $parsed, ":x", $email ); 
	oci_bind_by_name( $parsed, ":y", $actID ); 
	oci_execute( $parsed );
}

// grabs all users registered, prints as HTML table
function fetchUsers()
{
	global $accountDB;

	$users = array();

	$query = "SELECT *
		FROM Account";
	$parsed = oci_parse( $accountDB, $query );
	oci_execute( $parsed );

	while( $tuple = oci_fetch_array( $parsed, OCI_ASSOC ) )
	{
		$users[] = $tuple;	// we want to store a multidimensional array   
	}

	return $users;			// store an array of tuples (each of which is an 8-element array
}

// grabs info for specific user
function fetchUserInfo( $email ) 
{
	global $accountDB;

	$query = "SELECT * 
		FROM Account 
		WHERE email = :x";
	$parsed = oci_parse( $accountDB, $query );	
	oci_bind_by_name( $parsed, ":x", $email ); 
	oci_execute( $parsed );

	return oci_fetch_array( $parsed );	// return a single tuple, as an arry
}

function updateHeight( $number )
{
	global $accountDB;

	$query = "UPDATE Account 
		SET height = :x 
		WHERE email = :y";
	$parsed = oci_parse( $accountDB, $query );
	oci_bind_by_name( $parsed, ":x", $number );
	oci_bind_by_name( $parsed, ":y", $_SESSION['email'] );
	oci_execute( $parsed );
}

function updateWeight( $email, $number )
{
	global $accountDB;

	$query = "UPDATE Account 
		SET weight = :x 
		WHERE EMAIL = :y";
	$parsed = oci_parse( $accountDB, $query );
	oci_bind_by_name( $parsed, ":x", $number );
	oci_bind_by_name( $parsed, ":y", $email );
	oci_execute( $parsed );
}

function updateAge( $number )
{
	global $accountDB;

	$query = "UPDATE Account 
		SET age = :x 
		WHERE EMAIL = :y";
	$parsed = oci_parse( $accountDB, $query );
	oci_bind_by_name( $parsed, ":x", $number );
	oci_bind_by_name( $parsed, ":y", $_SESSION['email'] );
	oci_execute( $parsed );
}

function updateCalories( $number )
{
	global $accountDB;

	$query = "UPDATE Account 
		SET dailyCalories = :x 
		WHERE EMAIL = :y";
	$parsed = oci_parse( $accountDB, $query );
	oci_bind_by_name( $parsed, ":x", $number );
	oci_bind_by_name( $parsed, ":y", $_SESSION['email'] );
	oci_execute( $parsed );
}

?>
