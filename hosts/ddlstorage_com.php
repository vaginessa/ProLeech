<?php
if (preg_match('@^https?://(www\.)?ddlstorage\.com/\w{12}@i', $url, $lnk)) {
	$url = $lnk[0];
	unset($lnk);
	$page = $this->curl($url, '', '');
	if (stripos($page, 'The file you were looking for could not be found') !== false) die(Tools_get::report($Original, 'dead'));

	$account = trim($this->get_account('ddlstorage.com'));
	if (stripos($account, ':') !== false) list($user, $pass) = explode(':', $account, 2);
	else $cookie = $account;
	if (!empty($cookie) || (!empty($user) && !empty($pass))) {
		if (empty($cookie)) $cookie = $this->get_cookie('ddlstorage.com');
		if (empty($cookie)) {
			$post = array();
			$post['login'] = urlencode($user);
			$post['password'] = urlencode($pass);
			$post['op'] = 'login';
			$post['redirect'] = '';
			$page = $this->curl('http://ddlstorage.com/?op=login', '', $post);
			if (preg_match('@^[\s\t\r\n]*(Access from \w+ is not allowed)@i', substr($page, strpos($page, "\r\n\r\n") + 4), $err)) die(Tools_get::report($Original, 'Host: '.$err[1]));
			if (stripos(substr($page, 0, strpos($page, "\r\n\r\n")), "\nSet-Cookie: xfss=") === false) die(Tools_get::report($Original, 'erroracc'));
			$cookie = $this->GetCookies($page);
			$this->save_cookies('ddlstorage.com', $cookie);
		}
		$this->cookie = $cookie;
		$page = $this->curl($url, $cookie, '');
		if (preg_match('@^[\s\t\r\n]*(Access from \w+ is not allowed)@i', substr($page, strpos($page, "\r\n\r\n") + 4), $err)) die(Tools_get::report($Original, 'Host: '.$err[1]));

		if (!preg_match('@https?://[^/\r\n\:]+(?:\:\d+)?/(?:(?:files)|(?:dl?))/[^\'\"\t<>\r\n]+@i', $page, $dllink)) {
			$page2 = $this->cut_str($page, '<form name="F1" method="POST"', '</form>');
			if (empty($page2)) die(Tools_get::report($Original, 'erroracc'));
			$post = array();
			$post['op'] = $this->cut_str($page2, 'name="op" value="', '"');
			$post['id'] = $this->cut_str($page2, 'name="id" value="', '"');
			$post['rand'] = $this->cut_str($page2, 'name="rand" value="', '"');
			$post['referer'] = '';
			$post['method_premium'] = urlencode(html_entity_decode($this->cut_str($page2, 'name="method_premium" value="', '"')));
			$post['down_direct'] = 1;
			unset($page2);

			$page = $this->GetPage($link, $cookie, $post);

			preg_match('@https?://[^/\r\n\:]+(?:\:\d+)?/(?:(?:files)|(?:dl?))/[^\'\"\t<>\r\n]+@i', $page, $dllink);
		}

		if (!empty($dllink[0])) {
			$link = $dllink[0];
			$size_name = Tools_get::size_name($link, $this->cookie);
			$filesize = $size_name[0];
			$filename = $size_name[1];
		} else {
			$cookie = '';
			$this->save_cookies('ddlstorage.com', '');
		}
	}
}

//[25-11-2012] Written by Th3-822.

?>