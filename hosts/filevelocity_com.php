<?php
if (preg_match('#^http://(www\.)?filevelocity\.com/#', $url)){
	$account = trim($this->get_account('filevelocity.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("filevelocity.com");
			if(!$cookie){
				$data = $this->curl("http://filevelocity.com/login.php","","username=$user&password=$pass&cookie=on&submit=Login+to+filevelocity");
				$cookie = $this->GetCookies($data);
				$this->save_cookies("filevelocity",$cookie);
			}
			$this->cookie = $cookie;
			$data =  $this->curl($url,$cookie,"");
			if(preg_match('/ocation: *(.*)/i', $data, $redir)) $link = trim($redir[1]);
			elseif(preg_match('%(http:\/\/.+filevelocity\.com/download.php)"%U', $data, $value)) {
				$loginurl = trim($value[1]);
				if(preg_match_all('/input type="hidden" name="(.*?)" value="(.*?)"/i', $data, $value)) {
					$max =count($value[1]);
					$post = "";
					for ($k=0; $k < $max; $k++){
						$post .= $value[1][$k].'='.$value[2][$k].'&';
					}
					$data =  $this->curl($loginurl,$cookie,$post);
					if(preg_match('/ocation: *(.*)/i', $data, $redir)){
						$link = trim($redir[1]);
						$size_name = Tools_get::size_name($link, $this->cookie);
						if($size_name[0] < 200 ){
							$data =  $this->curl($link,$cookie,'');
							if(preg_match('/ocation: *(.*)/i', $data, $redir)) $link = trim($redir[1]);
						}
					}
				}
			}
			else if (stristr($data,'Selected file not found')) die(Tools_get::report($Original,"dead"));
			if($link) {
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize =  $size_name[0];
				$filename = $size_name[1];
				break;
			}
			else {
				$cookie = "";
				$this->save_cookies("filevelocity.com","");
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