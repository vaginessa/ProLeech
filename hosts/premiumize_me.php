<?php
$account = trim($this->get_account('premiumize.me'));
if (stristr($account,':')) list($user, $pass) = explode(':',$account);
else $cookie = $account;
if(empty($cookie)==false || ($user && $pass)){
	for ($j=0; $j < 2; $j++){
		$data = $this->curl("https://api.premiumize.me/pm-api/v1.php?method=directdownloadlink&params[login]=".$user."&params[pass]=".$pass."&params[link]=".$url, "", "", 0);
		$page = @json_decode($data, true);
		if ($page['status'] == 200) {
			if(isset($page['result']['location'])) {
				$link = trim($page['result']['location']);
				$size_name = Tools_get::size_name($link, "");
				if($size_name[0] > 200) {
					$filesize = $size_name[0];
					$filename = $size_name[1];
					break;
				}
				else $link = "";
			}
		}
		else {
			$cookie = "";
			$this->save_cookies("premiumize.me", "");
		}
	}
}
/*
* Home page: http://vinaget.us
* Blog:	http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.6.3
* Created: France
* Modified: KulGuy
*/
?>