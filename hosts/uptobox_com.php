<?php
if (preg_match('#^http://([a-z0-9]+)\.uptobox\.com/#', $url) || preg_match('#^http://uptobox\.com/#', $url)){
	$account = trim($this->get_account('uptobox.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	$maxacc = count($this->acc['uptobox.com']['accounts']);
	if($maxacc > 0){
		for ($k=0; $k < $maxacc; $k++){
			$account = trim($this->acc['uptobox.com']['accounts'][$k]);
			if (stristr($account,':')) list($user, $pass) = explode(':',$account);
			if(!$cookie) $cookie = $this->get_cookie("uploaded.net");
			if(!$cookie){//get cookie by login
			}
			$this->cookie = $cookie;
			$data = $this->curl($url,$cookie, "");
			if (preg_match('/(https?:.+)/i', $data, $link))
			$link=trim($link[1]);
			$size_name = Tools_get::size_name($link, "");
			$filesize =  $size_name[0];
			$filename = $size_name[1];
			if($link) break;
		}
	}
}


// Fixed By MrBLAKEN [ FrenchDebrid.eu ] [ 16/03/2015 ]
?>
