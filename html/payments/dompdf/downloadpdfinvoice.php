<?php
/*namespace Dompdf;
require_once 'dompdf/autoload.inc.php';
ob_start();*/
?>
<?php require_once('../Connections/connect.php'); ?><?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "../index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($connect,$theValue) : mysqli_escape_string($connect,$theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$currentPage = $_SERVER["PHP_SELF"];

$client_id_get_psmt_requests = "-1";
if (isset($_SESSION['MM_client_id'])) {
  $client_id_get_psmt_requests = $_SESSION['MM_client_id'];
}
$client_login_id_get_psmt_requests = "-1";
if (isset($_SESSION['MM_client_login_id'])) {
  $client_login_id_get_psmt_requests = $_SESSION['MM_client_login_id'];
}
$colname_getrequestdetails = "-1";
if (isset($_GET['payment_id'])) {
  $colname_getrequestdetails = $_GET['payment_id'];
}

$colname_payment_ref = "-1";
if (isset($_GET['payment_ref'])) {
  $colname_payment_ref = $_GET['payment_ref'];
}


$colname_request_payment_number = "-1";
if (isset($_GET['request_payment_ref'])) {
  $colname_request_payment_number = $_GET['request_payment_ref'];
}


mysqli_select_db($connect,$database_connect);
$query_getrequestdetails = sprintf("SELECT
pel_payments.payment_id,
pel_payments.payment_plan,
pel_payments.payment_ref,
pel_payments.plan_id,
pel_payments.plan_volume,
pel_payments.plan_discount,
pel_payments.payment_date,
pel_payments.client_id,
pel_payments.payment_status,
pel_payments.module_id,
pel_payments.pay_source,
pel_payments.`status`,
pel_payments.amount,
pel_payments.currency,
pel_payments.client_name,
pel_payments.source_ref,
pel_payments.plan_amount,
pel_payments.payment_account,
pel_payments.source_merchant_id,
pel_payments.account_email,
pel_payments.payment_file,
pel_payments.address_country,
pel_payments.address_city,
pel_payments.address_postal,
pel_payments.address_postal_code,
pel_payments.invoice_number,
pel_payments.verified_by,
pel_payments.verified_date,
pel_payments.client_login_id,
pel_payments.notes,
pel_client.client_parent_company,
pel_client.client_company_id
FROM
pel_payments
Inner Join pel_client ON pel_payments.client_id = pel_client.client_id
WHERE payment_id = %s", GetSQLValueString($colname_getrequestdetails, "int"));
$getrequestdetails = mysqli_query_ported($query_getrequestdetails, $connect) or die(mysqli_error($connect));
$row_getrequestdetails = mysqli_fetch_assoc($getrequestdetails);
$totalRows_getrequestdetails = mysqli_num_rows($getrequestdetails);


mysqli_select_db($connect,$database_connect);
$query_getprogress2= sprintf("SELECT * FROM pel_psmt_request WHERE request_payment_ref = %s ORDER BY request_date ASC", GetSQLValueString($colname_payment_ref, "text"));
$getprogress2 = mysqli_query_ported($query_getprogress2, $connect) or die(mysqli_error($connect));
$row_getprogress2 = mysqli_fetch_assoc($getprogress2);
$totalRows_getprogress2 = mysqli_num_rows($getprogress2);	

?><!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Peleza Screening and Monitoring Tool Kit">
	<meta name="author" content="Peleza">
	<title>PSMT-Peleza Screening Management Toolkit</title>

		<!-- BASE CSS -->
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="../css/style.css" rel="stylesheet">
    	<link href="../css/icon_fonts/css/all_icons_min.css" rel="stylesheet">

</head>

<body>

 <div class="container margin_60">
			
	    <div class="box_general_3 cart">
                                <table width="100%" border="0" bgcolor="#F3F4EF">
  <tr>
    <td width="25%" rowspan="2" style="padding-top:0px; padding-right:0px; padding-bottom:0px; border-bottom-right-radius:0px;" ><img src="../img/quoteheaderimage.png" width="240" height="200"></td>
    <td width="45%" rowspan="2" style="padding-top:10px; padding-right:5px; padding-bottom:5px; border-bottom-right-radius:0px;" ><p  style="color:#153f56"><strong>PELEZA INTERNATIONAL LIMITED</strong><br/><strong>P.O.Box 20816-00202, NAIROBI, KENYA.</strong>
      <br/><strong>PHONE NUMBER: +254 796 111 020</strong>
      <br/><strong>EMAIL: info@peleza.com</strong>  </p></td>
    <td width="30%" style="padding-top:10px; padding-right:20px; padding-bottom:5px; border-bottom-right-radius:20px;" ><img align="right" src="../img/Peleza_Logo_We_Get_It.png" width="240" height="70" alt="PELEZALOGO"></td>
  </tr>
  <tr>
    <td style="padding-top:10px; padding-right:20px; padding-bottom:5px; border-bottom-right-radius:20px;" ><h1 align="right"><strong>PSMT INVOICE</strong></h1></td>
  </tr>
  </table>	
<br/>    
											
									   <table width="100%" border="0">
  <tr>
    <td width="24%" bgcolor="#0D4157" bordercolor="#0A4157" style="padding-top:8px; padding-left:10px; padding-bottom:5px; border: 2px solid;">      <h5 style="color:#9BAF27"><strong>Invoice To:</strong></h5></td>
   
  <td bgcolor="#fff" style="padding-top:8px; padding-left:5px; padding-bottom:5px;" >&nbsp; </td>
  <td width="24%" bgcolor="#0D4157" bordercolor="#0A4157" style="padding-top:8px; padding-left:10px; padding-bottom:5px; border: 2px solid;">      <h5 style="color:#9BAF27"><strong>PIN Number:</strong></h5></td>
  <td bgcolor="#fff" style="padding-top:8px; padding-left:5px; padding-bottom:5px;" >&nbsp; </td>
  <td width="24%" bgcolor="#0D4157" bordercolor="#0A4157" style="padding-top:8px; padding-left:10px; padding-bottom:5px; border: 2px solid;">      <h5 style="color:#9BAF27"><strong>Reference Number:</strong></h5></td>
  <td bgcolor="#fff" style="padding-top:8px; padding-left:5px; padding-bottom:5px;" >&nbsp; </td>
     <td width="24%" bgcolor="#0D4157" bordercolor="#0A4157" style="padding-top:8px; padding-left:10px; padding-bottom:5px; border: 2px solid;">    <h5 style="color:#9BAF27"><strong>Date Generated:</strong></h5></td>
 
  </tr>
   
        <tr>
     <td width="24%" bgcolor="#C2D648" bordercolor="#0A4157" style="padding-top:8px; padding-left:10px; padding-bottom:5px; border: 2px solid;"><h6><strong><?php echo $row_getrequestdetails['client_parent_company']; ?>,
    <br/><?php echo $row_getrequestdetails['address_postal'];?>-<?php echo $row_getrequestdetails['address_postal_code'];?>.
    <br/><?php echo $row_getrequestdetails['address_city'];?>.</strong></h6></td>
    <td bgcolor="#fff" style="padding-top:8px; padding-left:5px; padding-bottom:5px;" >&nbsp; </td>
    
       <td width="24%" bgcolor="#C2D648" bordercolor="#0A4157" style="padding-top:8px; padding-left:10px; padding-bottom:5px; border: 2px solid;"><h6><strong>P051618629Q</strong></h6></td>
       <td bgcolor="#fff" style="padding-top:8px; padding-left:5px; padding-bottom:5px;" >&nbsp; </td>
    
       <td width="24%" bgcolor="#C2D648" bordercolor="#0A4157" style="padding-top:8px; padding-left:10px; padding-bottom:5px; border: 2px solid;"><h6><strong> <?php echo $row_getrequestdetails['invoice_number']; ?></strong></h6></td>
         <td bgcolor="#fff" style="padding-top:8px; padding-left:5px; padding-bottom:5px;" >&nbsp; </td>
    <td width="24%" bgcolor="#C2D648" bordercolor="#0A4157" style="padding-top:8px; padding-left:10px; padding-bottom:5px; border: 2px solid;"><h6><strong> <?php echo $row_getrequestdetails['payment_date']; ?></strong></h6></td>
 </tr>
    <tr>
      <td colspan="7" bgcolor="#FFFFFF" style="padding-top:1px; padding-bottom:1px;" >&nbsp;</td>
      </tr>
      
  </table>
  
  
  
    <table width="100%" border="0">
      <tr>
      <td colspan="7" bgcolor="#FFFFFF" style="padding-top:1px; padding-bottom:1px;" >&nbsp;</td>
      </tr>
  <tr>
    <td width="" bgcolor="#e7edb6" bordercolor="#0A4157" style="padding-top:8px; padding-left:10px; padding-bottom:5px; border: 2px solid;">      <h5 style="color:#0D4157"><strong>Description & Requests:</strong></h5></td>
   
    <td width="" bgcolor="#e7edb6" bordercolor="#0A4157" style="padding-top:8px; padding-left:10px; padding-bottom:5px; border: 2px solid;">      <h5 style="color:#0D4157"><strong>Quote Reference:</strong></h5></td>

  <td width="25%" bgcolor="#e7edb6" bordercolor="#0A4157" style="padding-top:8px; padding-left:10px; padding-bottom:5px; border: 2px solid;">      <h5 style="color:#0D4157; text-align:center"><strong>Cost:</strong></h5></td>

     
 
  </tr>
   <?php
   do
{
	
  ?>
        <tr>
     <td width="" bgcolor="#BDCCD2" bordercolor="#0A4157" style="padding-top:3px; padding-left:10px; padding-bottom:3px; border-left: 2px solid;"><h6 style="color:#000"><?php echo $row_getprogress2['request_plan']; ?>:-<?php echo $row_getprogress2['bg_dataset_name']; ?></h6></td>
     
     <td width="" bgcolor="#BDCCD2" bordercolor="#0A4157" style="padding-top:3px; padding-left:10px; padding-bottom:3px; border-left: 2px solid;"><h6 style="color:#000"><?php echo $row_getprogress2['request_quotation_ref']; ?></h6></td>
 
    
       <td width="25%" bgcolor="#BDCCD2" bordercolor="#0A4157" style="padding-top:3px; padding-right:15px; padding-bottom:3px; border-left: 2px solid;border-right: 2px solid;"><h6  style="color:#000" align="right"><?php echo round($row_getprogress2['package_cost'], 2); ?></h6></td>
       
       
 </tr>
 
 <?php
	
	   } while ($row_getprogress2 = mysqli_fetch_assoc($getprogress2)); 

  
 ?>
    <tr>
     <td width="" bgcolor="#BDCCD2" bordercolor="#0A4157" style="padding-top:3px; padding-left:10px; padding-bottom:3px; border-left: 2px solid;">&nbsp;</td>
 
      <td width="" bgcolor="#BDCCD2" bordercolor="#0A4157" style="padding-top:3px; padding-right:15px; padding-bottom:3px; border-left: 2px solid;border-right: 2px solid;">&nbsp;</td>
       <td width="25%" bgcolor="#BDCCD2" bordercolor="#0A4157" style="padding-top:3px; padding-right:15px; padding-bottom:3px; border-left: 2px solid;border-right: 2px solid;">&nbsp;</td>
      </tr>
      
     <!--  <tr>
     <td width="67%" bgcolor="#0D4157" bordercolor="#0A4157" style="padding-top:5px; padding-right:10px; padding-bottom:5px; border-left: 2px solid;border-top: 2px solid;"><h5 style="color:#9BAF27" align="right">Sales Tax (16%)</h5></td>
 
    
       <td width="33%" bgcolor="#0D4157" bordercolor="#0A4157" style="padding-top:5px; padding-right:15px; padding-bottom:5px; border-left: 2px solid;border-right: 2px solid;border-top: 2px solid;"><h5  style="color:#9BAF27" align="right"><?php echo $row_getrequestdetails['amount']* 0.16; ?></h5></td>
 
       
 </tr>-->
 
 <tr>
  <td width="" bgcolor="#0D4157" bordercolor="#0A4157" style="padding-top:4px; padding-right:10px; padding-bottom:4px; border-left: 2px solid;border-top: 2px solid;"><h6 style="color:#fff"><strong>Pelzaa International Limited,
    <br/>Account Number:-1004013243.
    <br/>Bank:- NIC.</strong>
     <br/>Branch:- The Mall.</strong></h6></td>
     <td width="" bgcolor="#0D4157" bordercolor="#0A4157" style="padding-top:4px; padding-right:10px; padding-bottom:4px; border-left: 2px solid;border-top: 2px solid;"><h5 style="color:#9BAF27" align="right">Total Amount:</h5></td>
 
    
       <td width="25%" bgcolor="#0D4157" bordercolor="#0A4157" style="padding-top:4px; padding-right:15px; padding-bottom:4px; border-left: 2px solid;border-right: 2px solid;border-top: 2px solid;"><h5  style="color:#9BAF27" align="right"><?php echo $row_getrequestdetails['amount']; ?></h5></td>
 
       
 </tr>
    <tr>
      <td colspan="7" bgcolor="#FFFFFF" style="padding-top:1px; padding-bottom:1px;" >&nbsp;</td>
      </tr>
      
  </table>
    <table width="100%" border="0">
      <tr>
      <td bgcolor="#FFFFFF"><img align="bottom" src="../img/nabsmemberlogo.png" width="300" height="140"></td>
           <td bgcolor="#FFFFFF"><img align="right" src="../img/quotefooterimage.png" width="200" height="230"></td>
      </tr>
      </table>
							 <hr>

</div>
        </div>
		<!-- /container -->





</body>
</html>
						
<?php
/*$html = ob_get_clean();
$dompdf = new DOMPDF();
$dompdf->setPaper('A4', 'landscape');
$dompdf->load_html($html);
$dompdf->render();

$name = $search_ref."-".$row_getrequestdetails['bg_dataset_name'];
//For view
//$dompdf->stream("",array("Attachment" => false));
 //for download
$dompdf->stream($name);*/
?>