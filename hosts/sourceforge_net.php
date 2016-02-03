<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?sourceforge\.net/#', $url)){
		$account = trim($this->get_account('sourceforge.net'));
		if (stristr($account,':')) list($user, $pass) = explode(':',$account);
		else $cookie = $account;
		$password = "";
		if(strpos($url,"|")) {
			$linkpass = explode('|', $url); 
			$url = $linkpass[0]; $password = $linkpass[1];
		}
		if (isset($_POST['password'])) $password = $_POST['password'];
		#==== Fix link MF ====#
		$url = str_replace("download.php", "", $url);
		if(strpos($url,"www")==false) $url = str_replace("http://", "http://www.", $url);
		#==== Fix link MF ====#
		if(empty($cookie)==false || ($user && $pass)){
			for ($j=0; $j < 2; $j++){
				if(!$cookie) $cookie = $this->get_cookie("sourceforge.net");
				if(!$cookie){
					$data = $this->curl($url,"","");
					$cookie = $this->GetCookies($data);
					$post = array();
					$post['login_email'] = $user;
					$post['login_pass'] = $pass;
					$post['submit_login.x'] = rand(0,100);
					$post['submit_login.y'] = rand(0,20);
					$data = $this->curl("http://sourceforge.net/account/login.php",$cookie,$post);
					$cookie = $cookie . "; " . $this->GetCookies($data);
					$this->save_cookies("sourceforge.net",$cookie);
				}
				$this->cookie = $cookie;
				if(empty($_POST['recaptcha_challenge_field'])==FALSE && empty($_POST['recaptcha_response_field'])==FALSE){
					$key = $_POST['recaptcha_challenge_field'];
					$value = $_POST['recaptcha_response_field'];
					$page = $this->curl($url,$cookie,"recaptcha_challenge_field=$key&&recaptcha_response_field=$value");
				}
				else {
					if($password) $post = "downloadp=".$password;
					else $post = "";
					$page = $this->curl($url,$cookie,$post);
				}
				if(preg_match ('/ocation: (.*)/', $page, $linkpre)) $link = trim ($linkpre[1]);
				else {
					if(stristr($page,"6LextQUAAAAAALlQv0DSHOYxqF3DftRZxA5yebEe")) {
						$page = $this->curl("http://api.recaptcha.net/challenge?k=6LextQUAAAAAALlQv0DSHOYxqF3DftRZxA5yebEe","","");
						if(preg_match("%challenge : '(.*)'%U", $page, $matches)){
							echo '<center>
							<div class="captcha_box" id="captchaprotected" name="captchaprotected" style="display:full; padding: 10px;">
								<font color=#000000>Authentication Required<br>
								<form action="'.$this->self.'" id="form_captcha" method="POST" name="form_captcha"> 
								<div class="small" style="margin-bottom:5px;">Please complete the form below to download this file</font></div>
								<div id="captchaerror" style="margin-bottom:5px;"></div> 
								<div id="recaptcha_widget_div" class="recaptcha_nothad_incorrect_sol recaptcha_isnot_showing_audio" style="margin-bottom:10px" align="center">
									<img src="http://www.google.com/recaptcha/api/image?c='.$matches[1].'" /><br>
									<input type="text" name="recaptcha_response_field" value=""/ size="40" maxlength="50" />
									<input type="hidden" name="recaptcha_challenge_field" value="'.$matches[1].'" />
									<input type="hidden" name="urllist" value="'.$url.'" />							
								</div>
								<input type="submit" value="enter"/><br>
							</div></center>';
							exit;
						}
					}
					elseif(stristr($page,"dh('');")) die(_reportpass);
					elseif(stristr($page,"This file is temporarily unavailable because")) {
						$report = Tools_get::report($url,"filebig");
					}
					elseif (stristr($page,"Invalid or Deleted File")) {
						$report = Tools_get::report($Original,"dead");
						break;
					}	
					else {
						$page = $this->cut_str($page, 'YmI = "', 'output = "');
						if (preg_match('/kNO = "(http:\/\/.+)"/i', $page, $value)) {
							$link = $value[1];
							break;
						}
					}
					if(!$link) {
						$ookie = "";
						$this->save_cookies("sourceforge.net","");
					}
				}
			}
		}
		else {
			if(empty($_POST['recaptcha_challenge_field'])==FALSE && empty($_POST['recaptcha_response_field'])==FALSE){
				$key = $_POST['recaptcha_challenge_field'];
				$value = $_POST['recaptcha_response_field'];
				$page = $this->curl($url,"","recaptcha_challenge_field=$key&&recaptcha_response_field=$value");
			}
			else {
				if($password) $post = "downloadp=".$password;
				else $post = "";
				$page = $this->curl($url,$cookie,$post);

			}
			if(stristr($page,"6LextQUAAAAAALlQv0DSHOYxqF3DftRZxA5yebEe")) {
				$page = $this->curl("http://api.recaptcha.net/challenge?k=6LextQUAAAAAALlQv0DSHOYxqF3DftRZxA5yebEe","","");
				if(preg_match("%challenge : '(.*)'%U", $page, $matches)){
					echo '<center>
					<div class="captcha_box" id="captchaprotected" name="captchaprotected" style="display:full; padding: 10px;">
						<font color=#000000>Authentication Required<br> 
						<form action="'.$this->self.'" id="form_captcha" method="POST" name="form_captcha"> 
						<div class="small" style="margin-bottom:5px;">Please complete the form below to download this file</font></div>
						<div id="captchaerror" style="margin-bottom:5px;"></div> 
						<div id="recaptcha_widget_div" class="recaptcha_nothad_incorrect_sol recaptcha_isnot_showing_audio" style="margin-bottom:10px" align="center">
							<img src="http://www.google.com/recaptcha/api/image?c='.$matches[1].'" /><br>
							<input type="text" name="recaptcha_response_field" value=""/ size="40" maxlength="50" />
							<input type="hidden" name="recaptcha_challenge_field" value="'.$matches[1].'" />
							<input type="hidden" name="urllist" value="'.$url.'" />
						</div>
						<input type="submit" value="enter"/><br>
					</div></center>';
					exit;
				}
			}
			elseif(stristr($page,"dh('');")) die(_reportpass);
			elseif(stristr($page,"This file is temporarily unavailable because")) {
				$report = Tools_get::report($url,"filebig");
			}
			elseif (stristr($page,"Invalid or Deleted File")) {
				$report = Tools_get::report($Original,"dead");
			}		

			else {
				$page = $this->cut_str($page, 'YmI = "', 'output = "');
				if (preg_match('/kNO = "(http:\/\/.+)"/i', $page, $value)) $link = $value[1];
			}
		}
		if($link){
			$size_name = Tools_get::size_name($link, $this->cookie);
			$filesize = $size_name[0];
			$filename = $size_name[1];
		}
}
/*
* Home page: http://vinaget.us
* Blog:	http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.6.3
* Created: ..:: [H] ::..
*/
# plugin have been created from original plugin mediafire of okoze (rapidleech.com)
?>