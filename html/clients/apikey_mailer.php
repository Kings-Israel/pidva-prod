<?php require_once('../../Connections/connect.php');
require("../../PHPMailer/PHPMailer.php");
require("../../PHPMailer/SMTP.php");
require("../../PHPMailer/Exception.php");

class ApikeyMailer
{
    public function __construct()
    {


    }

    public function send_mail(
        $client_name,
        $client_email,
        $apikey,
        $account_type,
        $client_id
    ) {

        
        $name = $client_name;
        $product_name = $account_type;
        $username = $client_email;
        $apikey = $apikey;
        

         
        $productionlink = 'https://api.psmt.pidva.africa';
        $devlink = 'https://dev.psmt.pidva.africa';
        $docslink = 'https://api.pidva.africa/';
        
            $mail = new PHPMailer\PHPMailer\PHPMailer();
 
            $mail->isSMTP();
            $mail->Host = 'smtppro.zoho.com';             // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                     // Enable SMTP authentication
            $mail->Username = 'supportadmin@peleza.com';          // SMTP username
            $mail->Password = 'zcu5bS+Vn5gw$n@a'; // SMTP password
            $mail->SMTPSecure = 'ssl';                  // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 465;                           // TCP port to connect to

            $mail->setFrom('supportadmin@peleza.com', 'Client Credentials>> Peleza International');
          
        $toemail = $username;

        $mail->addAddress($toemail);

        $mail->isHTML(true); // Set email format to HTML

        $bodyContent =
            '
        <html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Peleza International</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@200&display=swap" rel="stylesheet">
</head>

<body itemscope itemtype="http://schema.org/EmailMessage"
    style="-webkit-font-smoothing:antialiased; -webkit-text-size-adjust:none; align-items:center; display:flex; height:100vh; line-height:1.6em; background-color:#f6f6f6; width:100%;font-family: Roboto, sans-serif;"
    bgcolor="#f6f6f6" width="100%">
    <center height="100vh" width="100%">
        <table class="body-wrap" style="background-color:#f6f6f6; width:100%" bgcolor="#f6f6f6" width="100%">
            <tr>
                <td style="vertical-align:top" valign="top"></td>
                <td class="container" width="600"
                    style="vertical-align:top; clear:both; display:block; margin:0 auto; max-width:600px" valign="top">
                    <div class="content" style="display:block; margin:0 auto; max-width:600px; padding:20px">
                        <table class="main" width="100%" cellpadding="0" cellspacing="0"
                            style="background-color:#fff; border:1px solid #e9e9e9; border-radius:3px"
                            bgcolor="#ffffff">
                            <tr>
                                <td class="alert alert-warning"
                                    style="vertical-align:top; border-radius:3px 3px 0 0; color:#fff; font-size:16px;display: flex; font-weight:500; padding:20px; justify-content: center; text-align:center; background-color:#0a4157"
                                    valign="top" align="center" bgcolor="#0a4157">
                                    <h4 style="font-size:14px; font-weight:600"> Please Activate Your Account
                                    </h4>
                                </td>
                            </tr>
                            <tr>
                                <td class="content-wrap" style="vertical-align:top; padding:20px" valign="top">
                                    <table width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td class="content-block" style="vertical-align:top; padding:0 0 2px"
                                                valign="top"></td>
                                        </tr>
                                        <tr>
                                            <td class="content-block">
                                                <p style="text-transform: capitalize;">Hello ' .
            $name .
            ',</p>
                                                <p>Your Acccount has been enabled to access Peleza' .
            $product_name .
            'APIs. Its your responsibility to keep your API Keys securely.Your API Credentials are</p>
                                                

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="content-block" style="padding: .6rem; font-family: monospace;"
                                                align="center">
                                                <blockquote
                                                    style="padding: .2rem 1rem; background-color: #f4f4f4; border-radius: .4rem; text-align: left;">
                                                    <p>ClientID: ' .
            $client_id .
            '</p>
                                                    <p>API KEY: ' .
            $apikey .
            '</p>
                                                    <client_id />
                                                </blockquote>
                                            </td>
                                        </tr>
                                         <tr>
                                            <td class="content-block">
                                                <p><strong>Get Started with Integration<strong></p>
                                                <p>We have a handful of resources to boostrap your intergration. You will need both your ClientID and API Key. Below is the api endpoints and a link to api documentation</p>
                                                                                           <p>Production API Endpoint: ' .
            $productionlink .
            '</p>
                                                    <p>Development API Endpoint: ' .
            $devlink .
            '</p>
             <p>API Documentation: ' .
            $docslink .
            '</p>
                                            </td>
                                        </tr>

                                        <tr align="left">
                                            <td class="content-block" style="vertical-align:top; padding:0 0 20px"
                                                valign="top">
                                                <p style="margin: 0; padding:0;">Happy Integration,</p>
                                                <p style="margin: 0; padding:0; text-transform: capitalize;">
                                                    Peleza International Team.
                                                </p>
                                        </tr>
                                        <tr align="center">
                                            <td class="content-block" style="vertical-align:top; padding:0 0 20px"
                                                valign="top">
                                                <small style="opacity:0.73">If you are having trouble getting started,
                                                    feel
                                                    free to email our customer support <a
                                                        href="mailto:supportadmin@peleza.com">supportadmin@peleza.com</a>
                                                </small>

                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <div class="footer" style="clear:both; color:#999; padding:20px; width:100%" width="100%">
                            <table width="100%">
                                <tr>
                                    <td class="aligncenter content-block"
                                        style="vertical-align:top; padding:0 0 20px; text-align:center; color:#999; font-size:12px"
                                        valign="top" align="center">Please note that this is a system generated report.
                                        If
                                        you received this email by mistake, please contact supportadmin@peleza.com</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
                <td style="vertical-align:top" valign="top"></td>
            </tr>
        </table>
    </center>
</body>
<style>
    @media only screen and (max-width: 640px) {
        body {
            padding: 0
        }

        h1,
        h2,
        h3,
        h4 {
            font-weight: 800;
            margin: 20px 0 5px
        }

        h1 {
            font-size: 22px
        }

        h2 {
            font-size: 18px
        }

        h3 {
            font-size: 16px
        }

        .container {
            padding: 0;
            width: 100%
        }

        .content {
            padding: 0
        }

        .content-wrap {
            padding: 10px
        }

        .invoice {
            width: 100%
        }
    }
</style>

</html> ';

        $mail->Subject = 'Confidential Background Check Request - TEST';
        $mail->Body = $bodyContent;

        if (!$mail->Send()) {
            echo 'Error while sending Email.';
            //var_dump($mail);
        } else {
            echo 'Email sent successfully';
 // var_dump($mail);
        }
    }
}

$apikey_mailer = new ApikeyMailer();
