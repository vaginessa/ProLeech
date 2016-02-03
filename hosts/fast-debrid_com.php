<?php
$account = trim($this->get_account('fast-debrid.com'));
if (stristr($account,':')) list($user, $pass) = explode(':',$account);
else $cookie = $account;
if(empty($cookie)==false || ($user && $pass)){
	for ($j=0; $j < 2; $j++){
		if(!$cookie) $cookie = $this->get_cookie("fast-debrid.com");
		if(!$cookie) {
			$data = $this->curl("https://www.fast-debrid.com/lib/ajax/connection.php","","username=$user&password=$pass");
			if(preg_match('%(fast=.+);%U', $data, $cook)){
				$cookie = $this->GetAllCookies($data);
				$this->save_cookies("fast-debrid.com",$cookie);
			}
		}
		$this->cookie = $cookie;
		if(strpos($url,"|")) {
			$linkpass = explode('|', $url); 
			$url = $linkpass[0]; $pass = $linkpass[1];
		}
		$data = $this->curl("https://www.fast-debrid.com/debi.php",$cookie,"liens=$url&&directdl=0&display_filename=1&display_details=0&vision=download&pass$pass");

		if (preg_match('%(http:\/\/.+fast-debrid\.com/.+)">%U', $data, $linkpre)){
			$link = trim($linkpre[1]);
			$size_name = Tools_get::size_name($link, $this->cookie);
			if($size_name[0] <= 0) {
				$data = $this->curl($link,$this->cookie,"");
				$gach = explode('/',$link);
				if (count($gach) > 7) $link = "http://".$gach[2];
				if(preg_match('/ocation: (.*)/', $data, $match))$link = trim($link.$match[1]);
				$size_name = Tools_get::size_name($link, $this->cookie);
			}
			if($size_name[0] > 1024 ){
				$filesize =  $size_name[0];
				$filename = $size_name[1];
				break;
			}
			else $link='';
		}
		else {
			$cookie = "";
			$this->save_cookies("fast-debrid.com","");
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