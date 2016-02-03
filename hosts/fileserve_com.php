<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?fileserve\.com/#', $url)){
	$account = trim($this->get_account('fileserve.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		//==== Fix link FS ====
		$gach = explode('/',$url);
		if (count($gach) > 4) $url = "http://fileserve.com/file/".$gach[4];
		//==== Fix link FS ====
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("fileserve.com");
			else {
				$cookie = preg_replace("/(phpsessid=|Phpsessid=|PHPSESSID=)/","",$cookie);
				$cookie = "PHPSESSID=".$cookie;
			}
			if(!$cookie){
				$post['loginUserName'] = $user;
				$post['loginUserPassword'] = $pass;
				$post['loginFormSubmit'] = "Login";
				$data = $this->curl("http://www.fileserve.com/login.php",$cookie,$post);
				$cookie = $this->GetCookies($data);
				$this->save_cookies("fileserve.com",$cookie);
			}
			$this->cookie = $cookie;
			$data = $this->curl($url,$cookie,"");
			if(preg_match ( '/ocation: (.*)/', $data, $linkpre)){
				$link = trim ( $linkpre[1] );
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			elseif (stristr($data,"File not available")) {
				$report = Tools_get::report($Original,"dead");
				break;
			}
			else {
				$cookie = ""; 
				$this->save_cookies("fileserve.com","");
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