<?php
if (preg_match('#^http://(www\.)?uploadhero\.com/#', $url)){
	$account = trim($this->get_account('uploadhero.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("uploadhero.com");
			if(!$cookie){
				$data = $this->curl("http://uploadhero.com/lib/connexion.php","lang=en","pseudo_login=$user&password_login=$pass");
				$cookie = $this->GetCookies($data);
				$this->save_cookies("uploadhero.com",$cookie);
			}
			$this->cookie = $cookie;
			$data = $this->curl($url,$cookie.";lang=en","");
			if (preg_match('/ocation: (.*)/',$data,$match)) $link = trim($match[1]);
			elseif (preg_match('%<a href="(.*)" ><div class="download">%U', $data, $redir2)) $link = trim($redir2[1]);
			if($link){
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			else {
				$cookie = "";
				$this->save_cookies("uploadhero.com","");
			}
		}
	}
}
/*
* Home page: http://vinaget.us
* Blog: http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.6.3
* Created: France
*/
?>