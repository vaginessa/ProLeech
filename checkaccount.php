<?php
error_reporting(7);
@set_magic_quotes_runtime(0);

/* BlakDownloader 3.7.5 - Tu8 [ Public Edition ] */ 

if (isset($_POST["check"])) {
	$check = false;
	#======================= begin check acc rapidsahare =======================#
	if($_POST["check"]== "RS"){
		if(count($obj->acc["rapidshare.com"]["accounts"])>0){
			echo '<table id="tableRS" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
				<tr class="flisttblhdr" valign="bottom">
					<td width="30%"><B>accounts rapidshare.com</B></td>
					<td width="15%"><b>Type</b></td>
					<td><b>Validity</b></td>
					<td width="20%"><b>Report</b></td>
				</tr>
			';
			for($i = 0; $i < count($obj->acc["rapidshare.com"]["accounts"]); $i++){
				$account = $obj->acc["rapidshare.com"]["accounts"][$i];
				if (stristr($account,':')) {
					list($user, $pass) = explode(':', $account);
					$account = substr($account, 0, 5).'****';
					$type = "account";
					$data = $obj->curl("http://api.rapidshare.com/cgi-bin/rsapi.cgi","","sub=getaccountdetails&withcookie=1&withpublicid=1&login=".$user."&cbf=RSAPIDispatcher&cbid=2&password=".$pass);
					if(strpos($data,'Login failed')) { 
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownRS"><font color=#CCFF00><b>Login failed !!!</b></font></td>
						<td id="unknownRS"><font color=green><B>Removed</B></font></td></tr>';
						$delacc[] = $i;
						continue;
					}
					else $cookie  =  $obj->cut_str($data, "ncookie=","\\n");
				}
				else {
					$type = "cookie";
					$cookie = $account;
					$account = substr($account, 0, 15).'****';
				}
				$cookie = preg_replace("/(enc=|Enc=|ENC=)/","",$cookie);
				$data =  $obj->curl("http://api.rapidshare.com/cgi-bin/rsapi.cgi","","sub=getaccountdetails&withcookie=1&withpublicid=1&withsession=1&cookie=".$cookie."&cbf=RSAPIDispatcher&cbid=1");			
				if(strpos($data,'Login failed')) { 
					echo '<tr class="flistmouseoff" align="center">
					<td><B>'.$account.'</B></td><td>'.$type.'</td>
					<td id="unknownRS"><font color=#CCFF00><b>Login failed !!!</b></font></td>
					<td id="unknownRS"><font color=green><B>Removed</B></font></td></tr>';
					$delacc[] = $i;
				}
				else{
				//Validity				
					preg_match('/billeduntil=([0-9]+)/', $data, $matches);
					if ($matches[1]==0){	
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownRS"><b><font color=#666666>FREE ACC</font></b></td>
						<td id="unknownRS"><font color=green><B>Removed</B></font></b></td></tr>';
						$delacc[] = $i;
					} 
					else { 
						if (time() > $matches[1]) { 
							echo '<tr class="flistmouseoff" align="center">
							<td><B>'.$account.'</B></td><td>'.$type.'</td>
							<td id="unknownRS"><font color=red><b><s>'.date('H:i:s Y-m-d',$matches[1]).'</s></b></font></td>
							<td id="unknownRS"><font color=#330099><B>Expired</B></font></td></tr>';
							$delacc[] = $i;
						}
						else{
							echo '<tr class="flistmouseoff" align="center">
							<td><B>'.$account.'</B></td><td>'.$type.'</td>
							<td id="unknownRS"><font color=red><b>'.date('H:i:s Y-m-d',$matches[1]).'</b></font></td>
							<td id="unknownRS"><font color=blue><B>Working</B></font></td></tr>';
						}
					}
				}
			}
			echo "</table>";
			$obj = new stream_get();
			if(isset($delacc)) {
				foreach ($delacc as $i) unset($obj->acc["rapidshare.com"]["accounts"][$i]);
				unset($delacc);
				$check = true;
			}
		}
	}
	#======================= end check acc rapidsahare =======================#
	################################################################################
	#======================= begin check acc hotfile.com =====================#
	elseif($_POST["check"]== "HF"){
		if(count($obj->acc["hotfile.com"]["accounts"])>0){
			echo '<table id="tableHF" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
				<tr class="flisttblhdr" valign="bottom">
					<td width="30%"><B>accounts hotfile.com</B></td>
					<td width="15%"><b>Type</b></td>
					<td><b>Validity</b></td>
					<td width="20%"><b>Report</b></td>
				</tr>
			';
			for($i = 0; $i < count($obj->acc["hotfile.com"]["accounts"]); $i++){
				$account = $obj->acc["hotfile.com"]["accounts"][$i];
				if (stristr($account,':')) {
					list($user, $pass) = explode(':', $account);
					$account = substr($account, 0, 5).'****';
					$type = "account";
					$data = $obj->curl("http://www.hotfile.com/login.php","","user=$user&pass=$pass");
					if(strpos($data,"Bad username/password combination")) {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownHF"><font color=#CCFF00><b>Login failed !!!</b></font></td>
						<td id="unknownHF"><font color=green><B>Removed</B></font></td></tr>';
						$delacc[] = $i;
						continue;
					}
					else {
						preg_match('/^Set-Cookie: auth=(.*?);/m', $data, $matches);
						$cookie = $matches[1];
					}
				}
				else {
					$type = "cookie";
					$cookie = $account;
					$account = substr($account, 0, 15).'****';
				}
				$cookie = preg_replace("/(auth=|AUTH=|Auth=)/","",$cookie);
				$ch = @curl_init();
				curl_setopt($ch, CURLOPT_URL, "http://hotfile.com/myaccount.html");
				curl_setopt($ch, CURLOPT_HEADER, 1);
				curl_setopt($ch, CURLOPT_COOKIE, "auth=$cookie");
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:2.0.1) Gecko/20100101 Firefox/4.0.1');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT, 60); 
				$data = curl_exec( $ch);
				curl_close($ch); 
				if(strpos($data,'Location: http://hotfile.com/')) { 
					echo '<tr class="flistmouseoff" align="center">
					<td><B>'.$account.'</B></td><td>'.$type.'</td>
					<td id="unknownHF"><font color=#CCFF00><b>Login failed !!!</b></font></td>
					<td id="unknownHF"><font color=green><B>Removed</B></font></td></tr>';
					$delacc[] = $i;
				}
				else{
					//Validity	
					if(strpos($data,'<div class="centerSide"><p><span>Free</span>')) {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownHF"><b><font color=#666666>FREE ACC</font></b></td>
						<td id="unknownHF"><font color=green><B>Removed</B></font></b></td></tr>';
						$delacc[] = $i;
					}
					elseif(preg_match('%<p>Premium until: <span class="rightSide">(.+) <b>%U', $data, $matches)) {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownHF"><font color=red><b>'.$matches[1].'</b></font></td>
						<td id="unknownHF"><font color=blue><B>Working</B></font></td></tr>';
					}
					else{
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownHF">unknown</td>
						<td id="unknownHF">unknown</td></tr>';
					}
				}
			}
			echo "</table>";		
			$obj = new stream_get();	
			if(isset($delacc)) {
				foreach ($delacc as $i) unset($obj->acc["hotfile.com"]["accounts"][$i]);
				unset($delacc);
				$check = true;
			}
		}
	}
	#======================= end check acc hotfile.com =======================#
	###########################################################################
	#======================= begin check acc depositfiles.com ================#
	elseif($_POST["check"]== "DF"){
		if(count($obj->acc["depositfiles.com"]["accounts"])>0){
			echo '<table id="tableDF" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
				<tr class="flisttblhdr" valign="bottom">
					<td width="30%"><B>accounts depositfiles.com</B></td>
					<td width="15%"><b>Type</b></td>
					<td><b>Validity</b></td>
					<td width="20%"><b>Report</b></td>
				</tr>
			';
			for($i = 0; $i < count($obj->acc["depositfiles.com"]["accounts"]); $i++){
				$account = $obj->acc["depositfiles.com"]["accounts"][$i];
				if (stristr($account,':')) {
					list($user, $pass) = explode(':', $account);
					$account = substr($account, 0, 5).'****';
					$type = "account";
					$data=$obj->curl("http://depositfiles.com/login.php?return=%2F","lang_current=en","go=1&login=$user&password=$pass");
					if(strpos($data,"Your password or login is incorrect")) {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownDF"><font color=#CCFF00><b>Login failed !!!</b></font></td>
						<td id="unknownDF"><font color=green><B>Removed</B></font></td></tr>';
						$delacc[] = $i;
						continue;
					}
					else {
						$cookie = $obj->GetCookies($data);
					}
				}
				else {
					$type = "cookie";
					$cookie = $account;
					$account = substr($account, 0, 15).'****';
				}
				$data = $obj->curl("http://depositfiles.com/gold/payment_history.php",$cookie.';lang_current=en;',"");
				if(strpos($data,'Location: http://depositfiles.com/login.php')) { 
					echo '<tr class="flistmouseoff" align="center">
					<td><B>'.$account.'</B></td><td>'.$type.'</td>
					<td id="unknownDF"><font color=#CCFF00><b>Login failed !!!</b></font></td>
					<td id="unknownDF"><font color=green><B>Removed</B></font></td></tr>';
					$delacc[] = $i;
				}
				else{
					//Validity	
					if(strpos($data,'Your current status: FREE - member')) {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownDF"><b><font color=#666666>FREE ACC</font></b></td>
						<td id="unknownDF"><font color=green><B>Removed</B></font></b></td></tr>';
						$delacc[] = $i;
					}
					elseif(preg_match('%You have Gold access until: <b>(.*)</b>%U', $data, $matches)) {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownDF"><font color=red><b>'.$matches[1].'</b></font></td>
						<td id="unknownDF"><font color=blue><B>Working</B></font></td></tr>';
					}
					else{
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownDF">unknown</td>
						<td id="unknownDF">unknown</td></tr>';
					}
				}
			}
			echo "</table>";		
			$obj = new stream_get();	
			if(isset($delacc)) {
				foreach ($delacc as $i) unset($obj->acc["hotfile.com"]["accounts"][$i]);
				unset($delacc);
				$check = true;
			}
		}
	}
	#======================= end check acc depositfiles.com ==================#
	################################################################################
	#======================= begin check acc uploading.com ===================#
	elseif($_POST["check"]== "ULD"){
		if(count($obj->acc["uploading.com"]["accounts"])>0){
			echo '<table id="tableULD" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
				<tr class="flisttblhdr" valign="bottom">
					<td width="30%"><B>accounts uploading.com</B></td>
					<td width="15%"><b>Type</b></td>
					<td><b>Validity</b></td>
					<td width="20%"><b>Report</b></td>
				</tr>
			';
			for($i = 0; $i < count($obj->acc["uploading.com"]["accounts"]); $i++){
				$account = $obj->acc["uploading.com"]["accounts"][$i];
				if (stristr($account,':')) {
					list($user, $pass) = explode(':', $account);
					$account = substr($account, 0, 5).'****';
					$type = "account";
					$tid = str_replace(".","12",microtime(true));
					$page = $obj->curl("http://uploading.com/general/login_form/?ajax","","email=$user&password=$pass");
					if(strpos($page,"password combination")) {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownULD"><font color=#CCFF00><b>Login failed !!!</b></font></td>
						<td id="unknownULD"><font color=green><B>Removed</B></font></td></tr>';
						$delacc[] = $i;
						continue;
					}
					else {
						$cookie = $obj->GetCookies($page);
					}
				}
				else {
					$type = "cookie";
					$cookie = $account;
					$account = substr($account, 0, 15).'****';
				}
				$data = $obj->curl("http://uploading.com/profile/",$cookie,"");  
				if(strpos($data,"Sign up")) { 
					echo '<tr class="flistmouseoff" align="center">
					<td><B>'.$account.'</B></td><td>'.$type.'</td>
					<td id="unknownULD"><font color=#CCFF00><b>Login failed !!!</b></font></td>
					<td id="unknownULD"><font color=green><B>Removed</B></font></td></tr>';
					$delacc[] = $i;
				}
				else{
					//Validity	
					if(strpos($data,'<dd>Basic')) {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownULD"><b><font color=#666666>FREE ACC</font></b></td>
						<td id="unknownULD"><font color=green><B>Removed</B></font></b></td></tr>';
						$delacc[] = $i;
					}
					elseif(strpos($data,'<dt>Valid Until')) {
						$infoacc = $obj->cut_str($data, "<dt>Valid Until", "Renew");
						if(preg_match("%<dd>(.*) \(\<%U", $infoacc, $matches)) {
							$Validity = $matches[1];
							echo '<tr class="flistmouseoff" align="center">
							<td><B>'.$account.'</B></td><td>'.$type.'</td>
							<td id="unknownULD"><font color=red><b>'.$Validity.'</b></font></td>
							<td id="unknownULD"><font color=blue><B>Working</B></font></td></tr>';
						}
						else {
							echo '<tr class="flistmouseoff" align="center">
							<td><B>'.$account.'</B></td><td>'.$type.'</td>
							<td id="unknownULD">unknown</td>
							<td id="unknownULD">unknown</td></tr>';
						}
					}
					else {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownULD">unknown</td>
						<td id="unknownULD">unknown</td></tr>';
					}
				}
			}
			echo "</table>";		
			$obj = new stream_get();	
			if(isset($delacc)) {
				foreach ($delacc as $i) unset($obj->acc["uploading.com"]["accounts"][$i]);
				unset($delacc);
				$check = true;
			}
		}
	}
	#======================= end check acc uploading.com =====================#
	################################################################################
	#======================= begin check acc uploaded.net ==========================#
	elseif($_POST["check"]== "ULNET") {
		if(count($obj->acc["uploaded.net"]["accounts"])>0) {
			echo '<table id="tableULNET" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
				<tr class="flisttblhdr" valign="bottom">
					<td width="30%"><b>accounts uploaded.net</b></td>
					<td width="15%"><b>Type</b></td>
					<td width="20%"><b>Validity</b></td>
					<td width="15%"><b>Traffic</b></td>
					<td width="20%"><b>Report</b></td>
				</tr>
			';
			for($i = 0; $i < count($obj->acc["uploaded.net"]["accounts"]); $i++) {
				$account = $obj->acc["uploaded.net"]["accounts"][$i];
				if (stristr($account,':')) {
					list($user, $pass) = explode(':', $account);
					$account = substr($account, 0, 5).'****';
					$type = "account";
					$page = $obj->curl("http://uploaded.net/io/login",'',"id=".$user."&pw=".$pass."");
					if(strpos($page,"User and password do not match")) {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownULTO"><font color=#CCFF00><b>Login failed !!!</b></font></td>
						<td id="unknownULTO">unknown</td>
						<td id="unknownULTO"><font color=green><b>Removed</b></font></td></tr>';
						$delacc[] = $i;
						continue;
					}
					else {
						preg_match('/^Set-Cookie: login=(.*?);/m', $page, $temp);
						$cookie = $temp[1];
					}
				}
				else {
					$type = "cookie";
					$cookie = $account;
					$account = substr($account, 0, 10).'****';
				}
				$cookie = preg_replace("/(login=|LOGIN=|Login=)/","",$cookie);
				$data = $obj->curl("http://uploaded.net","login=".$cookie."","");
				if(strpos($data,'<a href="#login">')) { 
					echo '<tr class="flistmouseoff" align="center">
					<td><b>'.$account.'</b></td><td>'.$type.'</td>
					<td id="unknownULNET"><font color=#CCFF00><b>Login failed !!!</b></font></td>
					<td id="unknownULNET">unknown</td>
					<td id="unknownULNET"><font color=green><b>Removed</b></font></td></tr>';
					$delacc[] = $i;
				}
				else{
					//Validity	
					if(strpos($data,'<em>Free</em>')) {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownULNET"><b><font color=#666666>FREE ACC</font></b></td>
						<td id="unknownULNET">unknown</td>
						<td id="unknownULNET"><font color=green><b>Removed</b></font></td></tr>';
						$delacc[] = $i;
					}
					elseif(strpos($data,'<em>Premium</em>')) {
						$duration = $obj->cut_str($data, "Duration", "</tr>");
						preg_match('%<th>(.*)</th>%U', $duration, $matches);
						$traffic = $obj->cut_str($data, "For downloading", "</th>");
						preg_match('%<em class="cB">(.*)</em>%U', $traffic, $traff);
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownULNET"><font color=red><b>'.$matches[1].'</b></font></td>
						<td id="unknownULNET"><font color=red><b>'.$traff[1].'</b></font></td>
						<td id="unknownULNET"><font color=blue><b>Working</b></font></td></tr>';
					}
					else {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownULNET">unknown</td>
						<td id="unknownULNET">unknown</td>
						<td id="unknownULNET">unknown</td></tr>';
					}
				}
			}
			echo "</table>";		
			$obj = new stream_get();	
			if(isset($delacc)) {
				foreach ($delacc as $i) unset($obj->acc["uploaded.net"]["accounts"][$i]);
				unset($delacc);
				$check = true;
			}
		}
	}
	#======================== end check acc uploaded.net ===========================#
	################################################################################
	#======================== begin check acc filefactory.com =====================#
	elseif($_POST["check"]== "FFT") {
		if(count($obj->acc["filefactory.com"]["accounts"])>0) {
			echo '<table id="tableFFT" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
				<tr class="flisttblhdr" valign="bottom">
					<td width="30%"><b>accounts filefactory.com</b></td>
					<td width="15%"><b>Type</b></td>
					<td><b>Validity</b></td>
					<td width="20%"><b>Report</b></td>
				</tr>
			';
			//==== Fix link FFT ====
			$url = "http://filefactory.com/";
			$data = $obj->curl($url,"","");
			if (preg_match('/ocation: (.*)/', $data, $fftlink)) {
				$url = trim($fftlink[1]);
			}
			$linkFFT= explode('/', $url);
			$urllogin = "http://".$linkFFT[2]."/member/login.php";
			//==== Fix link FFT ====
			for($i = 0; $i < count($obj->acc["filefactory.com"]["accounts"]); $i++) {
				$account = $obj->acc["filefactory.com"]["accounts"][$i];
				if (stristr($account,':')) {
					list($user, $pass) = explode(':', $account);
					$account = substr($account, 0, 5).'****';
					$type = "account";
					$post["email"] = $user;
					$post["password"] = $pass;
					$page = $obj->curl($urllogin,"",$post);
					if(strpos($page,"Location: /member/login.php")) {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownFFT"><font color=#CCFF00><b>Login failed !!!</b></font></td>
						<td id="unknownFFT"><font color=green><b>Removed</b></font></td></tr>';
						$delacc[] = $i;
						continue;
					}
					else {
						preg_match('/^Set-Cookie: ff_membership=(.*?);/m', $page, $matches);
						$cookie = $matches[1];
					}
				}
				else {
					$type = "cookie";
					$cookie = $account;
					$account = substr($account, 0, 10).'****';
				}
				$cookie = preg_replace("/(ff_membership=|Ff_membership=|FF_MEMBERSHIP=)/","",$cookie);
				$data = $obj->curl($url."member/","ff_membership=".$cookie,""); 
				if(strpos($data,"Location: /member/login.php")) {
					echo '<tr class="flistmouseoff" align="center">
					<td><b>'.$account.'</b></td><td>'.$type.'</td>
					<td id="unknownFFT"><font color=#CCFF00><b>Login failed !!!</b></font></td>
					<td id="unknownFFT"><font color=green><b>Removed</b></font></td></tr>';
					$delacc[] = $i;
				}
				else {
					//Validity
					if(strpos($data,'<p class="greenText">Free member</p>')) {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownFFT"><font color=#666666><b>FREE ACC</b></font></td>
						<td id="unknownFFT">unknown</td>
						<td id="unknownFFT"><font color=green><b>Removed</b></font></td></tr>';
						$delacc[] = $i;
					}
					elseif(preg_match('%<time datetime="(.*)">%U', $data, $matches)) {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownFFT"><font color=red><b>'.$matches[1].'</b></font></td>
						<td id="unknownFFT"><font color=blue><b>Working</b></font></td></tr>';
					}
					else {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownFFT">unknown</td>
						<td id="unknownFFT">unknown</td></tr>';
					}
				}
			}
			echo "</table>";		
			$obj = new stream_get();	
			if(isset($delacc)) {
				foreach ($delacc as $i) unset($obj->acc["filefactory.com"]["accounts"][$i]);
				unset($delacc);
				$check = true;
			}
		}
	}
	#======================== end check acc filefactory.com =======================#
	################################################################################
	#======================== begin check acc uploadstation.com ===================#
	elseif($_POST["check"]== "ULST") {
		if(count($obj->acc["uploadstation.com"]["accounts"])>0) {
			echo '<table id="tableULST" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
				<tr class="flisttblhdr" valign="bottom">
					<td width="30%"><b>accounts uploadstation.com</b></td>
					<td width="15%"><b>Type</b></td>
					<td><b>Validity</b></td>
					<td width="20%"><b>Report</b></td>
				</tr>
			';
			for($i = 0; $i < count($obj->acc["uploadstation.com"]["accounts"]); $i++) {
				$account = $obj->acc["uploadstation.com"]["accounts"][$i];
				if (stristr($account,':')) {
					list($user, $pass) = explode(':', $account);
					$account = substr($account, 0, 5).'****';
					$type = "account";
					$page = $obj->curl("http://uploadstation.com/login.php","","loginUserName=".$user."&loginUserPassword=".$pass."&autoLogin=on&loginFormSubmit=Login");
					if(strpos($page,"Username doesn't exist.")) {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownULST"><font color=#CCFF00><b>Login failed !!!</b></font></td>
						<td id="unknownULST"><font color=green><b>Removed</b></font></td></tr>';
						$delacc[] = $i;
						continue;
					}
					else {
						preg_match('/PHPSESSID=(.*);/',$page,$temp);
						$cookie = $temp[1];
					}
				}
				else {
					$type = "cookie";
					$cookie = $account;
					$account = substr($account, 0, 10).'****';
				}
				$cookie = preg_replace("/(phpsessid=|Phpsessid=|PHPSESSID=)/","",$cookie);
				$data = $obj->curl("http://uploadstation.com/dashboard.php","PHPSESSID=".$cookie,""); 
				if(strpos($data,"Location: /index.php") || strpos($data,"Location: /signup.php")) { 
					echo '<tr class="flistmouseoff" align="center">
					<td><b>'.$account.'</b></td><td>'.$type.'</td>
					<td id="unknownULST"><font color=#CCFF00><b>Login failed !!!</b></font></td>
					<td id="unknownULST"><font color=green><b>Removed</b></font></td></tr>';
					$delacc[] = $i;
				}
				else {
					//Validity	
					if(strpos($data,'<span>FREE</span>')) {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownULST"><font color=#666666><b>FREE ACC</b></font></td>
						<td id="unknownULST"><font color=green><b>Removed</b></font></td></tr>';
						$delacc[] = $i;
					}
					elseif(preg_match('/Expiry date: ([^\r]+)/i', $data, $matches)) {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownULST"><font color=red><b>'.$matches[1].'</b></font></td>
						<td id="unknownULST"><font color=blue><b>Working</b></font></td></tr>';
					}
					else {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownULST">unknown</td>
						<td id="unknownULST">unknown</td></tr>';
					}
				}
			}
			echo "</table>";		
			$obj = new stream_get();	
			if(isset($delacc)) {
				foreach ($delacc as $i) unset($obj->acc["uploadstation.com"]["accounts"][$i]);
				unset($delacc);
				$check = true;
			}
		}
	}
	#======================== end check acc uploadstation.com =====================#
	################################################################################
	#======================== begin check acc bayfiles.com ========================#
	elseif($_POST["check"]== "BFS") {
		if(count($obj->acc["bayfiles.com"]["accounts"])>0) {
			echo '<table id="tableBFS" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
				<tr class="flisttblhdr" valign="bottom">
					<td width="30%"><b>accounts bayfiles.com</b></td>
					<td width="15%"><b>Type</b></td>
					<td><b>Validity</b></td>
					<td width="20%"><b>Report</b></td>
				</tr>
			';
			for($i = 0; $i < count($obj->acc["bayfiles.com"]["accounts"]); $i++) {
				$account = $obj->acc["bayfiles.com"]["accounts"][$i];
				if (stristr($account,':')) {
					list($user, $pass) = explode(':', $account);
					$account = substr($account, 0, 5).'****';
					$type = "account";
					$page = $obj->curl("http://bayfiles.com/ajax_login","","action=login&username=".$user."&password=".$pass."&next=%252F&=");
					if(strpos($page,"Login failed. Please try again")) {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownBFS"><font color=#CCFF00><b>Login failed !!!</b></font></td>
						<td id="unknownBFS"><font color=green><b>Removed</b></font></td></tr>';
						$delacc[] = $i;
						continue;
					}
					else {
						preg_match('/SESSID=(.*);/',$page,$temp);
						$cookie = $temp[1];
					}
				}
				else {
					$type = "cookie";
					$cookie = $account;
					$account = substr($account, 0, 10).'****';
				}
				$cookie = preg_replace("/(SESSID=|sessid=|Sessid=)/","",$cookie);
				$data = $obj->curl("http://bayfiles.com/account","SESSID=".$cookie,""); 
				if(strpos($data,"Create an account or sign in")) { 
					echo '<tr class="flistmouseoff" align="center">
					<td><b>'.$account.'</b></td><td>'.$type.'</td>
					<td id="unknownBFS"><font color=#CCFF00><b>Login failed !!!</b></font></td>
					<td id="unknownBFS"><font color=green><b>Removed</b></font></td></tr>';
					$delacc[] = $i;
				}
				else {
					//Validity	
					if(strpos($data,'<p>Normal</p>')) {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownBFS"><font color=#666666><b>FREE ACC</b></font></td>
						<td id="unknownBFS"><font color=green><b>Removed</b></font></td></tr>';
						$delacc[] = $i;
					}
					elseif(preg_match('%Expiration date: (.*)</p>%U', $data, $matches)) {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownBFS"><font color=red><b>'.$matches[1].'</b></font></td>
						<td id="unknownBFS"><font color=blue><b>Working</b></font></td></tr>';
					}
					else {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownBFS">unknown</td>
						<td id="unknownBFS">unknown</td></tr>';
					}
				}
			}
			echo "</table>";		
			$obj = new stream_get();	
			if(isset($delacc)) {
				foreach ($delacc as $i) unset($obj->acc["bayfiles.com"]["accounts"][$i]);
				unset($delacc);
				$check = true;
			}
		}
	}
	#======================== end check acc bayfiles.com ==========================#
	################################################################################
	#======================= begin check acc rapidgator.net =======================#
	elseif($_POST["check"]== "RGT") {
		if(count($obj->acc["rapidgator.net"]["accounts"])>0) {
			echo '<table id="tableRGT" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
				<tr class="flisttblhdr" valign="bottom">
					<td width="30%"><b>accounts rapidgator.net</b></td>
					<td width="15%"><b>Type</b></td>
					<td width="20%"><b>Validity</b></td>
					<td width="15%"><b>Traffic</b></td>
					<td width="20%"><b>Report</b></td>
				</tr>
			';
			for($i = 0; $i < count($obj->acc["rapidgator.net"]["accounts"]); $i++) {
				$account = $obj->acc["rapidgator.net"]["accounts"][$i];
				if (stristr($account,':')) {
					list($user, $pass) = explode(':', $account);
					$account = substr($account, 0, 5).'****';
					$type = "account";
					$data = $obj->curl("https://rapidgator.net/auth/login","lang=en","LoginForm[email]=".$user."&LoginForm[password]=".$pass."&LoginForm[rememberMe]=1");
					if(strpos($data,"Please fix the following input errors:")) {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownRGT"><font color=#CCFF00><b>Login failed !!!</b></font></td>
						<td id="unknownRGT">unknown</td>
						<td id="unknownRGT"><font color=green><b>Removed</b></font></td></tr>';
						$delacc[] = $i;
						continue;
					}
					else {
						$cookie = "lang=en; ".$obj->GetCookies($data);
					}
				}
				else {
					$type = "cookie";
					$cookie = $account;
					$account = substr($account, 0, 15).'****';
				}
				$data = $obj->curl("http://rapidgator.net/profile/index","lang=en; ".$cookie,"");
				if(strpos($data,'>Registration</a>')) { 
					echo '<tr class="flistmouseoff" align="center">
					<td><b>'.$account.'</b></td><td>'.$type.'</td>
					<td id="unknownRGT"><font color=#CCFF00><b>Login failed !!!</b></font></td>
					<td id="unknownRGT">unknown</td>
					<td id="unknownRGT"><font color=green><b>Removed</b></font></td></tr>';
					$delacc[] = $i;
				}
				else{
					//Validity	
					if(strpos($data,'">Free</a>')) {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownRGT"><b><font color=#666666>FREE ACC</font></b></td>
						<td id="unknownRGT">unknown</td>
						<td id="unknownRGT"><font color=green><b>Removed</b></font></b></td></tr>';
						$delacc[] = $i;
					}
					elseif(strpos($data, 'Premium')) {
						$duration = $obj->cut_str($data, ' ">Premium','</a></li>');
						$traffic = $obj->cut_str($data, 'Bandwith available</td>','<div style=');
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownRGT"><font color=red><b>'.$duration.'</b></font></td>
						<td id="unknownRGT"><font color=red><b>'.$traffic.'</b></font></td>
						<td id="unknownRGT"><font color=blue><b>Working</b></font></td></tr>';
					}
					else{
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownRGT">unknown</td>
						<td id="unknownRGT">unknown</td>
						<td id="unknownRGT">unknown</td></tr>';
					}
				}
			}
			echo "</table>";		
			$obj = new stream_get();	
			if(isset($delacc)) {
				foreach ($delacc as $i) unset($obj->acc["rapidgator.net"]["accounts"][$i]);
				unset($delacc);
				$check = true;
			}
		}
	}
	#======================= end check acc rapidgator.net =========================#
	################################################################################
	#======================= begin check acc filepost.com =========================#
	elseif($_POST["check"]== "FP") {
		if(count($obj->acc["filepost.com"]["accounts"])>0) {
			echo '<table id="tableFP" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
				<tr class="flisttblhdr" valign="bottom">
					<td width="30%"><b>accounts filepost.com</b></td>
					<td width="15%"><b>Type</b></td>
					<td><b>Validity</b></td>
					<td width="20%"><b>Report</b></td>
				</tr>
			';
			for($i = 0; $i < count($obj->acc["filepost.com"]["accounts"]); $i++) {
				$account = $obj->acc["filepost.com"]["accounts"][$i];
				if (stristr($account,':')) {
					list($user, $pass) = explode(':', $account);
					$account = substr($account, 0, 5).'****';
					$type = "account";
					$tid = str_replace(".","12",microtime(true));
					$data = $obj->curl("http://filepost.com/general/login_form/?JsHttpRequest=".$tid."-xml","","email=".$user."&password=".$pass."");
					if(strpos($data,"Incorrect e-mail\/password combination")) {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownFP"><font color=#CCFF00><b>Login failed !!!</b></font></td>
						<td id="unknownFP"><font color=green><b>Removed</b></font></td></tr>';
						$delacc[] = $i;
						continue;
					}
					elseif(strpos($data,"captcha")) {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownFP">unknown</td>
						<td id="unknownFP">unknown</td></tr>';
						continue;
					}
					else $cookie = $obj->GetCookies($data);
				}
				else {
					$type = "cookie";
					$cookie = "SID=".$account;
					$account = substr($account, 0, 15).'****';
				}
				$data = $obj->curl("http://filepost.com/profile/",$cookie,"");
				if(strpos($data,'<span>Sign Up</span>')) { 
					echo '<tr class="flistmouseoff" align="center">
					<td><b>'.$account.'</b></td><td>'.$type.'</td>
					<td id="unknownFP"><font color=#CCFF00><b>Login failed !!!</b></font></td>
					<td id="unknownFP"><font color=green><b>Removed</b></font></td></tr>';
					$delacc[] = $i;
				}
				else{
					//Validity	
					if(strpos($data,'Account type: <span>Free<')) {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownFP"><b><font color=#666666>FREE ACC</font></b></td>
						<td id="unknownFP"><font color=green><b>Removed</b></font></b></td></tr>';
						$delacc[] = $i;
					}
					elseif(preg_match('%Valid until: <span>(.*)</span>%U', $data, $matches)) {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownFP"><font color=red><b>'.$matches[1].'</b></font></td>
						<td id="unknownFP"><font color=blue><b>Working</b></font></td></tr>';
					}
					else{
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownFP">unknown</td>
						<td id="unknownFP">unknown</td></tr>';
					}
				}
			}
			echo "</table>";		
			$obj = new stream_get();	
			if(isset($delacc)) {
				foreach ($delacc as $i) unset($obj->acc["filepost.com"]["accounts"][$i]);
				unset($delacc);
				$check = true;
			}
		}
	}
	#======================= end check acc filepost.com ===========================#
	################################################################################
	#======================== begin check acc real-debrid.com =====================#
	elseif($_POST["check"]== "REAL") {
		if(count($obj->acc["real-debrid.com"]["accounts"])>0) {
			echo '<table id="tableREAL" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
				<tr class="flisttblhdr" valign="bottom">
					<td width="30%"><b>accounts real-debrid.com</b></td>
					<td width="15%"><b>Type</b></td>
					<td><b>Validity</b></td>
					<td width="20%"><b>Report</b></td>
				</tr>
			';
			for($i = 0; $i < count($obj->acc["real-debrid.com"]["accounts"]); $i++) {
				$account = $obj->acc["real-debrid.com"]["accounts"][$i];
				if (stristr($account,':')) {
					list($user, $pass) = explode(':', $account);
					$account = substr($account, 0, 5).'****';
					$type = "account";
					$data = $obj->curl("https://www.real-debrid.com/ajax/login.php?user=".urlencode($user)."&pass=".urlencode($pass)."","","");
					if(strpos($data,"Your login informations are incorrect") || strpos($data,"Your account is not active or has been suspended")) {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownREAL"><font color=#CCFF00><b>Login failed !!!</b></font></td>
						<td id="unknownREAL"><font color=green><b>Removed</b></font></td></tr>';
						$delacc[] = $i;
						continue;
					}
					else {
						preg_match('%(auth=.+);%U', $data, $cook);
						$cookie = $cook[1];
					}
				}
				else {
					$type = "cookie";
					$cookie = $account;
					$account = substr($account, 0, 10).'****';
				}
				$cookie = preg_replace("/(auth=|AUTH=|Auth=)/","",$cookie);
				$data = $obj->curl("https://www.real-debrid.com","auth=".$cookie,"");
				if(strpos($data,'<a href="#login-box" rel="facebox">Login</a>')) { 
					echo '<tr class="flistmouseoff" align="center">
					<td><b>'.$account.'</b></td><td>'.$type.'</td>
					<td id="unknownREAL"><font color=#CCFF00><b>Login failed !!!</b></font></td>
					<td id="unknownREAL"><font color=green><b>Removed</b></font></td></tr>';
					$delacc[] = $i;
				}
				else {
					//Validity	
					if(strpos($data,'<strong>Free</strong>')) {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownREAL"><font color=#666666><b>FREE ACC</b></font></td>
						<td id="unknownREAL"><font color=green><b>Removed</b></font></td></tr>';
						$delacc[] = $i;
					}
					elseif(preg_match('%<strong>Premium:</strong> (.*)                    </div>%U', $data, $matches)) {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownREAL"><font color=red><b>'.$matches[1].'</b></font></td>
						<td id="unknownREAL"><font color=blue><b>Working</b></font></td></tr>';
					}
					else {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownREAL">unknown</td>
						<td id="unknownREAL">unknown</td></tr>';
					}
				}
			}
			echo "</table>";		
			$obj = new stream_get();	
			if(isset($delacc)) {
				foreach ($delacc as $i) unset($obj->acc["real-debrid.com"]["accounts"][$i]);
				unset($delacc);
				$check = true;
			}
		}
	}
	#======================== end check acc real-debrid.com =======================#
	################################################################################
	#======================== begin check acc alldebrid.com =======================#
	elseif($_POST["check"]== "ALLD") {
		if(count($obj->acc["alldebrid.com"]["accounts"])>0) {
			echo '<table id="tableALLD" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
				<tr class="flisttblhdr" valign="bottom">
					<td width="30%"><b>accounts alldebrid.com</b></td>
					<td width="15%"><b>Type</b></td>
					<td><b>Validity</b></td>
					<td width="20%"><b>Report</b></td>
				</tr>
			';
			for($i = 0; $i < count($obj->acc["alldebrid.com"]["accounts"]); $i++) {
				$account = $obj->acc["alldebrid.com"]["accounts"][$i];
				if (stristr($account,':')) {
					list($user, $pass) = explode(':', $account);
					$account = substr($account, 0, 5).'****';
					$type = "account";
					$data = $obj->curl("http://www.alldebrid.com/register/?action=login&returnpage=","","login_login=".urlencode($user)."&login_password=".urlencode($pass)."");
					if(strpos($data,"The password is not valid")) {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownALLD"><font color=#CCFF00><b>Login failed !!!</b></font></td>
						<td id="unknownALLD"><font color=green><b>Removed</b></font></td></tr>';
						$delacc[] = $i;
						continue;
					}
					else if(preg_match("%uid=(.*);%U", $data, $matches))  $cookie = $matches[1];
				}
				else {
					$type = "cookie";
					$cookie = $account;
					$account = substr($account, 0, 10).'****';
				}
				$cookie = preg_replace("/(uid=|UID=|Uid=)/","",$cookie);
				$data = $obj->curl("http://www.alldebrid.com/account/","uid=".$cookie,"");
				if(strpos($data,'Location')) {
					echo '<tr class="flistmouseoff" align="center">
					<td><b>'.$account.'</b></td><td>'.$type.'</td>
					<td id="unknownALLD"><font color=#CCFF00><b>Login failed !!!</b></font></td>
					<td id="unknownALLD"><font color=green><b>Removed</b></font></td></tr>';
					$delacc[] = $i;
				}
				else {
					//Validity	
					if(strpos($data,'</strong>normal</li>')) {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownALLD"><font color=#666666><b>FREE ACC</b></font></td>
						<td id="unknownALLD"><font color=green><b>Removed</b></font></td></tr>';
						$delacc[] = $i;
					}
					elseif(strpos($data,'</strong>Premium</li>')) {
						preg_match("%<li><strong>You have now \: </strong>(.*) <%U", $data, $matches);
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownALLD"><font color=red><b>'.$matches[1].'</b></font></td>
						<td id="unknownALLD"><font color=blue><b>Working</b></font></td></tr>';
					}
					else {
						echo '<tr class="flistmouseoff" align="center">
						<td><b>'.$account.'</b></td><td>'.$type.'</td>
						<td id="unknownALLD">unknown</td>
						<td id="unknownALLD">unknown</td></tr>';
					}
				}
			}
			echo "</table>";		
			$obj = new stream_get();	
			if(isset($delacc)) {
				foreach ($delacc as $i) unset($obj->acc["alldebrid.com"]["accounts"][$i]);
				unset($delacc);
				$check = true;
			}
		}
	}
	#======================== end check acc alldebrid.com =========================#
	################################################################################
	#======================= begin check acc bitshare.com ====================#
	elseif($_POST["check"]== "BS"){
		if(count($obj->acc["bitshare.com"]["accounts"])>0){
			echo '<table id="tableBS" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
				<tr class="flisttblhdr" valign="bottom">
					<td width="30%"><B>accounts bitshare.com</B></td>
					<td width="15%"><b>Type</b></td>
					<td><b>Validity</b></td>
					<td width="20%"><b>Report</b></td>
				</tr>
			';
			for($i = 0; $i < count($obj->acc["bitshare.com"]["accounts"]); $i++){
				$account = $obj->acc["bitshare.com"]["accounts"][$i];
				if (stristr($account,':')) {
					list($user, $pass) = explode(':', $account);
					$account = substr($account, 0, 5).'****';
					$type = "account";
					$data=$obj->curl("http://bitshare.com/login.html","","user=$user&password=$pass&rememberlogin=&submit=Login");
					if(strpos($data,"Click here to login")) {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownBS"><font color=#CCFF00><b>Login failed !!!</b></font></td>
						<td id="unknownBS"><font color=green><B>Removed</B></font></td></tr>';
						$delacc[] = $i;
						continue;
					}
					else {
						$cookie = $obj->GetCookies($data);
					}
				}
				else {
					$type = "cookie";
					$cookie = $account;
					$account = substr($account, 0, 15).'****';
				}
				$data = $obj->curl("http://bitshare.com/myaccount.html",$cookie,"");
				if(strpos($data,'Location: http://bitshare.com')) { 
					echo '<tr class="flistmouseoff" align="center">
					<td><B>'.$account.'</B></td><td>'.$type.'</td>
					<td id="unknownBS"><font color=#CCFF00><b>Login failed !!!</b></font></td>
					<td id="unknownBS"><font color=green><B>Removed</B></font></td></tr>';
					$delacc[] = $i;
				}
				else{
					//Validity	
					if(strpos($data,'<i>Basic</i>')) {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownBS"><b><font color=#666666>FREE ACC</font></b></td>
						<td id="unknownBS"><font color=green><B>Removed</B></font></b></td></tr>';
						$delacc[] = $i;
					}
					elseif(preg_match('%Valid until: ([0-9].++)%U', $data, $matches)) {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownBS"><font color=red><b>'.$matches[1].'</b></font></td>
						<td id="unknownBS"><font color=blue><B>Working</B></font></td></tr>';
					}
					else{
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownBS">unknown</td>
						<td id="unknownBS">unknown</td></tr>';
					}
				}
			}
			echo "</table>";		
			$obj = new stream_get();	
			if(isset($delacc)) {
				foreach ($delacc as $i) unset($obj->acc["bitshare.com"]["accounts"][$i]);
				unset($delacc);
				$check = true;
			}
		}
	}
	#======================= end check acc bitshare.com ======================#
	if($check == true && is_array($obj->acc) && count($obj->acc) > 0) {
		$str = "<?php";
		$str .= "\n";
		$str .= "\n\$this->acc = array(";
		$str .= "\n";
		$str .= "# Example: 'accounts'	=> array('user:pass','cookie'),\n";
		$str .= "# Example with letitbit.net: 'accounts'    => array('user:pass','cookie','prekey=xxxx'),\n";
		$str .= "\n";
		foreach ($obj->acc as $host => $accounts) {
			$str .= "\n	'".$host."'		=> array(";
			$str .= "\n								'max_size'	=> ".($accounts['max_size']?$accounts['max_size']:1024).",";
			$str .= "\n								'accounts'	=> array(";
			foreach ($accounts['accounts'] as $acc) {
				$str .= "'".$acc."',";
			}
			$str .= "),";
			$str .= "\n							),";
			$str .= "\n";
		}
		$str .= "\n);";
		$str .= $obj->max_size_other_host ? "\n\$this->max_size_other_host = ".$obj->max_size_other_host.";" : "\n\$this->max_size_other_host = 1024;";
		$str .= "\n";
		$str .= "\n?>";
		$accountPath = "data/account.php";
		$CF = fopen ($accountPath, "w")
		or die('<CENTER><font color=red size=3>could not open file! Try to chmod the file "<B>data/account.php</B>" to 666</font></CENTER>');
		fwrite ($CF, $str)
		or die('<CENTER><font color=red size=3>could not write file! Try to chmod the file "<B>data/account.php</B>" to 666</font></CENTER>');
		fclose ($CF); 
		@chmod($accountPath, 0666);
	}
}
else {

	echo '<div style="overflow: auto; height: auto; width: 800px;" align="left">'; 
	if(count($obj->acc["bitshare.com"]["accounts"])>0){
		echo '<table id="tableBS" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
			<tr class="flisttblhdr" valign="bottom">
				<td width="30%"><B>accounts bitshare.com</B></td>
				<td width="15%"><b>Type</b></td>
				<td><b>Validity</b></td>
				<td width="20%"><b>Report</b></td>
			</tr>
		';
		for($i = 0; $i < count($obj->acc["bitshare.com"]["accounts"]); $i++){
			$account = $obj->acc["bitshare.com"]["accounts"][$i];
			if (stristr($account,':')) $type = "account";
			else $type = "cookie";
			$account = substr($account, 0, 5)."****";
			echo '<tr class="flistmouseoff" align="center"><td><B>'.$account.'</B></td><td>'.$type.'</td><td id="unknownBS">unknown</td><td id="unknownBS">unknown</td></tr>';
		}
		echo "</table>";
		echo "<a onclick=\"checkacc('BS');\" href=\"javascript:void(0)\" style='TEXT-DECORATION: none'><font color=#FF6600>Check accounts bitshare.com >>></font></a><BR><BR>";
		$checkall = true;
	}
	if(count($obj->acc["rapidshare.com"]["accounts"])>0) {
		echo '<table id="tableRS" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
			<tr class="flisttblhdr" valign="bottom">
				<td width="30%"><b>accounts rapidshare.com</b></td>
				<td width="15%"><b>Type</b></td>
				<td><b>Validity</b></td>
				<td width="20%"><b>Report</b></td>
			</tr>
		';
		for($i = 0; $i < count($obj->acc["rapidshare.com"]["accounts"]); $i++) {
			$account = $obj->acc["rapidshare.com"]["accounts"][$i];
			if (stristr($account,':')) $type = "account";
			else $type = "cookie";
			$account = substr($account, 0, 5)."****";
			echo '<tr class="flistmouseoff" align="center"><td><b>'.$account.'</b></td><td>'.$type.'</td><td id="unknownRS">unknown</td><td id="unknownRS">unknown</td></tr>';
		}
		echo "</table>";
		echo "<a onclick=\"checkacc('RS');\" href=\"javascript:void(0)\" style='TEXT-DECORATION: none'><font color=#FF6600>Check accounts rapidshare.com >>></font></a><br><br>";
		$checkall = true;
	}
	if(count($obj->acc["hotfile.com"]["accounts"])>0) {
		echo '<table id="tableHF" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
			<tr class="flisttblhdr" valign="bottom">
				<td width="30%"><b>accounts hotfile.com</b></td>
				<td width="15%"><b>Type</b></td>
				<td><b>Validity</b></td>
				<td width="20%"><b>Report</b></td>
			</tr>
		';
		for($i = 0; $i < count($obj->acc["hotfile.com"]["accounts"]); $i++) {
			$account = $obj->acc["hotfile.com"]["accounts"][$i];
			if (stristr($account,':')) $type = "account";
			else $type = "cookie";
			$account = substr($account, 0, 5)."****";
			echo '<tr class="flistmouseoff" align="center"><td><b>'.$account.'</b></td><td>'.$type.'</td><td id="unknownHF">unknown</td><td id="unknownHF">unknown</td></tr>';
		}
		echo "</table>";
		echo "<a onclick=\"checkacc('HF');\" href=\"javascript:void(0)\" style='TEXT-DECORATION: none'><font color=#FF6600>Check accounts hotfile.com >>></font></a><br><br>";
		$checkall = true;
	}
	if(count($obj->acc["depositfiles.com"]["accounts"])>0){
		echo '<table id="tableDF" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
			<tr class="flisttblhdr" valign="bottom">
				<td width="30%"><B>accounts depositfiles.com</B></td>
				<td width="15%"><b>Type</b></td>
				<td><b>Validity</b></td>
				<td width="20%"><b>Report</b></td>
			</tr>
		';
		for($i = 0; $i < count($obj->acc["depositfiles.com"]["accounts"]); $i++){
			$account = $obj->acc["depositfiles.com"]["accounts"][$i];
			if (stristr($account,':')) $type = "account";
			else $type = "cookie";
			$account = substr($account, 0, 5)."****";
			echo '<tr class="flistmouseoff" align="center"><td><B>'.$account.'</B></td><td>'.$type.'</td><td id="unknownDF">unknown</td><td id="unknownDF">unknown</td></tr>';
		}
		echo "</table>";
		echo "<a onclick=\"checkacc('DF');\" href=\"javascript:void(0)\" style='TEXT-DECORATION: none'><font color=#FF6600>Check accounts depositfiles.com >>></font></a><BR><BR>";
		$checkall = true;
	}
	if(count($obj->acc["uploading.com"]["accounts"])>0) {
		echo '<table id="tableULD" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
			<tr class="flisttblhdr" valign="bottom">
				<td width="30%"><b>accounts uploading.com</b></td>
				<td width="15%"><b>Type</b></td>
				<td><b>Validity</b></td>
				<td width="20%"><b>Report</b></td>
			</tr>
		';
		for($i = 0; $i < count($obj->acc["uploading.com"]["accounts"]); $i++) {
			$account = $obj->acc["uploading.com"]["accounts"][$i];
			if (stristr($account,':')) $type = "account";
			else $type = "cookie";
			$account = substr($account, 0, 5)."****";
			echo '<tr class="flistmouseoff" align="center"><td><b>'.$account.'</b></td><td>'.$type.'</td><td id="unknownULD">unknown</td><td id="unknownULD">unknown</td></tr>';
		}
		echo "</table>";
		echo "<a onclick=\"checkacc('ULD');\" href=\"javascript:void(0)\" style='TEXT-DECORATION: none'><font color=#FF6600>Check accounts uploading.com >>></font></a><br><br>";
		$checkall = true;
	}
	if(count($obj->acc["uploaded.net"]["accounts"])>0) {
		echo '<table id="tableULNET" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
			<tr class="flisttblhdr" valign="bottom">
				<td width="30%"><B>accounts uploaded.net</B></td>
				<td width="15%"><b>Type</b></td>
				<td width="20%"><b>Validity</b></td>
				<td width="15%"><b>Traffic</b></td>
				<td width="20%"><b>Report</b></td>
			</tr>
		';
		for($i = 0; $i < count($obj->acc["uploaded.net"]["accounts"]); $i++) {
			$account = $obj->acc["uploaded.net"]["accounts"][$i];
			if (stristr($account,':')) $type = "account";
			else $type = "cookie";
			$account = substr($account, 0, 5)."****";
			echo '<tr class="flistmouseoff" align="center"><td><B>'.$account.'</B></td><td>'.$type.'</td><td id="unknownULNET">unknown</td><td id="unknownULNET">unknown</td><td id="unknownULNET">unknown</td></tr>';
		}
		echo "</table>";
		echo "<a onclick=\"checkacc('ULNET');\" href=\"javascript:void(0)\" style='TEXT-DECORATION: none'><font color=#FF6600>Check accounts uploaded.net >>></font></a><br><br>";
		$checkall = true;
	}
	if(count($obj->acc["filefactory.com"]["accounts"])>0) {
		echo '<table id="tableFFT" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
			<tr class="flisttblhdr" valign="bottom">
				<td width="30%"><b>accounts filefactory.com</b></td>
				<td width="15%"><b>Type</b></td>
				<td><b>Validity</b></td>
				<td width="20%"><b>Report</b></td>
			</tr>
		';
		for($i = 0; $i < count($obj->acc["filefactory.com"]["accounts"]); $i++) {
			$account = $obj->acc["filefactory.com"]["accounts"][$i];
			if (stristr($account,':')) $type = "account";
			else $type = "cookie";
			$account = substr($account, 0, 5)."****";
			echo '<tr class="flistmouseoff" align="center"><td><b>'.$account.'</b></td><td>'.$type.'</td><td id="unknownFFT">unknown</td><td id="unknownFFT">unknown</td></tr>';
		}
		echo "</table>";
		echo "<a onclick=\"checkacc('FFT');\" href=\"javascript:void(0)\" style='TEXT-DECORATION: none'><font color=#FF6600>Check accounts filefactory.com >>></font></a><br><br>";
		$checkall = true;
	}
	if(count($obj->acc["uploadstation.com"]["accounts"])>0) {
		echo '<table id="tableULST" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
			<tr class="flisttblhdr" valign="bottom">
				<td width="30%"><b>accounts uploadstation.com</b></td>
				<td width="15%"><b>Type</b></td>
				<td><b>Validity</b></td>
				<td width="20%"><b>Report</b></td>
			</tr>
		';
		for($i = 0; $i < count($obj->acc["uploadstation.com"]["accounts"]); $i++) {
			$account = $obj->acc["uploadstation.com"]["accounts"][$i];
			if (stristr($account,':')) $type = "account";
			else $type = "cookie";
			$account = substr($account, 0, 5)."****";
			echo '<tr class="flistmouseoff" align="center"><td><b>'.$account.'</b></td><td>'.$type.'</td><td id="unknownULST">unknown</td><td id="unknownULST">unknown</td></tr>';
		}
		echo "</table>";
		echo "<a onclick=\"checkacc('ULST');\" href=\"javascript:void(0)\" style='TEXT-DECORATION: none'><font color=#FF6600>Check accounts uploadstation.com >>></font></a><br><br>";
		$checkall = true;
	}
	if(count($obj->acc["bayfiles.com"]["accounts"])>0) {
		echo '<table id="tableBFS" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
			<tr class="flisttblhdr" valign="bottom">
				<td width="30%"><b>accounts bayfiles.com</b></td>
				<td width="15%"><b>Type</b></td>
				<td><b>Validity</b></td>
				<td width="20%"><b>Report</b></td>
			</tr>
		';
		for($i = 0; $i < count($obj->acc["bayfiles.com"]["accounts"]); $i++) {
			$account = $obj->acc["bayfiles.com"]["accounts"][$i];
			if (stristr($account,':')) $type = "account";
			else $type = "cookie";
			$account = substr($account, 0, 5)."****";
			echo '<tr class="flistmouseoff" align="center"><td><b>'.$account.'</b></td><td>'.$type.'</td><td id="unknownBFS">unknown</td><td id="unknownBFS">unknown</td></tr>';
		}
		echo "</table>";
		echo "<a onclick=\"checkacc('BFS');\" href=\"javascript:void(0)\" style='TEXT-DECORATION: none'><font color=#FF6600>Check accounts bayfiles.com >>></font></a><br><br>";
		$checkall = true;
	}
	if(count($obj->acc["rapidgator.net"]["accounts"])>0) {
		echo '<table id="tableRGT" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
			<tr class="flisttblhdr" valign="bottom">
				<td width="30%"><b>accounts rapidgator.net</b></td>
				<td width="15%"><b>Type</b></td>
				<td width="20%"><b>Validity</b></td>
				<td width="15%"><b>Traffic</b></td>
				<td width="20%"><b>Report</b></td>
			</tr>
		';
		for($i = 0; $i < count($obj->acc["rapidgator.net"]["accounts"]); $i++) {
			$account = $obj->acc["rapidgator.net"]["accounts"][$i];
			if (stristr($account,':')) $type = "account";
			else $type = "cookie";
			$account = substr($account, 0, 5)."****";
			echo '<tr class="flistmouseoff" align="center"><td><B>'.$account.'</B></td><td>'.$type.'</td><td id="unknownRGT">unknown</td><td id="unknownRGT">unknown</td><td id="unknownRGT">unknown</td></tr>';
		}
		echo "</table>";
		echo "<a onclick=\"checkacc('RGT');\" href=\"javascript:void(0)\" style='TEXT-DECORATION: none'><font color=#FF6600>Check accounts rapidgator.net >>></font></a><br><br>";
		$checkall = true;
	}
	if(count($obj->acc["filepost.com"]["accounts"])>0) {
		echo '<table id="tableFP" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
			<tr class="flisttblhdr" valign="bottom">
				<td width="30%"><b>accounts filepost.com</b></td>
				<td width="15%"><b>Type</b></td>
				<td><b>Validity</b></td>
				<td width="20%"><b>Report</b></td>
			</tr>
		';
		for($i = 0; $i < count($obj->acc["filepost.com"]["accounts"]); $i++) {
			$account = $obj->acc["filepost.com"]["accounts"][$i];
			if (stristr($account,':')) $type = "account";
			else $type = "cookie";
			$account = substr($account, 0, 5)."****";
			echo '<tr class="flistmouseoff" align="center"><td><b>'.$account.'</b></td><td>'.$type.'</td><td id="unknownFP">unknown</td><td id="unknownFP">unknown</td></tr>';
		}
		echo "</table>";
		echo "<a onclick=\"checkacc('FP');\" href=\"javascript:void(0)\" style='TEXT-DECORATION: none'><font color=#FF6600>Check accounts filepost.com >>></font></a><br><br>";
		$checkall = true;
	}
	if(count($obj->acc["real-debrid.com"]["accounts"])>0) {
		echo '<table id="tableREAL" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
			<tr class="flisttblhdr" valign="bottom">
				<td width="30%"><b>accounts real-debrid.com</b></td>
				<td width="15%"><b>Type</b></td>
				<td><b>Validity</b></td>
				<td width="20%"><b>Report</b></td>
			</tr>
		';
		for($i = 0; $i < count($obj->acc["real-debrid.com"]["accounts"]); $i++) {
			$account = $obj->acc["real-debrid.com"]["accounts"][$i];
			if (stristr($account,':')) $type = "account";
			else $type = "cookie";
			$account = substr($account, 0, 5)."****";
			echo '<tr class="flistmouseoff" align="center"><td><b>'.$account.'</b></td><td>'.$type.'</td><td id="unknownREAL">unknown</td><td id="unknownREAL">unknown</td></tr>';
		}
		echo "</table>";
		echo "<a onclick=\"checkacc('REAL');\" href=\"javascript:void(0)\" style='TEXT-DECORATION: none'><font color=#FF6600>Check accounts real-debrid.com >>></font></a><br><br>";
		$checkall = true;
	}
	if(count($obj->acc["alldebrid.com"]["accounts"])>0) {
		echo '<table id="tableALLD" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
			<tr class="flisttblhdr" valign="bottom">
				<td width="30%"><b>accounts alldebrid.com</b></td>
				<td width="15%"><b>Type</b></td>
				<td><b>Validity</b></td>
				<td width="20%"><b>Report</b></td>
			</tr>
		';
		for($i = 0; $i < count($obj->acc["alldebrid.com"]["accounts"]); $i++) {
			$account = $obj->acc["alldebrid.com"]["accounts"][$i];
			if (stristr($account,':')) $type = "account";
			else $type = "cookie";
			$account = substr($account, 0, 5)."****";
			echo '<tr class="flistmouseoff" align="center"><td><b>'.$account.'</b></td><td>'.$type.'</td><td id="unknownALLD">unknown</td><td id="unknownALLD">unknown</td></tr>';
		}
		echo "</table>";
		echo "<a onclick=\"checkacc('ALLD');\" href=\"javascript:void(0)\" style='TEXT-DECORATION: none'><font color=#FF6600>Check accounts alldebrid.com >>></font></a></div>";
		$checkall = true;
	}
	if(isset($checkall)) echo '<p align="right"><input type=button onclick="checkacc(\'all\');" value="Check all accounts"></p>';
}

?>