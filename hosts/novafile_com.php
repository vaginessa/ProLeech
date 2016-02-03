<?php
if (preg_match('#^http://(www\.)?novafile\.com/#', $url)){
	$account = trim($this->get_account('novafile.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;

	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("novafile.com");
			if(!$cookie){
				$data = $this->curl("http://novafile.com/login","","login=$user&password=$pass&op=login&redirect=&rand=");
				$cookie = $this->GetCookies($data);
				$cookie = explode("; redirect=",$cookie);
				$cookie = $cookie[0];
				$this->save_cookies("novafile.com",$cookie);
				
			}
			$this->cookie = $cookie;
			$data =  $this->curl($url,$cookie,"");
			$rand = explode('name="rand" value="',$data);
			$rand = explode('">',$rand[1]);
			$rand = $rand[0];
			$ref = explode('name="referer" value="',$data);
			$ref = explode('">',$ref[1]);
			$ref= $ref[0];
			$id = explode("/",$url);
			$count = count($id);
			$id = $id[$count-1];
			if(strpos($data,"Create Download Link"))
			{
				$data = $this->curl($url,$cookie,"op=download2&id=$id&rand=$rand&referer=$ref&method_premium=1&down_direct=1");
				//echo $data;
				$link = explode('<p><a href="',$data);
				$link = explode('" class="btn btn-green">',$link[1]);
				$link = $link[0];
				$link = str_replace(" ","%20",trim($link));
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			
			
			
			else {
				$cookie = "";
				$this->save_cookies("novafile.com","");
			}
		}
	}
}


/*
* Home page: http://vinaget.us
* Blog:	http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.6.3
* Created: ..:: [H] ::..
*/
?>