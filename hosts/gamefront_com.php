<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?gamefront\.com/#', $url)) {
	$page = $this->curl($url,"","");
	$cookie = $this->GetCookies($page);
	$this->cookie = $cookie;

	if (preg_match('/<a href="(.*)" class="downloadNow" id="downloadLink">/i', $page, $redir)) {
		$data = $this->curl($redir[1],$cookie,"");
		if (preg_match("/var downloadUrl = '(.*)';/i", $data, $redir2)) {
			$link = trim($redir2[1]);
		} 
	} 
	if($link){
		$size_name = Tools_get::size_name($link, $this->cookie);
		if(preg_match('%<title>Downloading (.*) | Game Front</title>%U', $data, $matches)) $filename = $matches[1];
		else $filename = $size_name[1];
		$filesize = $size_name[0];
	}
	else {
		$cookie = "";
		$this->save_cookies("gamefront.com","");
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