<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?megaupload\.com/#', $url)){
	$account = trim($this->get_account('megaupload.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		$password = "";
		if(strpos($url,"|")) {
			$linkpass = explode('|', $url); 
			$url = $linkpass[0]; $password = $linkpass[1];
		}
		if (isset($_POST['password'])) $password = $_POST['password'];
		if($password) $post = "filepassword=".$password;
		else $post = "";

		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("megaupload.com");
			if(!$cookie){
				$data =  $this->curl("http://www.megaupload.com/?c=account","","username=$user&password=$pass&login=1");
				if(preg_match('/^Set-Cookie: (.*?);/m', $data, $matches)){
					$cookie = $matches[1];
					$this->save_cookies("megaupload.com",$cookie);
				}
			}
			$cookie = preg_replace("/(user=|USER=|User=)/","",$cookie);
			$data=$this->curl($url,"user=".$cookie,$post);
			if(strpos($data,"password protected")) {
				echo "password protected";
				exit;
			}
			elseif (empty($data)) $report = Tools_get::report($Original,"svload");
			elseif (preg_match('/ocation: (.*)/',$data,$match)) $link = trim($match[1]);
			elseif (preg_match('%(http:\/\/.+megaupload\.com/files/.+)" class="download_premium_but"></a>%U', $data, $redir2)) $link = trim($redir2[1]);
			if($link){
				$this->cookie = $cookie;
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filename = $size_name[1];
				$filesize = $size_name[0];
			}
			else {
				$cookie = "";
				$this->save_cookies("megaupload.com","");
			}
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