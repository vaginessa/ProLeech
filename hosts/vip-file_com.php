<?php
if (strpos($url,"vip-file.com")){
	$account = trim($this->get_account('vip-file.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $keycode = $account;
	
	if(empty($keycode)==false || ($user && $pass)){
		
			$data = $this->curl($url,'','');
			if(strpos($data,'302 Found'))
				{
					$data = explode('Location:',$data);
					$data = explode('X-My-Name:',$data[1]);
					$data = trim($data[0]);
					$data = $this->curl($data,'','');
				}
			$cookie = $this->GetAllCookies($data);
			$this->cookie = $cookie;
			$ul = explode('/',$url);
			$sv = $ul[2];
			$post_url = 'http://'.$sv.'/sms/check2.php?lang=en';
			
				
				if (preg_match('%<input type="hidden" name="uid5" value="(.*)"%U', $data, $value)) $post["uid5"] = $value[1];
				if (preg_match('%<input type="hidden" name="uid" value="(.*)"%U', $data, $value)) $post["uid"] = $value[1];
				if (preg_match('%<input type="hidden" name="id" value="(.*)"%U', $data, $value)) $post["id"] = $value[1];
				if (preg_match('%<input type="hidden" name="index" value="(.*)"%U', $data, $value)) $post["index"] = $value[1];
				if (preg_match('%<input type="hidden" name="seo_name" value="(.*)"%U', $data, $value)) $post["seo_name"] = $value[1];
				if (preg_match('%<input type="hidden" name="name" value="(.*)"%U', $data, $value)) $post["name"] = $value[1];
				if (preg_match('%<input type="hidden" name="pin" value="(.*)"%U', $data, $value)) $post["pin"] = $value[1];
				if (preg_match('%<input type="hidden" name="realuid" value="(.*)"%U', $data, $value)) $post["realuid"] = $value[1];
				if (preg_match('%<input type="hidden" name="realname" value="(.*)"%U', $data, $value)) $post["realname"] = $value[1];
				if (preg_match('%<input type="hidden" name="host" value="(.*)"%U', $data, $value)) $post["host"] = $value[1];
				if (preg_match('%<input type="hidden" name="ssserver" value="(.*)"%U', $data, $value)) $post["ssserver"] = $value[1];
				if (preg_match('%<input type="hidden" name="sssize" value="(.*)"%U', $data, $value)) $post["sssize"] = $value[1];
				if (preg_match('%<input type="hidden" name="dir" value="(.*)"%U', $data, $value)) $post["dir"] = $value[1];
				if (preg_match('%<input type="hidden" name="optiondir" value="(.*)"%U', $data, $value)) $post["optiondir"] = $value[1];
				if (preg_match('%<input type="hidden" name="pin_wm" value="(.*)"%U', $data, $value)) $post["pin_wm"] = $value[1];		
				$post['pass']= $keycode;
				$post['submit_sms_ways_have_pass'] = 'Download+file';
			
				$data = $this->curl($post_url,$cookie,$post);
		//	echo urldecode($data); exit;
			if(strpos($data,'Link to the file download'))
			{
				$lik = explode('<a target="_blank" title="Link to the file download" href="',$data);
				$lik = explode('"',$lik[1]);
				$lik = $lik[0];
				$link = trim($lik);
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				$filename=str_replace("/(;?=|;?)/","",$filename);
				//break;
			}
						
			
			else {
				$cookie = ""; 
				$this->save_cookies("vip-file.com","");
			}
		
	}
}


/*
* Home page: http://vietget.net
* Script Name: Vinaget
* Version: 2.6.3
* Created: ..:: BluE HearT ::..
*/
?>