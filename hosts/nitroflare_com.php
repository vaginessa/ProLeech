<?php
if(preg_match('#^(http|https)\:\/\/(www\.)?nitroflare\.com/#', $url)){
    $account = trim($this->get_account('nitroflare.com'));
    if(stristr($account,':')) list($user, $pass) = explode(':',$account);
    else $cookie = $account;
    if(empty($cookie) == false || ($user && $pass)){
        for($j = 0; $j < 2; $j++){
            if(preg_match('/nitroflare\.com\/view\/([A-Z0-9]+)/',$url,$fid)) $fileid = $fid[1];
        	$data=$this->curl("http://nitroflare.com/api/v2/getDownloadLink?file=".$fileid."&user={$user}&premiumKey={$pass}","lang=en","",0);
        	$info=json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data), true );
        	if(stristr($info['message'],"maximum volume for today")) die('<font color=red>bandwidth limit reached</font>');
        	$link=trim($info['result']['url']);
                if($link){
                $size_name = Tools_get::size_name($link,$this->cookie);
                if($size_name[0] > 300 ){
                    $filesize = $size_name[0];
                    $filename = $size_name[1];
                    break;
                }
                else $link = '';
            }
            else $link = '';
        }
    }
}


/*
* Coded By : Romaindu31 [ 09/03/2015 ] [ FrenchDebrid.eu ]
* For Version: 2.6.3
*/
?>