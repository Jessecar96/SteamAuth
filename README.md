SteamAuth
=========
Sign in through Steam library for PHP.

This is only intended for basic webpages. For bigger projects use this only as an example to build your own.


Features
--------
* Callbacks for login, logout, and login failure
* Easy login
* POST Logout

Requires
--------
* PHP >= 5.4


Simple example
-----
```php
include("SteamAuth/SteamAuth.class.php");
$auth = new SteamAuth();
$auth->Init();

if(isset($_POST['logout']))
{
	$auth->Logout();
}

if($auth->IsUserLoggedIn()) 
{
	echo "Your SteamID is " . $auth->SteamID . "<br/>";
	echo "<form method=\"POST\"><input type=\"submit\" name=\"logout\" value=\"Logout\" /></form>";
} 
else 
{
	echo "<a href=\"" . $auth->GetLoginURL() . "\"><img src=\"assets/sits_large_noborder.png\" alt=\"Sign in through Steam\" /></a>";
}
```
