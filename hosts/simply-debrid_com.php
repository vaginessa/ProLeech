<?php
$account = trim($this->get_account('simply-debrid.com'));
if (stristr($account,':')) list($user, $pass) = explode(':',$account);
else $cookie = $account;
if(empty($cookie)==false || ($user && $pass)){
	for ($j=0; $j < 2; $j++){
		if(!$cookie) $cookie = $this->get_cookie("simply-debrid.com");
		if(!$cookie){
		 $data = $this->curl("https://simply-debrid.com/api.php","","login=1&u=$user&p=$pass");
			$cookie = $this->GetCookies($data);
			$this->save_cookies("simply-debrid.com",$cookie);
		}
		$this->cookie = $cookie;
		$data = $this->curl("https://simply-debrid.com/api.php",$cookie,"dl=$url");
		if(preg_match('%(http\:\/\/.*?)%U', $data, $linkpre) && stristr($data,"sd.php")){
			$link = $linkpre[1];
			$size_name = Tools_get::size_name($link, $this->cookie);
			$filesize = $size_name[0];
			$filename = $size_name[1];
			break;
		}
		elseif (stristr($data,"Invalid link")) die('<font color=red><b>This Host Not Supported.</b></font>');
		elseif (stristr($data,"UNDER MAINTENANCE")) die('<font color=red><b>UNDER MAINTENANCE.</b></font>');
		else {
			$cookie = "";
			$this->save_cookies("simply-debrid.com","");
		}
	}
}


/*
* Home page: http://vinaget.us
* Blog:	http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.6.3
* Created: Unknown
* Modified: KulGuy
*/
?>