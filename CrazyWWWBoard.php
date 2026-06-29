<?php
	// DB Information
	$db_host = 'localhost';
	$db_id = '';
	$db_pw = '';
	$db_name = '';
	$db_prefix = 'cwb';

	if (!function_exists("mysql_connect")) {
		error("MySQL API is not supported.");
	}

	$db_conn = @mysql_connect($db_host, $db_id, $db_pw);

	if (!$db_conn) {
		error("MySQL connection error");
	}

	mysql_query("set names utf8", $db_conn);

	if (!@mysql_select_db($db_name ,$db_conn)) {
		error("MySQL selection error");
	}

	$db = $_GET['db'];

	if(!$db)
		error("You must specify the database name.");

	// $db값의 유효성 체크
	if(is_alphanum($db) == false)
		error("DB name is not valid.");

	// 소문자로 바꿈
	$db = strtolower($db);

	$result = @mysql_query("SELECT id FROM ${db_prefix}_admin", $db_conn);

	if ($result === false) {
		$result = @mysql_query("CREATE TABLE ${db_prefix}_admin (id varchar(30) primary key, Password text, Message text, HomeURL text, HomeTarget text, BackURL text, BackTarget text, LinkTarget text, ViewMode text, HTMLMode text, AdminName text, AdminEmail text, MailToAdmin text, MailToPoster text, ListOrder text, NameFirst text, ArticleNumber text, VirtualNumber text, FolderIcon text, UploadEnable text, TypeIcon text, Language text, WriteMode text, HideSearch text, ListBorderSize text, ListFontSize text, ListTitleFontColor text, ListTitleBgColor text, ListArticleFontColor text, ListArticleBgColor text, ReadTitleBorderSize text, ReadTextBorderSize text, ReadFontSize text, ReadTitleFontColor text, ReadTitleBgColor text, ReadTextFontColor text, ReadTextBgColor text, ReadTextFontType text, WriteTitleFontColor text, WriteTitleBgColor text, ArticlesPerPage text, UseSysDefHtml text, HtmlHead text, HtmlTail text, DefaultText text, UseSysDefMail text, MailHead text, MailTail text);", $db_conn);

		if ($result === false) {
			error("Can't create admin table");
		}

	}

	// 모드
	$mode = $_REQUEST["mode"];

	// DB가 없을 경우
	if (!file_exists("data/$db")) {
		error("DB is not found. If you want to create DB, just make 'data/$db' directory");
	}

	// 설정을 불러온다
	$result = @mysql_query("SELECT * FROM ${db_prefix}_admin WHERE id='$db';");

	$r = @mysql_num_rows($result);

	// 설정을 불러오는데 실패했을 경우	
	if($r == 0)
	{
		// 비정상적으로 종료된 것이라면
		if($r === false)
			error("Can't open admin table");

		// DB 만들기
		if($mode === null)
		{
			$cPassword = '0000';
			$cMessage = 'This text will be appeared at top of the articles';
			$cHomeURL = '';
			$cHomeTarget = '_self';
			$cBackURL = '';
			$cBackTarget = '_self';
			$cLinkTarget = '_top';
			$cViewMode = '0';
			$cHTMLMode = 'false';
			$cAdminName = 'Administrator';
			$cAdminEmail = '';
			$cMailToAdmin = 'false';
			$cMailToPoster = 'false';

			$cListOrder = 'thread';
			$cNameFirst = 'false';
			$cArticleNumber = 'true';
			$cVirtualNumber = 'false';
			$cFolderIcon = 'true';
			$cUploadEnable = 'true';
			$cTypeIcon = 'true';
			$cLanguage = 'korean';
			$cWriteMode = 'anybody';
			$cHideSearch = 'false';
			$cListBorderSize = '0';
			$cListFontSize = '2';
			$cListTitleFontColor = 'black';
			$cListTitleBgColor = '#ffffcc';
			$cListArticleFontColor = 'black';
			$cListArticleBgColor = '#f0f0f0';
			$cReadTitleBorderSize = '0';
			$cReadTextBorderSize = '0';
			$cReadFontSize = '2';
			$cReadTitleFontColor = 'black';
			$cReadTitleBgColor = '#ffffcc';
			$cReadTextFontColor = 'black';
			$cReadTextBgColor = '#f0f0f0';
			$cReadTextFontType = '굴림';
			$cWriteTitleFontColor = 'black';
			$cWriteTitleBgColor = '#f0f0f0';
			$cArticlesPerPage = '15';
			
			$cUseSysDefHtml = "true";
			$cHtmlHead = "";
			$cHtmlTail = "";
			$cDefaultText = "";
			$cUseSysDefMail = "true";
			$cMailHead = "";
			$cMailTail = "";

			$cPassword = crypt($cPassword, 'SY');

			$result = @mysql_query("CREATE TABLE ${db_prefix}_board_$db (no int primary key auto_increment, timeYear text, timeMon text, timeDay text, timeHour text, timeMin text, remoteAddr text, remoteHost text, queryName text, queryEmail text, querySubject text, queryPassword text, queryText text, readCount text, fileName text, realFileName text, grpno int, parent int default 0, grpord int default 0, depth int default 1)");
			

			if ($result === false) {
				error("Can't create board table");
			}

			$result = @mysql_query("INSERT INTO ${db_prefix}_admin (id, Password, Message, HomeURL, HomeTarget, BackURL, BackTarget, LinkTarget, ViewMode, HTMLMode, AdminName, AdminEmail, MailToAdmin, MailToPoster, ListOrder, NameFirst, ArticleNumber, VirtualNumber, FolderIcon, UploadEnable, TypeIcon, Language, WriteMode, HideSearch, ListBorderSize, ListFontSize, ListTitleFontColor, ListTitleBgColor, ListArticleFontColor, ListArticleBgColor, ReadTitleBorderSize, ReadTextBorderSize, ReadFontSize, ReadTitleFontColor, ReadTitleBgColor, ReadTextFontColor, ReadTextBgColor, ReadTextFontType, WriteTitleFontColor, WriteTitleBgColor, ArticlesPerPage, UseSysDefHtml, HtmlHead, HtmlTail, DefaultText, UseSysDefMail, MailHead, MailTail) VALUES ('$db', '$cPassword', '$cMessage', '$cHomeURL', '$cHomeTarget', '$cBackURL', '$cBackTarget', '$cLinkTarget', '$cViewMode', '$cHTMLMode', '$cAdminName', '$cAdminEmail', '$cMailToAdmin', '$cMailToPoster', '$cListOrder', '$cNameFirst', '$cArticleNumber', '$cVirtualNumber', '$cFolderIcon', '$cUploadEnable', '$cTypeIcon', '$cLanguage', '$cWriteMode', '$cHideSearch', '$cListBorderSize', '$cListFontSize', '$cListTitleFontColor', '$cListTitleBgColor', '$cListArticleFontColor', '$cListArticleBgColor', '$cReadTitleBorderSize', '$cReadTextBorderSize', '$cReadFontSize', '$cReadTitleFontColor', '$cReadTitleBgColor', '$cReadTextFontColor', '$cReadTextBgColor', '$cReadTextFontType', '$cWriteTitleFontColor', '$cWriteTitleBgColor', '$cArticlesPerPage', '$cUseSysDefHtml', '$cHtmlHead', '$cHtmlTail', '$cDefaultText', '$cUseSysDefMail', '$cMailHead', '$cMailTail');");

			if ($result === false) {
				error("Can't insert into board table");
			}

			echo "Config has been created. Default password is 0000";

			exit;	
		}

		if($mode != "admin" && $mode != "adminsave")
			error("db is not defined.");
	}
	else
	{	
		// 데이터를 불러온다
		$settings = mysql_fetch_array($result);
	}
	
	// 만약 어드민 모드라면
	if($mode == "admin")
	{
		$password = $_POST["password"];

		if($password === null)
		{
			if ($settings["UseSysDefHtml"] == "true") include "message/head.html";
			else echo $settings["HtmlHead"];
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?db=<?php echo $db; ?>&mode=admin">
<table align="center">
<tr align="center">
<td>DB Name</td><td><?php echo $db; ?></td>
</tr>
<tr align="center">
<td>Access Mode</td><td>admin</td>
</tr>
<tr align="center">
<td>Password</td><td><input type="password" name="password" /></td>
</tr>
<tr align="center">
<td colspan="2"><input type="submit" value="Check out" /></td>
</tr>
</table>
</form>
<?php
			if ($settings["UseSysDefHtml"] == "true") include "message/tail.html";
			else echo $settings["HtmlTail"];
			exit;
		}
		else
		{
			if(strcmp(crypt($password, "SY"), $settings["Password"]) != 0)
				error("Password incorrect.");

			if ($settings["UseSysDefHtml"] == "true") include "message/head.html";
			else echo $settings["HtmlHead"];
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?db=<?php echo $db; ?>&mode=adminsave">
<input type="hidden" name="password" value="<?php echo htmlspecialchars($password); ?>" />
<table align="center">
<tr>
<td colspan="2" align="center"><font size="5" face="arial"><strong>'CrazyGuestbook' Admin Configuration</strong></font></td>
</tr>
<tr>
<td colspan="2" align="center"><strong>Web Board Information</strong></td>
</tr>
<tr>
<td align="center">New Password</td>
<td><input name="cPassword" type="password" />&nbsp;Re-enter Password&nbsp;<input type="password" name="cPassword2" /></td>
</tr>
<tr>
<td align="center">Title Message</td>
<td><input name="cMessage" value="<?php echo htmlspecialchars($settings["Message"]); ?>" /></td>
</tr>
<tr>
<td align="center">HomePage URL</td>
<td><input name="cHomeURL" value="<?php echo htmlspecialchars($settings["HomeURL"]); ?>" />&nbsp;Target Frame&nbsp;
<input name="cHomeTarget" value="<?php echo htmlspecialchars($settings["HomeTarget"]); ?>" /></td>
</tr>
<tr>
<td align="center">Back URL</td>
<td><input name="cBackURL" value="<?php echo htmlspecialchars($settings["BackURL"]); ?>" />&nbsp;Target Frame&nbsp;
<input name="cBackTarget" value="<?php echo htmlspecialchars($settings["BackTarget"]); ?>" /></td>
</tr>
<tr><td colspan="2" align="center"><strong>Administrator Contact Information</strong></td></tr>
<tr>
<td align="center">Administrator Name</td>
<td><input name="cAdminName" value="<?php echo htmlspecialchars($settings["AdminName"]); ?>" /></td>
</tr>
<tr>
<td align="center">Administrator Email</td>
<td><input name="cAdminEmail" value="<?php echo htmlspecialchars($settings["AdminEmail"]); ?>" /></td>
</tr>
<tr>
<td align="center">Mail to Administrator</td>
<td><input required="required" type="radio" name="cMailToAdmin" value="true" <?php if($settings["MailToAdmin"] == "true") echo 'checked="checked" '; ?>/>&nbsp;
Yes, I wanna get a posting article by e-mail,&nbsp;
<input type="radio" name="cMailToAdmin" value="false"  <?php if($settings["MailToAdmin"] == "false") echo 'checked="checked" '; ?>/>&nbsp;
No, don't do that</td>
</tr>
<tr>
<td align="center">Mail to Poster</td>
<td><input required="required" type="radio" name="cMailToPoster" value="true" <?php if($settings["MailToPoster"] == "true") echo 'checked="checked" '; ?>/>&nbsp;
Yes, Poster wanna get a reply article by e-mail,&nbsp;
<input type="radio" name="cMailToPoster" value="false"  <?php if($settings["MailToPoster"] == "false") echo 'checked="checked" '; ?>/>&nbsp;
No, don't do that</td>
</tr>
<tr><td colspan="2" align="center"><strong>Article Configuration</strong></td></tr>
<tr>
<td align="center">List Order</td>
<td>
<input type="radio" name="cListOrder" value="date" <?php if ($settings["ListOrder"] == "date") echo "checked"; ?> />&nbsp;By Date&nbsp;
<input type="radio" name="cListOrder" value="thread" <?php if ($settings["ListOrder"] == "thread") echo "checked"; ?> />&nbsp;By Thread&nbsp;
</td>
</tr>
<tr>
<td align="center">Name First</td>
<td>
<input type="radio" name="cNameFirst" value="false" <?php if ($settings["NameFirst"] == "false") echo "checked"; ?> />&nbsp;Locate name field after subject&nbsp;
<input type="radio" name="cNameFirst" value="true" <?php if ($settings["NameFirst"] == "true") echo "checked"; ?> />&nbsp; Locate name field before subject&nbsp;
</td>
</tr>
<tr>
<td align="center">Article Number</td>
<td>
<input type="radio" name="cArticleNumber" value="true" <?php if ($settings["ArticleNumber"] == "true") echo "checked"; ?> />&nbsp;Show article number&nbsp;
<input type="radio" name="cArticleNumber" value="false" <?php if ($settings["ArticleNumber"] == "false") echo "checked"; ?> />&nbsp;Hide article number&nbsp;
</td>
</tr>
<tr>
<td align="center">Virtual Number</td>
<td>
<input type="radio" name="cVirtualNumber" value="true" <?php if ($settings["VirtualNumber"] == "true") echo "checked"; ?> />&nbsp;Show virtual number&nbsp;
<input type="radio" name="cVirtualNumber" value="false" <?php if ($settings["VirtualNumber"] == "false") echo "checked"; ?> />&nbsp;Show physical number&nbsp;
</td>
</tr>
<tr>
<td align="center">Folder Icon</td>
<td>
<input type="radio" name="cFolderIcon" value="true" <?php if ($settings["FolderIcon"] == "true") echo "checked"; ?> />&nbsp;Show folder icon&nbsp;
<input type="radio" name="cFolderIcon" value="false" <?php if ($settings["FolderIcon"] == "false") echo "checked"; ?> />&nbsp;Hide folder icon&nbsp;
</td>
</tr>
<tr>
<td align="center">File Upload</td>
<td>
<input type="radio" name="cUploadEnable" value="true" <?php if ($settings["UploadEnable"] == "true") echo "checked"; ?> />&nbsp;Enable&nbsp;
<input type="radio" name="cUploadEnable" value="false" <?php if ($settings["UploadEnable"] == "false") echo "checked"; ?> />&nbsp;Disable&nbsp;
</td>
</tr>
<tr>
<td align="center">File type icon</td>
<td>
<input type="radio" name="cTypeIcon" value="true" <?php if ($settings["TypeIcon"] == "true") echo "checked"; ?> />&nbsp;Show&nbsp;
<input type="radio" name="cTypeIcon" value="false" <?php if ($settings["TypeIcon"] == "false") echo "checked"; ?> />&nbsp;Hide&nbsp;
</td>
</tr>
<tr>
<td align="center">Link Target</td>
<td>
<input required="required" type="radio" name="cLinkTarget" value="_self" 
<?php if($settings["LinkTarget"] !== null && ($settings["LinkTarget"] == "" || $settings["LinkTarget"] == "_self")) echo 'checked="checked" '; ?>/>&nbsp;Current Frame,&nbsp;
<input type="radio" name="cLinkTarget" value="_top" <?php if($settings["LinkTarget"] == "_top") echo 'checked="checked" '; ?>/> Full Screen</td>
</tr>
<tr>
<td align="center">View Mode</td>
<td><input required="required" type="radio" name="cViewMode" value="0" <?php if($settings["ViewMode"] == "0") echo 'checked="checked" '; ?>/> English</td>
</tr>
<tr>
<td align="center">Post Permission</td>
<td>
<input type="radio" name="cWriteMode" value="admin" <?php if ($settings["WriteMode"] == "admin") echo "checked"; ?> />&nbsp;Only Admin&nbsp;
<input type="radio" name="cWriteMode" value="anybody" <?php if ($settings["WriteMode"] == "anybody") echo "checked"; ?> />&nbsp;Anybody&nbsp;
</td>
</tr>
<tr>
<td align="center">HTML Mode</td>
<td><input required="required" type="radio" name="cHTMLMode" value="true" <?php if($settings["HTMLMode"] == "true") echo 'checked="checked" '; ?>/> Accept HTML,&nbsp;
<input type="radio" name="cHTMLMode" value="false" <?php if($settings["HTMLMode"] == "false") echo 'checked="checked" '; ?>/> Ignore HTML &amp; Auto Link</td>
</tr>
<tr>
<td align="center">Hide Search</td>
<td>
<input type="radio" name="cHideSearch" value="true" <?php if ($settings["HideSearch"] == "true") echo "checked"; ?> />&nbsp;Hide Search&nbsp;
<input type="radio" name="cHideSearch" value="false" <?php if ($settings["HideSearch"] == "false") echo "checked"; ?> />&nbsp;Show Search&nbsp;
</td>
</tr>
<tr>
<td align="center">Articles per Page</td>
<td><input required="required" name="cArticlesPerPage" value="<?php echo htmlspecialchars($settings["ArticlesPerPage"]); ?>" /> ( Zero means limitless )</td>
</tr>
<tr><td colspan="2" align="center"><strong>User Interface / Common</strong></td></tr>
<tr>
<td align="center">HTML Header, Tailer</td>
<td>
<input type="radio" name="cUseSysDefHtml" value="true" <?php if ($settings["UseSysDefHtml"] == "true") echo "checked"; ?> />&nbsp;I want to use system default&nbsp;
<input type="radio" name="cUseSysDefHtml" value="false" <?php if ($settings["UseSysDefHtml"] == "false") echo "checked"; ?> />&nbsp;I want to use my own html below&nbsp;
</td>
</tr>

<tr>
<td align="center">HTML Header</td>
<td><textarea name="cHtmlHead" cols="60" rows="4"><?=htmlspecialchars($settings["HtmlHead"])?></textarea></td>
</tr>

<tr>
<td align="center">HTML Tailer</td>
<td><textarea name="cHtmlTail" cols="60" rows="4"><?=htmlspecialchars($settings["HtmlTail"])?></textarea></td>
</tr>

<tr>
<td align="center">Default Text</td>
<td><textarea name="cDefaultText" cols="60" rows="4"><?=htmlspecialchars($settings["cDefaultText"])?></textarea></td>
</tr>

<tr><td colspan="2" align="center"><strong>User Interface / List Module</strong></td></tr>
<tr>
<td align="center">Border Size</td>
<td>
<input type="text" name="cListBorderSize" value="<?=$settings["ListBorderSize"]?>" />
</td>
</tr>
<tr>
<td align="center">Font Size</td>
<td>
<input type="text" name="cListFontSize" value="<?=$settings["ListFontSize"]?>" />
</td>
</tr>
<tr>
<td align="center">Title Font Color</td>
<td>
<input type="text" name="cListTitleFontColor" value="<?=$settings["ListTitleFontColor"]?>" />&nbsp;Title BG Color&nbsp;
<input type="text" name="cListTitleBgColor" value="<?=$settings["ListTitleBgColor"]?>" />
</td>
</tr>
<tr>
<td align="center">Article Font Color</td>
<td>
<input type="text" name="cListArticleFontColor" value="<?=$settings["ListArticleFontColor"]?>" />&nbsp;Article BG Color&nbsp;
<input type="text" name="cListArticleBgColor" value="<?=$settings["ListArticleBgColor"]?>" />
</td>
</tr>
<tr><td colspan="2" align="center"><strong>User Interface / Read Module</strong></td></tr>
<tr>
<td align="center">Title Border Size</td>
<td>
<input type="text" name="cReadTitleBorderSize" value="<?=$settings["ReadTitleBorderSize"]?>" />
</td>
</tr>
<tr>
<td align="center">Text Border Size</td>
<td>
<input type="text" name="cReadTextBorderSize" value="<?=$settings["ReadTitleBorderSize"]?>" />
</td>
</tr>
<tr>
<td align="center">Font Size</td>
<td>
<input type="text" name="cReadFontSize" value="<?=$settings["ReadFontSize"]?>" />
</td>
</tr>
<tr>
<td align="center">Title Font Color</td>
<td>
<input type="text" name="cReadTitleFontColor" value="<?=$settings["ReadTitleFontColor"]?>" />&nbsp;Title BG Color&nbsp;
<input type="text" name="cReadTitleBgColor" value="<?=$settings["ReadTitleBgColor"]?>" />
</td>
</tr>
<tr>
<td align="center">Text Font Color</td>
<td>
<input type="text" name="cReadTextFontColor" value="<?=$settings["ReadTextFontColor"]?>" />&nbsp;Text BG Color&nbsp;
<input type="text" name="cReadTextBgColor" value="<?=$settings["ReadTextBgColor"]?>" />
</td>
</tr>
<tr>
<td align="center">Text Font Type</td>
<td>
<input type="text" name="cReadTextFontType" value="<?=$settings["ReadTextFontType"]?>" />
</td>
</tr>
<tr><td colspan="2" align="center"><strong>User Interface / Write Module</strong></td></tr>
<tr>
<td align="center">Title Font Color</td>
<td>
<input type="text" name="cWriteTitleFontColor" value="<?=$settings["WriteTitleFontColor"]?>" />&nbsp;Title BG Color&nbsp;
<input type="text" name="cWriteTitleBgColor" value="<?=$settings["WriteTitleBgColor"]?>" />
</td>
</tr>

<tr><td colspan="2" align="center"><strong>User Interface / Mail Module</strong></td></tr>
<tr>
<td align="center">Mail Header, Tailer</td>
<td>
<input type="radio" name="cUseSysDefMail" value="true" <?php if ($settings["UseSysDefMail"] == "true") echo "checked"; ?> />&nbsp;I want to use system default&nbsp;
<input type="radio" name="cUseSysDefMail" value="false" <?php if ($settings["UseSysDefMail"] == "false") echo "checked"; ?> />&nbsp;I want to use my own signature below&nbsp;
</td>
</tr>

<tr>
<td align="center">Mail Header</td>
<td><textarea name="cMailHead" cols="60" rows="4"><?=htmlspecialchars($settings["MailHead"])?></textarea></td>
</tr>

<tr>
<td align="center">Mail Tailer</td>
<td><textarea name="cMailTail" cols="60" rows="4"><?=htmlspecialchars($settings["MailTail"])?></textarea></td>
</tr>

<tr>
<td align="center"><input type='submit' value='Save' />&nbsp;<input type='reset' value='Reset' /></td><td>&nbsp;</td>
<tr>
</table>
</form>
<?php
			if ($settings["UseSysDefHtml"] == "true") include "message/tail.html";
			else echo $settings["HtmlTail"];
			exit;
		}
	}

	if($mode == "adminsave")
	{
		$password = $_POST["password"];
		
		if($settings !== null && strcmp(crypt($password, "SY"), $settings["Password"]) != 0)
			error("Password incorrect.");

		$cPassword = $_POST["cPassword"];
		$cPassword2 = $_POST["cPassword2"];
		$cMessage = addslashes(nl2sp($_POST["cMessage"]));
		$cHomeURL = addslashes(nl2sp($_POST["cHomeURL"]));
		$cHomeTarget = addslashes(nl2sp($_POST["cHomeTarget"]));
		$cBackURL = addslashes(nl2sp($_POST["cBackURL"]));
		$cBackTarget = addslashes(nl2sp($_POST["cBackTarget"]));
		$cLinkTarget = addslashes(nl2sp($_POST["cLinkTarget"]));
		$cViewMode = addslashes(nl2sp($_POST["cViewMode"]));
		$cHTMLMode = addslashes(nl2sp($_POST["cHTMLMode"]));
		$cAdminName = addslashes(nl2sp($_POST["cAdminName"]));
		$cAdminEmail = addslashes(nl2sp($_POST["cAdminEmail"]));
		$cMailToAdmin = addslashes(nl2sp($_POST["cMailToAdmin"]));
		$cMailToPoster = addslashes(nl2sp($_POST["cMailToPoster"]));

		$cListOrder = addslashes(nl2sp($_POST["cListOrder"]));
		$cNameFirst = addslashes(nl2sp($_POST["cNameFirst"]));
		$cArticleNumber = addslashes(nl2sp($_POST["cArticleNumber"]));
		$cVirtualNumber = addslashes(nl2sp($_POST["cVirtualNumber"]));
		$cFolderIcon = addslashes(nl2sp($_POST["cFolderIcon"]));
		$cUploadEnable = addslashes(nl2sp($_POST["cUploadEnable"]));
		$cTypeIcon = addslashes(nl2sp($_POST["cTypeIcon"]));
		$cLanguage = addslashes(nl2sp($_POST["cLanguage"]));
		$cWriteMode = addslashes(nl2sp($_POST["cWriteMode"]));
		$cHideSearch = addslashes(nl2sp($_POST["cHideSearch"]));
		$cListBorderSize = addslashes(nl2sp($_POST["cListBorderSize"]));
		$cListFontSize = addslashes(nl2sp($_POST["cListFontSize"]));
		$cListTitleFontColor = addslashes(nl2sp($_POST["cListTitleFontColor"]));
		$cListTitleBgColor = addslashes(nl2sp($_POST["cListTitleBgColor"]));
		$cListArticleFontColor = addslashes(nl2sp($_POST["cListArticleFontColor"]));
		$cListArticleBgColor = addslashes(nl2sp($_POST["cListArticleBgColor"]));
		$cReadTitleBorderSize = addslashes(nl2sp($_POST["cReadTitleBorderSize"]));
		$cReadTextBorderSize = addslashes(nl2sp($_POST["cReadTextBorderSize"]));
		$cReadFontSize = addslashes(nl2sp($_POST["cReadFontSize"]));
		$cReadTitleFontColor = addslashes(nl2sp($_POST["cReadTitleFontColor"]));
		$cReadTitleBgColor = addslashes(nl2sp($_POST["cReadTitleBgColor"]));
		$cReadTextFontColor = addslashes(nl2sp($_POST["cReadTextFontColor"]));
		$cReadTextBgColor = addslashes(nl2sp($_POST["cReadTextBgColor"]));
		$cReadTextFontType = addslashes(nl2sp($_POST["cReadTextFontType"]));
		$cWriteTitleFontColor = addslashes(nl2sp($_POST["cWriteTitleFontColor"]));
		$cWriteTitleBgColor = addslashes(nl2sp($_POST["cWriteTitleBgColor"]));

		$cArticlesPerPage = addslashes(nl2sp($_POST["cArticlesPerPage"]));

		$cUseSysDefHtml  = addslashes($_POST["cUseSysDefHtml"]);
		$cHtmlHead  = addslashes($_POST["cHtmlHead"]);
		$cHtmlTail  = addslashes($_POST["cHtmlTail"]);
		$cDefaultText  = addslashes($_POST["cDefaultText"]);
		$cUseSysDefMail  = addslashes($_POST["cUseSysDefMail"]);
		$cMailHead  = addslashes($_POST["cMailHead"]);
		$cMailTail  = addslashes($_POST["cMailTail"]);

		if($cPassword != $cPassword2)
			error("Your password incorrect.");

		if($cPassword)
			$newpw = crypt($cPassword, "SY");
		else
			$newpw = $settings["Password"];

		$content = "UPDATE ${db_prefix}_admin SET ";
		
		$content .= sprintf("Password='%s', ", $newpw);
		$content .= sprintf("Message='%s', ", $cMessage);
		$content .= sprintf("HomeURL='%s', ", $cHomeURL);
		$content .= sprintf("HomeTarget='%s', ", $cHomeTarget);
		$content .= sprintf("BackURL='%s', ", $cBackURL);
		$content .= sprintf("BackTarget='%s', ", $cBackTarget);
		$content .= sprintf("LinkTarget='%s', ", $cLinkTarget);
		$content .= sprintf("ViewMode='%s', ", $cViewMode);
		$content .= sprintf("HTMLMode='%s', ", $cHTMLMode);
		$content .= sprintf("AdminName='%s', ", $cAdminName);
		$content .= sprintf("AdminEmail='%s', ", $cAdminEmail);
		$content .= sprintf("MailToAdmin='%s', ", $cMailToAdmin);
		$content .= sprintf("MailToPoster='%s', ", $cMailToPoster);

		$content .= sprintf("ListOrder='%s', ", $cListOrder);
		$content .= sprintf("NameFirst='%s', ", $cNameFirst);
		$content .= sprintf("ArticleNumber='%s', ", $cArticleNumber);
		$content .= sprintf("VirtualNumber='%s', ", $cVirtualNumber);
		$content .= sprintf("FolderIcon='%s', ", $cFolderIcon);
		$content .= sprintf("UploadEnable='%s', ", $cUploadEnable);
		$content .= sprintf("TypeIcon='%s', ", $cTypeIcon);
		$content .= sprintf("Language='%s', ", $cLanguage);
		$content .= sprintf("WriteMode='%s', ", $cWriteMode);
		$content .= sprintf("HideSearch='%s', ", $cHideSearch);
		$content .= sprintf("ListBorderSize='%s', ", $cListBorderSize);
		$content .= sprintf("ListFontSize='%s', ", $cListFontSize);
		$content .= sprintf("ListTitleFontColor='%s', ", $cListTitleFontColor);
		$content .= sprintf("ListTitleBgColor='%s', ", $cListTitleBgColor);
		$content .= sprintf("ListArticleFontColor='%s', ", $cListArticleFontColor);
		$content .= sprintf("ListArticleBgColor='%s', ", $cListArticleBgColor);
		$content .= sprintf("ReadTitleBorderSize='%s', ", $cReadTitleBorderSize);
		$content .= sprintf("ReadTextBorderSize='%s', ", $cReadTextBorderSize);
		$content .= sprintf("ReadFontSize='%s', ", $cReadFontSize);
		$content .= sprintf("ReadTitleFontColor='%s', ", $cReadTitleFontColor);
		$content .= sprintf("ReadTitleBgColor='%s', ", $cReadTitleBgColor);
		$content .= sprintf("ReadTextFontColor='%s', ", $cReadTextFontColor);
		$content .= sprintf("ReadTextBgColor='%s', ", $cReadTextBgColor);
		$content .= sprintf("ReadTextFontType='%s', ", $cReadTextFontType);
		$content .= sprintf("WriteTitleFontColor='%s', ", $cWriteTitleFontColor);
		$content .= sprintf("WriteTitleBgColor='%s', ", $cWriteTitleBgColor);

		$content .= sprintf("ArticlesPerPage='%s' ,", $cArticlesPerPage);
		$content .= sprintf("UseSysDefHtml='%s', ", $cUseSysDefHtml);
		$content .= sprintf("UseSysDefMail='%s', ", $cUseSysDefMail);
		$content .= sprintf("HtmlHead='%s', ", $cHtmlHead);
		$content .= sprintf("HtmlTail='%s', ", $cHtmlTail);
		$content .= sprintf("DefaultText='%s', ", $cDefaultText);
		$content .= sprintf("MailHead='%s', ", $cMailHead);
		$content .= sprintf("MailTail='%s' ", $cHtmlTail);

		$content .= sprintf("WHERE id = '$db'");
		
		mysql_query($content);

		header("Location: ".$_SERVER['PHP_SELF'].'?db='.$db);

		exit;
	}

	if($mode == "write")
	{
		$num = $_GET["num"];

		$subject = "";
		$text = "";

		if ($num) {
			$num = (int)$num;
			$result = mysql_fetch_array(mysql_query("SELECT querySubject, queryText, queryName from ${db_prefix}_board_$db WHERE no=$num"));
			if ($result) {
				$subject = htmlspecialchars("Re: " . $result["querySubject"]);
				$name = $result["queryName"];
				$text = htmlspecialchars("$name wrote:" . "\n>" . str_replace("\n","\n>", $result["queryText"]));
			} else {
				error("There is no article");
			}
		} else {
			$text = htmlspecialchars($settings["DefaultText"]);
		}

		$admin_password = $_POST["admin_password"];

		if ($settings["WriteMode"] == "admin") {
			if (!$admin_password) {
				if ($settings["UseSysDefHtml"] == "true") include "message/head.html";
				else echo $settings["HtmlHead"];
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?db=<?php echo $db; ?>&mode=write">
<table align="center">
<tr align="center">
<td>DB Name</td><td><?php echo $db; ?></td>
</tr>
<tr align="center">
<td>Access Mode</td><td>write</td>
</tr>
<tr align="center">
<td>Password</td><td><input type="password" name="admin_password" /></td>
</tr>
<tr align="center">
<td colspan="2"><input type="submit" value="Check out" /></td>
</tr>
</table>
</form>
<?php
				if ($settings["UseSysDefHtml"] == "true") include "message/tail.html";
				else echo $settings["HtmlTail"];
				exit;
			} else {
				if (strcmp(crypt($admin_password, "SY"), $settings["Password"]) != 0)
					error("Password incorrect.");
			}
		}

		if ($settings["UseSysDefHtml"] == "true") include "message/head.html";
		else echo $settings["HtmlHead"];
?>
<p style="text-align: center"><font size="3"><strong><?php echo htmlspecialchars($settings['Message']); ?></strong></font></p>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?db=<?php echo $db; ?>&mode=writesave&num=<?=urlencode($num)?>" enctype="multipart/form-data">
<?php
	if ($settings["WriteMode"] == "admin") {
?>
<input type="hidden" name="admin_password" value="<?=htmlspecialchars($admin_password)?>" />
<?php
	}
?>
<table align="center">
<tr>
<td align="center">Name</td>
<td>
<input name="Name" required />
</td>
</tr>
<tr>
<td align="center">Email</td>
<td>
<input name="Email" />
</td>
</tr>
<tr>
<td align="center">Subject</td>
<td>
<input name="Subject" value="<?=$subject?>" required />
</td>
</tr>
<tr>
<td align="center">Password</td>
<td>
<input name="Password" type="password" required />
</td>
</tr>
<tr>
<td colspan="2" align="center">
<textarea cols="60" rows="8" name="Text" required>
<?=$text?>
</textarea>
</td>
</tr>
<?php
	if ($settings["UploadEnable"] == "true") {
?>
<tr>
<td align="center">File</td>
<td>
<input type="file" name="pAttachFile" />
</td>
</tr>
<?php
	}
?>
<tr>
<td>
<input type="submit" value="Sign" />
</td>
<td align="right">
<?php
		if($settings["HomeURL"])
		{
?>
<a href='<?php echo htmlspecialchars($settings["HomeURL"]); ?>' target="<?php echo htmlspecialchars($settings["HomeTarget"]); ?>">
<img src='icon/home.gif' alt="Home" /></a>
<?php 
		} 
		if($settings["BackURL"])
		{
?>
<a href='<?php echo htmlspecialchars($settings["BackURL"]); ?>' target="<?php echo htmlspecialchars($settings["BackTarget"]); ?>">
<img src='icon/back.gif' alt="Back" /></a>
<?php 
		} 
?>
<a href='<?php echo $_SERVER['PHP_SELF']; ?>?db=<?php echo $db; ?>'><img src='icon/list.gif' alt="Reload" /></a>
</td>
</tr>
</table>
</form>
<?php
		if ($settings["UseSysDefHtml"] == "true") include "message/tail.html";
		else echo $settings["HtmlTail"];

		exit;
	}

	if($mode == "writesave")
	{
		$num = $_GET["num"];

		$admin_password = $_POST["admin_password"];

		if ($settings["WriteMode"] == "admin") {
			if (strcmp(crypt($admin_password, "SY"), $settings["Password"]) != 0) {
				error("Incorrect password");
			}
		}
		
		$depth = 1;
		$parent = 0;
		$grpord = 0;

		if ($num) {
			$num = (int)$num;
			$result = mysql_fetch_array(mysql_query("SELECT * FROM ${db_prefix}_board_$db WHERE no=$num"));
			$grpno = $result["grpno"];
			$parent = $result["parent"];
			$depth = $result["depth"];
			$grpord = $result["grpord"];
		}

		$dir = "data/$db";

		$filename = '';
		$realfilename = '';

		// 첨부된 파일이 있을때

		if ($_FILES["pAttachFile"]['name']) {
			$filename = $_FILES["pAttachFile"]['name'];
			$file_path = tempnam($dir, "");
			move_uploaded_file($_FILES["pAttachFile"]["tmp_name"], $file_path);
			
			if (strtolower(substr($file_path, -4)) === '.tmp') {
				$a = explode(".", $file_path);
				$a[count($a)-1] = "";
				$realfilename = implode(".", $a);
			} else {
				$realfilename = $file_path;
			}

			$realfilename = substr($realfilename, 0, strlen($realfilename)-1);
			rename($file_path, $realfilename);
			$realfilename = basename($realfilename);
		}

		$Text = addslashes($_POST['Text']);

		$Name = addslashes(nl2sp($_POST['Name']));
		$Email = addslashes(nl2sp($_POST['Email']));
		$Subject = addslashes(nl2sp($_POST['Subject']));
		$Password = crypt($_POST['Password'], "SY");

		$content = "INSERT INTO ${db_prefix}_board_$db (timeYear, timeMon, timeDay, timeHour, timeMin, remoteAddr, remoteHost, queryName, queryEmail, querySubject, queryPassword, queryText, readCount, fileName, realFileName, grpno, parent, grpord, depth) VALUES (";
		$content .= sprintf("'%s', ", date("Y"));
		$content .= sprintf("'%s', ", date("n"));
		$content .= sprintf("'%s', ", date("j"));
		$content .= sprintf("'%s', ", date("G"));
		$content .= sprintf("'%s', ", date("i"));
		$content .= sprintf("'%s', ", $_SERVER['REMOTE_ADDR']);
		$content .= sprintf("'%s', ", $_SERVER['REMOTE_HOST']);
		$content .= sprintf("'%s', ", $Name);
		$content .= sprintf("'%s', ", $Email);
		$content .= sprintf("'%s', ", $Subject);
		$content .= sprintf("'%s', ", $Password);
		$content .= sprintf("'%s', ", $Text);
		$content .= sprintf("'0', ");
		$content .= sprintf("'%s', '%s',", addslashes($filename), addslashes($realfilename));

		if ($num)
			$content .= sprintf("$grpno, $num, $grpord+1, $depth+1)");
		else
			$content .= sprintf("NULL, $parent, $grpord, $depth)");

		mysql_query($content);

		mysql_query("UPDATE ${db_prefix}_board_$db SET grpno = no WHERE grpno IS NULL");

		header("Location: ".$_SERVER['PHP_SELF'].'?db='.$db);

		exit;
	}
	if ($mode == "download") {
		$dir = "data/$db";
		$num = (int)$_GET["num"];
		$result = mysql_query("SELECT fileName, realFileName FROM ${db_prefix}_board_$db WHERE no=$num");
		if (mysql_num_rows($result) == 0) error("File not found.");
		$result = mysql_fetch_array($result);
		$fileName = $result["fileName"];
		$realFileName = $result["realFileName"];

		if (!$fileName) error("File not found.");
		$filename = $fileName;
		$filesize = filesize($dir . '/' . $realFileName);

		header("Pragma: public");
		header("Expires: 0");
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: $filesize");
		
		readfile($dir . '/' . $realFileName);

		exit;
	}
	if ($mode == "modify") {
		$num = $_GET["num"];
		if(!$num)
			error("num is missing.");
		if(is_num($num) == false)
			error("num is not correct.");
		$num = (int)$num;
		if ($num < 1) $num = 1;
		$password = $_POST['password'];
		if ($password === null) {
			if ($settings["UseSysDefHtml"] == "true") include "message/head.html";
			else echo $settings["HtmlHead"];

?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?db=<?php echo $db; ?>&mode=modify&num=<?php echo $num; ?>">
<table align="center">
<tr align="center">
<td>DB Name</td><td><?php echo $db; ?></td>
</tr>
<tr align="center">
<td>Access Mode</td><td>modify</td>
</tr>
<tr align="center">
<td>Handling Number</td><td><?php echo $num; ?></td>
</tr>
<tr align="center">
<td>Password</td><td><input type="password" name="password" /></td>
</tr>
<tr align="center">
<td colspan="2"><input type="submit" value="Check out" /></td>
</tr>
</table>
</form>
<?php
			if ($settings["UseSysDefHtml"] == "true") include "message/tail.html";
			else echo $settings["HtmlTail"];

		} else {
			$result = mysql_fetch_array(mysql_query("SELECT * FROM ${db_prefix}_board_$db WHERE no=$num"));

			$user_password = $result["queryPassword"];

			// 관리자의 비번과 같지 않고 유저 패스워드와도 같지 않으면 끝냄.
			if(strcmp(crypt($password, "SY"), $settings["Password"]) != 0)
				if($user_password === null || strcmp(crypt($password, "SY"), $user_password) != 0)
					error("Password incorrect.");

			$name = htmlspecialchars($result["queryName"]);
			$email = htmlspecialchars($result["queryEmail"]);
			$subject = htmlspecialchars($result["querySubject"]);
			$text = htmlspecialchars($result["queryText"]);

			if ($settings["UseSysDefHtml"] == "true") include "message/head.html";
			else echo $settings["HtmlHead"];
?>
<p style="text-align: center"><font size="3"><strong><?php echo htmlspecialchars($settings['Message']); ?></strong></font></p>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?db=<?php echo $db; ?>&mode=modifysave&num=<?=urlencode($num)?>">
<input type="hidden" name="password" value="<?=htmlspecialchars($password)?>" />
<table align="center">
<tr>
<td align="center">Name</td>
<td>
<input name="Name" value="<?=$name?>" required />
</td>
</tr>
<tr>
<td align="center">Email</td>
<td>
<input name="Email" value="<?=$email?>" />
</td>
</tr>
<tr>
<td align="center">Subject</td>
<td>
<input name="Subject" value="<?=$subject?>" required />
</td>
</tr>
<tr>
<td align="center">New Password</td>
<td>
<input name="NewPassword" type="password" />
</td>
</tr>
<tr>
<td colspan="2" align="center">
<textarea cols="60" rows="8" name="Text" required>
<?=$text?>
</textarea>
</td>
</tr>
<tr>
<td>
<input type="submit" value="Sign" />
</td>
<td align="right">
<?php
		if($settings["HomeURL"])
		{
?>
<a href='<?php echo htmlspecialchars($settings["HomeURL"]); ?>' target="<?php echo htmlspecialchars($settings["HomeTarget"]); ?>">
<img src='icon/home.gif' alt="Home" /></a>
<?php 
		} 
		if($settings["BackURL"])
		{
?>
<a href='<?php echo htmlspecialchars($settings["BackURL"]); ?>' target="<?php echo htmlspecialchars($settings["BackTarget"]); ?>">
<img src='icon/back.gif' alt="Back" /></a>
<?php 
		}
?>
<a href='<?php echo $_SERVER['PHP_SELF']; ?>?db=<?php echo $db; ?>'><img src='icon/list.gif' alt="Reload" /></a>
</td>
</tr>
</table>
</form>
<?php
		if ($settings["UseSysDefHtml"] == "true") include "message/tail.html";
		else echo $settings["HtmlTail"];
		}
		exit;
	}

	if ($mode == "modifysave") {
		$num = (int)$_GET["num"];
		$password = $_POST["password"];

		$result = mysql_fetch_array(mysql_query("SELECT * FROM ${db_prefix}_board_$db WHERE no=$num"));

		$user_password = $result["queryPassword"];

		if(strcmp(crypt($password, "SY"), $settings["Password"]) != 0)
			if($user_password === null || strcmp(crypt($password, "SY"), $user_password) != 0)
				error("Password incorrect.");

		$name = addslashes($_POST["Name"]);
		$email = addslashes($_POST["Email"]);
		$subject = addslashes($_POST["Subject"]);
		$text = addslashes($_POST["Text"]);

		$new_password = $_POST["NewPassword"];

		if ($new_password) {
			$new_password = crypt($_POST["NewPassword"], "SY");
		} else {
			$new_password = $user_password;
		}
		
		mysql_query("UPDATE ${db_prefix}_board_$db SET queryName='$name', queryEmail='$email', querySubject='$subject', queryText='$text', queryPassword='$new_password' WHERE no=$num");

		header("Location: ".$_SERVER['PHP_SELF'].'?db='.$db);

		exit;
	}
	if($mode == "delete")
	{
		$Num = $_GET["Num"];
		if(!$Num)
			error("Num is missing.");
		if(is_num($Num) == false)
			error("Num is not correct.");
		$Num = (int)$Num;

		if ($Num < 1) $Num = 1;

		$password = $_POST['password'];
		if($password === null)
		{
			if ($settings["UseSysDefHtml"] == "true") include "message/head.html";
			else echo $settings["HtmlHead"];
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?db=<?php echo $db; ?>&mode=delete&Num=<?php echo $Num; ?>">
<table align="center">
<tr align="center">
<td>DB Name</td><td><?php echo $db; ?></td>
</tr>
<tr align="center">
<td>Access Mode</td><td>delete</td>
</tr>
<tr align="center">
<td>Handling Number</td><td><?php echo $Num; ?></td>
</tr>
<tr align="center">
<td>Password</td><td><input type="password" name="password" /></td>
</tr>
<tr align="center">
<td colspan="2"><input type="submit" value="Check out" /></td>
</tr>
</table>
</form>
<?php
			if ($settings["UseSysDefHtml"] == "true") include "message/tail.html";
			else echo $settings["HtmlTail"];

			exit;
		}
		else
		{
			// 유저 패스워드를 구한다. 없을경우 null로 유지됨.
			$result = mysql_fetch_array(mysql_query("SELECT * FROM ${db_prefix}_board_$db WHERE no=$Num"));

			$user_password = $result["queryPassword"];

			$filename = $result["fileName"];
			$realfilename = $result["realFileName"];

			// 관리자의 비번과 같지 않고 유저 패스워드와도 같지 않으면 끝냄.
			if(strcmp(crypt($password, "SY"), $settings["Password"]) != 0)
				if($user_password === null || strcmp(crypt($password, "SY"), $user_password) != 0)
					error("Password incorrect.");

			$dir = "data/$db";

			@unlink($dir . '/' . $realfilename);

			mysql_query("DELETE FROM ${db_prefix}_board_$db WHERE no=$Num");

			header("Location: ".$_SERVER['PHP_SELF'].'?db='.$db);
			
			exit;
		}
	}
	else if ($mode == 'read') 
	{
		$num = (int)$_GET["num"];

		$page = (int)$_GET["page"];

		$fname = $_GET["fname"];
		$fsubject = $_GET["fsubject"];
		$ftext = $_GET["ftext"];

		$fval = $_GET["fval"];

		if ($num <= 0) error("Incorrect Number");
		
		if ($settings["UseSysDefHtml"] == "true") include "message/head.html";
		else echo $settings["HtmlHead"];
		
		// 카운터 1 증가
		@mysql_query("UPDATE ${db_prefix}_board_$db SET readCount = readCount + 1 WHERE no=$num");

		$result = mysql_query("SELECT * FROM ${db_prefix}_board_$db WHERE no=$num");
		
		$contents = mysql_fetch_array($result);
		$content = $contents["queryText"];
		
		$dir = "data/$db";
?>
<p style="text-align: center"><font size="3"><strong><?php echo htmlspecialchars($settings['Message']); ?></strong></font></p>
<hr width="80%" />
<table cellspacing="0" align="center" width="80%">
<tr>
<td>
<font color=darkgreen size=1 face='Arial'>
<?php 
	echo '<font color=deeppink>'.time_format($contents['timeYear']).'.'.time_format($contents['timeMon']).'.'.time_format($contents['timeDay']).'('.time_format($contents['timeHour']).':'.time_format($contents['timeMin']).')</font>'.
	" from '<font color=deeppink>".$contents['remoteAddr']."</font>' of '<font color=deeppink>".($contents['remoteHost']?$contents['remoteHost']:$contents['remoteAddr'])."</font>'"; 
?>
</font>
</td>
<td align="right">
<font size="2" face='Arial'>Article Number : <?php echo $contents['no']; ?></font>&nbsp;
</td>
</tr>
<tr>
<td>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?db=<?php echo $db; ?>&mode=delete&Num=<?php echo $num; ?>"><img src="icon/delete.gif" alt="Delete" /></a>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?db=<?php echo $db; ?>&mode=modify&num=<?php echo $num; ?>"><img src="icon/modify.gif" alt="Modify" /></a>
<font size="2" face="굴림"><?php echo em(htmlspecialchars($contents['queryName']), $fval); ?></font>
<?php
	if ($contents['queryEmail']) {
?>
<font size="2" face="굴림">(<a href="mailto:<?=htmlspecialchars($contents['queryEmail'])?>"><?=htmlspecialchars($contents['queryEmail'])?></a>)</font>
<?php
	}
?>
</td>
<td align="right">
<font size="2" face="arial">
Access : <?php echo $contents["readCount"]; ?> , Lines : <?php echo count(explode("\n", $content)) ?>
</font>
</td>
</tr>
</table>

<table border='<?=$settings["ReadTitleBorderSize"]?>' width="80%" align="center"><tr bgcolor='<?=$settings["ReadTitleBgColor"]?>'><th>
  <font size="3" face='굴림' color='<?=$settings["ReadTitleFontColor"]?>'><?php echo em(htmlspecialchars($contents["querySubject"]), $fval); ?></font>
</th></tr></table>
<?php
	if ($contents["fileName"]) {
?>
<table border='<?=$settings["ReadTextBorderSize"]?>' width="80%" align="center"><tr bgcolor='<?=$settings["ReadTextBgColor"]?>'><td>
  <font size="2" face='굴림' color='<?=$settings["ReadTextFontColor"]?>'>
	<img src="icon/type/<?=file_icon($contents["fileName"])?>.gif" />&nbsp;Download&nbsp;:
	<a href="<?=$_SERVER["PHP_SELF"]?>?db=<?=$db?>&mode=download&num=<?=$contents["no"]?>">
	<?=htmlspecialchars($contents["fileName"])?></a> (<?=ceil((filesize($dir.'/'.$contents["realFileName"]))/1024)?> Kbytes)
  </font>
  </td></tr></table>
<?php
	}
?>
<table border='<?=$settings["ReadTextBorderSize"]?>' width="80%" align="center">
<tr bgcolor='<?=$settings["ReadTextBgColor"]?>'><td><font size="<?=$settings["ReadFontSize"]?>" face='<?=$settings["ReadTextFontType"]?>' color='<?=$settings["ReadTextFontColor"]?>'>
<?php
		if($settings["HTMLMode"] == 'true')
		{
			echo xss_filter(em(nl2br($content), $fval)); 
		}
		else
		{
			echo em(autolink(nl2br(htmlspecialchars($content)), $settings['LinkTarget']), $fval);
		}
?>
</font></td></tr>
</table>

<hr width="80%" />

<table width="80%" align="center">
<tr>
<td align="right">
<?php 
	$result = mysql_query("SELECT min(no) FROM ${db_prefix}_board_$db WHERE no > " . $contents['no']);
	$temp = mysql_fetch_array($result);
	$min = (int)$temp[0];

	if ($min) {
?>
<a href="<?=$_SERVER["PHP_SELF"]?>?db=<?=$db?>&mode=read&num=<?=$min?>"><img src='icon/up.gif' border=0 alt='Backward'></a>
<?php 
	}
	$result = mysql_query("SELECT max(no) FROM ${db_prefix}_board_$db WHERE no < " . $contents['no']);
	$temp = mysql_fetch_array($result);
	$max = (int)$temp[0];
	if ($max) {
?>
<a href="<?=$_SERVER["PHP_SELF"]?>?db=<?=$db?>&mode=read&num=<?=$max?>"><img src='icon/down.gif' border=0 alt='Forward'></a>
<?php
	}
?>
<a href="<?=$_SERVER["PHP_SELF"]?>?db=<?=$db?>&mode=write"><img src='icon/write.gif' border=0 alt='Post'></a>
<a href="<?=$_SERVER["PHP_SELF"]?>?db=<?=$db?>&mode=write&num=<?php echo $num; ?>"><img src='icon/reply.gif' border=0 alt='Reply'></a>
<a href="<?=$_SERVER["PHP_SELF"]?>?db=<?=$db?>&mode=list&page=<?=$page?>&fname=<?=urlencode($fname)?>&fsubject=<?=urlencode($fsubject)?>&ftext=<?=urlencode($ftext)?>&fval=<?=urlencode($fval)?>"><img src='icon/list.gif' border=0 alt='List'></a>

</td>
</tr>
</table>

<?php
		if ($settings["UseSysDefHtml"] == "true") include "message/tail.html";
			else echo $settings["HtmlTail"];
		exit;
	}
	else
	{
		if($mode && $mode != 'list')
			error("Unknown mode.");
	}





	// 그 외의 경우에는 글 목록 모드

	$fname = $_GET["fname"];
	$fsubject = $_GET["fsubject"];
	$ftext = $_GET["ftext"];
	$fval = $_GET["fval"];

	$page = (int)$_GET["page"];
	if ($page <= 0) $page = 1;

	$articlesperpage = (int)$settings["ArticlesPerPage"];

	$result = mysql_fetch_array(mysql_query("SELECT count(no) FROM ${db_prefix}_board_$db"));

	$total = $result[0];

	if ($articlesperpage === 0) {
		$articlesperpage = $total;
	}

	$search = addslashes($fval);
	$querys = array();
	$query = "";
	if ($fname) $querys[] = "queryName LIKE '%$search%'";
	if ($fsubject) $querys[] = "querySubject LIKE '%$search%'";
	if ($ftext) $querys[] = "queryText LIKE '%$search%'";
	if (count($querys) > 0) $query = "WHERE " . implode(" OR ", $querys);

	$result = mysql_fetch_array(mysql_query("SELECT count(no) FROM ${db_prefix}_board_$db $query"));
	$total_search = $result[0];
	
	if ($articlesperpage != 0)
		$total_page = ceil($total_search / $articlesperpage);
	else
		$total_page = 1;

	if ($total_page == 0) $total_page++;

	$virtual_start = $total_search - ($page-1) * $articlesperpage;

	if ($settings["UseSysDefHtml"] == "true") include "message/head.html";
	else echo $settings["HtmlHead"];
