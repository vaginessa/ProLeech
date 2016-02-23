<?php
$account = trim($this->get_account('uploaded.net'));
if (stristr($account,':')) list($user, $pass) = explode(':',$account);
else $cookie = $account;
$maxacc = count($this->acc['uploaded.net']['accounts']);//checking number of accounts
if($maxacc > 0){//more than 0 accounts
	$n = rand(0,$maxacc);
	for ($k=0; $k < $maxacc; $k++){
		$account = trim($this->acc['uploaded.net']['accounts'][$n%$maxacc]);
		$n++;
		if (stristr($account,':')) list($user, $pass) = explode(':',$account);
			if(!$cookie) $cookie = $this->get_cookie("uploaded.net");
			if(!$cookie){
				$data = $this->lib->curl("http://uploaded.net/io/login", "", "id={$user}&pw={$pass}");
				$cookie = $this->GetCookies($data);
				$this->save_cookies("uploaded.net",$cookie);
			}
			$this->cookie = $cookie;
			$data = $this->curl($url,$cookie, "");
			if (preg_match('/(https?:.+)/i', $data, $link))
			$link=trim($link[1]);
			$size_name = Tools_get::size_name($link, "");
			$filesize =  $size_name[0];
			$filename = $size_name[1];
			if($filesize=="-1")
			{
				continue;
			}
			if($link) break;
	}
}
?>
