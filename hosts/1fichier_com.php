<?php
if (preg_match('#^(http|https)://([a-z0-9]+\.)?1fichier\.com#', $url)) {
    $account = trim($this->get_account('1fichier.com'));
    if (stristr($account, ':')) list($user, $pass) = explode(':', $account);
    else $cookie = $account;
    if (empty($cookie) == false || ($user && $pass)) {
        for ($j = 0;$j < 2;$j++) {
            if (!$cookie) $cookie = $this->get_cookie('1fichier.com');
            if (!$cookie) {
                $data = $this->curl('https://1fichier.com/login.pl', 'LG=en', 'mail='.$user.'&pass='.$pass.'&lt=on&valider=Send');
                $cookie = 'LG=en; '.$this->GetCookies($data);
                $this->save_cookies('1fichier.com',$cookie);
            }
            $this->cookie = $cookie;
            if(stristr($url, "http://")) $url = str_replace("http://", "https://", $url);
            $data = $this->curl($url, $cookie, '');
            if (stristr($data, 'file has been deleted')) die(Tools_get::report($Original,"dead"));
            if (stristr($data, 'File not found')) die(Tools_get::report($Original,"dead"));
            if (preg_match('/ocation: *(.*)/i',$data,$redir)) {
                $link = trim($redir[1]);
                $size_name = Tools_get::size_name($link, $this->cookie);
		$filesize =  $size_name[0];
		$filename = $size_name[1];
		break;
            }
            else {
                $cookie = '';
                $this->save_cookies('1fichier.com','');
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
* Updated: 12.08.2015 by Romaindu31
*/
?>