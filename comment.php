<?php

$yourname = $_POST['yourname'];
$comment = $_POST['comment'];

// format the comment data into how you want it to be displayed on the page
$data = $yourname . "<br>" . $comment . "<br><br>";

//Open a text file for writing and save it in a variable of your chosen.    
//Remember to use "a" not "w" to indicate write. Using 'w' will overwrite 
// any existing item in the file whenever a new item is written to it.

$myfile = fopen("comment.txt", "a"); 

//write the formatted data into the opened file and close it
fwrite($myfile, $data); 
fclose($myfile);

// Reopen the file for reading, echo the content and close the file
$myfile = fopen("comment.txt", "r");
echo fread($myfile,filesize("comment.txt")); 

?>
