<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?fileopic\.com/#', $url)){
	$account = trim($this->get_account('fileopic.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("fileopic.com");
			if(!$cookie){
				$post = array();
				$post['login'] = $user;
				$post['password'] = $pass;
				$x = rand(0,30);
				$y = rand(0,20);
				$data =  $this->curl("fileopic.com","",'op=login&redirect=http%3A%2F%2fileopic.com%2F&login='.$user.'&password='.$pass.'&x='.$x.'&y='.$y);
				$cookie = $this->GetCookies($data);
				$this->save_cookies("fileopic.com",$cookie);
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
				$this->save_cookies("fileopic.com","");
			}
		}
	}
}


/*
# megarapido.net
*/
?>