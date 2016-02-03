<?php
$account = trim($this->get_account('debridmax.com'));
if (stristr($account,':')) list($user, $pass) = explode(':',$account);
else $cookie = $account;
if(empty($cookie)==false || ($user && $pass)){
	for ($j=0; $j < 2; $j++){
		if(!$cookie){
			$data = $this->curl("http://www.debridmax.com/login.php","","usr_email=$user&pwd=$pass&doLogin=Connexion");	
			$cookie = $this->GetCookies($data);
			$this->save_cookies("debridmax.com",$cookie);
		}
		$this->cookie = $cookie;
		if(strpos($url,"|")) {
			$linkpass = explode('|', $url); 
			$url = $linkpass[0]; $pass = $linkpass[1];
		}
		$data = $this->curl("http://www.debridmax.com/multimax/p.php",$cookie,"hotlink=$url&pass=$pass&t=".rand(1, 3));

		if (stristr($data,"http://www.debridmax.com/premium.php")) $report = Tools_get::report($url,"disabletrial");
		elseif(preg_match('%(http:\/\/s.+debridmax\.com/max/.+)"%U', $data, $linkpre)){
			$link = trim($linkpre[1]);
			$size_name = Tools_get::size_name($link, $this->cookie);
			if($size_name[0] > 500 ){
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			else $link='';
		}
		else {
			$cookie = ""; 
			$this->save_cookies("debridmax.com","");
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