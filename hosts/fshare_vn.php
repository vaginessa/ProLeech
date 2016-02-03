<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?fshare\.vn/#', $url)){
	$account = trim($this->get_account('fshare.vn'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)) {
		$password = "";
		if(strpos($url,"|")) {
			$linkpass = explode('|', $url); 
			$url = $linkpass[0];
			$password = $linkpass[1];
		}
		if (isset($_POST['password'])) $password = $_POST['password'];
		if ($password) {
			$page = $this->curl($url, "", "");
       			$file_id = $this->cut_str($page, 'name="file_id" value="', '"');
        		$post['link_file_pwd_dl'] = $password;
			$post['action'] = "download_file";
			$post['file_id'] = $file_id;
		}
		else $post = "";
		for ($j=0; $j < 2; $j++) {
			if(!$cookie) $cookie = $this->get_cookie("fshare.vn");
			if(!$cookie){
				$post['login_useremail'] = $user;
				$post['login_password'] = $pass;
				$post['url_refe'] = $url;
				$post['auto_login'] = '1';
				$page = $this->curl("https://www.fshare.vn/login.php", "", $post);
				$cookie = $this->GetCookies($page);
				if(preg_match('/fshare_userpass=(.*); (.*); fshare_userid=(.*)/', $cookie, $temp)) {
					$cookie = "fshare_userpass=$temp[1]; fshare_userid=$temp[3]";
					$this->save_cookies("fshare.vn",$cookie);
				}
			}
			$this->cookie = $cookie;
			$page = $this->curl($url, $cookie, $post); 
			if (preg_match('/ocation: (http:\/\/.+.fshare\.vn\/vip\/.+)/i', $page, $redir)) $link = trim($redir[1]);
			elseif(preg_match("%'(http:\/\/.+.fshare\.vn\/vip\/.+)'%U", $page, $redir2)) $link = trim($redir2[1]);
			elseif(stristr($page,'<font color="RED">')) die("Tài khoản này đang được sử dụng trên máy khác (hoặc IP khác), bạn không được phép download lúc này.");
			elseif(strpos($page,'fieldset class="m_t_10" style=""')) die(_reportpass);
			if($link){
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			else {
				$cookie = ""; 
				$this->save_cookies("fshare.vn","");
			}
		}
	}
}
/*
* Home page: http://vinaget.us
* Blog:	http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.6.3
* Created: France
*/
?>