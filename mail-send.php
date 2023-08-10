 <?php

echo('here'); exit;
 if($_POST)
 {
     
 	$sender_name = filter_var($_POST["s_name"], FILTER_SANITIZE_STRING);
 	$sender_email = filter_var($_POST["s_email"], FILTER_SANITIZE_STRING);
 	$sender_subject = filter_var($_POST["s_subject"], FILTER_SANITIZE_STRING);
 	$sender_message = filter_var($_POST["s_message"], FILTER_SANITIZE_STRING);
 	$attachments = $_FILES['file'];

 	$recipient_email    = "admin@email.com";
 	$from_email         = $sender_email;
 	$subject            =  $sender_subject;

 	if(strlen($sender_name)<4){
 		die('Name is too short or empty');
 	}
 	if (!filter_var($sender_email, FILTER_VALIDATE_EMAIL)) {
 		die('Invalid email');
 	}
 	if(strlen($sender_message)<4){
 		die('Too short message! Please enter something');
 	}

 	$file_count = count($attachments['name']); 
 	$boundary = md5("sanwebe.com");

 	if($file_count > 0){ 
 		$headers = "MIME-Version: 1.0\r\n";
 		$headers .= "From:".$from_email."\r\n";
 		$headers .= "Reply-To: ".$sender_email."" . "\r\n";
 		$headers .= "Subject: ".$sender_subject."" . "\r\n";
 		$headers .= "Content-Type: multipart/mixed; boundary = $boundary\r\n\r\n";

        //message text
 		$body = "--$boundary\r\n";
 		$body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
 		$body .= "Content-Transfer-Encoding: base64\r\n\r\n";
 		$body .= chunk_split(base64_encode($sender_message));

        //attachments
 		for ($x = 0; $x < $file_count; $x++){      
 			if(!empty($attachments['name'][$x])){

 				if($attachments['error'][$x]>0) 
 				{
 					$mymsg = array(
 						1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini",
 						2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
 						3=>"The uploaded file was only partially uploaded",
 						4=>"No file was uploaded",
 						6=>"Missing a temporary folder" );

                                   echo json_encode(array( 'msg' => 'error', 'response' => $mymsg[$attachments['error'][$x]]));
 				}


 				$file_name = $attachments['name'][$x];
 				$file_size = $attachments['size'][$x];
 				$file_type = $attachments['type'][$x];

 				$handle = fopen($attachments['tmp_name'][$x], "r");
 				$content = fread($handle, $file_size);
 				fclose($handle);
 				$encoded_content = chunk_split(base64_encode($content)); 

 				$body .= "--$boundary\r\n";
 				$body .="Content-Type: $file_type; name=".$file_name."\r\n";
 				$body .="Content-Disposition: attachment; filename=".$file_name."\r\n";
 				$body .="Content-Transfer-Encoding: base64\r\n";
 				$body .="X-Attachment-Id: ".rand(1000,99999)."\r\n\r\n";
 				$body .= $encoded_content;
 			}
 		}

 	}else{ 
 		$headers = "From:".$from_email."\r\n".
 		"Reply-To: ".$sender_email. "\n" .
 		"X-Mailer: PHP/" . phpversion();
 		$body = $sender_message;
 	}

 	$sentMail = @mail($recipient_email, $subject, $body, $headers);
 	if ($sentMail) {
 		$response = array('msg' => 'success', 'response' => 'Thank you for contacting us we respond you ASAP.');
 	} else {
 		$response = array( 'msg' => 'error', 'response' => 'Could not send mail! Please check your PHP mail configuration.'
 	);
 	}

 	header('Content-Type: application/json');
 	echo json_encode($response);
 }

?>
