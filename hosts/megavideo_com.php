<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?megavideo\.com/#', $url)){
	$account = trim($this->get_account('megaupload.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("megaupload.com");
			if(!$cookie){
				$data =  $this->curl("http://www.megaupload.com/?c=account","","username=$user&password=$pass&login=1");
				if(preg_match('/^Set-Cookie: (.*?);/m', $data, $matches)){
					$cookie = $matches[1];
					$this->save_cookies("megaupload.com",$cookie);
				}
			}
			$cookie = preg_replace("/(user=|USER=|User=)/","",$cookie);
			$page = $this->curl($url,"user=$cookie","");
			if(preg_match("%previewplayer/\?(.*)&width%U", $page, $redir)) $w = $redir[1];
			else { 
				$ww = explode('?', $url);
				$w = $w[1];
			}
			$page = $this->curl("http://www.megavideo.com/xml/player_login.php?u=$cookie&".$w,"",""); 
			if(preg_match('%downloadurl="(.*)"%U', $page, $redir2)){
				$this->cookie = $cookie;
				$link= urldecode($redir2[1]);  
				if($link){
					$size_name = Tools_get::size_name($link, $this->cookie);
					$filename = $size_name[1];
					$filesize = $size_name[0];
					break;
				}
				else {
					$cookie = "";
					$this->save_cookies("megaupload.com","");
				}
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