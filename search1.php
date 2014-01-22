<?php 
include("config.php");
$search = $_POST['search'];
if($search != NULL) {
$check = mysql_query("SELECT * FROM cache WHERE keyword = '$search'");
$key = mysql_fetch_array($check);
	if($key != 0){
		echo "<p>Search result for: " . "<b>" . $search . "</b><br>";
		$display = mysql_query("SELECT DISTINCT tags, scores FROM scores WHERE keyword = '$search' ORDER BY scores DESC");
				while($num = mysql_fetch_array($display)) {
					$all = mysql_num_rows($display);
					$sc = $num['scores'];
					$ave = ($sc / $all) * 100;
					$round = round($ave, 2);
					$log =($all / $sc);
					echo "<b>Tags: </b>" . $num['tags'] . "&nbsp&nbsp&nbsp&nbsp" . log($log) . "%" . "<br>";
					
					//echo (log($all / $sc));
				}
		}

	else{
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
				echo "<H1>Search result for: " . "<b>" . $search . "</b></h1><br>";
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
				$display = mysql_query("SELECT DISTINCT tags, scores FROM scores WHERE keyword = '$search' ORDER BY scores ASC");
				while($num = mysql_fetch_array($display)) {
					$all = mysql_num_rows($display);
					$sc = $num['scores'];
					$ave = ($sc / $all) * 100;
					$round = round($ave, 2);
					
			
					echo "<b>Tags: </b>" . $num['tags'] . "&nbsp&nbsp&nbsp&nbsp" . $round . "%" . "<br>";
				}
		
	}

}
?>