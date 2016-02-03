<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?filejungle\.com/#', $url)){
	$account = trim($this->get_account('filejungle.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("filejungle.com");
			if(!$cookie){
				$data = $this->curl("http://www.filejungle.com/login.php","","loginUserName=$user&loginUserPassword=$pass&loginFormSubmit=");
				$cookie = $this->GetCookies($data);
				$this->save_cookies("filejungle.com",$cookie);
			}
			$cookie = preg_replace("/(phpsessid=|Phpsessid=|PHPSESSID=)/","",$cookie);
			$cookie = "PHPSESSID=".$cookie;
			$this->cookie = $cookie;
			$data =  $this->curl($url,$cookie,"");
			if(preg_match('/ocation: *(.*)/i', $data, $redir)){
				$link = trim($redir[1]);
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			elseif (stristr($data,"This file is no longer available")) {
				$report = Tools_get::report($Original,"dead");
				break;
			}
			else {
				$cookie = ""; 
				$this->save_cookies("filejungle.com","");
			}
		}
	}
}

/*
* Home page: http://vinaget.us
* Blog:	http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.6.3
* Created: ..:: [H] ::..
*/
?>