?>
<p style="text-align: center"><font size="3"><strong><?php echo htmlspecialchars($settings['Message']); ?></strong></font></p>
<hr width="80%" />

<table width="80%" align="center">
<tr>
<td align="left">
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?db=<?php echo $db; ?>&mode=admin"><img src="icon/admin.gif" alt="Administration" /></a>
</td>
<td align="right">
<font size="1">Total Articles : <font color="darkgreen"><?=$total_search?></font> / <font color="darkblue"><?=$total?></font>, </font>
<font size="1">Page : <font color="red"><?=$page?></font> / <font color="darkblue"><?=$total_page?></font></font>
</td>
</tr>
</table>

<table align="center" width="80%" border="<?= $settings["ListBorderSize"] ?>" style="font-family: 굴림">
<tr bgcolor="<?= $settings["ListTitleBgColor"] ?>" style="color: <?= $settings["ListTitleFontColor"] ?>;">
<?php
	if ($settings["ArticleNumber"] == "true") {
?>
<th><font size="<?=$settings["ListFontSize"]?>">No</font></th>
<?php
	}
?>
<?php
	if ($settings["TypeIcon"] == "true") {
?>
<th><font size="<?=$settings["ListFontSize"]?>">&copy;</font></th>
<?php
	}
?>
<?php
	if ($settings["NameFirst"] == "true") {
?>
<th><font size="<?=$settings["ListFontSize"]?>">Name</font></th>
<?php
	}
