 <?php 
 // load lines for ordering..................................................order it!
  if (isset($_POST['sticknum']) || isset($_POST['sticknumb']) ) {

      //catch some posts
      if (isset($_POST['sticknum'])) {$sticknum = $_POST['sticknum'];}
      if (isset($_POST['sticknumb'])) {$sticknum = $_POST['sticknumb'];}     
      if (isset($_POST['wishname'])) { $wishname = $_POST['wishname'];}
      if (isset($_POST['clientref'])) { $clientref = $_POST['clientref'];}

      //man only if client exists, insert new total order?               
                                                        
      $quest =  "INSERT INTO ppeorders(commid, clientid, clientref, subtotal, deliveryfee, total, orderstamp ) VALUES ('$useride','$clientide', '$clientref', '$subtotal', '$delfee', '$total', now())";
      if ($connex->query($quest) === TRUE) {
         $last_id = $connex->insert_id;

        echo "Thank you.<br>";
         //message to user
         if($firetype == "Clientinvoice"){
            echo "<br>The order was successfully added. Order number: ".$last_id.". Total amount is R".$total.". ";            
            echo "<br>Your customer has been emailed: ".$clientemailaddr.". ";
         }  
         //message to user
         if($firetype == "Eft"){
            echo "<br>Your order was successfully added. Order number: ".$last_id.". Total amount is R".$total.". ";
            echo "Please remit the amount so that we can go ahead with the delivery. ";
            echo "<br>You have been emailed: ".$clientemailaddr.". ";
         }

         // the message
         if($firetype == "Clientinvoice"){
         $clientmsg = "Order no ".$last_id.".\nYour order is in progress. Please check your footprints menu for order tracking.";
         $adminmsg = "Order no ".$last_id.".\nFrom ".$firstname." ".$lastname." tel." .$telephone.". Please check your footprints menu for invoicing..";
         }
         if($firetype == "Eft"){
            $clientmsg = "Order no ".$last_id.".\nPlease remit to amount to:".$accountname." ".$bankname." ".$accountnum.". Please check your footprints menu for order tracking. ";
            $adminmsg = "Order no ".$last_id.".\nEFT to be made from ".$firstname." ".$lastname." tel." .$telephone.". Please check your footprints menu for order tracking.";
         }
         // use wordwrap() if lines are longer than 70 characters
         $clientmsg = wordwrap($clientmsg,70);
         $adminmsg = wordwrap($adminmsg,70);
         if(strlen($clientemailaddr)>4){
             // send email to customer
             mail($clientemailaddr,"Order successful",$clientmsg, "From: erik@rocketline.co.za");
             // send email to admin
             mail("eriksaether@msn.com","Order success for userid:".$useride, $adminmsg, "From: erik@rocketline.co.za");
             
         }

      } 
      else {
          echo "Error: " . $quest. "<br>" . $connex->error;
      }
      $orderid = $last_id;
     
     //ready array catch
     $stick = array("stickid0");  
     //create array for input post catch
     for ($i = 1; $i <= $sticknum; $i++) {
         //create variable $c for stickid number
         $c =  "stickid" . $i;
         array_push($stick, $c);
         //get info and ready for update on to detailed orders             
         $lineinfo[$i] = $_POST[$stick[$i]];
         $idedit = $lineinfo[$i];
         if (isset($_POST['clientref']) ) {

           $queryveedetail = "UPDATE orders SET  orderid = '$orderid', commid = '$useride', moonid = '$clientide', clientref = '$clientref', orderstatus = 'Ordered', orderstamp = now() WHERE detailid = '$idedit' "; 
         }
         else {
           $queryveedetail = "UPDATE orders SET orderstatus = 'Wishlist', moonid = '$clientide', commid = '$useride', wishref = '$wishname' WHERE detailid = '$idedit' "; 
         }            
         if ($connex->query($queryveedetail) === TRUE) {
            echo "";
         } 
         else {
             echo "Error: " . $queryveedetail . "<br>" . $connex->error;
         }
     }                           
     
     $_POST["sticknum"]=null;
  }
  //wishref does not seem to do anything right now..
  ?>