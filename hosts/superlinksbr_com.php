<?php
$account = trim($this->get_account('superlinksbr.com'));
if (stristr($account,':')) list($user, $pass) = explode(':',$account);
else $cookie = $account;
if(empty($cookie)==false || ($user && $pass)){
	for ($j=0; $j < 2; $j++){
		if(!$cookie) $cookie = $this->get_cookie("superlinksbr.com");
		if(!$cookie){
			$data = $this->curl("http://superlinksbr.com/registro.php","","login_usuario=".$user."&login_senha=".$pass."");
			
			
			$cookie = $this->GetAllCookies($data);
			$this->save_cookies("superlinksbr.com",$cookie);
			$data = $this->curl("http://superlinksbr.com/downloader",$cookie,"");
		}
		
		$this->cookie = $cookie;
		$data = $this->curl("http://superlinksbr.com/downloader.php?url=".$url,$this->cookie,"");
		
		if(preg_match('/<a title=\'Clique aqui para baixar.\' href=\'(.*?)\' style/i', $data, $linkpre)){
			$link = trim($linkpre[1]);
			$data = $this->curl($link,"","");
			$link =  $this->cut_str($data, "Location:","\\s X-Powered");
			$link = explode("\n",$link);
			$link = trim($link[0]);
			if($link){
				$size_name = Tools_get::size_name($link, $this->cookie);
				if($size_name[0] > 200 ){
					$filesize =  $size_name[0];
					$filename = $size_name[1];
					break;
				}
				else $link='';
			}
		}
		else {
			$cookie = "";
			$this->save_cookies("superlinksbr.com","");
		}
	}
}
?>