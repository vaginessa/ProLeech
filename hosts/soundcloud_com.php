<?php
if (strpos($url,'soundcloud.com')){
	include "phpquery.php";
	$post = array("track" => $url);
	$data = $this->curl('http://offliberty.com/off.php','',$post);
	phpQuery::newDocumentHTML($data);
	$link = pq('a:first')->attr('href');
	$size_name = Tools_get::size_name(trim($link), "");
	if($size_name[0] > 200 ){
		
				$filesize = $size_name[0];
				$filename = explode("/",$url);
				$filename = $filename[3]."_".$filename[4].".mp3";
				$filename=str_replace("[","_",$filename);
				$filename=str_replace("www.","www_",$filename);
				$filename=str_replace("]","_",$filename);
				$filename=str_replace("\\","_",$filename);
				$filename=str_replace("@","_",$filename);
				$filename=str_replace('&#039;',"_",$filename);
				$filename=str_replace('"',"_",$filename);
				$filename=str_replace('$',"_",$filename);
				$filename=str_replace('%',"_",$filename);
				$filename=str_replace('&',"_",$filename);
				$filename=str_replace(' ',"_",$filename);
				$filename=str_replace('%20',"_",$filename);
				$filename=str_replace('-',"_",$filename);
	}
	else die('Can not get link !');
}

/*
* Home page: http://vinaget.us
* Blog: http://blog.vinaget.us
* Script Name: Vinaget
* Version: 2.6.3
* Created: afterburnerleech.com (7 Sep 2011)
* Updated:
                - By H (Wednesday, November 16, 2011)
                - By H (Thursday, February 02, 2012)
				- By _rchaves_ (Saturday, September 29, 2012)
                - By H (Sunday, September 30, 2012)

*/
?>