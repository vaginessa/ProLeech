﻿<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?up\.4share\.vn/#', $url)){
	$account = trim($this->get_account('up.4share.vn'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)) {
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("up.4share.vn");
			if(!$cookie){
				$page = $this->curl("http://up.4share.vn/?control=login","","inputUserName=$user&inputPassword=$pass&rememberlogin=");
				$cookie = $this->GetCookies($page);
				$this->save_cookies("up.4share.vn",$cookie);
			}
			$this->cookie = $cookie;
			$page = $this->curl($url, $cookie, "");
			if(preg_match("/ocation: (http:\/\/.+\.4share\.vn\/.+)/", $page, $redir)) $link = trim($redir[1]);
			elseif(preg_match("%<a href='(http:\/\/.+\.4share\.vn\/.+)'>DOWNLOAD </a>%U", $page, $redir)) $link = trim($redir[1]);
			elseif(stristr($page,"File not found")){
				echo "ERROR: File not found (location)!";
				exit;
			}
			if($link){
				$link = str_replace("%2520","_",$link) ;
				$link = str_replace("%20","_",$link);
				$link = str_replace(" ","_",$link);
				$link = trim($link);
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			else {
				$cookie = ""; 
				$this->save_cookies("up.4share.vn","");
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