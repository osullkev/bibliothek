<!-- Simple Script that iterates over all entries in the database and just prints the result and a hyperlink to the pdf file
TO-DO: Content into a dynamic table which can be sorted -->
<!DOCTYPE html>
<html> 
<head> 
<meta charset="utf-8"> 
<title>Database Entry</title> 
</head> 
<body> <h1>Current entries in the database</h1> 

<?php
require_once 'login.php'; 
$conn = new mysqli($hn, $un, $pw, $db); 
if($conn->connect_error) die($conn->connect_error); 

$query_content  = "SELECT *FROM content"; 
$query_user  = "SELECT * FROM user"; 
$query_genre  = "SELECT * FROM genre"; 
$result_content = $conn->query($query_content); 
$result_user = $conn->query($query_user); 
$result_genre = $conn->query($query_genre);

$rows_content = $result_content->num_rows; 
for ($j = 0 ; $j < $rows_content ; ++$j) { 

	$result_content->data_seek($j); # Right now both tables are queried without checking if the key is matching. TO-DO: Use the isbn of content table to query the genre table
	$result_genre->data_seek($j); 
	$row_content = $result_content->fetch_array(MYSQLI_ASSOC);
	$row_genre = $result_genre->fetch_array(MYSQLI_ASSOC);

	$file = str_replace(" ","_",$row_content['title']);
	$file = $file . "_" . $row_content['version'] . ".pdf";
   	echo 'Author: '   . $row_content['author']   . '<br>';
	echo 'Title: '    . $row_content['title']    . '<br>';
	echo 'Genre: ' . $row_genre['genre'] . '<br>';
	echo 'Tag: '     . $row_genre['tag']     . '<br>';
	echo '<a href="'. "./files/". $file .'" target="_blank"> File </a>'     . '<br><br>';
	# TO-DO: List the date too
	
	}

$result_content->close();
$result_user->close();
$result_genre->close();
$conn->close();

?>

  </body>
</html>
