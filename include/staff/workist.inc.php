<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin()) die('Access Denied');


if(isset($_POST['submit']))
{
	$spojeni->execute_query("UPDATE `cis_workist` SET `DeptName` = ?,      `tmpDir` = ?,      `adressTo` = ?,      `subject` = ?,      `body` = ?,      `charset` = ?,      `host` = ?,      `username` = ?,      `Password` = ?,      `SMTPsecure` = ?,      `port` = ?,      `message` = ?", 
										     [$_POST['wDeptName'], $_POST['wtmpDir'], $_POST['wadressTo'], $_POST['wsubject'], $_POST['wbody'], $_POST['wcharset'], $_POST['whost'], $_POST['wusername'], $_POST['wPassword'], $_POST['wSMTPsecure'], $_POST['wport'], $_POST['wmessage']]);
}

$cis_workist = mysqli_query($spojeni, "SELECT * FROM `cis_workist` WHERE `id` = 1");
		while ($zaznam_workist = mysqli_fetch_array ($cis_workist)) 
		{
			$wDeptName 		= $zaznam_workist["DeptName"];
			$wtmpDir 		= $zaznam_workist["tmpDir"];
			$wadressTo 		= $zaznam_workist["adressTo"];
			$wsubject 		= $zaznam_workist["subject"];
			$wbody 			= $zaznam_workist["body"];
			$wcharset 		= $zaznam_workist["charset"];
			$whost 			= $zaznam_workist["host"];
			$wusername 		= $zaznam_workist["username"];
			$wPassword 		= $zaznam_workist["Password"];
			$wSMTPsecure 	= $zaznam_workist["SMTPsecure"];
			$wport 			= $zaznam_workist["port"];
			$wmessage		= $zaznam_workist["message"];
		} ?>

<form action="workist_settings.php" method="POST">
	<?php csrf_token(); ?>
	<input type="hidden" name="t" value="system" >
	<table class="form_table settings_table" width="940" border="0" cellspacing="0" cellpadding="2">
		<div style="padding-left:2px;">
			<tbody>
				<tr>
					<th colspan="2">
						<em><b><?php echo __('General Settings'); ?></b></em>
					</th>
				</tr>
				<tr>
					<td width="300"><?php echo __('Department name');?>:</td>
					<td><input size="60" class="typeahead" id="wDeptName" size=15 name="wDeptName" value="<?php echo Format::htmlchars($wDeptName); ?>" autocomplete="off"></td>
				</tr>
				<tr>
					<td><?php echo __('A temporary directory for storing files');?>:</td>
					<td><input size="60" class="typeahead" id="wtmpDir" size=15 name="wtmpDir" value="<?php echo Format::htmlchars($wtmpDir); ?>" autocomplete="off"></td>
				</tr>
				<tr>
					<th colspan="2">
						<em><b><?php echo __('Mail Settings'); ?></b></em>
					</th>
				</tr>
				<tr>
					<td><?php echo __('Recipients address');?>:</td>
					<td><input size="60" class="typeahead" id="wadressTo" size=15 name="wadressTo" value="<?php echo Format::htmlchars($wadressTo); ?>" autocomplete="off"></td>
				</tr>
				<tr>
					<td><?php echo __('Email subject');?>:</td>
					<td><input size="60" class="typeahead" id="wsubject" size=15 name="wsubject" value="<?php echo Format::htmlchars($wsubject); ?>" autocomplete="off"></td>
				</tr>
				<tr>
					<td><?php echo __('The message in the email');?>:</td>
					<td><input size="60" class="typeahead" id="wbody" size=15 name="wbody" value="<?php echo Format::htmlchars($wbody); ?>" autocomplete="off"></td>
				</tr>
				<tr>
					<td><?php echo __('Charset');?>:</td>
					<td><input size="60" class="typeahead" id="wcharset" size=15 name="wcharset" value="<?php echo Format::htmlchars($wcharset); ?>" autocomplete="off"></td>
				</tr>
				<tr>
					<td><?php echo __('Host');?>:</td>
					<td><input size="60" class="typeahead" id="whost" size=15 name="whost" value="<?php echo Format::htmlchars($whost); ?>" autocomplete="off"></td>
				</tr>
				<tr>
					<td><?php echo __('User name');?>:</td>
					<td><input size="60" class="typeahead" id="wusername" size=15 name="wusername" value="<?php echo Format::htmlchars($wusername); ?>" autocomplete="off"></td>
				</tr>
				<tr>
					<td><?php echo __('Password');?>:</td>
					<td><input size="60" class="typeahead" id="wPassword" size=15 name="wPassword" value="<?php echo Format::htmlchars($wPassword); ?>" autocomplete="off"></td>
				</tr>
				<tr>
					<td><?php echo __('SMTP secure');?>:</td>
					<td><input size="60" class="typeahead" id="wSMTPsecure" size=15 name="wSMTPsecure" value="<?php echo Format::htmlchars($wSMTPsecure); ?>" autocomplete="off"></td>
				</tr>
				<tr>
					<td><?php echo __('Port number');?>:</td>
					<td><input size="60" class="typeahead" id="wport" size=15 name="wport" value="<?php echo Format::htmlchars($wport); ?>" autocomplete="off"></td>
				</tr>
				<tr>
					<td><?php echo __('Note in the ticket');?>:</td>
					<td><input size="60" class="typeahead" id="wmessage" size=15 name="wmessage" value="<?php echo Format::htmlchars($wmessage); ?>" autocomplete="off"></td>
				</tr>
			</tbody>
		</div>
	</table>
	<p style="text-align:center;">
		<input class="button" type="submit" name="submit" value="<?php echo __('Save Changes');?>">
		<input class="button" type="reset" name="reset" value="<?php echo __('Reset Changes');?>">
	</p>
</form>