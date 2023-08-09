<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_connect = "localhost";
$database_connect = "peleza_db";
$username_connect = "root";
$password_connect = "mysql";
$connect = mysqli_pconnect($hostname_connect, $username_connect, $password_connect) or trigger_error(mysqli_error($connect),E_USER_ERROR);
?>