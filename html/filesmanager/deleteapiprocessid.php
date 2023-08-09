<?php
require_once('../../Connections/connect.php');
 //Get the name of the Method
 //The method name has to be passed as Method via post

 $Request_Method=$_REQUEST['method'] or die('Method name not found');

 
 //Get all th employees that are managed the by the given emplyee
 if($Request_Method=="inputfile")
 {
   
/* $conn = mysqli_pconnect("localhost", "root", "mysql")
or die('Could not connect: ' . mysqli_error($connect));

mysqli_select_db("peleza_db") or die('Could not select database');*/
 
 mysqli_select_db($connect,$database_connect);
  $file_token=$_REQUEST['file_token'];
  $filenameuploaded=$_REQUEST['filenameuploaded'];
  $file_source=$_REQUEST['file_source'];
  $file_blockchain=$_REQUEST['file_blockchain'];
  $uploadedby=$_REQUEST['uploadedby'];
  $module_id=$_REQUEST['module_id'];
  $module_name=$_REQUEST['module_name'];
  $added_date=$_REQUEST['added_date'];
  $sha1name=$_REQUEST['sha1name'];
  $sha1content=$_REQUEST['sha1content'];


$query="delete FROM pel_individual_id WHERE shafile ='$sha1content' and token_number ='$file_token'";
$result = mysqli_query_ported($query, $connect) or die(mysqli_error($connect));
//$row = mysqli_fetch_assoc($result);


$query2 = "delete from pel_data_files WHERE file_blockchain ='$file_blockchain'";
 $result2 = mysqli_query_ported($query2, $connect) or die(mysqli_error($connect));

 
 $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "../searches/idrequests1.php";
  $MM_redirectLoginFailed = "idupload.php";
  $MM_redirecttoReferrer = false;

 mysqli_close($Connection);
// mysqli_free_result($gettotalcounts);
  if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
}	
header("Location: " . $MM_redirectLoginSuccess );
}
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
 //Close Connection



?>
