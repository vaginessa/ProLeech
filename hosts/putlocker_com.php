<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?putlocker\.com/#', $url)){
	$account = trim($this->get_account('putlocker.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)) {
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("putlocker.com");
			$page = $this->curl($url,$cookie,"");
			$cookie = $cookie."; ".$this->GetCookies($page);
			$this->cookie = $cookie;
			if(preg_match('/href="(\/get_file.php.+)" class=/', $page, $redir)) {
				$linkpre = 'http://www.putlocker.com'.trim($redir[1]);
				$data = $this->curl($linkpre, $cookie, "");
				if(preg_match('/ocation: (.*)/', $data, $redir2)) $link = trim($redir2[1]);
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				if(preg_match('%<h1>(.*)<strong>%U', $page, $matches)) $filename = $matches[1];
				else $filename = $size_name[1];
				break;
			}
			else {
				$cookie = ""; 
				$this->save_cookies("putlocker.com","");
			}
		}
	}
}
/*
* Home page: http://vinaget.us
* Blog:	http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.6.3
* Created: France
*/
?>