?>
<th><font size="<?=$settings["ListFontSize"]?>">S u b j e c t</font></th>
<?php
	if ($settings["NameFirst"] == "false") {
?>
<th><font size="<?=$settings["ListFontSize"]?>">Name</font></th>
<?php
	}
?>
<th><font size="<?=$settings["ListFontSize"]?>">Date</font></th>
<th><font size="<?=$settings["ListFontSize"]?>">Access</font></th>
</tr>
<?php
	$pagestart = ($page-1) * $articlesperpage;

	$articlesperpage1p = $articlesperpage + 1;
	
	if ($settings["ListOrder"] == "thread")
		$result = mysql_query("SELECT * FROM ${db_prefix}_board_$db $query ORDER BY grpno DESC, grpord ASC LIMIT $pagestart, $articlesperpage1p");
	else
		$result = mysql_query("SELECT * FROM ${db_prefix}_board_$db $query ORDER BY timeYear desc, timeMon desc, timeDay desc, timeHour desc, timeMin desc, no desc LIMIT $pagestart, $articlesperpage1p");

	$n = mysql_num_rows($result);

	if (!($n < $articlesperpage1p)) {
		$n--;
	}

	$virtual = $virtual_start;

	for ($i=0; $i < $n; $i++) {
		$no = mysql_result($result, $i, "no");
		$fileName = mysql_result($result, $i, "fileName");
		$querySubject = mysql_result($result, $i, "querySubject");
		$queryName = mysql_result($result, $i, "queryName");
		$readCount = mysql_result($result, $i, "readCount");
		$timeYear = mysql_result($result, $i, "timeYear");
		$timeMon = mysql_result($result, $i, "timeMon");
		$timeDay = mysql_result($result, $i, "timeDay");
		$fileName = mysql_result($result, $i, "fileName");
		$depth = mysql_result($result, $i, "depth");
		$nextDepth = @mysql_result($result, $i+1, "depth");

?>
<tr bgcolor="<?= $settings["ListArticleBgColor"] ?>" style="color: <?= $settings["ListArticleFontColor"] ?>;">
<?php
	if ($settings["ArticleNumber"] == "true") {
?>
<td align="center"><font size="<?=$settings["ListFontSize"]?>">
<?php if ($settings["VirtualNumber"] == "true") echo $virtual; else echo $no; ?>
</font></td>
<?php
	}
?>
<?php
	if ($settings["TypeIcon"] == "true") {
?>
<td align="center">
<?php
		if ($fileName) {
?>
<a href="<?=$_SERVER["PHP_SELF"]?>?db=<?=$db?>&mode=download&num=<?=$no?>">
<img src="<?php echo "icon/type/" . file_icon($fileName) . ".gif"; ?>" alt="<?=file_icon($fileName)?>" border="0" />
</a>
<?php
		} else {
?>
<img src="<?php echo "icon/type/" . file_icon($fileName) . ".gif"; ?>" alt="<?=file_icon($fileName)?>" border="0" />
<?php
		}
?>
</td>
<?php
	}
?>
<?php
	if ($settings["NameFirst"] == "true") {
?>
<td align="center">
<font size="<?=$settings["ListFontSize"]?>">
<?php 
	$temp = htmlspecialchars($queryName);
	if ($fval)
		$temp = preg_replace("/($fval)/i",'<font color="red">$1</font>',$temp);
	echo $temp;
?>
</font>
</td>
<?php
	}
?>
<td>
<font size="<?=$settings["ListFontSize"]?>">
<?php
	if ($settings["ListOrder"] == "thread")
		echo str_repeat("&nbsp;", $depth - 1);
	if ($settings["ListOrder"] == "thread" && $depth < $nextDepth) {
		if ($settings["FolderIcon"] == "true") {
?>
<img src="icon/open.gif" alt="open" />
<?php
		}
	} else {
		if ($settings["FolderIcon"] == "true") {
?>
<img src="icon/close.gif" alt="close" />
<?php
		}
	}
?>
<a href="<?= $_SERVER["PHP_SELF"] . "?db=$db&mode=read&page=" . $page ."&num=" . $no . "&fname=" . urlencode($fname) . "&fsubject=" . urlencode($fsubject) . "&ftext=" . urlencode($ftext) . "&fval=" . urlencode($fval) ?>">
<?php 
	$temp = htmlspecialchars($querySubject);
	if ($fval)
		$temp = preg_replace("/($fval)/i",'<font color="red">$1</font>',$temp);
	echo $temp;
?>
</a>
</font>
</td>
<?php
	if ($settings["NameFirst"] == "false") {
?>
<td align="center">
<font size="<?=$settings["ListFontSize"]?>">
<?php 
	$temp = htmlspecialchars($queryName);
	if ($fval)
		$temp = preg_replace("/($fval)/i",'<font color="red">$1</font>',$temp);
	echo $temp;
?>
</font>
</td>
<?php
	}
?>
<td align="center"><font size="<?=$settings["ListFontSize"]?>"><?php echo time_format((int)$timeYear % 100) . "/" . time_format($timeMon) . "/" . time_format($timeDay); ?></font></td>
<td align="center"><font size="<?=$settings["ListFontSize"]?>"><?php echo $readCount; ?></font></td>
</tr>
<?php
		$virtual--;
	}
