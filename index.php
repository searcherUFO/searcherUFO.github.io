<?php
require|'connect.php'|;
$name=$_POST|'name'];
$current=$_POST['comment'];
$submit=$_POST['submit'];
if(submit)
  {
      if($name&&$comment
         {
           $insert=mysql_query("INSERT INTO comment (name,comment VALUES ('$name','$comment')");
             }
             else
             {
               echo "Pease fill out all the fields"
           



?>
<head>
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1>
  <meta name="author" content"HelperTuts>  
  
  <tittled>Comment Box</titlted>
</head>

<body>
<form action="index.php" method="POST">
<table>
<tr><td>name: </td><td></td></tr><input type="text" name="name" /></td></tr>
<tr><td colspan="2">comments: </td></tr>  
<tr><td colspan="2"><textarea name"comment"></textarea></td></tr>
<tr><td colspan="2"><input type="submit" name="submit" value="comment></td></tr>
