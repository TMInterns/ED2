<?php
include "config.php";
ini_set('max_execution_time', 360000);
error_reporting(E_ERROR);
$size = $_FILES['files']['size'];
$valid_formats = array("json");
$max_file_size = 1024*100; //100 kb
$count = 0;
$count2 = 0;
$filename = '/trial/tester.json';
if(file_exists($filename)){
	unlink("tester.json");
	}
else {
	
		if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
		// Loop $_FILES to execute all files
		foreach ($_FILES['files']['name'] as $f => $name) {     
			if ($_FILES['files']['error'][$f] == 4) {
				continue; // Skip file if any error found
			}	       
			if ($_FILES['files']['error'][$f] == 0) {	           
				if ($size [$f] > $max_file_size) {
					$message[] = "$name is too large!.";
					continue; // Skip large files
				}
				elseif( ! in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats) ){
					$message[] = "$name is not a valid format";
					continue; // Skip invalid file formats
				}
				else{ // No error found! Move uploaded files 
					if($_FILES['files']['tmp_name'][$f]) {	
					$jsonFile = $_FILES["files"]["tmp_name"][$f];
					$handle = fopen($jsonFile, 'r+');
					if($handle){
					$i = 0;
					while (($buffer = fgets($handle, 4096)) !== false) {
					if($i<=0) {
					//input URL to database
					//echo $buffer;
					$query = mysql_query("INSERT INTO tblurl (url) VALUES ('$buffer')");
					$i++;
					while(($buffer = fgets($handle, 4096)) !== false) {
						if($i>0) {
						//echo $buffer;
						$test = 'tester.json';
						file_put_contents($test,$buffer, FILE_APPEND | LOCK_EX);
						$jsonData = file("tester.json");
						$jsonArray = implode("",$jsonData);
						$phpArray = json_decode($jsonArray, true);
							foreach($phpArray as $key => $val) { //populate assoc. array
							$result = mysql_query("SELECT id FROM tblurl ORDER BY id DESC"); 
							$row2 = mysql_fetch_array($result);
							$ids = $row2['id'];
							$label = mysql_query("INSERT INTO tblfield (url_id, fields) VALUES ('$ids', '$key')");
							$tblfield = mysql_query("SELECT * FROM tblfield WHERE url_id = '$ids'");
								while($row3 = mysql_fetch_array($tblfield)){
								$value = $row3['id'];
								$result = mysql_query("SELECT id FROM tblurl ORDER BY id DESC"); 
								$row = mysql_fetch_array($result); 
								$id = $row['id']; 
								//echo $id;
								$tblurlfields = mysql_query("INSERT INTO tblurlfields (tblfield_id, tblurl_id) VALUES ('$value', '$id')");
								}
								//dapat malagay sa tblurlfields ung mga ID na bagong pasok
								//$select = mysql_query("SELECT * FROM 
								//echo "<br><b>".$key."</b>"; //fields (insert to tblfields
									foreach($val as $va) {
									$tags = mysql_query("SELECT * FROM tblfield WHERE fields = '$key' and url_id = '$id'");
										while($display = mysql_fetch_array($tags)){			
										$value1 = $display['id'];
										//echo "<br>" . $display['fields'];
										//echo "<br><i>" . $va . "</i>";
										//echo $value1;
										$tags = mysql_query("INSERT INTO tbltags (field_id, tags) VALUES ('$value1','$va')");
										$tbltags = mysql_query();
										}
										$transfer = mysql_query("SELECT * FROM tbltags");
											while($proceed = mysql_fetch_array($transfer)){
											$insert1 = $proceed['id'];
											$insert2 = $proceed['field_id'];
											$fieldtags = mysql_query("INSERT INTO tblfieldtag (tblfield_id, tbltags_id) VALUES 	('$insert2', '$insert1')");							
											unlink("tester.json");
											}
											//echo "<br>".$va."<i>"; //tags isert to tbltags
											//dapat malagay sa tblfieldtag
											}//foreach
										}//foreach
								}//if($i>0)
						}//while<br />

			}//if
		}//while
				$count2++;
						}
						//$count++; // Number of successfully uploaded files
					}
				}
			}
		}
	}
}
	?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>Multiple File Upload with PHP - Demo</title>
<style type="text/css">
a{ text-decoration: none; color: #333}
h1{ font-size: 1.9em; margin: 10px 0}
p{ margin: 8px 0}
*{
	margin: 0;
	padding: 0;
	box-sizing: border-box;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	-webkit-font-smoothing: antialiased;
	-moz-font-smoothing: antialiased;
	-o-font-smoothing: antialiased;
	font-smoothing: antialiased;
	text-rendering: optimizeLegibility;
}
body{
	font: 12px Arial,Tahoma,Helvetica,FreeSans,sans-serif;
	text-transform: inherit;
	color: #333;
	background: #e7edee;
	width: 100%;
	line-height: 18px;
}
.wrap{
	width: 500px;
	margin: 15px auto;
	padding: 20px 25px;
	background: white;
	border: 2px solid #DBDBDB;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	overflow: hidden;
	text-align: center;
}
.status{
	/*display: none;*/
	padding: 8px 35px 8px 14px;
	margin: 20px 0;
	text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
	color: #468847;
	background-color: #dff0d8;
	border-color: #d6e9c6;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
}
input[type="submit"] {
	cursor:pointer;
	width:100%;
	border:none;
	background:#991D57;
	background-image:linear-gradient(bottom, #8C1C50 0%, #991D57 52%);
	background-image:-moz-linear-gradient(bottom, #8C1C50 0%, #991D57 52%);
	background-image:-webkit-linear-gradient(bottom, #8C1C50 0%, #991D57 52%);
	color:#FFF;
	font-weight: bold;
	margin: 20px 0;
	padding: 10px;
	border-radius:5px;
}
input[type="submit"]:hover {
	background-image:linear-gradient(bottom, #9C215A 0%, #A82767 52%);
	background-image:-moz-linear-gradient(bottom, #9C215A 0%, #A82767 52%);
	background-image:-webkit-linear-gradient(bottom, #9C215A 0%, #A82767 52%);
	-webkit-transition:background 0.3s ease-in-out;
	-moz-transition:background 0.3s ease-in-out;
	transition:background-color 0.3s ease-in-out;
}
input[type="submit"]:active {
	box-shadow:inset 0 1px 3px rgba(0,0,0,0.5);
}
</style>

</head>
<body>
	<div class="wrap">
		<h1>Json File Uploader</h1>
		<?php	
		# error messages
		if (isset($message)) {
			foreach ($message as $msg) {
				printf("<p class='status'>%s</p></ br>\n", $msg);
			}
		}
		if($count2 != 0){
		//echo $_FILES["files"]["tmp_name"][$f];
			printf("<p class='status'>%d JSON Uploaded Successfully!</p>\n", $count2);
		}
		# success message
		if($count !=0){
			printf("<p class='status'>%d files added successfully!</p>\n", $count);
		}
		?>
		<p>Max file size 10kb. Valid formats .json </p>
		<br />
		<br />
		<!-- Multiple file upload html form-->
		<form action="" method="post" enctype="multipart/form-data">
			<input type="file" name="files[]" multiple="multiple">
			<input type="submit" value="Upload">
		</form>
</div>
</body>
</html>