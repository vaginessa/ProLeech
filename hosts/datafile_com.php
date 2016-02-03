<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?datafile\.com/#', $url)){
	$account = trim($this->get_account('datafile.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		$login = 'https://www.datafile.com/login.html';
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("datafile.com");
			if(!$cookie){
				$post = array();
				$post['login'] = $user;
				$post['password'] = $pass;
				$post['remember_me'] = 0;
				$post['remember_me'] = 1;
				$post['btn'] = '';
				$page = $this->curl($login,"",$post);
				if(strpos($page,'Incorrect login or password!')) Tools_get::report('Incorrect login or password!');
				$cookie = $this->GetAllCookies($page);
				$this->save_cookies("datafile.com",$cookie);
			}
			$this->cookie = $cookie;
			$profile = $this->curl('https://www.datafile.com/profile.html',$cookie,'');
			if(!strpos($page,'Premium Expires')) Tools_get::report('Free account !');
			$page=$this->curl($url,$cookie,"");
			if (!preg_match('/Location: (\/[^\s\t\r\n]+)/i', $page, $rd)) Tools_get::report('Error[Redirect Link - PREMIUM not found!]');
			$page = $this->curl('https://www.datafile.com'. $rd[1], $cookie, '');
			if (!preg_match('/Location: (https?:\/\/n\d+\.datafile\.com\/[^\s\t\r\n]+)/i', $page, $dl)) Tools_get::report('Error[Download Link - PREMIUM not found!]');
			$link = trim($dl[1]);
			$size_name = Tools_get::size_name($link, $this->cookie);
			$filesize = $size_name[0];
			$filename = $size_name[1];
			
		}
	}
}


/*
* Home page: http://www.vietget.net
* Script Name: Vinaget 
* Version: 2.6.3
* Created: ..:: VietGet.Net ::..
*/
?>