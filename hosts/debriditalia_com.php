<?php
$account = trim($this->get_account('debriditalia.com'));
if (stristr($account, ":")) list($user, $pass) = explode(":", $account);
else $cookie = $account;
if(empty($cookie)==false || ($user && $pass)) {
	for ($j=0; $j < 2; $j++){
		if(!$cookie) $cookie = $this->get_cookie("debriditalia.com");
		if(!$cookie) {
			$data = $this->curl("http://debriditalia.com/login.php?u=".urlencode($user)."&p=".urlencode($pass)."&sid=".time(), "", "");
			$cookie = $this->GetCookies($data);
			$this->save_cookies("debriditalia.com", $cookie);
		}
		$this->cookie = $cookie;
		$data = $this->curl("http://debriditalia.com/linkgen2.php", $cookie, "xjxfun=convertiLink&xjxr=".time()."&xjxargs[]=S<![CDATA[".urlencode($url)."]]>&xjxargs[]=S&xjxargs[]=Slink0&xjxargs[]=S&xjxargs[]=S");
		if(preg_match('%"(http:\/\/[^\r\n\"]+)"%U', $data, $linkpre)) {
			$link = trim($linkpre[1]);
			$size_name = Tools_get::size_name($link, $this->cookie);
			if($size_name[0] > 200) {
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			else {
				$data = $this->curl($link, $cookie, "");
				if(preg_match('/ocation: (.*)/i', $data, $linkpre)) {
					$link = trim($linkpre[1]);
					$size_name = Tools_get::size_name($link, $this->cookie);
					if($size_name[0] > 200) {
						$filesize = $size_name[0];
						$filename = $size_name[1];
						break;
					}
					else $link = "";
				}
			}
		}
		else {
			$cookie = "";
			$this->save_cookies("debriditalia.com", "");
		}
	}
}
/*
* Home page: http://vinaget.us
* Blog:	http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.6.3
* Created: Erkan_2034
*/
?>