<?php
if (preg_match('#^http://(www\.)?filecloud\.io/#', $url)){
	$account = trim($this->get_account('filecloud.io'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("filecloud.io");
			if(!$cookie){
				$data = $this->curl("https://secure.filecloud.io/user-login_p.html","","username=$user&password=$pass");
				$cookie = $this->GetCookies($data);
				$this->save_cookies("filecloud.io",$cookie);
			}
			$this->cookie = $cookie;
			$data = $this->curl($url,$cookie,"");
			if (preg_match('/ocation: (.*)/',$data,$match)) {
				$link = trim($match[1]);
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			else {
				$cookie = "";
				$this->save_cookies("filecloud.io","");
			}
		}
	}
}
/*
* Home page: http://vinaget.us
* Blog: http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.6.3
* Created: France
*/
?>