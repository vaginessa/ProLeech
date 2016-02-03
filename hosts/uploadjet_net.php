<?php
if (preg_match('#^http://(www\.)?uploadjet\.net/#', $url)){
	$account = trim($this->get_account('uploadjet.net'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("uploadjet.net");
			if(!$cookie){
				$data = $this->curl("http://uploadjet.net/","","op=login&redirect=&login=$user&password=$pass");
				$cookie = $this->GetCookies($data);
				$this->save_cookies("uploadjet.net",$cookie);
			}
			$this->cookie = $cookie;
			$data = $this->curl($url,$cookie,"");
			if (preg_match('/ocation: (.*)/',$data,$match)) $link = trim($match[1]);
			elseif (preg_match('%input type="hidden" name="op" value="(.*)">%U', $data, $redir2)) {
				$post["op"] = $redir2[1];
				if (preg_match('%<input type="hidden" name="id" value="(.*)">%U', $data, $redir2)) $post["id"] = $redir2[1];
				if (preg_match('%<input type="hidden" name="rand" value="(.*)">%U', $data, $redir2)) $post["rand"] = $redir2[1];
				if (preg_match('%<input type="hidden" name="referer" value="(.*)">%U', $data, $redir2)) $post["referer"] = $redir2[1];
				if (preg_match('%<input type="hidden" name="method_free" value="(.*)">%U', $data, $redir2)) $post["method_free"] = $redir2[1];
				if (preg_match('%<input type="hidden" name="method_premium" value="(.*)">%U', $data, $redir2)) $post["method_premium"] = $redir2[1];
				if (preg_match('%<input type="hidden" name="down_direct" value="(.*)">%U', $data, $redir2)) $post["down_direct"] = $redir2[1];
				$post["x"] = rand(1,45);
				$post["y"] = rand(1,10);
				$data = $this->curl($url,$cookie,$post);
				if (preg_match('%"(http:\/\/.+uploadjet\.net.+\/d\/.+)"%U',$data, $redir2)) $link = trim($redir2[1]);
			}
			if($link){
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			else {
				$cookie = "";
				$this->save_cookies("uploadjet.net","");
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