<?php 
include("config.php");
ini_set('max_execution_time', 360000);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Search Document</title>
</head>
<form action = "" method = "POST">
<table width="1000" border="0" align="left">
  <tr>
  	<td width = "110"><h4>Enter Keyword: </h4> </td>
    <td align = "left" width = "190"><input name="search" type="text" /><input name="Search" type="submit" value="Search" /></td>
	<td width = "500" align = "right"><img src = "haha.jpg">  </td>
  </tr>
</table>
<table width="1200" border="0" align="left">
  <tr>
    <td><hr /></td>
  </tr>
</table>

<table width="500" border="0">
<tr>
    <td align = "Left" width = "2000"> <font size="5"><?php 
	if(isset($_POST) != NULL and $_SERVER["REQUEST_METHOD"] == "POST"){
			echo "Search Results for: " . $_POST["search"];
				}
	?> </font></td>
  </tr>

  <tr>
    <td align = "Left" width = "250"><b>Tags</b></td>
    <td align = "Left" width = "250"><b>Scores</b></td>
  </tr>
    <?php 
		if(isset($_POST) and $_SERVER["REQUEST_METHOD"] == "POST"){
				$val = $_POST["search"];
					$query = mysql_query("SELECT * FROM cache WHERE keyword ='$val'");
					$count = mysql_fetch_array($query);
					
					if($count != 0){ //check if keyword is present in cache
						$display = mysql_query("SELECT DISTINCT tags, scores FROM scores WHERE keyword = '$val' ORDER BY scores DESC");
						while($dis = mysql_fetch_array($display)){
							$all = mysql_num_rows($display);
							$sc = $dis['scores'];
							$ave = ($sc / $all) * 100;
							$round = round($ave, 2);
							
							echo "<tr>";
							echo "<td align = 'Left'>";
							echo $dis['tags'];
							echo "</td>";
							
							echo "<td>";
							echo $round . "%";
							echo "</td>";
							echo "</tr>";
						}
						
					}
				else { //if keyword is not present in cache
				$search = $_POST["search"];
					$query = mysql_query("SELECT * FROM tbltags WHERE tags = '$search'");
		$insert = mysql_query("INSERT INTO cache (keyword) VALUES ('$search')");
				while($row = mysql_fetch_array($query)){
					$tags = $row['tags'];
					$id = $row['field_id'];
						$query2 = mysql_query("SELECT * FROM tbltags WHERE field_id = '$id'");
							while($row2 = mysql_fetch_array($query2)) {
								$tags1 = $row2['tags'];
									if($tags1 != $tags) {
										//echo $row2['tags'] . "<br>";
										$query3 = mysql_query("SELECT * FROM `cache` WHERE `keyword` = '$search' ORDER BY id DESC");
										$row3 = mysql_fetch_array($query3);
										$id_cache = $row3['id'];
										//echo $tags1 . "<br>";
										$insert1 = mysql_query("INSERT INTO tbldocs (id_cache, tags) VALUES ('$id_cache', '$tags1')");
										}
							}
				}
				$docs = mysql_query("SELECT * FROM tbldocs ORDER BY tags");
				while($rows = mysql_fetch_array($docs)) {
					$pangalan = $rows['tags'];
					//echo $pangalan . "&nbsp";
					$resource = mysql_query("SELECT * FROM tbldocs WHERE tags = '$pangalan' ORDER BY tags");
					$score = mysql_num_rows($resource);
					$add = mysql_query("INSERT INTO scores (keyword, tags, scores) VALUES ('$search', '$pangalan', '$score')");	
				}
					$counter = mysql_query("SELECT * FROM cache WHERE keyword = '$search'");
						$key_id = mysql_fetch_array($counter);
						$keys = $key_id["id"];
						$display = mysql_query("SELECT DISTINCT tags, scores FROM scores WHERE keyword = '$search' ORDER BY scores DESC");
				while($num = mysql_fetch_array($display)) {
					$all = mysql_num_rows($display);
					$sc = $num['scores'];
					$ave = ($sc / $all) * 100;
					$round = round($ave, 2);
					echo "<tr>";
							echo "<td align = 'Left'>";
							echo $num['tags'];
							echo "</td>";
							
							echo "<td>";
							echo $round . "%";
							echo "</td>";
							echo "</tr>";
				}
					
				}
		}
	?>
</table>
</form>

<body>
</body>
</html>