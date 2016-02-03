<?php
$account = trim($this->get_account('downmasters.com'));
if (stristr($account,':')) list($user, $pass) = explode(':',$account);
else $cookie = $account;
if(empty($cookie)==false || ($user && $pass)){
	for ($j=0; $j < 2; $j++){
		if(!$cookie) $cookie = $this->get_cookie("downmasters.com");
		if(!$cookie){
			$data = $this->curl('http://downmasters.com/dologin.php',"",'username='.$user.'&password='.$pass);
			if(stristr($data,'memberszone.php')) {
				$j=2;
				$cookie = $this->GetCookies($data);
				$this->save_cookies("downmasters.com",$cookie);
			}
			else die('Error Login');
		}
		$this->cookie = $cookie;
		$data = $this->curl("http://downmasters.com/ajax.php",$this->cookie,'showlinks=0&links='.$url);
		if (stristr($data,"The host is not supported or check the link")) die("<font color=red><b>The host is not supported or check the link</b></font>");
		if(preg_match('%<ul><li><a href="(.*)" target="_blank">Download%U', $data, $matches)){
			$link = trim($matches[1]);
			$size_name = Tools_get::size_name($link, $this->cookie);
			if($size_name[0] > 200 ){
				$filesize =  round($size_name[0]/(1024*1024),2)." MB";
				$filename = $size_name[1];
			}
			else continue;
			break;
		}
		else {
			$cookie = "";
			$this->save_cookies("downmasters.com","");
		}
	}
}

/*
* Home page: http://vinaget.us
* Script Name: Vinaget 
* Version: gate3
* Created: ..:: [H] ::.. 
* Updated: Saturday, January 26, 2013
*/
?>