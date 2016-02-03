<?php


/* BlakDownloader 3.7.5 - Tu8 [ Public Edition ] */ 


error_reporting(7);
@set_magic_quotes_runtime(0);
ob_start();
ob_implicit_flush (TRUE);
ignore_user_abort (0);
if( !ini_get('safe_mode') ){

} 
define('vinaget', 'yes');
include("class.php");
$obj = new stream_get(); 

if ($obj->Deny == false && isset($_POST['urllist'])) $obj->main();
elseif(isset($_GET['infosv'])) $obj->notice();
############################################### Begin Secure ###############################################
elseif($obj->Deny == false) {
	if (!isset($_POST['urllist'])) {
		include ("hosts/hosts.php");
		asort($host);
?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml"><head profile="http://gmpg.org/xfn/11">
		<head>
			<link rel="SHORTCUT ICON" href="images/dove.png" type="image/x-icon" />
			<title>&#9734 ProDownloader 3.7.5 &#9734 - Free Premium Link Generator </title>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<meta name="keywords" content="<?php printf($obj->lang['version']); ?>, download, get, vinaget, file, generator, premium, link, sharing, bitshare.com, crocko.com, depositfiles.com, extabit.com, filefactory.com, filepost.com, filesmonster.com, freakshare.com, gigasize.com, hotfile.com, jumbofiles.com, letitbit.net, mediafire.com, megashares.com, netload.in, oron.com, rapidgator.net, rapidshare.com, ryushare.com, sendspace.com, share-online.biz, shareflare.net, uploaded.to, uploading.com" />
			<link title="Rapidleech Style" href="images/rl_style_pm.css" rel="stylesheet" type="text/css" />
			<link href='http://fonts.googleapis.com/css?family=Metal+Mania' rel='stylesheet' type='text/css'>
		</head>
		<body>
			<script type="text/javascript" language="javascript" src="images/jquery-1.7.1.min.js"></script>
			<script type="text/javascript" src="images/ZeroClipboard.js"></script>
			<script type="text/javascript" language="javascript">
				var title = '<?php echo $obj->title; ?>';
				var colorname = '<?php echo $obj->colorfn; ?>';
				var colorfile = '<?php echo $obj->colorfs; ?>';

				var more_acc ='<?php printf($obj->lang["moreacc"]); ?>';
				var less_acc ='<?php printf($obj->lang["lessacc"]); ?>';
				var d_error ='<?php printf($obj->lang["invalid"]); ?>';
				var d_succ1 ='<?php printf($obj->lang["dsuccess"]); ?>';
				var d_succ2 ='<?php printf($obj->lang["success"]); ?>';
				var get_loading ='<?php printf($obj->lang["getloading"]); ?>';
				var notf ='<?php printf($obj->lang["notfound"]); ?>';
			</script> 
			<!--
			<center><img src="images/logo.png" alt="RapidLeech PlugMod" border="0" /></center><br />
			-->
			<div id="showlistlink" class="showlistlink" align="center">
				<div style="border:1px #ffffff solid; width:960px; padding:5px; margin-top:50px;">
					<div id="listlinks"><textarea style='width:950px;height:400px' id="textarea"></textarea></div>
					<table style='width:950px;'><tr>
					<td width="50%" vAlign="left" align="left">	
					<input type='button' value="bbcode" onclick="return bbcode('list');" />
					<input type='button' id ='SelectAll' value="Select All"/>
					<input type='button' id="copytext" value="Copy To Clipboard"/>
					
					</td>
					<td id="report"  width="50%" align="center"></td>
					</tr></table>
				</div>
				<div style="width:120px; padding:5px; margin:2px;border:1px #ffffff solid;">
					<a onclick="return makelist(document.getElementById('showresults').innerHTML);" href="javascript:void(0)" style='TEXT-DECORATION: none'><font color=#FF6600>Click to close</font></a>
				</div>
			</div>
			<table align="center"><tbody>
				<tr>
				<!-- ########################## Begin Plugins ########################## -->
				<td valign="top">
					<table width="100%"  border="0">
						<tr><td valign="top">
							<table border="0" cellpadding="1" cellspacing="1">
								<tr><td width="160" height="100%"><div class="cell-plugin"><em><font face="metal mania" size="4" color="#ADFF2F"><b>Plugins</b></em></div></td></tr>
								<tr><td height="100%" style="padding:3px;">
									<div dir="rtl" align="left" style="overflow-y:scroll; height:255px; padding-left:05px;">
									<?php
										foreach ($host as $file => $site){
											$site = substr($site,0,-4);
											$site = str_replace("_",".",$site) ;
											echo "<span class='plugincollst'>" .$site."</span><br />";
										}
									?>
									</div><br />
									<div class="cell-plugin"><em><font face="metal mania" size="4" color="#ADFF2F"><b>Accounts</b></em></div>
									<table border="0">
										<tr><td height="100%" style="padding:3px;">
											<div dir="rtl" align="left" style="padding-left:5px;">
												<?php $obj->showplugin(); ?>
											</div>
										</td></tr>
									</table><br />
								</td></tr>
							</table>
						</td></tr>
					</table>
				</td>
				<!-- ########################## End Plugins ########################## -->
				<!-- ########################## Begin Main ########################### -->
				<td align="center" valign="top">
					<table border="0" cellpadding="0" cellspacing="1"><tbody>
						<tr>
							<td class="cell-nav"><a class="ServerFiles" href="./"><em><font face="Arial mania" size="4" color="#ADFF2F"><b><?php printf($obj->lang['main']); ?></b></em></a></td>
							<td class="cell-nav"><a class="ServerFiles" href="./?id=donate"><em><font face="Arial mania" size="4" color="#ADFF2F"><b><?php printf($obj->lang['donate']); ?></b></em></a></td>
							<td class="cell-nav"><a class="ServerFiles" href="./?id=listfile"><em><font face="Arial mania" size="4" color="#ADFF2F"><b><?php printf($obj->lang['listfile']); ?></b></em></a></td>
							<td class="cell-nav"><a class="ServerFiles" href="./?id=check"><em><font face="Arial mania" size="4" color="#ADFF2F"><b><?php printf($obj->lang['check']); ?></b></em></a></td>
							<?php if ($obj->Secure == true) 
							echo '<td class="cell-nav"><a class="ServerFiles" href="./login.php?go=logout"><em><font face="Arial mania" size="4" color="#ADFF2F"><b> '.$obj->lang['log'].' </b></em></a></td>'; ?>
						</tr>
					</tbody></table>
					<table id="tb_content"><tbody>
						<tr><td height="5px"></td></tr>
						<tr><td align="center">
<?php 
						#---------------------------- begin list file ----------------------------#
						if ((isset($_GET['id']) && $_GET['id']=='listfile') || isset($_POST['listfile']) || isset($_POST['option']) || isset($_POST['renn']) || isset($_POST['remove']))  {
							if($obj->listfile==true)$obj->fulllist();
							else echo "<BR><BR><font color=red size=2>".$obj->lang['notaccess']."</b></font>";
						}
						#---------------------------- end list file ----------------------------#

						#---------------------------- begin donate  ----------------------------#
						else if (isset($_GET['id']) && $_GET['id']=='donate') { 
?>
							<div align="center">
								<div id="wait"><?php printf($obj->lang['donations']); ?></div><BR>
								<form action="javascript:donate(document.getElementById('donateform'));" name="donateform" id="donateform">
									<table>
										<tr>
											<td>
												<?php printf($obj->lang['acctype']); ?> 
												<select name='type' id='type'>
													<option value="real-debrid">real-debrid.com</option>
													<option value="alldebrid">alldebrid.com</option>
													<option value="fast-debrid">fast-debrid.com</option>
													<option value="rapidshare">rapidshare.com</option>
													<option value="Premiumize">premiumize.me</option>
													<option value="bitshare">bitshare.com</option>
													<option value="hotfile">hotfile.com</option>
													<option value="depositfiles">depositfiles.com</option>
													<option value="fileserve">fileserve.com</option>
													<option value="filesonic">filesonic.com</option>
													<option value="wupload">wupload.com</option>
													<option value="oron">oron.com</option>
													<option value="uploading">uploading.com</option>
													<option value="uploadednet">uploaded.net</option>
													<option value="filefactory">filefactory.com</option>
													<option value="uploadstation">uploadstation.com</option>
													<option value="filejungle">filejungle.com</option>
													<option value="bayfiles">bayfiles.com</option>
													<option value="rapidgator">rapidgator.net</option>
													<option value="filepost">filepost.com</option>
												</select>
											</td>
											<td>
												&nbsp; &nbsp; &nbsp; <input type="text" name="accounts" id="accounts" value="" size="50" maxlength="50"><br />
											</td>
											<td>&nbsp; &nbsp; &nbsp; <input type=submit value="<?php printf($obj->lang['sbdonate']); ?>">
											</td>
										</tr>
									</table>
								</form>
								<div id="check"><font color=#FF6600>user:pass</font> or <font color=#FF6600>cookie</font></div>
							</div>
<?php					
						}
						#---------------------------- end donate  ----------------------------#

						#---------------------------- begin check  ---------------------------#
						else if (isset($_GET['id']) && $_GET['id']=='check'){
							if($obj->checkacc==true) include("checkaccount.php");
							else echo "<BR><BR><font color=red size=2>".$obj->lang['notaccess']."</b></font>";
						}
						#---------------------------- end check  ------------------------------#

						#---------------------------- begin get  ------------------------------#
						else {
?>
							<blaken><form action="javascript:get(document.getElementById('linkform'));" name="linkform" id="linkform">
							<br>
<center><span style="font-family: Arial mania;font-size:180%;background-image: url(http://i.imgur.com/lGVURbM.gif)">
							<font size= "5" color="ffffff">ProDownloader - 3 . 7 . 5</font></a></span></center><br>


								<BR>
								<textarea id='links' style='width:650px;height:80px;' name='links'></textarea><BR>
								<?php printf($obj->lang['example']); ?><BR>
								<input type="submit"  class="blkdownbutton"  id ='submit' value='<?php printf($obj->lang['sbdown']); ?>'/>&nbsp;&nbsp;&nbsp;
								<input type="button"  class="blkdownbutton" onclick="reseturl();return false;" value="Reset">&nbsp;&nbsp;&nbsp;
								<input type="checkbox" name="autoreset" id="autoreset" checked>
							</form></blaken><BR><BR>
							<div id="dlhere" align="left" style="display: none;">
								<BR><hr /><small style="color:#55bbff"><?php printf($obj->lang['dlhere']); ?></small>
								<div align="right"><a onclick="return bbcode('bbcode');" href="javascript:void(0)" style='TEXT-DECORATION: none'><font color=#FF6600>BB code</font></a>&nbsp;&nbsp;&nbsp;
								<a onclick="return makelist(document.getElementById('showresults').innerHTML);" href="javascript:void(0)" style='TEXT-DECORATION: none'><font color=#FF6600>Make List</font></a></div>
							</div>
							<div id="bbcode" align="center" style="display: none;"></div>
							<div id="showresults" align="center"></div>
<?php						
						}
						#---------------------------- end get  ------------------------------#
?>

<br><br><br><!-- Copyright please don't remove-->
<br>
<div>
<marquee  onMouseOver="this.scrollAmount=0" onMouseOut="this.scrollAmount=2" scrollamount="2" direction="up" loop="true" width="100%" height="60">
<center>
<font color="#ffffff" size="+1">Thank You For Visit This Site...</font><p><br>
<font color="#ff0000" size="+1">ProDownloader - Free Premium Link Generator </font><p><br>
<font color="#ffffff" size="+1">Daley Update Premium Account....</font><p><br>
<font color="#ffffff" size="+1">Now Working Host :- Turbobit , Uploaded , Uptobox , Rapidgator , Uploadable , 1fichier , Depfile , Letitbit ,</font><p><br>
<font color="#ffffff" size="+1">And More account comming soon........</font><p><br>
<font color="#ff0000" size="+1">If Your Link Not Work First Time ! , Then Retry Again </font><p><br>
<font color="#ffffff" size="+1"> Tell your friend about our services . Help us to improve our services </font>
</center>
</marquee>
</div><br>
							
<center>
<!-- COUNTER: BEGIN -->
		<a href='http://www.blogutils.net/' id='lnolt_' target='_blank' style='font-size:17px;font-weight:bold;text-decoration:none;color:white'>
		<script language='JavaScript' src='http://blogutils.net/olct/online.php?site=kolaysoft.mobie.in&interval=600'></script></a><a href='' target='_blank' style='font-size:17px;font-weight:bold;text-decoration:none;color:Pink'> User Online</a>
		<!-- COUNTER: END -->
<br><br><br><SPAN style="FONT-FAMILY: Arial; FONT-SIZE: 16px; color:#FFFFFF">Copyright 2015 by <a href='http://ProDownloader.tk/' target='_blank' > ProDownloader.tk</a>. All rights reserved. </SPAN><br></center>

															<!-- Copyright please don't remove-->


						</td></tr>
					</tbody></table>
				</td></tr>
				<!-- ########################## End Main ########################### -->
			</tbody></table>
			<script type="text/javascript" language="javascript" src="images/tables.js"></script> 
		</body>

	</html>

<?php
	} #(!$_POST['urllist'])
} 
############################################### End Secure ###############################################
else {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<link rel="SHORTCUT ICON" href="images/dove.png" type="image/x-icon" />
<title>&#9734 ProkDownloader 3.7.5 &#9734 - Free Premium Link Generator </title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="keyw;ords" content="&#9734 ProkDownloader 3.7.5 &#9734 - Free Premium Link Generator " />
<link title="VinaGetPro Style" href="images/login.css" rel="stylesheet" type="text/css" />
</head>
<body>
 <hgroup align="center">
<br /><a href="http://prodownloader.tk/" target="_blank" ><img src="http://s7.postimg.org/uzwmtvkzv/logo.png" alt="ProDownloader.tk" align="center"></a>
</hgroup>
<hr />
<div class='body'>
<br /><center>
<form class="form" action="login.php" method="post" />
<b>Please Put Password To Login</b><br /><br />
<input type="password" placeholder="Password" name="secure" size="30"/><br /><br />
Remember Me : <input type="checkbox" name="remember_me" />
<br/><br /><input class="button" type="submit" value="Login" />
<br />
<br />
</form>
</center>
</div>
<hr />
<br />
<center>   <p>
            Copyright &copy; 2015 <a href="http://prodownloader.tk/">ProDownloader</a>. All rights reserved.</p></center>
        </body>
<?php
}
ob_end_flush();
?>