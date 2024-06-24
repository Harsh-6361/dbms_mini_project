<?php   
session_start();  
if(!isset($_SESSION["sess_ename"])){  
    header("location:emplogin.php");  
} else {
?>
<!doctype html>	
<html>  
<head>  
<title>Welcome Employee</title>  
<style>
    body{  
    background-image: url("enter.jpg");      
       margin-top: 100px;  
    margin-bottom: 100px;  
    margin-right: 150px;  
    margin-left: 80px;  
    background-size: 100%;
    background-attachment: fixed; 
    color: #261A15;
    font-family: 'Yantramanav', sans-serif;;  
    font-size: 100%;  
     
        }  
            h1 {  
   color: black;}
        h3 {  
    color: rgb(44,62,80);  
    font-family: verdana;  
    font-size: 100%;  
}
         a {
    color: rgb(102, 51, 153);
}
fieldset {
  background-color: black;
  color: white;
  opacity: 0.8;
}
fieldset {
  background-color: black;
  color: white;
  opacity: 0.8;
}
</style>
<link rel="stylesheet" type="text/css" href="emp.css">
</head>  
<body>  
<center><h1><u> AIRLINE RESERVATION SYSTEM </u></h1></center>

<br /><div class="right"><button class="button">
	<a href="viewflights.php"  style="color:black"> Go Back</a></button>
<div><div><div><div><div><br><br>

<?php
$sql = "DELETE from Aircraft where Flight_ID= ('".$_GET["Flight_ID"]."')";
echo "Plane with Flight ID ";
echo $_GET["Flight_ID"];

$con=mysqli_connect("localhost","root","","airline",3307);
if ($con->query($sql) === TRUE) {
    echo " deleted successfully..!";
} else {
    echo "Error:" . $con->error;
}

$con->close();
}
?>