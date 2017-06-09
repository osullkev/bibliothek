<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Database Entry</title>
  </head>
  <body>
  <h1> Please fill out the formulars</h1>
<?php



echo <<<_END
<form action="upload.php" method="post" enctype="multipart/form-data">
Select file to upload:
<input type="file" name="fileToUpload" id="fileToUpload">
<p> ISBN  <br> <input type="text" name="Isbn" /> </p>
<p> Title <br> <input type="text" name="Title" /> </p>
<p> Author <br> <input type="text" name="Author" /> </p>
<p> Genre <br> <input type="text" name="Genre" /> </p>
<p> Tag <br> <input type="text" name="Tag" /> </p>
<p> Version <br> <input type="text" name="Version" /> </p>
<p> User <br> <input type="text" name="User" /> </p>
<input type="submit" value="Upload file" name="submit">
</form>
_END;


?>

  </body>
</html>
