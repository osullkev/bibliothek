<?php
#Upload and database entry script 

#Make errors visible
error_reporting(E_ALL);
ini_set('display_errors', 1);

#Connection to the database
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);

if($conn->connect_error) die("Connection failed:" . $conn->connect_error);

#Only if the post contains data [In this case only one field must not be empty] TO-DO: All Fields must contain reasonable data
if($_POST){
$isbn = $_POST['Isbn'];
$title = $_POST['Title'];
$author= $_POST['Author'];                                                                          
$genre = $_POST['Genre'];
$tag = $_POST['Tag'];
$version = $_POST['Version'];
$user = $_POST['User'];

$target_dir = "./files/"; #Currently the targetdir is inside the Webroot. TO-DO: Change target dir to external folder on harddrive
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$target_file_database = $target_dir . $title . "_" . $version . ".pdf"; #In order to save database space, the location of the file is build up from the name and the version
$target_file_database = str_replace(" ", "_", $target_file_database);

$uploadOk = 1;

if (is_dir($target_dir) && is_writable($target_dir)) {

	$uploadOk = 1;

} else {
	
	echo 'Upload directory is not writable, or does not exist.';
	$uploadOk = 0;
}
#Some cases where a file upload is not applicable
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

if (file_exists($target_file_database)) {
	echo "Sorry, file already exists.";
	$uploadOk = 0;
}
if($imageFileType != "pdf" ) {
	echo "Sorry, only PDF files are allowed.";
	$uploadOk = 0;
}
if ($uploadOk == 0) {
	echo "Sorry, your file was not uploaded.";

} else {
	$moved =  move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file_database );
	if ($moved){
		#Input all the fields into the different tables of the database
    		echo "The file has been uploaded.";

		#Check if the user who uploads is existing in the database. TO-DO: There must be a smarter way later, if we use logins then we can create this entry when the person signs up and we don't need 
		#to check every time.
		$query_exists = "SELECT EXISTS (SELECT id FROM user WHERE user=" . "'$user'" .");";
		$result_exists = $conn->query($query_exists);
		if(!$result_exists->fetch_array()[0]){
			$query_user = "INSERT INTO user(user) VALUES" . "('$user')" .";";
			$result_user = $conn->query($query_user);
		}
		$result_id = $conn->query("SELECT id FROM user WHERE user=" . "'$user'" .";");
		$uid = $result_id->fetch_assoc()['id'];
		$query_cont = "INSERT INTO content(isbn, author, title, version, uid) VALUES" . "('$isbn','$author', '$title', '$version', '$uid') ";                                       
		$result = $conn->query($query_cont);
		if(!$result) die ($conn->error);
		$query_genre = "INSERT INTO genre(isbn, genre, tag) VALUES" . "('$isbn','$genre','$tag')"; 
		$result_genre = $conn->query($query_genre);
		if(!$result_genre) die ($conn->error);
		$query_date =  "INSERT INTO date(isbn) VALUES" . "('$isbn')"; 
		$result_date = $conn->query($query_date);
		# TO-DO: Overall some more sageguards regarding the connection and possible errors


	}
	else{
 		echo "Sorry, there was an error uploading your file.";
	}
 
	}
}

?>
