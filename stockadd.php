<?php

// Grab the profile data from the database to ready php catches
if (isset($_POST['idstockadd']) && isset($_COOKIE['firstname']) && isset($_COOKIE['username']) ) {
  
  $itemidadd = $_POST['idstockadd'];
  $notify = $_POST['notify'];

  $sqlsup = "SELECT supplierid, ownerid FROM stocktypes WHERE stockid = '$itemidadd' ";    
  $resultsup = $connex->query($sqlsup);  
  while($rowsup = $resultsup->fetch_assoc()) {         
         $supplierid = $rowsup["supplierid"];
         $themanid = $rowsup["ownerid"];
  }
  if($_POST['qtyadd'] < 0){
    echo "<p class='stockchange' id='stockchange'>The quantity entered is negative. Oops!</p>";      
  }
  $qtyadd = $_POST['qtyadd'];
  $colouradd = $_POST['colouradd'];
  $sizeadd = $_POST['sizeadd'];
  $orderstatus = $_POST['statusupdate'];

  if($qtyadd > 0){
      $questdetail =  "INSERT INTO orders(userid, stockid, supplierid, moonid, qty, colour, size, orderstatus) VALUES ('$useride','$itemidadd', '$supplierid', '$themanid', '$qtyadd', '$colouradd','$sizeadd', '$orderstatus')";
      if ($connex->query($questdetail) === TRUE) {
         echo "<p class='stockchange' id='stockchange' onclick='goPlease();'>Item successfully added.</p>";
      } 
      else {
          echo "Error: " . $itemidadd. "<br>" . $connex->error;
      }

  }


}
elseif (isset($_POST['idstockadd']) && !isset($_COOKIE['firstname']) && !isset($_COOKIE['username']) ) {
  echo "You are not logged in. ";
}
?>
