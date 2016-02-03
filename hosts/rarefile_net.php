<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?rarefile\.net/#', $url)){
	$account = trim($this->get_account('rarefile.net'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("rarefile.net");
			if(!$cookie){
				$post = array();
				$post['login'] = $user;
				$post['password'] = $pass;
				$x = rand(0,30);
				$y = rand(0,20);
				$data =  $this->curl("rarefile.net","",'op=login&redirect=http%3A%2F%2Frarefile.net%2F&login='.$user.'&password='.$pass.'&x='.$x.'&y='.$y);
				$cookie = $this->GetCookies($data);
				$this->save_cookies("rarefile.net",$cookie);
			}
			$this->cookie = $cookie;
			$data =  $this->curl($url,$cookie,"");
			if (stristr($data,'<b>File Not Found</b>')) die(Tools_get::report($Original,"dead"));
			if(preg_match('/ocation: *(.*)/i', $data, $redir)){
				$link = trim($redir[1]);
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			else {
				$cookie = "";
				$this->save_cookies("rarefile.net","");
			}
		}
	}
}


/*
# megarapido.net
*/
?>