<?php
if (preg_match('#^http://(www\.)?unibytes\.com/#', $url)){
	$account = trim($this->get_account('unibytes.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("unibytes.com");
			if(!$cookie){
				$post['lb_login'] = $user;
				$post['lb_password'] = $pass;
				$data = $this->curl($url,"",$post);
				$cookie = $this->GetCookies($data);
				$this->save_cookies("unibytes.com",$cookie);
			}
			$this->cookie = $cookie;
			$data = $this->curl($url,$cookie,"");
			if(preg_match('/<a href="(http:\/\/([a-z0-9]+)\.unibytes\.com\/download\/.+)">Download file/i', $data, $redir)) {
				$link = trim($redir[1]);
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			elseif (stristr($data,'File not found or removed')) die(Tools_get::report($Original,"dead"));
			else {
				$cookie = "";
				$this->save_cookies("unibytes.com","");
			}
		}
	}
}
/*
* Home page: http://vinaget.us
* Blog:	http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.6.3
* Created: France
*/
?>