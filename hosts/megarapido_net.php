<?php
$account = trim($this->get_account('megarapido.net'));
if (stristr($account,':')) list($user, $pass) = explode(':',$account);
else $cookie = $account;
if(empty($cookie)==false || ($user && $pass)){
	for ($j=0; $j < 2; $j++){
		if(!$cookie){
			$data = $this->curl("http://megarapido.net/painel_user/ajax/logar.php","","login=$user&senha=$pass");
			$cookie = $this->GetCookies($data);
		}
		$this->cookie = $cookie;
		$data = $this->curl('http://megarapido.net/gerar.php', $this->cookie,"urllist=$url&links=$url&usar=premium&user=1122&autoreset=");
		preg_match('/;\'>(.*?)<\/textarea>/', $data, $linkpre);
			$link = trim($linkpre[1]);
			$size_name = Tools_get::size_name($link, $this->cookie);
			$filesize = $size_name[0];
			$filename = $size_name[1];
                        break;
	}
}

/*
* Script Name: Vinaget 
* Version: 2.6.3
* Created: -zess- ( 24/1/2014 )
*/
?>