?>
</table>

<table width="80%" align="center">
<tr align="center"><td><font size="1">
<?php
	$a = floor(($page - 1) / 10); 
	$b = $page % 10;
	if ($a > 0) {
?>
<a href="<?=$_SERVER["PHP_SELF"]?>?db=<?=$db?>&mode=list&page=1&fname=<?=urlencode($fname)?>&fsubject=<?=urlencode($fsubject)?>&ftext=<?=urlencode($ftext)?>&fval=<?=urlencode($fval)?>">[1]</a>
<a href="<?=$_SERVER["PHP_SELF"]?>?db=<?=$db?>&mode=list&page=<?=$page-10?>&fname=<?=urlencode($fname)?>&fsubject=<?=urlencode($fsubject)?>&ftext=<?=urlencode($ftext)?>&fval=<?=urlencode($fval)?>">[Prev]</a>
-
<?php
	}
	for ($i=1;$i<=10;$i++) {
		$c = $a * 10 + $i;
		if ($total_page < $c) break;
		if ($b == $i || ($b == 0 && $i == 10)) {
?>
<font color="red">[<?=$c?>]</font>
<?php
		} else {
?>
<a href="<?=$_SERVER["PHP_SELF"]?>?db=<?=$db?>&mode=list&page=<?=$c?>&fname=<?=urlencode($fname)?>&fsubject=<?=urlencode($fsubject)?>&ftext=<?=urlencode($ftext)?>&fval=<?=urlencode($fval)?>">[<?=$c?>]</a>
<?php
		}
	}
	if ($total_page > $c) {
?>
-
<a href="<?=$_SERVER["PHP_SELF"]?>?db=<?=$db?>&mode=list&page=<?=min(array($page+10, $total_page))?>&fname=<?=urlencode($fname)?>&fsubject=<?=urlencode($fsubject)?>&ftext=<?=urlencode($ftext)?>&fval=<?=urlencode($fval)?>">[Next]</a>
<a href="<?=$_SERVER["PHP_SELF"]?>?db=<?=$db?>&mode=list&page=<?=$total_page?>&fname=<?=urlencode($fname)?>&fsubject=<?=urlencode($fsubject)?>&ftext=<?=urlencode($ftext)?>&fval=<?=urlencode($fval)?>">[<?=$total_page?>]</a>
<?php
	}
