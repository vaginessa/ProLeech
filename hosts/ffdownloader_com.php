<?php
$account = trim($this->get_account('ffdownloader.com'));
if (stristr($account,':')) list($user, $pass) = explode(':',$account);
else $cookie = $account;
if(empty($cookie)==false || ($user && $pass)){
	for ($j=0; $j < 2; $j++){
		if(!$cookie) $cookie = $this->get_cookie("ffdownloader.com");
		if(!$cookie){
			$data = $this->curl("https://ffdownloader.com/app.php/login_panel","hl=en;","hl=en");
			$data = str_replace('\\','',$data);
			if(!preg_match('%csrf_token" value="(.*)"%U', $data, $token)) die('plugin error');
			$cookie = $this->GetCookies($data);
			$data = $this->curl("https://ffdownloader.com/app.php/login_check",$cookie,'_csrf_token='.trim($token[1]).'&_email='.$user.'&_password='.$pass);	
			$cookie = $cookie.';'.$this->GetCookies($data);
			$this->save_cookies("ffdownloader.com",$cookie);
		}
		$this->cookie = $cookie;
		if(strpos($url,"|")) {
			$linkpass = explode('|', $url); 
			$url = $linkpass[0]; $pass = $linkpass[1];
		}
		$data = $this->curl('https://ffdownloader.com/app.php/_generateLink',$cookie,'link='.urlencode($url).'&id=0',0);
		$page = json_decode($data, true);
		if (isset($page['result']) && $page['result'] == true) {
			$link = trim($page['generateLink']);
			$size_name = Tools_get::size_name($link, $this->cookie);
			if($size_name[0] > 300 ){
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			else $link='';
		}
		if (isset($page['result']) && $page['result'] == false) die($page['message']);
		else {
			$cookie = ""; 
			$this->save_cookies("ffdownloader.com","");
		}
	}
}


/*
* Home page: http://vinaget.us
* Blog:	http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.6.3
* Created: ..:: [H] ::.. 
* 
*/
?>