<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?dailymotion\.com/#', $url)){
	$data = $this->curl($url,"","");

	if (preg_match('@"hqURL":"([^|\r|\n|"]+)@i', urldecode($data), $match)) {
		$redir = trim($match[1]);
	}
	elseif (preg_match('@"(?:sd)?URL":"([^|\r|\n|"]+)@i', urldecode($data), $match)) {
		$redir = trim($match[1]);
	}
	if($redir) {
		$redir = str_replace("\/", "/", $redir);
		$page = $this->curl($redir,"","");
		if (preg_match('/ocation: (.*)/', $page, $match)) {
			$link = trim($match[1]);
			$size_name = Tools_get::size_name($link, "");
			$filesize = $size_name[0];
			if(preg_match('%<title>(.*) - Video Dailymotion</title>%U', $data, $matches)) $filename = $matches[1].".mp4";
			else $filename = $size_name[1];
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