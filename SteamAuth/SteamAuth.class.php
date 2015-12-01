<?php

if(session_id() == '') {
	session_start();
}

include("openid.php");

class SteamAuth
{

	private $OpenID;
	private $OnLoginCallback;
	private $OnLoginFailedCallback;
	private $OnLogoutCallback;

	public $SteamID;

	public function __construct($Server = 'DEFAULT')
	{
		if($Server = 'DEFAULT') $Server = $_SERVER['SERVER_NAME'];
		$this->OpenID = new LightOpenID($Server);
		$this->OpenID->identity = 'http://steamcommunity.com/openid';

		$this->OnLoginCallback = function(){};
		$this->OnLoginFailedCallback = function(){};
		$this->OnLogoutCallback = function(){};
	}

	public function __call($closure, $args)
	{
	        return call_user_func_array($this->$closure, $args);
	}

	public function Init()
	{
		if($this->IsUserLoggedIn())
		{
			$this->SteamID = $_SESSION['steamid'];
			return;
		}

		if($this->OpenID->mode == 'cancel')
		{

			$this->OnLoginFailedCallback();

		}
		else if($this->OpenID->mode)
		{
			if($this->OpenID->validate())
			{
				$this->SteamID = basename($this->OpenID->identity);
				if($this->OnLoginCallback($this->SteamID))
				{
					$_SESSION['steamid'] = $this->SteamID;
				}
			}
			else
			{
				$this->OnLoginFailedCallback();
			}
		}
	}

	public function IsUserLoggedIn()
	{
		return isset($_SESSION['steamid']) && strpos($_SESSION['steamid'], "7656") === 0 ? true : false;
	}

	public function RedirectLogin()
	{
		header("Location: " . $this->GetLoginURL());
	}

	public function GetLoginURL()
	{
		return $this->OpenID->authUrl();
	}

	public function Logout()
	{
		$this->OnLogoutCallback($this->SteamID);

		unset($_SESSION['steamid']);
		header("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	}

	public function SetOnLoginCallback($OnLoginCallback)
	{
		$this->OnLoginCallback = $OnLoginCallback;
	}

	public function SetOnLogoutCallback($OnLogoutCallback)
	{
		$this->OnLogoutCallback = $OnLogoutCallback;
	}

	public function SetOnLoginFailedCallback($OnLoginFailedCallback)
	{	
		$this->OnLoginFailedCallback = $OnLoginFailedCallback;
	}
}

?>
