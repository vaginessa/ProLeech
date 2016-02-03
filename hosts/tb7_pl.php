<?php
$account = trim($this->get_account('tb7.pl'));
if (stristr($account,':')) list($user, $pass) = explode(':',$account);
else $cookie = $account;
if(empty($cookie)==false || ($user && $pass)){
	for ($j=0; $j < 2; $j++){
		if(!$cookie) $cookie = $this->get_cookie("tp7.pl");
		if(!$cookie){
			$data = $this->curl("http://tb7.pl/login","","login=".urlencode($user)."&password=".urlencode($pass));
			if(preg_match("%session=(.*);%U", $data, $matches)) {
				$cookie = 'session='.$matches[1].';';
			}
			if(preg_match("%autologin=(.*);%U", $data, $matches)) {
				$cookie .= 'autologin='.$matches[1].';';
			}
			$this->save_cookies("tb7.pl",$cookie);
		}
		$this->cookie = $cookie;
		if(strpos($url,"|")) {
			$linkpass = explode('|', $url); 
			$url = $linkpass[0]; $pass = $linkpass[1];
		}
		$data = $this->curl("http://tb7.pl/mojekonto/sciagaj",$cookie,"step=1&content=".$url,0);
		if(preg_match("%<span class=\"name\">(.*)<\/span>%U", $data, $matches)) {
				$filename = $matches[1];
			}
		if(preg_match("%Rozmiar: (.*)<\/label>%U", $data, $matches)) {
			$filesize = $matches[1];
		}
		$data = $this->curl("http://tb7.pl/mojekonto/sciagaj",$cookie,"step=2&0=on",0);
		//$page = json_decode($data, true);

		if (stristr($data,"disable for trial account")) $report = Tools_get::report($url,"disabletrial");
		elseif (stristr($data,"Ip not allowed")) die("<font color=red><b>Ip host have been banned by tb7.pl !</b></font>");
		elseif(preg_match('%<a href="(.*)" target="_blank">Pobierz</a>%U', $data, $matches)) {
			$link = $matches[1];
			$data = $this->curl($link, "", "");
			if(preg_match('/ocation: (.*)/', $data, $match)) {
				$link = $match[1];
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