<?php
	/*
		CrazyGuestBook 3.2.2 PHP ver. v0.1
	*/

	// outerput buffering
	ob_start(); 

	$db = $_GET['db'];
	
	if(!$db)
		die("You must specify the database name.");

	// $db값의 유효성 체크
	if(is_alphanum($db) == false)
		die("DB name is not valid.");
	
	$db_dir = "data/$db";

	// 파일이 없으면 DB가 없는 것으로 간주
	if(!file_exists($db_dir))
		die("There is no database named '$db'.");

	// 모드
	$mode = $_REQUEST["mode"];

	// 설정을 불러온다
	$f = @file_get_contents("$db_dir/db.conf");

	// 설정을 불러오는데 실패했을 경우	
	if($f === false)
	{
		// 설정 파일이 있는데 못 읽어온 거라면 에러로 보고 종료.
		if(file_exists("$db_dir/db.conf"))
			die("Can't open db.conf");
		if($mode === null)
		{
			$cPassword = '0000';
			$cMessage = 'This text will be appeared at top of the articles';
			$cHomeURL = 'http://Your HomePage URL';
			$cHomeTarget = '_self';
			$cBackURL = 'http://Previous Page URL';
			$cBackTarget = '_self';
			$cLinkTarget = '_top';
			$cViewMode = '0';
			$cHTMLMode = 'false';
			$cAdminName = 'Administrator';
			$cAdminEmail = '';
			$cMailToAdmin = 'false';
			$cTableWidth = '600';
			$cArticlesPerPage = '15';
			$cWriteName = 'must';
			$cWriteSex = 'false';
			$cWriteBirth = 'false';
			$cWriteEmail = 'true';
			$cWriteHomeURL = 'true';
			$cWriteHomeTitle = 'false';
			$cWritePhone = 'false';
			$cWriteAddress = 'false';
			$cWriteSubject = 'false';
			$cWriteText = 'must';
			$cWritePassword = 'must';

			$cPassword = crypt($cPassword, 'SY');

			$content = "";
		
			$content .= sprintf("Password=%s\n", $cPassword);
			$content .= sprintf("Message=%s\n", $cMessage);
			$content .= sprintf("HomeURL=%s\n", $cHomeURL);
			$content .= sprintf("HomeTarget=%s\n", $cHomeTarget);
			$content .= sprintf("BackURL=%s\n", $cBackURL);
			$content .= sprintf("BackTarget=%s\n", $cBackTarget);
			$content .= sprintf("LinkTarget=%s\n", $cLinkTarget);
			$content .= sprintf("ViewMode=%s\n", $cViewMode);
			$content .= sprintf("HTMLMode=%s\n", $cHTMLMode);
			$content .= sprintf("AdminName=%s\n", $cAdminName);
			$content .= sprintf("AdminEmail=%s\n", $cAdminEmail);
			$content .= sprintf("MailToAdmin=%s\n", $cMailToAdmin);
			$content .= sprintf("TableWidth=%s\n", $cTableWidth);
			$content .= sprintf("ArticlesPerPage=%s\n", $cArticlesPerPage);
			$content .= sprintf("WriteName=%s\n", $cWriteName);
			$content .= sprintf("WriteSex=%s\n", $cWriteSex);
			$content .= sprintf("WriteBirth=%s\n", $cWriteBirth);
			$content .= sprintf("WriteEmail=%s\n", $cWriteEmail);
			$content .= sprintf("WriteHomeURL=%s\n", $cWriteHomeURL);
			$content .= sprintf("WriteHomeTitle=%s\n", $cWriteHomeTitle);
			$content .= sprintf("WritePhone=%s\n", $cWritePhone);
			$content .= sprintf("WriteAddress=%s\n", $cWriteAddress);
			$content .= sprintf("WritePassword=%s\n", $cWritePassword);
			$content .= sprintf("WriteSubject=%s\n", $cWriteSubject);
			$content .= sprintf("WriteText=%s\n", $cWriteText);
		
			$f = @file_put_contents("$db_dir/db.conf", $content);
			if($f === false)
				die("Can't create db.conf");

			echo "Config file has been created. Default password is 0000";

			exit;

			
		}
		if($mode != "admin" && $mode != "adminsave")
			die("db.conf is not defined.");
	}
	else
	{
		// 설정을 불러온다.
		$a = explode("\n", $f);
		$settings = array();
		foreach($a as $key => $value)
		{
			// 공백만 있는 줄일경우 무시
			if(trim($value === '')) continue;
			// 처음에 나오는 = 구분자로 텍스트를 둘로 나눔
			$b = explode("=", $value, 2);
			$settings[$b[0]] = chop($b[1]);
		}
	}
	
	// 만약 어드민 모드라면
	if($mode == "admin")
	{
		$password = $_POST["password"];
		if($password === null && $settings !== null)
		{
			include "message/adminhead.html";
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
			include "message/admintail.html";
			exit;
		}
		else
		{
			if($settings !== null && strcmp(crypt($password, "SY"), $settings["Password"]) != 0)
				die("Password incorrect.");
			include "message/adminhead.html";
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?db=<?php echo $db; ?>&mode=adminsave">
<input type="hidden" name="password" value="<?php echo htmlspecialchars($password); ?>" />
<table align="center">
<tr>
<td colspan="2" align="center"><font size="5" face="arial"><strong>'CrazyGuestbook' Admin Configuration</strong></font></td>
</tr>
<tr>
<td align="center">New Password</td>
<td><input name="cPassword" type="password" />&nbsp;Re-enter Password&nbsp<input type="password" name="cPassword2" /></td>
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
<td align="center">HTML Mode</td>
<td><input required="required" type="radio" name="cHTMLMode" value="true" <?php if($settings["HTMLMode"] == "true") echo 'checked="checked" '; ?>/> Accept HTML,&nbsp;
<input type="radio" name="cHTMLMode" value="false" <?php if($settings["HTMLMode"] == "false") echo 'checked="checked" '; ?>/> Ignore HTML &amp; Auto Link</td>
</tr>
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
<td align="center">Table Width</td>
<td><input required="required" name="cTableWidth" value="<?php echo htmlspecialchars($settings["TableWidth"]); ?>" /> Pixels</td>
</tr>
<tr>
<td align="center">Articles per Page</td>
<td><input required="required" name="cArticlesPerPage" value="<?php echo htmlspecialchars($settings["ArticlesPerPage"]); ?>" /> ( Zero means limitless )</td>
</tr>
<tr>
<td align="center">Field Name</td>
<td>
<input required="required" type="radio" name="cWriteName" value="false" <?php if($settings["WriteName"] == "false") echo 'checked="checked" '; ?>/> Hidden,&nbsp;
<input type="radio" name="cWriteName" value="true" <?php if($settings["WriteName"] == "true") echo 'checked="checked" '; ?>/> Appear,&nbsp;
<input type="radio" name="cWriteName" value="must" <?php if($settings["WriteName"] == "must") echo 'checked="checked" '; ?>/> Must
</td>
</tr>
<tr>
<td align="center">Field Sex</td>
<td>
<input required="required" type="radio" name="cWriteSex" value="false" <?php if($settings["WriteSex"] == "false") echo 'checked="checked" '; ?>/> Hidden,&nbsp;
<input type="radio" name="cWriteSex" value="true" <?php if($settings["WriteSex"] == "true") echo 'checked="checked" '; ?>/> Appear,&nbsp;
<input type="radio" name="cWriteSex" value="must" <?php if($settings["WriteSex"] == "must") echo 'checked="checked" '; ?>/> Must
</td>
</tr>
<tr>
<td align="center">Field Birth</td>
<td>
<input required="required" type="radio" name="cWriteBirth" value="false" <?php if($settings["WriteBirth"] == "false") echo 'checked="checked" '; ?>/> Hidden,&nbsp;
<input type="radio" name="cWriteBirth" value="true" <?php if($settings["WriteBirth"] == "true") echo 'checked="checked" '; ?>/> Appear,&nbsp;
<input type="radio" name="cWriteBirth" value="must" <?php if($settings["WriteBirth"] == "must") echo 'checked="checked" '; ?>/> Must
</td>
</tr>
<tr>
<td align="center">Field Email</td>
<td>
<input required="required" type="radio" name="cWriteEmail" value="false" <?php if($settings["WriteEmail"] == "false") echo 'checked="checked" '; ?>/> Hidden,&nbsp;
<input type="radio" name="cWriteEmail" value="true" <?php if($settings["WriteEmail"] == "true") echo 'checked="checked" '; ?>/> Appear,&nbsp;
<input type="radio" name="cWriteEmail" value="must" <?php if($settings["WriteEmail"] == "must") echo 'checked="checked" '; ?>/> Must
</td>
</tr>
<tr>
<td align="center">Field HomePage URL</td>
<td>
<input required="required" type="radio" name="cWriteHomeURL" value="false" <?php if($settings["WriteHomeURL"] == "false") echo 'checked="checked" '; ?>/> Hidden,&nbsp;
<input type="radio" name="cWriteHomeURL" value="true" <?php if($settings["WriteHomeURL"] == "true") echo 'checked="checked" '; ?>/> Appear,&nbsp;
<input type="radio" name="cWriteHomeURL" value="must" <?php if($settings["WriteHomeURL"] == "must") echo 'checked="checked" '; ?>/> Must
</td>
</tr>
<tr>
<td align="center">Field HomePage Title</td>
<td>
<input required="required" type="radio" name="cWriteHomeTitle" value="false" <?php if($settings["WriteHomeTitle"] == "false") echo 'checked="checked" '; ?>/> Hidden,&nbsp;
<input type="radio" name="cWriteHomeTitle" value="true" <?php if($settings["WriteHomeTitle"] == "true") echo 'checked="checked" '; ?>/> Appear,&nbsp;
<input type="radio" name="cWriteHomeTitle" value="must" <?php if($settings["WriteHomeTitle"] == "must") echo 'checked="checked" '; ?>/> Must
</td>
</tr>
<tr>
<td align="center">Field Phone</td>
<td>
<input required="required" type="radio" name="cWritePhone" value="false" <?php if($settings["WritePhone"] == "false") echo 'checked="checked" '; ?>/> Hidden,&nbsp;
<input type="radio" name="cWritePhone" value="true" <?php if($settings["WritePhone"] == "true") echo 'checked="checked" '; ?>/> Appear,&nbsp;
<input type="radio" name="cWritePhone" value="must" <?php if($settings["WritePhone"] == "must") echo 'checked="checked" '; ?>/> Must
</td>
</tr>
<tr>
<td align="center">Field Address</td>
<td>
<input required="required" type="radio" name="cWriteAddress" value="false" <?php if($settings["WriteAddress"] == "false") echo 'checked="checked" '; ?>/> Hidden,&nbsp;
<input type="radio" name="cWriteAddress" value="true" <?php if($settings["WriteAddress"] == "true") echo 'checked="checked" '; ?>/> Appear,&nbsp;
<input type="radio" name="cWriteAddress" value="must" <?php if($settings["WriteAddress"] == "must") echo 'checked="checked" '; ?>/> Must
</td>
</tr>
<tr>
<td align="center">Field Subject</td>
<td>
<input required="required" type="radio" name="cWriteSubject" value="false" <?php if($settings["WriteSubject"] == "false") echo 'checked="checked" '; ?>/> Hidden,&nbsp;
<input type="radio" name="cWriteSubject" value="true" <?php if($settings["WriteSubject"] == "true") echo 'checked="checked" '; ?>/> Appear,&nbsp;
<input type="radio" name="cWriteSubject" value="must" <?php if($settings["WriteSubject"] == "must") echo 'checked="checked" '; ?>/> Must
</td>
</tr>
<tr>
<td align="center">Field Password</td>
<td>
<input required="required" type="radio" name="cWritePassword" value="false" <?php if($settings["WritePassword"] == "false") echo 'checked="checked" '; ?>/> Hidden,&nbsp;
<input type="radio" name="cWritePassword" value="true" <?php if($settings["WritePassword"] == "true") echo 'checked="checked" '; ?>/> Appear,&nbsp;
<input type="radio" name="cWritePassword" value="must" <?php if($settings["WritePassword"] == "must") echo 'checked="checked" '; ?>/> Must
</td>
</tr>
<tr>
<td align="center">Field Text</td>
<td>
<input required="required" type="radio" name="cWriteText" value="false" <?php if($settings["WriteText"] == "false") echo 'checked="checked" '; ?>/> Hidden,&nbsp;
<input type="radio" name="cWriteText" value="true" <?php if($settings["WriteText"] == "true") echo 'checked="checked" '; ?>/> Appear,&nbsp;
<input type="radio" name="cWriteText" value="must" <?php if($settings["WriteText"] == "must") echo 'checked="checked" '; ?>/> Must
</td>
</tr>
<tr>
<td align="center"><input type='submit' value='Save' />&nbsp;<input type='reset' value='Reset' /></td><td>&nbsp;</td>
<tr>
</table>
</form>
<?php
			include "message/admintail.html";
			exit;
		}
	}

	if($mode == "adminsave")
	{
		$password = $_POST["password"];
		
		if($settings !== null && strcmp(crypt($password, "SY"), $settings["Password"]) != 0)
			die("Password incorrect.");

		$cPassword = $_POST["cPassword"];
		$cPassword2 = $_POST["cPassword2"];
		$cMessage = nl2sp($_POST["cMessage"]);
		$cHomeURL = nl2sp($_POST["cHomeURL"]);
		$cHomeTarget = nl2sp($_POST["cHomeTarget"]);
		$cBackURL = nl2sp($_POST["cBackURL"]);
		$cBackTarget = nl2sp($_POST["cBackTarget"]);
		$cLinkTarget = nl2sp($_POST["cLinkTarget"]);
		$cViewMode = nl2sp($_POST["cViewMode"]);
		$cHTMLMode = nl2sp($_POST["cHTMLMode"]);
		$cAdminName = nl2sp($_POST["cAdminName"]);
		$cAdminEmail = nl2sp($_POST["cAdminEmail"]);
		$cMailToAdmin = nl2sp($_POST["cMailToAdmin"]);
		$cTableWidth = nl2sp($_POST["cTableWidth"]);
		$cArticlesPerPage = nl2sp($_POST["cArticlesPerPage"]);
		$cWriteName = nl2sp($_POST["cWriteName"]);
		$cWriteSex = nl2sp($_POST["cWriteSex"]);
		$cWriteBirth = nl2sp($_POST["cWriteBirth"]);
		$cWriteEmail = nl2sp($_POST["cWriteEmail"]);
		$cWriteHomeURL = nl2sp($_POST["cWriteHomeURL"]);
		$cWriteHomeTitle = nl2sp($_POST["cWriteHomeTitle"]);
		$cWritePhone = nl2sp($_POST["cWritePhone"]);
		$cWriteAddress = nl2sp($_POST["cWriteAddress"]);
		$cWriteText = nl2sp($_POST["cWriteText"]);
		$cWriteSubject = nl2sp($_POST["cWriteSubject"]);
		$cWritePassword = nl2sp($_POST["cWriteText"]);

		if($settings === null && !$cPassword)
			die("You must input your password at first setting.");

		if($cPassword != $cPassword2)
			die("Your password incorrect.");

		if($cPassword)
			$newpw = crypt($cPassword, "SY");
		else
			$newpw = $settings["Password"];

		$content = "";
		
		$content .= sprintf("Password=%s\n", $newpw);
		$content .= sprintf("Message=%s\n", $cMessage);
		$content .= sprintf("HomeURL=%s\n", $cHomeURL);
		$content .= sprintf("HomeTarget=%s\n", $cHomeTarget);
		$content .= sprintf("BackURL=%s\n", $cBackURL);
		$content .= sprintf("BackTarget=%s\n", $cBackTarget);
		$content .= sprintf("LinkTarget=%s\n", $cLinkTarget);
		$content .= sprintf("ViewMode=%s\n", $cViewMode);
		$content .= sprintf("HTMLMode=%s\n", $cHTMLMode);
		$content .= sprintf("AdminName=%s\n", $cAdminName);
		$content .= sprintf("AdminEmail=%s\n", $cAdminEmail);
		$content .= sprintf("MailToAdmin=%s\n", $cMailToAdmin);
		$content .= sprintf("TableWidth=%s\n", $cTableWidth);
		$content .= sprintf("ArticlesPerPage=%s\n", $cArticlesPerPage);
		$content .= sprintf("WriteName=%s\n", $cWriteName);
		$content .= sprintf("WriteSex=%s\n", $cWriteSex);
		$content .= sprintf("WriteBirth=%s\n", $cWriteBirth);
		$content .= sprintf("WriteEmail=%s\n", $cWriteEmail);
		$content .= sprintf("WriteHomeURL=%s\n", $cWriteHomeURL);
		$content .= sprintf("WriteHomeTitle=%s\n", $cWriteHomeTitle);
		$content .= sprintf("WritePhone=%s\n", $cWritePhone);
		$content .= sprintf("WriteAddress=%s\n", $cWriteAddress);
		$content .= sprintf("WritePassword=%s\n", $cWritePassword);
		$content .= sprintf("WriteSubject=%s\n", $cWriteSubject);
		$content .= sprintf("WriteText=%s\n", $cWriteText);
		
		$f = @file_put_contents("$db_dir/db.conf", $content);
		if($f === false)
			die("Can't write db.conf");

		header("Location: ".$_SERVER['PHP_SELF'].'?db='.$db);

		exit;
	}

	if($mode == "write")
	{
			include "message/writehead.html";
?>
<p style="text-align: center"><font size="3"><strong><?php echo htmlspecialchars($settings['Message']); ?></strong></font></p>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?db=<?php echo $db; ?>&mode=writesave">
<table align="center">
<?php
		if($settings["WriteName"] == "true" || $settings["WriteName"] == "must")
		{
?>
<tr>
<td align="center">Name</td>
<td>
<input name="Name" <?php echo (($settings["WriteName"]=="must")? 'required="required" ' : "" );  ?>/>
</td>
</tr>
<?php
		}
		if($settings["WriteSex"] == "true" || $settings["WriteSex"] == "must")
		{
		
?>
<tr>
<td align="center">Sex</td>
<td>
<select name="Sex"<?php echo (($settings["WriteSex"]=="must")? ' required="required"' : "" );  ?>>
<option selected="selected"></option><option>Man</option><option>Woman</option></select>
</td>
</tr>
<?php
		}
		if($settings["WriteBirth"] == "true" || $settings["WriteBirth"] == "must")
		{
		
?>
<tr>
<td align="center">Birth</td>
<td>
<select name="Birth"<?php echo (($settings["WriteBirth"]=="must")? ' required="required"' : "" );  ?>>
<option selected="selected""></option>
<option>88 - 83 year</option>
<option>82 - 80 year</option>
<option>79 - 78 year</option>
<option>77 year</option>
<option>76 year</option>
<option>75 year</option>
<option>74 year</option>
<option>73 year</option>
<option>72 year</option>
<option>71 year</option>
<option>70 year</option>
<option>69 year</option>
<option>68 - 65 year</option>
<option>64 - 60 year</option>
<option>59 - 55 year</option>
<option>54 - 50 year</option>
<option>Grandfather</option>
</select>
</td>
</tr>
<?php
		}
		if($settings["WriteEmail"] == "true" || $settings["WriteEmail"] == "must")
		{
?>
<tr>
<td align="center">Email</td>
<td>
<input name="Email" <?php echo (($settings["WriteEmail"]=="must")? 'required="required" ' : "" );  ?>/>
</td>
</tr>
<?php
		}
		if($settings["WriteHomeURL"] == "true" || $settings["WriteHomeURL"] == "must")
		{
?>
<tr>
<td align="center">HomePage URL</td>
<td>
<input name="HomeURL" <?php echo (($settings["WriteHomeURL"]=="must")? 'required="required" ' : "" );  ?>/>
</td>
</tr>
<?php
		}
		if($settings["WriteHomeTitle"] == "true" || $settings["WriteHomeTitle"] == "must")
		{
?>
<tr>
<td align="center">HomePage Title</td>
<td>
<input name="HomeTitle" <?php echo (($settings["WriteHomeTitle"]=="must")? 'required="required" ' : "" );  ?>/>
</td>
</tr>
<?php
		}
		if($settings["WritePhone"] == "true" || $settings["WritePhone"] == "must")
		{
?>
<tr>
<td align="center">Phone</td>
<td>
<input name="Phone" <?php echo (($settings["WritePhone"]=="must")? 'required="required" ' : "" );  ?>/>
</td>
</tr>
<?php
		}
		if($settings["WriteAddress"] == "true" || $settings["WriteAddress"] == "must")
		{
?>
<tr>
<td align="center">Address</td>
<td>
<input name="Address" <?php echo (($settings["WriteAddress"]=="must")? 'required="required" ' : "" );  ?>/>
</td>
</tr>
<?php
		}
		if($settings["WriteSubject"] == "true" || $settings["WriteSubject"] == "must")
		{
?>
<tr>
<td align="center">Subject</td>
<td>
<input name="Subject" <?php echo (($settings["WriteSubject"]=="must")? 'required="required" ' : "" );  ?>/>
</td>
</tr>
<?php
		}
		if($settings["WritePassword"] == "true" || $settings["WritePassword"] == "must")
		{
?>
<tr>
<td align="center">Password</td>
<td>
<input name="Password" type="password" <?php echo (($settings["WritePassword"]=="must")? 'required="required" ' : "" );  ?>/>
</td>
</tr>
<?php
		}
		if($settings["WriteText"] == "true" || $settings["WriteText"] == "must")
		{
?>
<tr>
<td colspan="2" align="center">
<textarea cols="60" rows="8" name="Text"<?php echo (($settings["WriteText"]=="must")? ' required="required"' : "" );  ?>>
</textarea>
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
		include "message/writetail.html";
		exit;
	}

	if($mode == "writesave")
	{
		$Name = $_POST['Name'];
		$Sex = $_POST['Sex'];
		$Birth = $_POST['Birth'];
		$Email = $_POST['Email'];
		$HomeURL = $_POST['HomeURL'];
		$HomeTitle = $_POST['HomeTitle'];
		$Phone = $_POST['Phone'];
		$Address = $_POST['Address'];
		$Subject = $_POST['Subject'];
		$Password = $_POST['Password'];
		$Text = $_POST['Text'];

		if($settings["WriteName"] == "must" && !$Name)
			die("'Name' field must be specified.");
		if($settings["WriteSex"] == "must" && !$Sex)
			die("'Sex' field must be specified.");
		if($settings["WriteBirth"] == "must" && !$Birth)
			die("'Birth' field must be specified.");
		if($settings["WriteEmail"] == "must" && !$Email)
			die("'Email' field must be specified.");
		if($settings["WriteHomeURL"] == "must" && !$HomeURL)
			die("'HomeURL' field must be specified.");
		if($settings["WriteHomeTitle"] == "must" && !$HomeTitle)
			die("'HomeTitle' field must be specified.");
		if($settings["WritePhone"] == "must" && !$Phone)
			die("'Phone' field must be specified.");
		if($settings["WriteAddress"] == "must" && !$Address)
			die("'Address' field must be specified.");
		if($settings["WriteSubject"] == "must" && !$Address)
			die("'Subject' field must be specified.");
		if($settings["WritePassword"] == "must" && !$Password)
			die("'Password' field must be specified.");
		if($settings["WriteText"] == "must" && !$Text)
			die("'Text' field must be specified.");

		$Name = nl2sp($_POST['Name']);
		$Sex = nl2sp($_POST['Sex']);
		$Birth = nl2sp($_POST['Birth']);
		$Email = nl2sp($_POST['Email']);
		$HomeURL = nl2sp($_POST['HomeURL']);
		$HomeTitle = nl2sp($_POST['HomeTitle']);
		$Phone = nl2sp($_POST['Phone']);
		$Address = nl2sp($_POST['Address']);
		$Subject = nl2sp($_POST['Subject']);
		$Password = crypt($Password, "SY");

		$content = '';
		$content .= sprintf("time-year=%s\n", date("Y"));
		$content .= sprintf("time-mon=%s\n", date("n"));
		$content .= sprintf("time-day=%s\n", date("j"));
		$content .= sprintf("time-hour=%s\n", date("G"));
		$content .= sprintf("time-min=%s\n", date("i"));
		$content .= sprintf("remote-addr=%s\n", $_SERVER['REMOTE_ADDR']);
		$content .= sprintf("remote-host=%s\n", $_SERVER['REMOTE_HOST']);
		$content .= sprintf("query-name=%s\n", $Name);
		$content .= sprintf("query-sex=%s\n", $Sex);
		$content .= sprintf("query-birth=%s\n", $Birth);
		$content .= sprintf("query-email=%s\n", $Email);
		$content .= sprintf("query-homeurl=%s\n", $HomeURL);
		$content .= sprintf("query-hometitle=%s\n", $HomeTitle);
		$content .= sprintf("query-phone=%s\n", $Phone);
		$content .= sprintf("query-address=%s\n", $Address);
		$content .= sprintf("query-subject=%s\n", $Subject);
		$content .= sprintf("query-password=%s\n", $Password);

		$text = explode("\n", $Text);
		$j = count($text);
		for($i=0; $i<$j; $i++)
		{
			$content .= sprintf("query-text%d=%s\n", $i+1, $text[$i]);
		}

		// 글번호 읽어오기
		$datas = glob("data/$db/*.dat");
		if($datas === false)
			die("Can't access data directory.");
	
		foreach($datas as $key => $value)
		{
			$value = basename($value, ".dat");
			if(is_num($value) == false)
				continue;
			$datas[$key] = (int)$value;
		}
	
		// 오름차순으로 정렬
		rsort($datas);

		// 마지막 글 번호를 읽어옴.
		$lastnum = $datas[0];

		$lastnum++;

		while (false === ($f = @file_put_contents("$db_dir/$lastnum.dat", $content, LOCK_EX))) {
			if (file_exists("$db_dir/$lastnum.dat")) {
				$lastnum++;
			} else {
				die("Can't open $db_dir/$lastnum.dat.");
			}
		}
		
		header("Location: ".$_SERVER['PHP_SELF'].'?db='.$db);

		exit;
	}
	if($mode == "delete")
	{
		$Num = $_GET["Num"];
		if(!$Num)
			die("Num is missing.");
		if(is_num($Num) == false)
			die("Num is not correct.");
		$Num = (int)$Num;

		if(!file_exists("$db_dir/$Num.dat")) 
			die("Incorrect article number.");

		$password = $_POST['password'];
		if($password === null)
		{
			include "message/adminhead.html";
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?db=<?php echo $db; ?>&mode=delete&Num=<?php echo (int)$_GET["Num"]; ?>">
<table align="center">
<tr align="center">
<td>DB Name</td><td><?php echo $db; ?></td>
</tr>
<tr align="center">
<td>Access Mode</td><td>delete</td>
</tr>
<tr align="center">
<td>Handling Number</td><td><?php echo (int)$_GET["Num"]; ?></td>
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
			include "message/admintail.html";

			exit;
		}
		else
		{
			// 유저 패스워드를 구한다. 없을경우 null로 유지됨.
			$user_password = null;

			$f = @file_get_contents("$db_dir/$Num.dat");
			if($f===false)
				die("Can't access $Num.dat");
			$a = explode("\n", $f);
			foreach($a as $key => $value)
			{
				if($value === '') continue;
				$b = explode("=", $value, 2);
				if($b[0] == 'query-password')
				{
					$user_password = chop($b[1]);
					break;
				}
			}

			// 관리자의 비번과 같지 않고 유저 패스워드와도 같지 않으면 끝냄.
			if(strcmp(crypt($password, "SY"), $settings["Password"]) != 0)
				if($user_password === null || strcmp(crypt($password, "SY"), $user_password) != 0)
					die("Password incorrect.");

			$f = unlink("$db_dir/$Num.dat");
			if($f===false)
				die("Can't delete $Num.dat");

			header("Location: ".$_SERVER['PHP_SELF'].'?db='.$db);
			
			exit;
		}
	}
	else if ($mode == 'read') 
	{
		include "message/listhead.html";

		
		$num = (int)$_GET["num"];

		$f = @file_get_contents("$db_dir/".$num.".dat");

		if(!$f)
		{
			ob_clean();
			die("Can't access file ".$num.".dat");
		}
		
		// 방문수를 읽어옴.
		$count = (int)@file_get_contents("$db_dir/count.conf");

		$a = explode("\n", $f);
		$contents = array();
		$content = '';
		foreach($a as $key => $value)
		{
			if($value === '') continue;
			$b = explode("=", $value, 2);
			$contents[$b[0]] = chop($b[1]);
			if(preg_match('/(query\-text)([0-9]*)/', $b[0]))
			{
				$content .= chop($b[1]) . "\n";
			}
			}
			// 맨 마지막 줄의 단락 제거.
			$content = chop($content);
?>
<p style="text-align: center"><font size="3"><strong><?php echo htmlspecialchars($settings['Message']); ?></strong></font></p>
<table cellspacing="0" align="center" width="<?php echo $settings["TableWidth"]; ?>">
<tr style="background-color: black;">
<td style="width: 120; text-align: center;">
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?db=<?php echo $db; ?>&mode=admin"><img src="icon/admin.gif" alt="Admin" /></a>
</td>
<td style="text-align: right;">
<font size="2" color="white"><strong>Access : <?php echo $count; ?></strong></font>&nbsp;
</td>
</tr>
</table>
<table cellspacing="0" align="center" width="<?php echo $settings["TableWidth"]; ?>" style="border-bottom: 1px solid black;">
<tr>
<td bgcolor="f0f0f0" align="center" width="120">
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?db=<?php echo $db; ?>&mode=delete&Num=<?php echo $num; ?>"><img src="icon/delete.gif" alt="Del" /></a>
</td>
<td>
<font size="1" face='arial' color="green">
<?php 
	echo $contents['time-year'].'.'.$contents['time-mon'].'.'.$contents['time-day'].'('.$contents['time-hour'].':'.$contents['time-min'].')'.
	" from '".$contents['remote-addr']."' of '".$contents['remote-host']."'"; 
?>
</font>
</td>
</tr>
<?php
	if($contents['query-name'] !== null)
	{
?>
<tr>
<td bgcolor="f0f0f0" align="center"><font size="2">Name</font></td>
<td><font size="2"><?php echo htmlspecialchars($contents['query-name']); ?></font></td>
</tr>
<?php
	}
	if($contents['query-sex'])
	{
?>
<tr>
<td bgcolor="f0f0f0" align="center"><font size="2">Sex</font></td>
<td><font size="2"><?php echo htmlspecialchars($contents['query-sex']); ?></font></td>
</tr>
<?php
	}
	if($contents['query-birth'])
	{
?>
<tr>
<td bgcolor="f0f0f0" align="center"><font size="2">Birth</font></td>
<td><font size="2"><?php echo htmlspecialchars($contents['query-birth']); ?></font></td>
</tr>
<?php
	}
	if($contents['query-email'])
	{
?>
<tr>
<td bgcolor="f0f0f0" align="center"><font size="2">E-mail</font></td>
<td><font size="2"><a href="mailto:<?php echo htmlspecialchars($contents['query-email']); ?>"><?php echo htmlspecialchars($contents['query-email']); ?></a></font></td>
</tr>
<?php
	}
	if($contents['query-homeurl'])
	{
?>
<tr>
<td bgcolor="f0f0f0" align="center"><font size="2">HomePage URL</font></td>
<td><font size="2"><a href="<?php echo htmlspecialchars($contents['query-homeurl']); ?>" target="<?php echo $settings['LinkTarget']; ?>">
<?php echo htmlspecialchars($contents['query-homeurl']); ?></a></font></td>
</tr>
<?php
	}
	if($contents['query-hometitle'])
	{
?>
<tr>
<td bgcolor="f0f0f0" align="center"><font size="2">HomePage Title</font></td>
<td><font size="2"><?php echo htmlspecialchars($contents['query-hometitle']); ?></font></td>
</tr>
<?php
	}
	if($contents['query-phone'])
	{
?>
<tr>
<td bgcolor="f0f0f0" align="center"><font size="2">Phone</font></td>
<td><font size="2"><?php echo htmlspecialchars($contents['query-phone']); ?></font></td>
</tr>
<?php
	}
	if($contents['query-address'])
	{
?>
<tr>
<td bgcolor="f0f0f0" align="center"><font size="2">Address</font></td>
<td><font size="2"><?php echo htmlspecialchars($contents['query-address']); ?></font></td>
</tr>
<?php
	}
	if($contents['query-subject'])
	{
?>
<tr>
<td bgcolor="f0f0f0" align="center"><font size="2">Subject</font></td>
<td><font size="2"><?php echo htmlspecialchars($contents['query-subject']); ?></font></td>
</tr>
<?php
	}
	if($content)
	{
?>
<tr>
<td bgcolor="f0f0f0" align="center"><font size="2">Comments</font></td>
<td>
<font size="2">
<?php 
	if($settings["HTMLMode"] == 'true')
	{
		echo xss_filter(nl2br($content)); 
	}
	else
	{
		echo autolink(nl2br(htmlspecialchars($content)), $settings['LinkTarget']);
	}
?>
</font>
</td>
</tr>
<?php
	}
?>
</table>
<table cellspacing="0" width="<?php echo $settings["TableWidth"]; ?>" border="0" align="center">
<tr>
<td>
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
<a href='message/help.txt'><img src='icon/help.gif' alt="Help" /></a>
</td>
<td align="right">
<a href='<?php echo $_SERVER['PHP_SELF']; ?>?db=<?php echo $db; ?>&mode=write'><img src='icon/write.gif' alt="Write" /></a>
</td>
</tr>
</table>
<?php
		include "message/listtail.html";
		exit;
	}
	else
	{
		if($mode && $mode != 'list')
			die("Unknown mode.");
	}

	// 그 외의 경우에는 글 읽기 모드

	// 방문수를 읽어옴.
	$count = (int)@file_get_contents("$db_dir/count.conf");

	// 카운터를 올린다.
	$count++;
	@file_put_contents("$db_dir/count.conf", (string)$count);

	include "message/listhead.html";
?>
<p style="text-align: center"><font size="3"><strong><?php echo htmlspecialchars($settings['Message']); ?></strong></font></p>
<table cellspacing="0" align="center" width="<?php echo $settings["TableWidth"]; ?>">
<tr style="background-color: black;">
<td style="width: 120; text-align: center;">
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?db=<?php echo $db; ?>&mode=admin"><img src="icon/admin.gif" alt="Admin" /></a>
</td>
<td style="text-align: right;">
<font size="2" color="white"><strong>Access : <?php echo $count; ?></strong></font>&nbsp;
</td>
</tr>
</table>
<?php

	// 글번호 읽어오기
	$datas = glob("data/$db/*.dat");
	if($datas === false)
		die("Can't access data directory.");
	
	foreach($datas as $key => $value)
	{
		$value = basename($value, ".dat");
		if(is_num($value) == false)
			continue;
		$datas[$key] = (int)$value;
	}
	
	// 오름차순으로 정렬
	rsort($datas);

	// 몇번부터 읽을지?
	$Num = $_GET["Num"];
	if ($Num === null)
		$Num = $datas[0];
	
	// 정수화.
	$Num = (int)$Num;

	// 읽어오는 갯수 제한.
	$remain = (int)$settings["ArticlesPerPage"];
	if($remain == 0) $remain = -1;
	
	// 읽어온다.
	$j = count($datas);
	for($i=0; $i<$j; $i++)
	{
		if($datas[$i] <= $Num)
		{
			if($remain > 0) $remain--;
			$f = @file_get_contents("$db_dir/".$datas[$i].".dat");
			if(!$f)
			{
				ob_clean();
				die("Can't access file ".$datas[$i].".dat");
			}
			$a = explode("\n", $f);
			$contents = array();
			$content = null;
			foreach($a as $key => $value)
			{
				if($value === '') continue;
				$b = explode("=", $value, 2);
				$contents[$b[0]] = chop($b[1]);
				if(preg_match('/(query\-text)([0-9]*)/', $b[0]))
				{
					$content .= chop($b[1]) . "\n";
				}
			}
			// 맨 마지막 줄의 단락 제거.
			$content = chop($content);
?>
<table cellspacing="0" align="center" width="<?php echo $settings["TableWidth"]; ?>" style="border-bottom: 1px solid black;">
<tr>
<td bgcolor="f0f0f0" align="center" width="120">
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?db=<?php echo $db; ?>&mode=delete&Num=<?php echo $datas[$i]; ?>"><img src="icon/delete.gif" alt="Del" /></a>
</td>
<td>
<font size="1" face='arial' color="green">
<?php 
	echo $contents['time-year'].'.'.$contents['time-mon'].'.'.$contents['time-day'].'('.$contents['time-hour'].':'.$contents['time-min'].')'.
	" from '".$contents['remote-addr']."' of '".$contents['remote-host']."'"; 
?>
</font>
</td>
</tr>
<?php
	if($contents['query-name'] !== null)
	{
?>
<tr>
<td bgcolor="f0f0f0" align="center"><font size="2">Name</font></td>
<td><font size="2"><?php echo htmlspecialchars($contents['query-name']); ?></font></td>
</tr>
<?php
	}
	if($contents['query-sex'])
	{
?>
<tr>
<td bgcolor="f0f0f0" align="center"><font size="2">Sex</font></td>
<td><font size="2"><?php echo htmlspecialchars($contents['query-sex']); ?></font></td>
</tr>
<?php
	}
	if($contents['query-birth'])
	{
?>
<tr>
<td bgcolor="f0f0f0" align="center"><font size="2">Birth</font></td>
<td><font size="2"><?php echo htmlspecialchars($contents['query-birth']); ?></font></td>
</tr>
<?php
	}
	if($contents['query-email'])
	{
?>
<tr>
<td bgcolor="f0f0f0" align="center"><font size="2">E-mail</font></td>
<td><font size="2"><a href="mailto:<?php echo htmlspecialchars($contents['query-email']); ?>"><?php echo htmlspecialchars($contents['query-email']); ?></a></font></td>
</tr>
<?php
	}
	if($contents['query-homeurl'])
	{
?>
<tr>
<td bgcolor="f0f0f0" align="center"><font size="2">HomePage URL</font></td>
<td><font size="2"><a href="<?php echo htmlspecialchars($contents['query-homeurl']); ?>" target="<?php echo $settings['LinkTarget']; ?>">
<?php echo htmlspecialchars($contents['query-homeurl']); ?></a></font></td>
</tr>
<?php
	}
	if($contents['query-hometitle'])
	{
?>
<tr>
<td bgcolor="f0f0f0" align="center"><font size="2">HomePage Title</font></td>
<td><font size="2"><?php echo htmlspecialchars($contents['query-hometitle']); ?></font></td>
</tr>
<?php
	}
	if($contents['query-phone'])
	{
?>
<tr>
<td bgcolor="f0f0f0" align="center"><font size="2">Phone</font></td>
<td><font size="2"><?php echo htmlspecialchars($contents['query-phone']); ?></font></td>
</tr>
<?php
	}
	if($contents['query-address'])
	{
?>
<tr>
<td bgcolor="f0f0f0" align="center"><font size="2">Address</font></td>
<td><font size="2"><?php echo htmlspecialchars($contents['query-address']); ?></font></td>
</tr>
<?php
	}
	if($contents['query-subject'])
	{
?>
<tr>
<td bgcolor="f0f0f0" align="center"><font size="2">Subject</font></td>
<td><font size="2"><?php echo htmlspecialchars($contents['query-subject']); ?></font></td>
</tr>
<?php
	}
	if($content)
	{
?>
<tr>
<td bgcolor="f0f0f0" align="center"><font size="2">Comments</font></td>
<td>
<font size="2">
<?php 
	if($settings["HTMLMode"] == 'true')
	{
		echo xss_filter(nl2br($content)); 
	}
	else
	{
		echo autolink(nl2br(htmlspecialchars($content)), $settings['LinkTarget']);
	}
?>
</font>
</td>
</tr>
<?php
	}
?>
</table>
<?php
			if($remain == 0) 
			{
				// 종료함
				break;
			}
		}
	}
?>
<table cellspacing="0" width="<?php echo $settings["TableWidth"]; ?>" border="0" align="center">
<tr>
<td>
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
<a href='message/help.txt'><img src='icon/help.gif' alt="Help" /></a>
</td>
<td align="right">
<?php
	// 이전에 글이 있을 경우 뒤로 버튼을 만듬.
	// 뒤로 버튼을 더 정교하게 만들 수 있지만 일단 자바스크립트로 처리.
	if(count($datas) != 0 && $datas[0] > $Num)
	{
?>
<a href="#" onclick="history.back();return false;"><img src='icon/up.gif' alt="Backward" /></a>
<?php
	}
	// 남김없이 다 출력되었을 때, 뒤에 글이 더 있다면 다음 버튼을 표시
	if($remain == 0 && $i<$j-1)
	{
?>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?db=<?php echo $db; ?>&mode=list&Num=<?php echo $datas[$i+1]; ?>"><img src='icon/down.gif' alt="Forward" /></a>
<?php
	}
?>
<a href='<?php echo $_SERVER['PHP_SELF']; ?>?db=<?php echo $db; ?>&mode=write'><img src='icon/write.gif' alt="Write" /></a>
</td>
</tr>
</table>
<?php
	include "message/listtail.html";

	exit;
	









	/* 다용도 함수들 */

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
		'|frameset|frame|noframes|iframe|applet|object|param|noscript|noembed|basefont|xmp|plaintext|comment)/i',
		'&lt;$2$3', $content);
 
		// Strip script handlers.
		$content = preg_replace_callback("/([^a-z])(o)(n)/i", 
		create_function('$matches', 'if($matches[2]=="o") $matches[2] = "&#111;";
		else $matches[2] = "&#79;"; return $matches[1].$matches[2].$matches[3];'), $content);

		// Embed 태그 처리
		$content = str_ireplace("<embed", '<embed allowscriptaccess="never"', $content);
 
		return $content;
	}
