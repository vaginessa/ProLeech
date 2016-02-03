<?php
if (preg_match('#^http:\/\/(www.)?uploadable\.ch/#', $url)) {
	$account = trim($this->get_account('uploadable.ch'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie){
				$data =  $this->curl("http://www.uploadable.ch/login.php","","action__login=normalLogin&userName=$user&userPassword=$pass&autoLogin=");
				$cookie = $this->GetCookies($data);
			}
			$this->cookie = $cookie;
			$data =  $this->curl($url,$cookie,"");
			if (stristr($data,'File Not Found')) die(Tools_get::report($Original,"dead"));
			if (preg_match('/ocation: (.*)/',$data,$match)) {
				$link = trim($match[1]);
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}else{
				$cookie = "";
				$this->save_cookies("uploadable.ch","");
			}
		}
	}
}
/*
* Home page: http://vinaget.us
* Script Name: getlink4u.info
* Version: 2.6.3
* Created: ..:: [thanhhbaker] ::..
*/
?>