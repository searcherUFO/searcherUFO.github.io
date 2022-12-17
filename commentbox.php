<?php
 
 if($_POST['Submit']){
  print "<h1>Your comment has been submitted!</h1>";

  $Name = $_POST['Name'];
  $Comment = $_POST['Comment'];

  #Get old comments
  $old = fopen("Demo.html", "r+t");
  $old_comments = fread($old, 1024);

  #Delete everything, write down new and old comments
  $write = fopen("Demo.html", "w+");
  $string = "<b>".$Name."</b><br>".$Comment."<br>\n".$old_comments;
  fwrite($write, $string);
  fclose($write);
  fclose($old);
 }

 #Read comments
 $read = fopen("Demo.html", "r+t");
 echo "<br><br>Comments<hr>".fread($read, 1024);
 fclose($read);

?>
