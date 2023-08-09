<?php

 //Get the name of the Method
 //The method name has to be passed as Method via post

 $Request_Method=$_REQUEST['method'] or die('Method name not found');



 //Method to inputdata
 if($Request_Method=="inputdata")
 {
//require_once('Connections/conn.php');


$linkdata = mysqli_connect("localhost", "root", "")
or die('Could not connect: ' . mysqli_error($connect));

mysqli_select_db("peleza_db") or die('Could not select database');

  
            $ADMISSION_DATE = $_REQUEST['ADMISSION_DATE'];
            $IDENTITY_NUMBER = $_REQUEST['IDENTITY_NUMBER'];
			$REGISTRATION_NUMBER = $_REQUEST['REGISTRATION_NUMBER'];
            $NAME = $_REQUEST['NAME'];
		    $GENDER = $_REQUEST['GENDER'];
			$CITIZENSHIP = $_REQUEST['CITIZENSHIP'];
            $student_token = $_REQUEST['student_token'];
			$date_insert = $_REQUEST['date_insert'];
			$uploadedby = $_REQUEST['uploadedby'];
			$USR_ID = $_REQUEST['USR_ID'];
			$graduation_date = $_REQUEST['graduation_date'];
			$course_name = $_REQUEST['course_name'];
			$award = $_REQUEST['award'];
			$student_specialization = $_REQUEST['student_specialization'];
			$course_level = $_REQUEST['course_level'];
			$institution_name = $_REQUEST['institution_name'];
			
				$ADMISSION_DATE2 = mysqli_real_escape_string($connect,$ADMISSION_DATE);
				$IDENTITY_NUMBER2 = mysqli_real_escape_string($connect,$IDENTITY_NUMBER);
				$REGISTRATION_NUMBER2 = mysqli_real_escape_string($connect,$REGISTRATION_NUMBER);				
				$NAME2 = mysqli_real_escape_string($connect,$NAME);
				$GENDER2 = mysqli_real_escape_string($connect,$GENDER);
				$CITIZENSHIP2 = mysqli_real_escape_string($connect,$CITIZENSHIP);		
				
				$IDENTITY_NUMBER3 = strtoupper($IDENTITY_NUMBER2);
				$ADMISSION_DATE3 = strtoupper($ADMISSION_DATE2);
				$REGISTRATION_NUMBER3 = strtoupper($REGISTRATION_NUMBER2);
				$NAME3 = strtoupper($NAME2);
				$GENDER3 = strtoupper($GENDER2);
				$CITIZENSHIP3 = strtoupper($CITIZENSHIP2);

				
 
 
 $sql_insert="INSERT INTO pel_edu_data (student_first_name,student_reg_number,student_national_id,student_date_uploaded,student_token,added_by,user_id,graduation_date, course_name, award, student_specialization, faculty_name, course_level, institution_name, admission_date, blockchain, shafile, file_token) VALUES ('$NAME3','$REGISTRATION_NUMBER3','$IDENTITY_NUMBER3','$date_insert','$student_token','$uploadedby','$USR_ID','$graduation_date','$course_name','$award','$student_specialization','$faculty_name','$course_level', '$institution_name', '$ADMISSION_DATE3', '$BLOCKCHAIN','$sha1content','$file_token')";
				//$result_insert = mysqli_query_ported($sql_insert, $conn) or die(mysqli_error($connect));
				
					 $result = mysqli_query_ported($sql_insert, $linkdata) or die('Query failed: ' . mysqli_error($connect));

// //Generate the sql query based on username and password
// $query="select usr_name from users where usr_username='$username' and usr_password='$password'";
//
// //Execute the query
// $result = mysqli_query_ported($query);

 ////Get the rowcount
// $rowcount= mysqli_num_rows($result);
//
// //if the count is 0 then no matching rows are found
// if($rowcount==0)
// {
//  echo json_encode(array('result'=>0));
// }
// //Else there is an employee with the given credentials
// else {
//  $row = mysqli_fetch_assoc($result);
//  //Get and return his employee id
//  echo json_encode(array('result'=>$row['usr_name']));
// }
 }



?>