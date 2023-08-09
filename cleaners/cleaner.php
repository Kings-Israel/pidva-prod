<?php require_once('../Connections/connect.php'); ?>
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


mysqli_select_db($connect,$database_connect);
$query_getfaulty = "SELECT faculty_id, faculty_name FROM pel_edu_faculty ORDER BY faculty_id ASC";
$getfaulty = mysqli_query_ported($query_getfaulty, $connect) or die(mysqli_error($connect));
$row_getfaulty = mysqli_fetch_assoc($getfaulty);
$totalRows_getfaulty = mysqli_num_rows($getfaulty);

do {

echo $row_getfaulty['faculty_name']."<br/>";

$facultyone = $row_getfaulty['faculty_name'];
echo  $facultytwo= trim($facultyone);

//echo  $facultytwo= rtrim($facultyone, "<br/>");

echo "<br/>";

 $updateSQL = sprintf("UPDATE pel_edu_faculty SET faculty_name=%s WHERE faculty_name=%s and faculty_id=%s",
                       GetSQLValueString($facultytwo, "text"),
                       GetSQLValueString($row_getfaulty['faculty_name'], "text"),
					   GetSQLValueString($row_getfaulty['faculty_id'], "text"));
			/*		   
					    $updateSQL = sprintf("UPDATE pel_edu_courses SET faculty_id=%s WHERE faculty_name=%s",
                       GetSQLValueString($row_getfaulty['faculty_id'], "text"),
                       GetSQLValueString($row_getfaulty['faculty_name'], "text"));*/
					   
			/*		   $updateSQL = sprintf("UPDATE pel_course_specialization SET faculty_id=%s WHERE faculty_name=%s",
                       GetSQLValueString($row_getfaulty['faculty_id'], "text"),
                       GetSQLValueString($row_getfaulty['faculty_name'], "text"));*/
		

  mysqli_select_db($connect,$database_connect);
  $Result1 = mysqli_query_ported($updateSQL, $connect) or die(mysqli_error($connect));
  
 } while ($row_getfaulty = mysqli_fetch_assoc($getfaulty));


mysqli_free_result($getfaulty);
?>
