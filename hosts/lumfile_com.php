<?php
if (preg_match('#^http://(www\.)?lumfile\.com/#', $url)){
	$account = trim($this->get_account('lumfile.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("lumfile.com");
			if(!$cookie){
				$data = $this->curl("http://lumfile.com/login.html","lang=english","op=login&redirect=http%3A%2F%2Flumfile.com%2F&login=$user&password=$pass");
				$cookie = $this->GetCookies($data);
				$this->save_cookies("lumfile.com",$cookie);
			}
			$this->cookie = $cookie;
			$data =  $this->curl($url,$cookie.';lang=english;',"");
			if(preg_match('/ocation: *(.*)/i', $data, $redir)){
				$link = str_replace(" ","%20",trim($redir[1]));
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			elseif (stristr($data,'File Not Found')) die(Tools_get::report($Original,"dead"));
			else {
				$cookie = "";
				$this->save_cookies("lumfile.com","");
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