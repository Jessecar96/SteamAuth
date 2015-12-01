<?php

/*

Example usage of SteamAuth
Uses: handlers, login, SteamID, POST Logout

*/

include("SteamAuth/SteamAuth.class.php");

$auth = new SteamAuth();

// You can use this to do other checks on the person, such as making an account in a database
$auth->SetOnLoginCallback(function($steamid){
	return true; // returning true will log them in, false will stop the login (you should put an error message in that case)
});

// This handler is for when a login fails Ex: canceled, auth failed, exploit attempt, etc
$auth->SetOnLoginFailedCallback(function(){
	return true;
});

// You can use this to do other checks on the person, such as making an modifying a database
$auth->SetOnLogoutCallback(function($steamid){
	return true; 
});

// Always call Init() on pages you want to check a login from.  Call this AFTER you set handlers!
$auth->Init();

// Where we handle the POST logout from the form below
if(isset($_POST['logout'])){
	$auth->Logout(); // The logout function also refreshes the page
}

//Check if user is logged in
if($auth->IsUserLoggedIn()){

	// Display your content here~

	// Display SteamID
	echo "Your SteamID is " . $auth->SteamID . "<br/>";

	// We use POST to logout so people can't embed images to the logout function and annoy people.
	echo "<form method=\"POST\"><input type=\"submit\" name=\"logout\" value=\"Logout\" /></form>";

}else{

	// Display login button
	echo "<a href=\"" . $auth->GetLoginURL() . "\"><img src=\"assets/sits_large_noborder.png\" alt=\"Sign in through Steam\" /></a>";
}



?>
