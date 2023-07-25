<?php
$spojeni = mysqli_connect(DBHOST, DBUSER, DBPASS, "cis_extra"); // Převzato ze souboru \include\ost-config.php
$workist = mysqli_query($spojeni, "SELECT * from cis_workist WHERE `id` = 1");
while ($zaznam_workist = mysqli_fetch_array ($workist)) 
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
	$wmessage 		= $zaznam_workist["message"];
}

if($ticket->getDeptName() == $wDeptName OR $thisstaff->getId() == 8) //Přístup admina
{
	if(isset($_GET['uname']))
	{
		//Následující je umístěno v C:\wamp\www\cis-ticket\scp\tickets.php, protože namespace nelze definovat uprostřed kódu
		
		//require_once	($_SERVER['DOCUMENT_ROOT'].'/PH_scripts/PHPMailer/phpmailer.php');
		//require_once	($_SERVER['DOCUMENT_ROOT'].'/PH_scripts/PHPMailer/SMTP.php');
		//require_once	($_SERVER['DOCUMENT_ROOT'].'/PH_scripts/PHPMailer/Exception.php');
		//use PHPMailer\PHPMailer\PHPMailer;
		//use PHPMailer\PHPMailer\Exception;
		//use PHPMailer\PHPMailer\SMTP;
		//$mail = new PHPMailer();

		$ph_ticket_id 	= $_GET['id'];
		$ph_user_id 	= $_GET['uid'];
		$ph_username 	= $_GET['uname'];
		$ph_owner		= $ticket->getOwner();
		$ph_usermail 	= $thisstaff->getEmail();
		$ph_dept_id 	= $_GET['dept'];
		$ph_statustext 	= '{"status":[2,"Geschlossen \/ Uzav\u0159eno"]}';
		$dir 			= $wtmpDir;
		$dirto 			= $dir.$ph_ticket_id."/";
		$ph_files 		= array();
		$ph_m_title 	= $ticket->getSubject(); //Uložili jsme si předmět

		$mail->SetFrom($ph_usermail, $thisstaff->getName());
		$mail->AddAddress( $wadressTo );
		$mail->IsHTML(true);
		$mail->Subject    = $wsubject;
		$mail->Body       = $wbody;
		$mail->CharSet    = $wcharset;
		$mail->Host 	  = $whost; // Specify main and backup SMTP servers
		$mail->SMTPAuth   = true; // Enable SMTP authentication
		$mail->Username   = $wusername; // SMTP username
		$mail->Password   = $wPassword; // SMTP password
		$mail->SMTPSecure = $wSMTPsecure; // Enable TLS encryption, `ssl` also accepted
		$mail->Port		  = $wport;
		//$mail->isSMTP(); // Set mailer to use SMTP

		mkdir($dirto, 0777);						//Vytvořili jsme adresář s názvem ticket_id
		
		//Smažeme případné staré soubory
		$del_files = glob("$dir/*"); 
		foreach($del_files as $del_file)
		{
			if(is_file($del_file)) {unlink($del_file);}
		}
		sleep (1);
		$spojeni->close();

		$spojeni = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
		date_default_timezone_set("Europe/Prague");
		$dnes=date("Y-m-d H:i:s");
		$ost_thread = mysqli_query($spojeni, "SELECT * from ost_thread WHERE `object_id` = $ph_ticket_id");
		while ($zaznam_ticket = mysqli_fetch_array ($ost_thread)) 
		{
			$ph_thread_id = $zaznam_ticket["id"]; //Zjistili jsme thread_id
		}

		//mysqli_query($spojeni, "INSERT INTO `ost_thread_event`(`thread_id`, `thread_type`, `event_id`, `staff_id`, `team_id`, `dept_id`, `topic_id`, `data`, `username`, `uid`, `uid_type`, `annulled`, `timestamp`) VALUES ('$ph_thread_id','T','2','$ph_user_id','0','$ph_dept_id','0','$ph_statustext','$ph_username','$ph_user_id','S','0','$dnes')");
		//Vložili jsme nový event
		echo "UPDATE `ost_thread` SET `lastresponse` = $dnes WHERE `object_id` = $ph_ticket_id";
		mysqli_query($spojeni, "UPDATE `ost_thread` SET `lastresponse` = '".$dnes."' WHERE `ost_thread`.`object_id` = $ph_ticket_id"); //Upravili jsme datum poslední úpravy tiketu
		mysqli_query($spojeni, "INSERT INTO `ost_thread_entry`(`pid`, `thread_id`, `staff_id`, `user_id`, `type`, `time_spent`, `time_type`, `time_bill`, `flags`, `poster`, `body`, `format`, `ip_address`, `created`) VALUES (0,'$ph_thread_id','$ph_user_id',0,'N',0,0,0,64,'$ph_owner','$wmessage','html','::1','$dnes')");
		//Vložili jsme poznámku
		//mysqli_query($spojeni, "UPDATE `ost_ticket` SET `status_id` = '2' WHERE `ost_ticket`.`ticket_id` = $ph_ticket_id"); //Zavřeli jsme tiket

		//Přílohy
		$ost_thread_entry = mysqli_query($spojeni, "SELECT * FROM `ost_thread_entry` WHERE `thread_id` = $ph_thread_id AND `type` = 'M'"); //Zjistíme všechny zápisy do tiketu
		while ($zaznam_entry = mysqli_fetch_array ($ost_thread_entry)) 
		{
			$ph_m_id = $zaznam_entry["id"]; //Uložili jsme si message id
			$ph_m_body = $zaznam_entry["body"]; //Uložili jsme si text zprávy
			
			$ost_attachment = mysqli_query($spojeni, "SELECT * from ost_attachment WHERE `object_id` = $ph_m_id AND `inline` = 0"); //`inline` = 0 omezuje na soubory pouze v příloze
			while ($zaznam_ticket = mysqli_fetch_array ($ost_attachment)) 
			{
				$ph_attachment_id = $zaznam_ticket["file_id"]; //Zjistili jsme file_id

				$ost_file = mysqli_query($spojeni, "SELECT * from ost_file WHERE `id` = $ph_attachment_id");
				while ($zaznam_file = mysqli_fetch_array ($ost_file)) 
				{
					$ph_file_name = $zaznam_file["name"]; //Zjistili jsme jméno souboru
					$ph_file_key = $zaznam_file["key"]; //Zjistili jsme key souboru - tedy jeho umístění
					
					$file_from = $_SERVER['DOCUMENT_ROOT'].'/Attachments/'.substr($ph_file_key, 0, 1).'/'.$ph_file_key;
					$file_to = $dirto.$ph_file_name;
					copy ($file_from, $file_to);
					
					$ph_files[] = $file_to;
				}
			}
		}

		//Pokud jsou v adresáři soubory, přidáme je do přílohy a odešleme e-mail
		if (count(glob("$dirto/*")) === 0)
		{
			//echo "<h1>Nebyly nalezeny žádné přílohy</h1>";
		}
		else
		{
			foreach ($ph_files as $ph_file)	{$mail->AddAttachment( $ph_file );}
			
			if ($ph_m_title <> "") {$mail->Subject = $ph_m_title;}
			if ($ph_m_body <> "") {$mail->Body    = $ph_m_body;}
			//return $mail->Send();
			if ($thisstaff->getId() <> 8)
			{$mail->Send();}
		}
		?>
		<li>
			<a href="tickets.php?id=<?php echo $ticket->getId().'&uid='.$thisstaff->getId().'&uname='.$thisstaff->getUserName().'&dept='.$dept->getId(); ?>">			
				<i class="icon-weibo"></i> <?php echo __('Workist'); ?>
		   </a>
		</li>
		<?php 
	}
	else
	{
		?>
		<li>
			<a href="tickets.php?id=<?php echo $ticket->getId().'&uid='.$thisstaff->getId().'&uname='.$thisstaff->getUserName().'&dept='.$dept->getId(); ?>">				
				<i class="icon-weibo"></i> <?php echo __('Workist'); ?>
		   </a>
		</li>
		<?php
	}
}
?>