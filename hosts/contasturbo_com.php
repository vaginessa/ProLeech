<?php
$account = trim($this->get_account('contasturbo.com'));
if (stristr($account,':')) list($user, $pass) = explode(':',$account);
else $cookie = $account;
if(empty($cookie)==false || ($user && $pass)){
        if(!$cookie) $cookie = $this->get_cookie("contasturbo.com");
        if(!$cookie){
                $data = $this->curl("http://www.contasturbo.com/login/","","email=".$user."&password=".$pass."&submit=Acessar&remember=1");
                if(preg_match("#ocation: http://www.contasturbo.com/gerador/#", $data)){
					$cookie = $this->GetAllCookies($data);
					$this->save_cookies("contasturbo.com",$cookie);
                }
        }
        $this->cookie = $cookie;

        $data = $this->curl("http://www.contasturbo.com/linkRequest/",$this->cookie,"links=".$url);
        if(preg_match("#class=\"two\">(.*?)</textarea>#", $data, $saida))
		{
			$data = $this->curl(trim($saida[1]),$this->cookie,"");
			if(preg_match("#Location: (.*?)\r#", $data, $link2))
			{
				$link = $link2['1'];
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
			}
        }
		else $this->save_cookies("contasturbo.com","");
}
#Código por EAMI
#eamihost.com
?>