?>
</font></td></tr>
</table>

<hr width="80%" />

<form method="get" action="<?=$_SERVER["PHP_SELF"]?>">
<input name="db" value="<?=$db?>" type="hidden" />
<input type='hidden' name='mode' value='list' />
<table cellspacing="0" width="80%" border="0" align="center">
<tr>
<td align="left" width="31%">
<?php
		if($settings["HomeURL"])
		{
?>
<a href='<?php echo htmlspecialchars($settings["HomeURL"]); ?>' target="<?php echo htmlspecialchars($settings["HomeTarget"]); ?>">
<img src='icon/home.gif' alt="Home" /></a>
<?php 
		} 
		if($settings["BackURL"])
		{
?>
<a href='<?php echo htmlspecialchars($settings["BackURL"]); ?>' target="<?php echo htmlspecialchars($settings["BackTarget"]); ?>">
<img src='icon/back.gif' alt="Back" /></a>
<?php 
		} 
?>
<a href='message/help-en.txt'><img src='icon/help.gif' alt="Help" /></a>
</td>
<td align="center">
<?php
	if ($settings["HideSearch"] == "false") {
?>
Name <input type="checkbox" name="fname" value="checked" <?php if ($fval && $fname) echo "checked"; ?>>
Subject <input type="checkbox" name="fsubject" value="checked" <?php if ($fval && $fsubject || $fval=="") echo "checked"; ?>>
Text <input type="checkbox" name="ftext" value="checked" <?php if ($fval && $ftext || $fval=="") echo "checked"; ?>><br />
<input type="text" name="fval" value="<?php echo htmlspecialchars($fval); ?>" />
<input type="submit" value="Go!!" />
<?php
	}
