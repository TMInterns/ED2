<?php 
include "config.php";

$query = mysql_query("SELECT * FROM tblurl");
$query2 = mysql_query("SELECT * FROM tblfield");
$query3 = mysql_query("SELECT * FROM tbltags");

$row =  mysql_num_rows($query);
$row2 = mysql_num_rows($query2);
$row3 = mysql_num_rows($query3);
echo "<b>URL: </b>" . $row . "<br>";
echo "<b>Fields: </b>" . $row2 . "<br>";
echo "<b>Tags: </b>" . $row3;

?>
