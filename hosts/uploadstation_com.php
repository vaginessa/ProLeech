<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?uploadstation\.com/#', $url)){
	$account = trim($this->get_account('uploadstation.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("uploadstation.com");
			if(!$cookie){
				$data = $this->curl("http://www.uploadstation.com/login.php","","loginUserName=$user&loginUserPassword=$pass&autoLogin=on&loginFormSubmit=Login");
				$cookie = $this->GetCookies($data);
				$this->save_cookies("uploadstation.com",$cookie);
			}
			$cookie = preg_replace("/(phpsessid=|Phpsessid=|PHPSESSID=)/","",$cookie);
			$cookie = "PHPSESSID=".$cookie;
			$this->cookie = $cookie;
			$data = $this->curl($url,$cookie,"download=premium");
			if(preg_match('/ocation: (.*)/', $data, $match)) {
				$link = trim($match[1]);
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			elseif (stristr($data,"File is not available")) {
				$report = Tools_get::report($Original,"dead");
				break;
			}
			else {
				$cookie = "";
				$this->save_cookies("uploadstation.com","");
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