?>
</td>
<td align="right" width="31%">
<?php
	// 이전에 글이 있을 경우 뒤로 버튼을 만듬.
	if($page > 1)
	{
?>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?db=<?php echo $db; ?>&mode=list&page=<?=$page-1?>&fname=<?=urlencode($fname)?>&fsubject=<?=urlencode($fsubject)?>&ftext=<?=urlencode($ftext)?>&fval=<?=urlencode($fval)?>">
<img src='icon/up.gif' alt="Backward" />
</a>
<?php
	}
	// 남김없이 다 출력되었을 때, 뒤에 글이 더 있다면 다음 버튼을 표시
	if($total_page > $page)
	{
		if($fval) {
?>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?db=<?php echo $db; ?>&mode=list&page=<?=$page+1?>&fname=<?=urlencode($fname)?>&fsubject=<?=urlencode($fsubject)?>&ftext=<?=urlencode($ftext)?>&fval=<?=urlencode($fval)?>">
<img src='icon/down.gif' alt="Forward" /></a>
<?php
		} else {
?>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?db=<?php echo $db; ?>&mode=list&page=<?=$page+1?>">
<img src='icon/down.gif' alt="Forward" /></a>
<?php
		}
	}
?>
<a href='<?php echo $_SERVER['PHP_SELF']; ?>?db=<?php echo $db; ?>&mode=write'><img src='icon/write.gif' alt="Write" /></a>
</td>
</tr>
</table>
</form>
<?php
	if ($settings["UseSysDefHtml"] == "true") include "message/tail.html";
	else echo $settings["HtmlTail"];

	exit;




	
	/* 다용도 함수들 */

	function error($msg) {
?>
<html>
<body bgcolor="white">
<font color="red" size="6"><b>Error !!!</b></font><br /><br />
<font size="3"><b><i><?php echo htmlspecialchars($msg); ?></i></b></font><br /><br />
<center>
<font size="2">
<a href="javascript:history.back()">BACK</a>
</font>
</center>
</body>
</html>
<?php
		exit;
	}

	// 검색어 강조
	function em($content, $keyword) {
		if (!$keyword)
			return $content;
		return preg_replace("/($keyword)/i",'<font color="red">$1</font>',$content);
	}

	// 파일명의 아이콘을 구함
	function file_icon($name) {
		$a = explode(".", $name);
		$ext = strtolower($a[count($a)-1]);
		switch ($ext) {
			case "":
				return "default";
				break;
			case "bat":
				return "bat";
				break;
			case "bmp":
				return "bmp";
				break;
			case "com":
				return "com";
				break;
			case "zip":
				return "compressed";
				break;
			case "sys":
				return "device";
				break;
			case "exe":
				return "exe";
				break;
			case "gif":
				return "gif";
				break;
			case "html":
			case "htm":
				return "html";
				break;
			case "hwp":
				return "hwp";
				break;
			case "png":	
				return "image";
				break;
			case "jpg":
				return "jpg";
				break;
			case "avi":
				return "movie";
				break;
			case "mp3":
				return "mp3";
				break;
			case "pcx":
				return "pcx";
				break;
			case "wma":
				return "ra";
				break;
			case "mid":
			case "midi":
				return "sound";
				break;
			case "rtf":
				return "text";
				break;
			case "txt":
				return "txt";
				break;
			case "wav":
				return "wav";
				break;
			default:
				return "unknown";
				break;
		}
	}

	// 7을 07과 같이 함
	function time_format($t) {
		$t = (int)$t;
		if ($t < 10) {
			$t = '0'.$t;
		}
		return $t;
	}

	// 문자열이 숫자로만 되어있는지 검사
	function is_num($str)
	{
		$j = strlen($str);
		for($i=0; $i<$j; $i++)
		{
			$chr = ord(substr($str, $i, 1));
			if((ord('0') <= $chr) && (ord('9') >= $chr)) continue;
			else return false;
		}
		return true;
	}

	// 문자열이 알파벳과 숫자로만 되어있는지 검사
	function is_alphanum($str)
	{
		$j = strlen($str);
		for($i=0; $i<$j; $i++)
		{
			$chr = ord(substr($str, $i, 1));
			if((ord('0') <= $chr) && (ord('9') >= $chr)) continue;
			else if((ord('A') <= $chr) && (ord('Z') >= $chr)) continue;
			else if((ord('a') <= $chr) && (ord('z') >= $chr)) continue;
			else if($chr === ord('_')) continue;
			else return false;
		}
		return true;
	}

	// 줄바꿈 문자를 공백문자로 바꿈
	function nl2sp($str)
	{
		return str_replace("\n", ' ', $str);
	}

	// URL을 찾아서 링크로 바꿈 (제로보드 발췌)
	function autolink($str, $target="_blank") {
		// URL 치환
		$homepage_pattern = "/([^\"\'\=\>])(mms|http|HTTP|ftp|FTP|telnet|TELNET)\:\/\/(.[^ \n\<\"\']+)/";
		$str = preg_replace($homepage_pattern,"\\1<a href=\"\\2://\\3\" target=\"".$target."\">\\2://\\3</a>", " ".$str);

		// 메일 치환
		$email_pattern = "/([ \n]+)([a-z0-9\_\-\.]+)@([a-z0-9\_\-\.]+)/";
		$str = preg_replace($email_pattern,"\\1<a href=mailto:\\2@\\3>\\2@\\3</a>", " ".$str);

		return $str;
	}

	// 자바스크립트를 첨부하거나 <xmp> 따위의 태그를 사용한 테러를 방지함.
	function xss_filter($content)
	{
		// Strip bad elements.
		$content = preg_replace('/(<)(|\/)(\!|\?|html|head|title|meta|body|style|link|base|script'.
		'|frameset|frame|noframes|applet|object|param|noscript|noembed|embed|basefont|xmp|plaintext|comment)/i',
		'&lt;$2$3', $content);
 
		// Strip script handlers.
		$content = preg_replace_callback("/([^a-z])(o)(n)/i", 
		create_function('$matches', 'if($matches[2]=="o") $matches[2] = "&#111;";
		else $matches[2] = "&#79;"; return $matches[1].$matches[2].$matches[3];'), $content);

		// Strip iframe sandbox.
		$content = preg_replace_callback("/([^a-z])(s)(andbox)/i", 
		create_function('$matches', 'if($matches[2]=="s") $matches[2] = "&#115;";
		else $matches[2] = "&#83;"; return $matches[1].$matches[2].$matches[3];'), $content);

		// Strip iframe srcdoc
		$content = preg_replace_callback("/([^a-z])(s)(rcdoc)/i", 
		create_function('$matches', 'if($matches[2]=="s") $matches[2] = "&#115;";
		else $matches[2] = "&#83;"; return $matches[1].$matches[2].$matches[3];'), $content);

		// Embed 태그 처리
		$content = str_ireplace("<embed", '<embed allowscriptaccess="never"', $content);
 
		return $content;
	}
