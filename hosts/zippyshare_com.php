<?php
if (preg_match('#^http:\/\/(www)?([0-9].+)(\.)?zippyshare.com/#', $url)) {
	if(strpos($url,"www")==false) $url = str_replace("http://", "http://www.", $url);
	$page = $this->curl($url,"","");
	$this->cookie = $this->GetCookies($page);

	if (preg_match_all("#var (\w) = (\d+);#", $page, $temp)) {
		$a = $temp[2][0];
		$b = $temp[2][1];
		$a = floor($temp[2][0]/3);
		$link = str_replace("/v/", "/d/", $link);
		$link = str_replace("file.html", $a+$temp[2][0]%$temp[2][1], $link);
		$link .= $temp[2];
	} 
	else if (preg_match("/url: '([^']+)', seed: (\d+)}/i", $page, $L)) {
		$link = $L[1] . "&time=" . $L[2]*3 % 1424574;
	} 
	else if (preg_match("/var a = ([0-9]+)%([0-9]+);\s+var b = ([0-9]+)%([0-9]+);\s+.+\/(.+)\";/", $page, $L)) {
		$link = str_replace("/v/", "/d/", $link);
		$link = str_replace("file.html", (($L[1] % $L[2]) * ($L[3] % $L[4])) + 19 . "/" . $L[5], $link);
	} 
	else if (preg_match('/\/([0-9]+)\/"\+\(([0-9]+)\%([0-9]+) \+ ([0-9]+)\%([0-9]+)\)\+"\/(.+)";/', $page, $L)) {
		$server = $this->cut_str($url,'http://','.zippyshare.com');
		$link = "http://" . $server . ".zippyshare.com/d/" . $L[1] . "/" . (($L[2]%$L[3])+($L[4]%$L[5])) . "/" . $L[6];
	} 
	else if (preg_match('/var.+= ([0-9]+) (.+) ([0-9]+);\s+var.+[a-z] (.+) ([0-9]+);\s+var.+[a-z] (.+) ([0-9]+);\s+.+\/d\/([0-9]+)\/.+\/(.+)";/', $page, $L)) {
		$server = $this->cut_str($url,'http://','.zippyshare.com');
		$n = $L[1] + $L[3];
		$b = $n - $L[5];
		$z = $b - $L[7];
		$link = "http://" . $server . ".zippyshare.com/d/" . $L[8] . "/" . $z . "/" . $L[9];
	}
	elseif (stristr($page,"File does not exist on this server")) {
		$report = Tools_get::report($Original,"dead");
		break;
	}
	if($link){
		$size_name = Tools_get::size_name($link, $this->cookie);
		if(preg_match('%<title>Zippyshare.com - (.*)</title>%U', $page, $matches)) $filename = $matches[1];
		else $filename = $size_name[1];
		$filesize = $size_name[0];
		$this->max_size = $this->max_size_other_host;
	}
}
/*
* Home page: http://vinaget.us
* Blog:	http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.6.3
* Created: ..:: [H] ::..
* Note: Convert from rapidleech zippyshare_com plugin of vdhdevi, defport, motor
*/
?>