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
$query_getfaulty = "SELECT course_id, course_name, faculty_id, faculty_name, specialization_id, specialization_name FROM pel_course_specialization ORDER BY course_id ASC";
$getfaulty = mysqli_query_ported($query_getfaulty, $connect) or die(mysqli_error($connect));
$row_getfaulty = mysqli_fetch_assoc($getfaulty);
$totalRows_getfaulty = mysqli_num_rows($getfaulty);

do {

echo $row_getfaulty['faculty_name']."<br/>";

$facultyone = $row_getfaulty['faculty_name'];
echo  $facultytwo= trim($facultyone);

echo "<br/>";

echo $row_getfaulty['course_name']."<br/>";

$courseone = $row_getfaulty['course_name'];
echo  $coursetwo= trim($courseone);

echo "<br/>";

echo $row_getfaulty['specialization_name']."<br/>";

$specialone = $row_getfaulty['specialization_name'];
echo  $specialtwo= trim($specialone);

echo "<br/>";

 $updateSQL = sprintf("UPDATE pel_course_specialization SET faculty_name=%s, course_name=%s, specialization_name=%s WHERE faculty_id=%s and course_id=%s and specialization_id=%s",
                       GetSQLValueString($facultytwo, "text"),
					   GetSQLValueString($coursetwo, "text"),
					   GetSQLValueString($specialtwo, "text"),
                       GetSQLValueString($row_getfaulty['faculty_id'], "text"),
					   GetSQLValueString($row_getfaulty['course_id'], "text"),
					   GetSQLValueString($row_getfaulty['specialization_id'], "text"));
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
