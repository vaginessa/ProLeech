<?php
if(preg_match('#^http||https://([a-z0-9]+)\.depfile\.com/#', $url) || preg_match('#^http://depfile\.com/#', $url))
{
    $account = trim($this->get_account('depfile.com'));
    if(stristr($account,':')) list($user, $pass) = explode(':',$account);
    else $cookie = $account;
    if(empty($cookie) == false || ($user && $pass))
    {
        for($j = 0; $j < 1; $j++)
        {
            if(!$cookie) $cookie = $this->get_cookie("depfile.com");
            if(!$cookie)
            {
                $data   = $this->curl("https://depfile.com/", "sdlanguageid=2", "login=login&loginemail={$user}&loginpassword={$pass}&submit=login&rememberme=1");
                $cookie = $this->GetCookies($data);
                $this->save_cookies("depfile.com",$cookie);
            }
            $this->cookie = $cookie;
            if(!stristr($url, "https"))
            {
                $url = str_replace('http', 'https', $url);
            }
            $data = $this->curl($url,$this->cookie,"");
            if(stristr($data,"You spent limit on links"))die('<font color=red>bandwidth limit reached</font>');
            //preg_match('/<td > (.*) < \ / td>/', $match, $matches);
            else
            if(preg_match('/value="(.*)"><\/td>/', $data, $redir))
            {
                //print_r($redir);
                $link      = trim($redir[1]);
                $size_name = Tools_get::size_name($link, $this->cookie);
                $filesize  = $size_name[0];
                $filename  = $size_name[1];
            }
            else
            if(stristr($data,'File was not found')) die(Tools_get::report($Original,"dead"));
            else
            {
                $cookie = "";
                $this->save_cookies("depfile.com","");
            }
        }
    }
}


/*
* Home page: http://vinaget.us
* Blog:    http://blog.vinaget.us
* Script Name: Vinaget
* Version: 2.6.3
* Created: Amanat
*/
?>