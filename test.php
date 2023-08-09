
<?php
  require ("PHPMailer/PHPMailer.php");

require("PHPMailer/SMTP.php");
require("PHPMailer/Exception.php");

//$mail = new PHPMailer;
$mail = new PHPMailer\PHPMailer\PHPMailer();

$mail->isSMTP();   
//$mail->isMail();                          // Set mailer to use SMTP
/*$mail->Host = 'two.deepafrica.com';             // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                     // Enable SMTP authentication
$mail->Username = 'support@edcheckafrica.com';          // SMTP username
$mail->Password = '93l3z@1nt'; // SMTP password
$mail->SMTPSecure = 'tls';                  // Enable TLS encryption, `ssl` also accepted
$mail->Port = 487;                       // TCP port to connect to

*/
$mail->Host = 'two.deepafrica.com';             // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                     // Enable SMTP authentication
$mail->Username = 'verify@edcheckafrica.com';          // SMTP username
$mail->Password = 'vkr67XpjsBnVkKK5'; // SMTP password
//$mail->SMTPSecure = 'ssl';                  // Enable TLS encryption, `ssl` also accepted
//$mail->Port = 465;                    // TCP port to connect to
$mail->SMTPSecure = 'tls';                  // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;    

$mail->setFrom('verify@edcheckafrica.com', 'Verification Test Email');

$mail->addAddress('marita@peleza.com');
//$mail->addAddress('omintolbert@gmail.com');   // Add a recipient
$mail->addCC('omintolbert@gmail.com');
$mail->addBCC('omintolbert@gmail.com');

$mail->isHTML(true);  // Set email format to HTML

$bodyContent = '<body>
<table width="530" cellspacing="0" cellpadding="0" border="0" align="center">
  <tbody>
    <tr valign="top">
      <td>Hello, <br />
          <br />
       Thank you for registering as a client at EdCheck Africa. </td>
    </tr>
  </tbody>
</table>
<table width="530" cellspacing="0" cellpadding="0" border="0" align="center">
  <tbody>
    <tr valign="top">
      <td><br />
      TEST EMAIL TEST EMAIL<br />
        <br />
      </td>
    </tr>
  </tbody>
</table>
<table width="530" cellspacing="0" cellpadding="0" border="0" align="center">
  <tbody>
    <tr valign="top">
      <td><br />
        * Your registered login Username will be your confirmed Email Address. *<br />
        <br />
      </td>
    </tr>
  </tbody>
</table>';
//$bodyContent .= '<p>This is the HTML email sent from localhost using PHP script by <b>CodexWorld</b></p>';

$mail->Subject = 'Do Not Reply: TEST TEST';
$mail->Body    = $bodyContent;

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;

} else {
    echo 'Message has been sent'; 
	}
?>
