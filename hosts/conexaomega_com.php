<?php
$account = trim($this->get_account('conexaomega.com'));
if (stristr($account,':')) list($user, $pass) = explode(':',$account);
else $cookie = $account;
if(empty($cookie)==false || ($user && $pass)){
	for ($j=0; $j < 2; $j++){
		if(!$cookie) $cookie = $this->get_cookie("conexaomega.com");
		if(!$cookie){
			$data = $this->curl("http://www.conexaomega.com/login","","email=$user&senha=$pass&remember=1&x=".rand(1,80)."&y=".rand(1,20));
			$cookie = $this->GetCookies($data);
			$this->save_cookies("conexaomega.com",$cookie);
		$data = $this->curl("http://www.conexaomega.com/gerador",$cookie,"");
		}
		$data = $this->curl("http://www.conexaomega.com/_gerar?link=$url&rnd=",$cookie,"",00);
		if(preg_match('%(http:\/\/.+conexaomega\.com/.+)">%U', $data, $linkpre)){
			$link = trim($linkpre[1]);
			$data = $this->curl($link,"","");
			$link =  $this->cut_str($data, "Location:","\\s X-Powered");
			$link = explode("\n",$link);
			$link = trim($link[0]);
			if($link){
				$size_name = Tools_get::size_name($link, $this->cookie);
				if($size_name[0] > 1024 ){
					$filesize =  $size_name[0];
					$filename = $size_name[1];
					break;
				}
else $link='';
$this->save_cookies("conexaomega.com","");

			}
		}
		else {
			$cookie = "";
			$this->save_cookies("conexaomega.com","");
		}
	}
}

/*
* Home page: http://vinaget.us
* Blog:	http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.6.3
* Created: raj
*/
?>