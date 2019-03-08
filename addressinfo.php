<?php
 	$queryaddress = "SELECT * FROM addresses WHERE clientid = $useride"; 
    $result=mysqli_query($dbc,$queryaddress);
    $row_cnt = $result->num_rows;
    $addresscount=intval($row_cnt);
?>