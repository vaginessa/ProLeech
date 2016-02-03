<?php
error_reporting(7);
define('vinaget', 'yes');
include("class.php");

/* BlakDownloader 3.7.5 - Tu8 [ Public Edition ] */ 


function check_account($host,$account){
	global $obj;
	foreach ($obj->acc[$host]['accounts'] as $value)
		if ($account == $value) return true; 
	return false;
}
if (empty($_POST["accounts"])==false) {
	$obj = new stream_get();
	$type = $_POST['type'];

	$_POST["accounts"] = str_replace(" ","",$_POST["accounts"]);
	$account = trim($_POST['accounts']);
	$donate = false;
################################## DONATE ACC real-debrid.com #################################################################
	if($type == "real-debrid"){
		if(check_account("real-debrid.com",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$data = $obj->curl("https://www.real-debrid.com/ajax/login.php?user=".urlencode($user)."&pass=".urlencode($pass)."","","");
			//You are blocked for one hour because of too many attempts !
			if(strpos($data,"You are blocked")) die("You are blocked for one hour because of too many attempts !");
			elseif(strpos($data,"Your login informations are incorrect") || strpos($data,"Your account is not active or has been suspended") || strpos($data,"You are blocked"))
				die("false");
			else {
				preg_match('%(auth=.+);%U', $data, $cook);
				$cookie = $cook[1];
			}
		}
		else $cookie = $account;
		if(check_account("real-debrid.com",$cookie)==true) die("false");
		$cookie = preg_replace("/(auth=|AUTH=|Auth=)/","",$cookie);
		$data = $obj->curl("https://www.real-debrid.com","auth=".$cookie,"");
		if(preg_match('%<strong>Premium:</strong> (.*)                    </div>%U', $data, $matches)) {
			$obj->acc["real-debrid.com"]['accounts'][] = $account;
			$donate = true;
		}
	}
################################## DONATE ACC real-debrid.com #################################################################

################################## DONATE ACC alldebrid.com ###################################################################
	elseif($type == "alldebrid"){
		if(check_account("alldebrid.com",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$data = $obj->curl("http://www.alldebrid.com/register/?action=login&returnpage=","","login_login=".urlencode($user)."&login_password=".urlencode($pass)."");
			//you are banned 23min for non respect of AllDebrid flood policy (reason : Too mutch login fail.)
			if(strpos($data,"The password is not valid") || strpos($data,"You are banned"))
				die("false");
			else {
				preg_match("%uid=(.*);%U", $data, $matches);
				$cookie = $matches[1];
			}
		}
		else $cookie = $account;
		if(check_account("alldebrid.com",$cookie)==true) die("false");
		$cookie = preg_replace("/(uid=|UID=|Uid=)/","",$cookie);
		$data = $obj->curl("http://www.alldebrid.com/account/","uid=".$cookie,"");
		if(strpos($data,'</strong>Premium</li>')) {
			$obj->acc["alldebrid.com"]['accounts'][] = $account;
			$donate = true;
		}
	}
################################## DONATE ACC alldebrid.com ###################################################################

################################## DONATE ACC fast-debrid.com #################################################################
	elseif($type == "fast-debrid"){
		if(check_account("fast-debrid.com",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$data = $obj->curl("https://www.fast-debrid.com/lib/ajax/connection.php","","username=".$user."&password=".$pass."");
			if(strpos($data,"error_login"))
				die("false");
			else {
				preg_match("%PHPSESSID=(.*);%U", $data, $matches);
				$cookie = $matches[1];
			}
		}
		else $cookie = $account;
		if(check_account("fast-debrid.com",$cookie)==true) die("false");
		$cookie = preg_replace("/(phpsessid=|Phpsessid=|PHPSESSID=)/","",$cookie);
		$data = $obj->curl("https://www.fast-debrid.com/my-account","PHPSESSID=".$cookie,"");
		if(strpos($data,'myaccount">Premium</div>') || strpos($data,'myaccount">Platinium</div>')) {
			$obj->acc["fast-debrid.com"]['accounts'][] = $account;
			$donate = true;
		}
	}
################################## DONATE ACC fast-debrid.com #################################################################

################################## DONATE ACC rapidshare.com ##################################################################
	elseif($type == "rapidshare"){
		if(check_account("rapidshare.com",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$data = $obj->curl("http://api.rapidshare.com/cgi-bin/rsapi.cgi","","sub=getaccountdetails&withcookie=1&withpublicid=1&login=".$user."&cbf=RSAPIDispatcher&cbid=2&password=".$pass."");
			if(strpos($data,'Login failed'))
				die("false");
			else {
				$cookie  = $obj->cut_str($data, "ncookie=","\\n");
			}
		}
		else $cookie = $account;
		if(check_account("rapidshare.com",$cookie)==true) die("false");
		$cookie = preg_replace("/(enc=|Enc=|ENC=)/","",$cookie);
		$data = $obj->curl("http://api.rapidshare.com/cgi-bin/rsapi.cgi","","sub=getaccountdetails&withcookie=1&withpublicid=1&withsession=1&cookie=".$cookie."&cbf=RSAPIDispatcher&cbid=1");
		if(preg_match('/billeduntil=([0-9]+)/', $data, $matches)) {
			if (time() < $matches[1]) { 
				$obj->acc["rapidshare.com"]['accounts'][] = $account;
				$donate = true;
			}
		}
	}
################################## DONATE ACC rapidshare.com ##################################################################

################################## DONATE ACC Premiumize.me ##################################################################
	elseif($type == "Premiumize"){
		if(check_account("Premiumize.me",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$data = $obj->curl("https://secure.premiumize.me/?show=login","","username=".$user."&password=".$pass."&login=1");
			if(strpos($data,"Username and password do not match. Please try again!"))
				die("false");
			else {
				preg_match('/^Set-Cookie: (.*?);/m', $data, $matches);
				$cookie = $matches[1];
			}
		}
		else $cookie = $account;
		if(check_account("Premiumize.me",$cookie)==true) die("false");
		$cookie = preg_replace("/(user=|USER=|User=)/","",$cookie);
		$data = $obj->curl("https://secure.premiumize.me/?show=login","user=".$cookie,"");
		if(strpos($data,"Lifetime Platinum") || strpos($data,"remaining")) {
			$obj->acc["megaupload.com"]['accounts'][] = $account;
			$donate = true;
		}
	}
################################## DONATE ACC Premiumize.me ##################################################################

################################## DONATE ACC bitshare.com ####################################################################
	elseif($type == "bitshare"){
		if(check_account("bitshare.com",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$data=$obj->curl("http://bitshare.com/login.html","","user=$user&password=$pass&rememberlogin=&submit=Login");
			if(strpos($data,"Click here to login"))
				die("false");
			else {
				$cookie = $obj->GetCookies($data);
			}
		}
		else $cookie = $account;
		if(check_account("bitshare.com",$cookie)==true) die("false");
		$data = $obj->curl("http://bitshare.com/myaccount.html",$cookie,"");
		if(strpos($data,'Premium  <a href="http://bitshare.com/myupgrade.html">Extend</a>')) {
			$obj->acc["bitshare.com"]['accounts'][] = $account;
			$donate = true;
		}
	}
################################## DONATE ACC bitshare.com ####################################################################

################################## DONATE ACC hotfile.com #####################################################################
	elseif($type == "hotfile"){
		if(check_account("hotfile.com",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$data = $obj->curl("http://www.hotfile.com/login.php","","returnto=/&user=".$user."&pass=".$pass."&=Login");
			if(strpos($data,"Bad username/password combination"))
				die("false");
			else {
				preg_match('/^Set-Cookie: auth=(.*?);/m', $data, $matches);
				$cookie = $matches[1];
			}
		}
		else $cookie = $account;
		if(check_account("hotfile.com",$cookie)==true) die("false");
		$cookie = preg_replace("/(auth=|AUTH=|Auth=)/","",$cookie);
		$ch = @curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://hotfile.com/myaccount.html");
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIE, "auth=".$cookie);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:2.0.1) Gecko/20100101 Firefox/4.0.1');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60); 
		$data = curl_exec( $ch);
		curl_close($ch); 
		if(preg_match('%<p>Premium until: <span class="rightSide">(.+) <b>%U', $data, $matches)) {
			$obj->acc["hotfile.com"]['accounts'][] = $account;
			$donate = true;
		}
	}
################################## DONATE ACC hotfile.com #####################################################################

################################## DONATE ACC depositfiles.com ################################################################
	elseif($type == "depositfiles"){
		if(check_account("depositfiles.com",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$data=$obj->curl("http://depositfiles.com/login.php?return=%2F","lang_current=en","go=1&login=$user&password=$pass");
			if(strpos($data,"Your password or login is incorrect"))
				die("false");
			else {
				$cookie = $obj->GetCookies($data);
			}
		}
		else $cookie = $account;
		if(check_account("depositfiles.com",$cookie)==true) die("false");
		$data = $obj->curl("http://depositfiles.com/gold/payment_history.php",$cookie.';lang_current=en;',"");
		if(strpos($data,"You have Gold access until")) {
			$obj->acc["depositfiles.com"]['accounts'][] = $account;
			$donate = true;
		}
	}
################################## DONATE ACC depositfiles.com ################################################################

################################## DONATE ACC fileserve.com ###################################################################
	elseif($type == "fileserve"){
		if(check_account("fileserve.com",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$data = $obj->curl("http://fileserve.com","","");
			$cookie = $obj->GetCookies($data);
			$post['loginUserName'] = $user;
			$post['loginUserPassword'] = $pass;
			$post['loginFormSubmit'] = "Login";
			$data = $obj->curl("http://www.fileserve.com/login.php",$cookie,$post);
			if(strpos($data,"You are not logged in"))
				die("false");
		}
		else $cookie = $account;
		if(check_account("fileserve.com",$cookie)==true) die("false");
		$cookie = preg_replace("/(phpsessid=|Phpsessid=|PHPSESSID=)/","",$cookie);
		$data = $obj->curl("http://fileserve.com/dashboard.php","PHPSESSID=".$cookie,"");
		if(strpos($data,"<td><h4>Premium Until</h4></td>")) {
			$obj->acc["fileserve.com"]['accounts'][] = $account;
			$donate = true;
		}
	}
################################## DONATE ACC fileserve.com ###################################################################

################################## DONATE ACC filesonic.com ###################################################################
	elseif($type == "filesonic"){
		//==== Fix link FSN ====
		$url = "http://www.filesonic.com/";
		$data = $obj->curl("".$url."","","");
		if (preg_match('/ocation: (.*)/', $data, $fsnlink)) $url = trim($fsnlink[1]);
		$linkFSN= explode('/', $url);
		$urllogin = "http://".$linkFSN[2]."/user/login";
		//==== Fix link FSN ====
		if(check_account("filesonic.com",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$post['email']= $user;
			$post['password']= $pass;
			$page = $obj->curl("".$urllogin."","",$post);
			if(strpos($page,"No user found with such email")) 
				die("false");
			else {
				$cookies = $obj->GetCookies($page);
				$cookie = explode(" ", $cookies);
				$cookie = $cookie[3];
			}
		}
		else $cookie = $account;
		if(check_account("filesonic.com",$cookie)==true) die("false");
		$cookie = preg_replace("/(phpsessid=|Phpsessid=|PHPSESSID=)/","",$cookie);
		$data = $obj->curl($url."user/settings","PHPSESSID=".$cookie,""); 
		if(strpos($data,'Pro Membership Valid Until')) {
			$obj->acc["filesonic.com"]['accounts'][] = $account;
			$donate = true;
		}
	}
################################## DONATE ACC filesonic.com ###################################################################

################################## DONATE ACC wupload.com #####################################################################
	elseif($type == "wupload"){
		//==== Fix link ====
		$url = "http://www.wupload.com/";
		$data = $obj->curl("".$url."","","");
		if (preg_match('/ocation: (.*)/', $data, $wulink)) $url = trim($wulink[1]);
		$linkWU= explode('/', $url);
		$urllogin = "http://".$linkWU[2]."/account/login";
		//==== Fix link ====
		if(check_account("wupload.com",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$page = $obj->curl("".$urllogin."","","email=".$user."&redirect=%2F&password=".$pass."");
			if(strpos($page,"No user found with such email"))
				die("false");
			else {
				$cookie = $obj->GetCookies($page);
				preg_match('/lang=en; role=anonymous;(.*); PHPSESSID=(.*);/',$cookie,$temp);
				$cookie = $temp[2];
			}
		}
		else $cookie = $account;
		if(check_account("wupload.com",$cookie)==true) die("false");
		$cookie = preg_replace("/(phpsessid=|Phpsessid=|PHPSESSID=)/","",$cookie);
		$data = $obj->curl("http://".$linkWU[2]."/account/settings","PHPSESSID=".$cookie,"");  
		if(strpos($data,'Premium Membership Valid Until')) {
			$obj->acc["wupload.com"]['accounts'][] = $account;
			$donate = true;
		}
	}
################################## DONATE ACC wupload.com #####################################################################

################################## DONATE ACC oron.com ########################################################################
	elseif($type == "oron"){
		if(check_account("oron.com",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$data = $obj->curl("http://oron.com/login","lang=english","login=".$user."&password=".$pass."&op=login");
			if(strpos($data,"Incorrect Login or Password"))
				die("false");
			else {
				preg_match('/xfss=(.*)/', $data, $matches);
				$cookie = $matches[1];
			}
		}
		else $cookie = $account;
		if(check_account("oron.com",$cookie)==true) die("false");
		$cookie = preg_replace("/(xfss=|XFSS=|Xfss=)/","",$cookie);
		$data = $obj->curl("http://oron.com/?op=my_account","xfss=".$cookie,"");
		if(strpos($data,"Premium Account expires")) {
			$obj->acc["oron.com"]['accounts'][] = $account;
			$donate = true;
		}
	}
################################## DONATE ACC oron.com ########################################################################

################################## DONATE ACC uploading.com ###################################################################
	elseif($type == "uploading"){
		if(check_account("uploading.com",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$tid = str_replace(".","12",microtime(true));
			$data = $obj->curl("http://uploading.com/general/login_form/?JsHttpRequest=".$tid."-xml","","email=".$user."&password=".$pass."");
			if(strpos($data,"Incorrect e-mail\/password combination") || strpos($data,"captcha"))
				die("false");
			else $cookie = $obj->GetCookies($data);
		}
		else $cookie = "remembered_user=".$account;
		if(check_account("uploading.com",$cookie)==true) die("false");
		$data = $obj->curl("http://uploading.com/profile",$cookie,"");
		if(strpos($data,"Valid Until")) {
			$obj->acc["uploading.com"]['accounts'][] = $account;
			$donate = true;
		}
	}
################################## DONATE ACC uploading.com ###################################################################

################################## DONATE ACC uploaded.to #####################################################################
	elseif($type == "uploaded"){
		if(check_account("uploaded.to",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$page = $obj->curl("http://uploaded.to/io/login",'',"id=$user&pw=$pass");
			if(strpos($page,"password combination"))
				die("false");
			else {
				$cookie = $obj->GetCookies($page);
			}
		}
		else $cookie = $account;
		if(check_account("uploaded.to",$cookie)==true) die("false");
		$data = $obj->curl("http://uploaded.to",$cookie,"");  
		if(strpos($data,'<em>Premium</em>')) {
			$obj->acc["uploaded.to"]['accounts'][] = $account;
			$donate = true;
		}
	}
################################## DONATE ACC uploaded.to #####################################################################

################################## DONATE ACC uploaded.net #####################################################################
	elseif($type == "uploadednet"){
		if(check_account("uploaded.net",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$page = $obj->curl("http://uploaded.net/io/login",'',"id=$user&pw=$pass");
			if(strpos($page,"password combination"))
				die("false");
			else {
				$cookie = $obj->GetCookies($page);
			}
		}
		else $cookie = $account;
		if(check_account("uploaded.net",$cookie)==true) die("false");
		$data = $obj->curl("http://uploaded.net",$cookie,"");  
		if(strpos($data,'<em>Premium</em>')) {
			$obj->acc["uploaded.net"]['accounts'][] = $account;
			$donate = true;
		}
	}
################################## DONATE ACC uploaded.net #####################################################################

################################## DONATE ACC filefactory.com #################################################################
	elseif($type == "filefactory"){
		//==== Fix link ====
		$url = "http://filefactory.com/";
		$data = $obj->curl("".$url."","","");
		if (preg_match('/ocation: (.*)/', $data, $fftlink)) $url = trim($fftlink[1]);
		$linkFFT= explode('/', $url);
		$urllogin = "http://".$linkFFT[2]."/member/login.php";
		//==== Fix link ====
		if(check_account("filefactory.com",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$post["email"] = $user;
			$post["password"] = $pass;
			$page = $obj->curl("".$urllogin."","",$post);
			if(strpos($page,"Location: /member/login.php"))
				die("false");
			else {
				preg_match('/^Set-Cookie: ff_membership=(.*?);/m', $page, $matches);
				$cookie = $matches[1];
			}
		}
		else $cookie = $account;
		if(check_account("filefactory.com",$cookie)==true) die("false");
		$cookie = preg_replace("/(ff_membership=|Ff_membership=|FF_MEMBERSHIP=)/","",$cookie);
		$data = $obj->curl("".$url."member/","ff_membership=".$cookie,"");
		if(strpos($data,"Premium member until")) {
			$obj->acc["filefactory.com"]['accounts'][] = $account;
			$donate = true;
		}
	}
################################## DONATE ACC filefactory.com #################################################################

################################## DONATE ACC uploadstation.com ###############################################################
	elseif($type == "uploadstation"){
		if(check_account("uploadstation.com",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$page = $obj->curl("http://uploadstation.com/login.php","","loginUserName=".$user."&loginUserPassword=".$pass."&autoLogin=on&loginFormSubmit=Login");
			if(strpos($page,"Username doesn't exist."))
				die("false");
			else {
				preg_match('/PHPSESSID=(.*);/',$page,$temp);
				$cookie = $temp[1];
			}
		}
		else $cookie = $account;
		if(check_account("uploadstation.com",$cookie)==true) die("false");
		$cookie = preg_replace("/(phpsessid=|Phpsessid=|PHPSESSID=)/","",$cookie);
		$data = $obj->curl("http://uploadstation.com/dashboard.php","PHPSESSID=".$cookie,"");
		if(strpos($data,"<span>PREMIUM</span>")) {
			$obj->acc["uploadstation.com"]['accounts'][] = $account;
			$donate = true;
		}
	}
################################## DONATE ACC uploadstation.com ###############################################################

################################## DONATE ACC filejungle.com ##################################################################
	elseif($type == "filejungle"){
		if(check_account("filejungle.com",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$page = $obj->curl("http://filejungle.com/login.php","","loginUserName=".$user."&loginUserPassword=".$pass."&loginFormSubmit=");
			if(strpos($page,"Username doesn't exist."))
				die("false");
			else {
				preg_match('/PHPSESSID=(.*);/',$page,$temp);
				$cookie = $temp[1];
			}
		}
		else $cookie = $account;
		if(check_account("filejungle.com",$cookie)==true) die("false");
		$cookie = preg_replace("/(phpsessid=|Phpsessid=|PHPSESSID=)/","",$cookie);
		$data = $obj->curl("http://filejungle.com/dashboard.php","PHPSESSID=".$cookie,"");
		if(strpos($data,'premium_blue_type">PREMIUM')) {
			$obj->acc["filejungle.com"]['accounts'][] = $account;
			$donate = true;
		}
	}
################################## DONATE ACC filejungle.com ##################################################################

################################## DONATE ACC bayfiles.com ####################################################################
	elseif($type == "bayfiles"){
		if(check_account("bayfiles.com",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$page = $obj->curl("http://bayfiles.com/ajax_login","","action=login&username=".$user."&password=".$pass."&next=%252F&=");
			if(strpos($page,"Login failed. Please try again"))
				die("false");
			else {
				preg_match('/SESSID=(.*);/',$page,$temp);
				$cookie = $temp[1];
			}
		}
		else $cookie = $account;
		if(check_account("bayfiles.com",$cookie)==true) die("false");
		$cookie = preg_replace("/(SESSID=|sessid=|Sessid=)/","",$cookie);
		$data = $obj->curl("http://bayfiles.com/account","SESSID=".$cookie,"");
		if(strpos($data,'<p>Premium</p>')) {
			$obj->acc["bayfiles.com"]['accounts'][] = $account;
			$donate = true;
		}
	}
################################## DONATE ACC bayfiles.com ####################################################################

################################## DONATE ACC rapidgator.net ##################################################################
	elseif($type == "rapidgator"){
		if(check_account("rapidgator.net",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$data = $obj->curl("http://rapidgator.net/auth/login","","LoginForm[email]=".$user."&LoginForm[password]=".$pass."&LoginForm[rememberMe]=1");
			if(strpos($data,"Please fix the following input errors:"))
				die("false");
			else {
				preg_match('/user__=(.*)/', $data, $matches);
				$cookie = $matches[1];
			}
		}
		else $cookie = $account;
		if(check_account("rapidgator.net",$cookie)==true) die("false");
		$cookie = preg_replace("/(user__=|USER__=|User__=)/","",$cookie);
		$data = $obj->curl("http://rapidgator.net/article/premium","user__=".$cookie,"");
		if(strpos($data,"You already have premium")) {
			$obj->acc["rapidgator.net"]['accounts'][] = $account;
			$donate = true;
		}
	}
################################## DONATE ACC rapidgator.net ##################################################################

################################## DONATE ACC filepost.com ####################################################################
	elseif($type == "filepost"){
		if(check_account("filepost.com",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$tid = str_replace(".","12",microtime(true));
			$data = $obj->curl("http://filepost.com/general/login_form/?JsHttpRequest=".$tid."-xml","","email=".$user."&password=".$pass."");
			if(strpos($data,"Incorrect e-mail\/password combination") || strpos($data,"captcha"))
				die("false");
			else $cookie = $obj->GetCookies($data);
		}
		else $cookie = "SID=".$account;
		if(check_account("filepost.com",$cookie)==true) die("false");
		$data = $obj->curl("http://filepost.com/profile/",$cookie,"");
		if(strpos($data,"Account type: <span>Premium<")) {
			$obj->acc["filepost.com"]['accounts'][] = $account;
			$donate = true;
		}
	}
################################## DONATE ACC filepost.com ####################################################################

################################## Save Account ###############################################################################
	if($donate == true && is_array($obj->acc) && count($obj->acc) > 0) {
		$str = "<?php";
		$str .= "\n";
		$str .= "\n\$this->acc = array(";
		$str .= "\n";
		$str .= "# Example: 'accounts'	=> array('user:pass','cookie'),\n";
		$str .= "# Example with letitbit.net: 'accounts'    => array('user:pass,cookie,prekey=xxxx'),\n";
		$str .= "\n";
		foreach ($obj->acc as $host => $accounts) {
			$str .= "\n	'".$host."'		=> array(";
			$str .= "\n								'max_size'	=> ".($accounts['max_size']?$accounts['max_size']:102400).",";
			$str .= "\n								'accounts'	=> array(";
			foreach ($accounts['accounts'] as $acc) {
				$str .= "\"".$acc."\",";
			}
			$str .= "),";
			$str .= "\n							),";
			$str .= "\n";
		}
		$str .= "\n);";
		$str .= $obj->max_size_other_host ? "\n\$this->max_size_other_host = ".$obj->max_size_other_host.";" : "\n\$this->max_size_other_host = 102400;";
		$str .= "\n";
		$str .= "\n?>";
		$accountPath = "data/account.php";
		$CF = fopen ($accountPath, "w")
		or die('<CENTER><font color=red size=3>could not open file! Try to chmod the file "<B>data/account.php</B>" to 666</font></CENTER>');
		fwrite ($CF, $str)
		or die('<CENTER><font color=red size=3>could not write file! Try to chmod the file "<B>data/account.php</B>" to 666</font></CENTER>');
		fclose ($CF); 
		@chmod($accountPath, 0666);

		echo "true";
	}
	else echo "false";
################################## Save Account ###############################################################################

}

?>