<?php
$account = trim($this->get_account('superdown.com.br'));
if (stristr($account,':')) list($user, $pass) = explode(':',$account);
else $cookie = $account;
if(empty($cookie)==false || ($user && $pass)){
        if(!$cookie) $cookie = $this->get_cookie("superdown.com.br");
        if(!$cookie){
                $data = $this->curl("http://www.superdown.com.br/login","","email=".$user."&senha=".$pass."&lembrar=on");
                if(preg_match("#Location: /#", $data)){
					$cookie = $this->GetAllCookies($data);
					$this->save_cookies("superdown.com.br",$cookie);
                }
        }
        $this->cookie = $cookie;

        $data = $this->curl("http://www.superdown.com.br/_gerar?link=".$url,$this->cookie,"");
        if(preg_match("#http://www.superdown.com.br/(.*?)\|#", $data, $saida)){
                $link1 = trim("http://www.superdown.com.br/".$saida[1]);
				$data = $this->curl($link1,$this->cookie,"");
				
				if(preg_match("#Location: (.*?)\r#", $data, $link2)){
				$link = $link2['1'];
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				}
        }
		else $this->save_cookies("superdown.com.br","");
}

#Código por EAMI
#eamihost.com
?>