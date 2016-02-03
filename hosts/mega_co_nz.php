<?php

	function dec_attr($attr, $key) {
		$attr = trim(aes_cbc_decrypt($attr, a32_to_str($key)));
		if (substr($attr, 0, 6) != 'MEGA{"')  return false;
		return json_decode(substr($attr, 4));
	}

	function aes_cbc_decrypt($data, $key) {
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0");
	}
		
	function a32_to_str($hex) {
		return call_user_func_array('pack', array_merge(array('N*'), $hex));
	}
	
	function base64urldecode($data) {
		$data .= substr('==', (2 - strlen($data) * 3) % 4);
		$data = str_replace(array('-', '_', ','), array('+', '/', ''), $data);
		return base64_decode($data);  
	}
	
	function str_to_a32($b) {
		// Add padding, we need a string with a length multiple of 4
		$b = str_pad($b, 4 * ceil(strlen($b) / 4), "\0");
		return array_values(unpack('N*', $b));
	}
	
	function base64_to_a32($s) {
		return str_to_a32(base64urldecode($s));
	}

	
	if (!extension_loaded('mcrypt') || !in_array('rijndael-128', mcrypt_list_algorithms(), true))    die("<strong><font color=red>Mcrypt module isn't installed or it doesn't have support for the needed encryption.</font></strong>"); 
		
	if (stristr($url, "mega.co.nz/#F!"))  die("<strong><font color=red>Not Support Folder</font></strong>");
	if (!preg_match('@!([^!]{8})!([\w\-\,]{43})@i', $url, $fid))  die("<strong><font color=red>FileID or Key not found at link.</font></strong>");
	else {
		$sid = '';
		$seqno = rand(0, 0xFFFFFFFF);
				
		$post = array(array('a' => 'g', 'g'=>1, 'p' => $fid[1]));
		$json = json_encode($post); 
			
		$data = $this->curl('https://g.api.mega.co.nz/cs?id=' . ($seqno++) . ($sid ? '&sid=' . $sid : ''), "", $json, 0, 1);
		$res = json_decode($data, true);
		
		if (isset($res[0]['e']))  {
			$code = $res[0]['e'];
			if (is_numeric($code)) {
				die("<strong><font color=red>File temporarily not available.</font></strong>");
			}
		}	
				
		if (!isset($res[0]['s']) || !isset($res[0]['at'])) 	die(Tools_get::report($Original,"dead"));
		$key = base64_to_a32($fid[2]);
						
		$k = array($key[0] ^ $key[4], $key[1] ^ $key[5], $key[2] ^ $key[6], $key[3] ^ $key[7]);
		$iv = array_merge(array_slice($key, 4, 2), array(0, 0));
		$meta_mac = array_slice($key, 6, 2);
		$enc_attributes = base64urldecode($res[0]['at']);
		$attributes = dec_attr($enc_attributes, $k);
					
		$infolink = array('url' => $res[0]['g'], 'size' => $res[0]['s'], 'name' => $attributes->n, 'key'=>$key, 'iv'=>$iv, 'mac'=>$meta_mac);
					
		$link = trim($infolink['url']);
		$filename = $infolink['name'];
		$filesize = $infolink['size'];
		
	}

?>