<div class="Hello">Click Me</div>    

<?php 
$find_counts = mysqli_query($conn, "SELECT * FROM ad_section");
 while($row = mysqli_fetch_assoc($find_counts)){
  $current_counts = $row['no_of_clicks'];
  $new_count = $current_counts + 1;
  $update_count = mysqli_query($conn, "UPDATE `ad_section` SET `no_of_clicks`= $new_count");
 }
?>
