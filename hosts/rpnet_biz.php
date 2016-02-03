<?php
$account = trim($this->get_account('rpnet.biz'));
if (stristr($account,':')) list($user, $pass) = explode(':',$account);
else $cookie = $account;
if(empty($cookie)==false || ($user && $pass)){
	for ($j=0; $j < 2; $j++){
		if(!$cookie) $cookie = $this->get_cookie("rpnet.biz");
		if(!$cookie){
			$data = $this->curl("https://premium.rpnet.biz/login.php","","login=&username=".$user."&password=".$pass);
			$cookie = $this->GetCookies($data);
		}
		$this->cookie = $cookie;
		if(strpos($url,"|")) {
			$linkpass = explode('|', $url); 
			$url = $linkpass[0]; $pass = $linkpass[1];
		}
		$data = $this->curl("https://premium.rpnet.biz/usercp.php?action=downloader",$cookie,"download=&links=$url");
		if(preg_match('/id = "links">(.*)<\/textarea/i', $data, $linkpre)) {
			$link = trim($linkpre[1]);
			$size_name = Tools_get::size_name($link, $this->cookie);
			if($size_name[0] > 200 ){
				$filesize =  $size_name[0];
				$filename = $size_name[1];
				break;
			}
			else $link='';
		}
		else {
			$cookie = "";
			$this->save_cookies("rpnet.biz","");
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