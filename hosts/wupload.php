<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?wupload\.([a-z]+)/#', $url)){
	$account = trim($this->get_account('wupload.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		$password = "";
		if(strpos($url,"|")) {
			$linkpass = explode('|', $url); 
			$url = $linkpass[0]; $password = $linkpass[1];
		}
		if (isset($_POST['password'])) $password = $_POST['password'];
		//==== Fix link ====
		$gach = explode('/', $url);
		if (count($gach)> 5) $url = 'http://www.wupload.com/file/' . $gach[4];
		$data = $this->curl($url,"","");
		if (preg_match('/Location: (.*)/', $data, $wulink)) {
			$url = trim($wulink[1]);
			$data = $this->curl($url,"","");
		}
		//==== Fix link ====	
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("wupload.com");
			if(!$cookie){
				$linkWU= explode('/file/', $url);
				$data = $this->curl($linkWU[0]."/account/login","","email=$user&redirect=%2F&password=$pass");
				$cookie = $this->GetCookies($data);
				if(preg_match('/lang=en; role=anonymous;(.*); PHPSESSID=(.*);/',$cookie,$temp)){
					$cookie = "PHPSESSID=$temp[2];";
					$this->save_cookies("wupload.com",$cookie);
				}
			}
			$cookie = preg_replace("/(phpsessid=|Phpsessid=|PHPSESSID=)/","",$cookie);
			$cookie = "PHPSESSID=".$cookie;
			$this->cookie = $cookie;
			if($password) $post = "passwd=".$password;
			else $post = "";
			$data = $this->curl($url,$cookie,$post);
			if(preg_match('/ocation: (.*)/', $data, $match)) {
				$link=trim($match[1]);
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				$link = $url;
				break;
			}			
			elseif (stristr($data,'6LdNWbsSAAAAAIMksu-X7f5VgYy8bZiiJzlP83Rl')){
				$data = $this->curl("http://api.recaptcha.net/challenge?k=6LdNWbsSAAAAAIMksu-X7f5VgYy8bZiiJzlP83Rl","","");
				if(preg_match("%challenge : '(.*)'%U", $data, $matches)){
					echo $report = Tools_get::report($matches[1],"captchawu");
					exit;
				}
			}
			elseif(strpos($data,"Please Enter Password")){
				echo "password protected";
				exit;
			}
			elseif(strpos($data,"The server is temporarily offline for maintenance")){
				echo _temporarily;
				exit;
			}
			elseif (stristr($data,"Sorry, this file has been removed")) {
				$report = Tools_get::report($Original,"dead");
				break;
			}
			else {
				$cookie = "";
				$this->save_cookies("wupload.com","");
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