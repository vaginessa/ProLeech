<?php
if (preg_match('#^http://www.letitbit.net/#', $url) || preg_match('#^http://letitbit.net/#', $url)){
	$account = trim($this->get_account('letitbit.net'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("letitbit.net");
			if(!$cookie) {
				$data = $this->curl("http://letitbit.net/","lang=en","act=login&login=".urlencode($user)."&password=$pass");
				$cookie = $this->GetCookies($data);
				$this->save_cookies("letitbit.net",$cookie);
			}
			if (stristr($cookie,'prekey')) {
				$cookie = preg_replace("/(;|prekey=)/","",$cookie);
				$data = $this->curl($url,"lang=en","");
				$cookies = $this->GetCookies($data);
				$data2 = $this->cut_str ($data, '<form action="/sms/', '/form');
				if(preg_match_all('/input type="hidden" name="(.*?)" value="(.*?)"/i', $data2, $value)) {
					$max =count($value[1]);
					$post = "";
					for ($k=0; $k < $max; $k++){
						$post .= $value[1][$k].'='.$value[2][$k].'&';
					}
					$post .= 'pass='.$cookie;
					$data = $this->curl("http://letitbit.net/sms/check2.php",$cookies,$post);
					$cookies = $this->GetCookies($data);
					$this->cookie = $cookies;
					if (stristr($data,'direct_link_2')) {
						$data2 = trim ($this->cut_str ($data, "direct_link_1", "direct_link_2" ));
						if (preg_match('%(http:\/\/.+)" :%U', $data2, $value)) $link2 = trim($value[1]);
					}
					$data1 = trim ($this->cut_str ($data, "var direct_links", "direct_link_1" ));
					if (preg_match('%(http:\/\/.+)" :%U', $data1, $value)) {
						$link = trim($value[1]);
						$size_name = Tools_get::size_name($link, $this->cookie);
						if($size_name[0] > 200) {
							$filesize = $size_name[0];
							$filename = $size_name[1];
						}
						elseif(isset($link2)) {
							$link = $link2;
							$size_name = Tools_get::size_name($link, $this->cookie);
							$filesize = $size_name[0];
							$filename = $size_name[1];
						}
						break;
					}
					elseif (stristr($data,'The file is temporarily unavailable for download')) die(Tools_get::report($Original,"dead"));
					else {
						$cookie = "";
					}
				}
			}
			else {
				$data = $this->curl($url,$cookie,"");
				if (stristr($data,'Registration</a></li>')) {
					$cookie = "";
					$this->save_cookies("letitbit.net","");
					continue;
				}
				$this->cookie = $cookie.';'.$this->GetCookies($data);
				if(preg_match ( '/ocation: (http:\/\/u([0-9].*?)\.letitbit.net\/download\/.+)/', $data, $linkpre)) $check2 = trim($linkpre[1]);
				else continue;
				$data = $this->curl($check2,$this->cookie,"");
				$this->cookie = $cookie.';'.$this->GetCookies($data);
				if(preg_match ( '/ocation: (http:\/\/u([0-9].*?)\.letitbit.net\/sms\/check2.php)/', $data, $linkpre)) $check3 = trim($linkpre[1]);
				else continue;
				$data = $this->curl($check3,$this->cookie,"");
				if (stristr($data,'direct_link_2')) {
					$data2 = trim ($this->cut_str ($data, "direct_link_1", "direct_link_2" ));
					if (preg_match('%(http:\/\/.+)" :%U', $data2, $value)) $link2 = trim($value[1]);
				}
				$data1 = trim ($this->cut_str ($data, "var direct_links", "direct_link_1" ));
				if (preg_match('%(http:\/\/.+)" :%U', $data1, $value)) {
					$link = trim($value[1]);
					$size_name = Tools_get::size_name($link, $this->cookie);
					if($size_name[0] < 200 && isset($link2)) $link = $link2;
					else {
						$filesize = $size_name[0];
						$filename = $size_name[1];
					}
					break;
				}
				elseif (stristr($data,'The file is temporarily unavailable for download')) {
					echo Tools_get::report($url,"dead");
					exit;
				}
				else {
					$cookie = "";
					$this->save_cookies("letitbit.net","");
				}
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
* Updated:
		- Updated by ..:: [H] ::.. (Monday, October 22, 2012)
*/
?>