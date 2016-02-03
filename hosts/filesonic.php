<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?filesonic\.([a-z]+)/#', $url)){
	$account = trim($this->get_account('filesonic.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		$password = "";
		if(strpos($url,"|")) {
			$linkpass = explode('|', $url); 
			$url = $linkpass[0]; $password = $linkpass[1];
		}
		if (isset($_POST['password'])) $password = $_POST['password'];
		//==== Fix link FSN ====
		$data = $this->curl($url,"","");
		if (preg_match('/Location: (.*)/', $data, $fsnlink)) {
			$url = trim($fsnlink[1]);
			$data = $this->curl($url,"","");
		}
		//==== Fix link FSN ====
		for ($j=0; $j < 2; $j++){
			if (stristr($data,'6LdNWbsSAAAAAIMksu-X7f5VgYy8bZiiJzlP83Rl')){
				$page = $this->curl("http://api.recaptcha.net/challenge?k=6LdNWbsSAAAAAIMksu-X7f5VgYy8bZiiJzlP83Rl","","");
				preg_match("%challenge : '(.*)'%U", $page, $matches);
				echo $report = Tools_get::report($matches[1],"captchafsn");
				exit;
			}
			if(!$cookie) $cookie = $this->get_cookie("filesonic.com");
			else {
				$cookie = preg_replace("/(phpsessid=|Phpsessid=|PHPSESSID=)/","",$cookie);
				$cookie = "PHPSESSID=".$cookie;
			}
			if(!$cookie){
				$linkFSN= explode('file/', $url);$urllogin = $linkFSN[0]."user/login";
				$post["email"]= $user;
				$post["password"]= $pass;
				$page = $this->curl($urllogin,"",$post);
				if(strpos($page,"Provided password does not match.") == false) {
					$cookies = $this->GetCookies($page);
					$cookie = explode(" ", $cookies);
					$cookie = $cookie[3];
					$this->save_cookies("filesonic.com",$cookie);
				}
			}
			$this->cookie = $cookie;
			if($password) $post = "passwd=".$password;
			else $post = "";
			$page = $this->curl($url,$cookie,$post); 
			if(preg_match('/ocation: (.*)/',$page,$match)){
				$link=trim($match[1]);
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				$link = $url;
				break;
			}
			elseif(strpos($page,"Please Enter Password")){
				if($this->linkcbox) echo '[B][color=red]'._reportpass.'[/color][/b][/center]\'></center><BR/>';
				echo _reportpass;
				echo "<center><form action='".$this->self."' method='post'><BR>
					<input type='text' id='password' name='password' width='500px'/><BR><BR>
					<input type=submit value='"._sbdown."' /></form><BR>
					</form></center>
				";
				exit;
			}
			elseif (stristr($page,"Sorry, this file has been removed")) {
				$report = Tools_get::report($Original,"dead");
				break;
			}
			else {
				$cookie = ""; 
				$this->save_cookies("filesonic.com","");
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