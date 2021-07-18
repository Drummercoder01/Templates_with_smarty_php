<?php
error_reporting(15);

if ($_SERVER['SERVER_NAME'] != "localhost")
{
  $_hostname = "";
  $_username = "";
  $_password = "";
  $_database = "";
}
else
{
  $_hostname = "localhost";
  $_username = "root";
  $_password = "root";
  $_database = "db_school";
}
  $_PDO = new PDO("mysql:host=$_hostname; dbname=$_database","$_username", "$_password");
  
  $_PDO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


?>