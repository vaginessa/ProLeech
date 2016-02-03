<?php
$account = trim($this->get_account('rehost.to'));
if (stristr($account,':')) list($user, $pass) = explode(':',$account);
else $cookie = $account;
if(empty($cookie)==false || ($user && $pass)){
	for ($j=0; $j < 2; $j++){
		if(!$cookie) $cookie = $this->get_cookie("rehost.to");
		if(!$cookie){
			$data = $this->curl("http://rehost.to/index.php?page=myrehost&action=login", "", "user=".urlencode($user)."&pass=".urlencode($pass)."&login=Login");
			if(preg_match('%<a href="(.*)">Click</a>%U', $data, $matches)) {
				$data = $this->curl("http://rehost.to/".$matches[1],"","");
				$cookie = $this->GetCookies($data);
				if(preg_match("%long_ses=(.*);%U", $data, $matches)) {
					$cookie = $matches[1];
					$this->save_cookies("rehost.to",$cookie);
				}
			}
		}
		$cookie = preg_replace("/(long_ses=|Long_ses=|LONG_SES=)/","",$cookie);
		$this->cookie = "long_ses=".$cookie;
		if(strpos($url,"|")) {
			$linkpass = explode('|', $url); 
			$url = $linkpass[0];
			$pass = $linkpass[1];
		}
		$data = $this->curl("http://rehost.to/process_download.php?user=cookie&pass=".$cookie."&dl=".urlencode($url), "long_ses=".$cookie, "");
		if (preg_match('/ocation: *(.*)/i', $data, $redir)) {
			$link = trim($redir[1]);
			$size_name = Tools_get::size_name($link, $this->cookie);
			if($size_name[0] > 200) {
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			else $link = "";
		}
		else {
			$cookie = "";
			//$this->save_cookies("rehost.to","");
		}
	}
}
/*
* Home page: http://vinaget.us
* Blog:	http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.6.3
*/
?>