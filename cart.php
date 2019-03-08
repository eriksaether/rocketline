<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shop Cart</title>

  <!-- Responsive viewport tag, tells small screens that it's responsive -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Normalize.css, a cross-browser reset file -->
  <link href="" rel="stylesheet">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">


</head>
<style>
.chosen:nth-child(1) {
  color: yellow;
}
</style>
<body>
  <?php require_once("heading.php"); ?>
  <main>
  <h4>Cart</h4>

<?php
$loaded =0;

if (isset($_COOKIE['firstname']) && isset($_COOKIE['username']) ) {

  require_once('appvars.php');
  require_once('basecamp/connectvars2.php');
  $connex = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  // Check connection
  if ($connex->connect_error) {
      die("Connection failed: " . $connex->connect_error);
  } 
  if (isset($usernaam)) {

        $up =0;
        //check user rights
        $sqlup = "SELECT * FROM theshopuser WHERE userid = $useride";
        $resultup = $connex->query($sqlup);
        while($row = $resultup->fetch_assoc()) {
          if($row['userstatus'] == "superuser") {
              $up = 1;
          }
        }

        //get some clients
        $clientcounter=0;
        $clientemailaddr="?@?";
        $clientide =0;
        $orderid=0;
        $sqlq = "SELECT * FROM clients WHERE commid = '$useride' ";    
        $resultq = $connex->query($sqlq);  
        while($row = $resultq->fetch_assoc()) {
               $clientcounter = $clientcounter+1;
               $businessname[$clientcounter] = $row["businessname"];
               $clientid[$clientcounter] = $row["clientid"];
               $clientemail[$clientcounter] = $row["emailaddr"];
        }
        //load qty change if needed
        require_once('stockadd.php');
       
         // load lines for ordering..................................................order it!
        if (isset($_POST['sticknum']) || isset($_POST['sticknumb']) ) {            

            //catch some posts
            if(isset($_POST['clientinfo'])) { $clientide = $_POST['clientinfo']; }

            for($j=1;$j<=$clientcounter;$j++){
                if($clientid[$clientcounter]==$clientide){
                     $clientemailaddr = $clientemail[$j];
                }
            }
            if (isset($_POST['sticknum'])) {$sticknum = $_POST['sticknum'];}
            if (isset($_POST['sticknumb'])) {$sticknum = $_POST['sticknumb'];}
           
            if (isset($_POST['wishname'])) { $wishname = $_POST['wishname'];}
            if (isset($_POST['clientref'])) { $clientref = $_POST['clientref'];}

            //man only if client exists, update total orders
            if($clientide > 0.5) {                
                $clientref = $_POST['clientref'];
                $subtotal = $_POST['subtotal'];
                $deliveryfee = $_POST['deliveryfee'];
                $total = $_POST['total'];                                                   
                $quest =  "INSERT INTO ppeorders(commid, clientid, clientref, orderstamp ) VALUES ('$useride','$clientide', '$clientref', now())";  
                if ($connex->query($quest) === TRUE) {
                   $last_id = $connex->insert_id;
                   echo "<br>Order successfully added. Order number: ".$last_id.". Total amount is R".$total.".";
                   echo "<br>Your customer has been emailed to: ".$clientemailaddr;
                   // the message
                   $msg = "Order no ".$last_id.".\nYour order is in progress. Please check your footprints menu for order tracking.";

                   // use wordwrap() if lines are longer than 70 characters
                   $msg = wordwrap($msg,70);
                   if(strlen($clientemailaddr)>4){
                       // send email
                       mail("eriksaether@msn.com","Order success userid:".$useride,$msg);
                       mail($clientemailaddr,"Order successful",$msg);
                   }
                } 
                else {
                    echo "Error: " . $quest. "<br>" . $connex->error;
                }
                $orderid = $last_id;
           }
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
           
           $loaded =1;
           $_POST["sticknum"]=null;
        }


        //update or edit quantity...........
         if (isset($_POST['idthumb']) && isset($usernaam)) {

             $idedit= $_POST["idthumb"];
             $thumbit= $_POST["thumbit"];
             if($thumbit>0 || $up > 0){
               $queryvee = "UPDATE orders SET qty = '$thumbit' WHERE detailid = '$idedit' ";
               if(mysqli_query($connex, $queryvee)) {
                  echo "Order detail:".$idedit." updated to quantity ".$thumbit;
               }               
             }
             else {
                  echo "Cannot update order. Quantity error.";
             }
         }
         
        //delete a record..................
        if (!empty($_POST["idnum"])) {
          $idnum= $_POST["idnum"];
          if ($idnum > 0) { 
           
            $sqldel = "DELETE FROM orders WHERE detailid=$idnum";
            if (mysqli_query($connex, $sqldel)) {
              echo "Item deleted successfully. ";
            } else {
              echo "Error deleting record: " . mysqli_error($connex);
            }
                 
          }
          $_POST["idnum"]=0;
        }
  }
 
  
  //show cart info......
  require_once('viewgo.php');// ...........................>>
  if($loaded == 0){
    //list cart for user
    $sqlq = "SELECT * FROM orders WHERE userid = $useride && (orderstatus ='Cart' OR orderstatus ='')";  
    $resultq = $connex->query($sqlq);
    $counter=0;
    $stocksung=0;
    $subtotal = 0;
    $deliveryfee=0;
    $total =0;

    echo "<table width='100%' style='  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>";
    echo "<tr  style='border-bottom-style: outset; background-color: lightgray;'><td></td><td>Stock&nbsp;item&nbsp;&nbsp;</td><td>Option 1&nbsp;&nbsp;</td><td>Option 2&nbsp;&nbsp;</td><td>Qty</td><td></td><td>&nbsp;&nbsp;Price</td><td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Amount</td></tr>";

    while($row = $resultq->fetch_assoc()) {

           
           $counter = $counter+1;
           $detailid[$counter] = $row["detailid"];
           $commid[$counter] = $row["commid"];
           $quantity[$counter] = $row["qty"];
           if(!empty($row["colour"])) {$stockcolour[$counter] = $row["colour"];} else {$stockcolour[$counter] = "n/a"; }
           if(!empty($row["size"])) {$stocksize[$counter] = $row["size"];} else {$stocksize[$counter] =0; }
           $ordertime[$counter] = $row["laststamp"];       
           $stocksing[$counter] = $row["stockid"];
           $stocksung = $row["stockid"];

           if ($row["stockid"]>0) {
               $sqltm = "SELECT * FROM stocktypes WHERE stockid = '".$stocksung."' ";
               $resultstm = $connex->query($sqltm);
               while($rowmen = $resultstm->fetch_assoc()) {             
                 $stockname[$counter] = $rowmen['stockname'];
                 $stockprice[$counter] = $rowmen['retailprice'];                 
               }
           }
           $amount = $quantity[$counter] * $stockprice[$counter];
           $amount = number_format((float)$amount, 2, '.', '');
        
           echo "<tr style='border-bottom: solid 1px lightgray;'>";
           echo"<td style='cursor:pointer; color: red;'  onclick='myDelete(".$detailid[$counter].");' >X</td>"; 
           echo "<td style='word-wrap: break-word; cursor:pointer;' onclick='myViewItem(".$stocksing[$counter].", ".$detailid[$counter].");'>".$stockname[$counter]."</td><td>".$stockcolour[$counter]."</td><td>".$stocksize[$counter]."</td><td id='".$counter."'>&nbsp;&nbsp;&nbsp;&nbsp;". $quantity[$counter]."</td><td style='cursor:pointer; ' class='glyphicon glyphicon-pencil pencil' onclick='laGoon(".$counter.",".$detailid[$counter].", ". $quantity[$counter].");'></td><td ALIGN=RIGHT>".$stockprice[$counter]."</td>";
           echo" <td ALIGN=RIGHT>".$amount."</td>";
                 
           echo "</tr>";
           $subtotal = $subtotal + $amount;         
           $subtotal = number_format((float)$subtotal, 2, '.', '');
           //if($subtotal > 1000){
           //   $deliveryfee=0;
           //}
           //else {
           //   $deliveryfee=100;
           //}
           $total = $subtotal + $deliveryfee;
    }
    echo "<tr class='total'><td></td><td>Subtotal</td><td></td><td></td><td></td><td></td><td></td><td ALIGN=RIGHT>". $subtotal."</td><td></td><td></td></tr>";
    //echo "<tr ><td></td><td>Admin charge</td><td colspan='3' class='orange'></td><td></td><td></td><td ALIGN=RIGHT>".$deliveryfee."</td><td></td><td></td></tr>";
    //echo "<tr class='total'><td></td><td>Total</td><td></td><td></td><td></td><td></td><td></td><td ALIGN=RIGHT>".$total."</td><td></td><td></td></tr>";
    echo"</table>";
    //echo"<br><i>* Delivery for orders more than R1000 are for free!</i>";
    $connex->close();
  }
  else {
    echo"Your cart is now empty. ";
  }
  if( isset($_POST["sticknumb"]) ){
    echo"Check your wishlist for more info. <a href='https://www.rocketline.co.za/wish.php'><button class='wishbutton'>Review wishlists</button></a>";
  }
}
else {
  echo "You are not logged in.";
}
//<embed src="buyerlist.php" width="300" height="400">

?>
<script>
  <?php     
      $js_cars = json_encode($counter);
      echo "var totalStix = ". $js_cars . ";\n";      

      $js_mars = json_encode($detailid);
      echo "var orderCart = ". $js_mars . ";\n";  
  ?>
</script>

<?php 
if($loaded == 0 && isset($_COOKIE['firstname'])){
echo "<i><br>* Please check your items in your cart.</i>";
echo "<br><dir id='tester'></dir>
<div class='controller'>    <button class='benbtn'>Cart</button><span class='ore'></span>
     
</div>
<div class='triangle'> </div><br>";

 echo "<div class='ahead'>Go ahead with order? <a href='confirmation.php'><button class='purchbtn moveleft'>Confirm</button></a>";
 echo "<br><br><div class='bottompls'>Unable to pay. Save to wishlist for trading later.<br><button class='purchbtn moveleft' onclick='onWishMake()' >Save</button></div></div>"; 
}
?>
<form id="wishstuff" class="" action="cart.php" method="post">
  <div id='containerplus'></div>
  <br>
  <br><label>Name:</label><input type="text" name="wishname" placeholder="wishlist or cart name" required>
  <br>
  <button class="sendbtn" onclick="runTwigWish();">Save</button>
</form>

<form action='cart.php' id='myFormViewGo' method='post'>
  <input type='number' id='idviewgo' name='idviewgo' style='width: 40px' hidden>
  <input type='number' id='idviewitem' name='idviewitem' style='width: 40px' hidden>
</form>
<form action='mygridlist.php' id='myFormView' method='post'>
  <input type='number' id='idview' name='idviewgo' style='width: 40px' hidden>
  <input type='number' id='detailid' name='detailid' style='width: 40px' hidden>
</form>

<form action='' id='myFormDel' method='post'>
  <input type='number' id='iddelete' name='idnum' style='width: 40px' hidden>
</form>

  <form action='cart.php' id='myFormAdd' method='post'>
    <input type='number' id='idstockadd' name='idstockadd' style='width: 40px' hidden>
    <input type='number' id='qtyadd' name='qtyadd' style='width: 40px' hidden>
    <input type='text' id='statusupdate' name='statusupdate' style='width: 40px' hidden>
  </form>

<span id="response"></span><span id="mc"></span>

</main>

</body>
</html>


<script type="text/javascript">

var oldabc=0;
var quants = [];
var w =0;

function myDelete(littlejohn) {
    if (confirm("Are you sure Robinhood?") == true) {
      document.getElementById("iddelete").value = littlejohn;
      document.getElementById("myFormDel").submit();
    }   
}

function myView(littlejohn) {
    document.getElementById("idview").value = littlejohn;
    document.getElementById("myFormView").submit();
}

//qty changer - abc is id js tracker and littlejohn is detailid number
function laGoon(abc, littlejohn,newqty){
    document.getElementById("tester").innerHTML = "page order:"+abc+"detailid"+littlejohn;
  document.getElementById(abc).innerHTML = "<form action='' id='myFormEdit' method='post'><input style='width: 30px;' type='number' id='idthumb' name='idthumb'  value='"+littlejohn+"' hidden><input style='width: 30px;' type='number' id='thumbit' name='thumbit'  value='"+newqty+"'><button class='glyphicon glyphicon-ok smallgreen'  onclick='addSlogan(this.form.thumbit);'></button></form>";
  oldabc=abc;
  oldqty=newqty;  
}
//confirm qty change - post
function addSlogan(littlejohn) {
    newqty = document.getElementById('thumbit').value;
    document.getElementById("thumbit").value = newqty;    
    document.getElementById("myFormEdit").submit();
}


function  onWishMake() {  
    w = 1-w; 
    var x = document.getElementById("wishstuff");
        if (w == 1) {
            x.style.display = "block";
        }
        if (w == 0) {
            x.style.display = "none";
        }
}





//from cart to...wishlist - idea here is to have multiple carts in your wishlist..
function runTwigWish(tennisball) {    
    if(totalStix>0){
       var containerplus = document.getElementById("containerplus");
      //createAnArk();
      addAirFolds();   
    return true; 
    }
    else{ return false;} 
}
function addAirFolds(){    
    stick = 0;
    realTotal = totalStix;     
    //ready count of sticks for upload    
    while (containerplus.hasChildNodes()) {
        containerplus.removeChild(containerplus.lastChild);
    }
    //run all sticks available and check which ones have data to upload to db
    for (istick=1;istick<=totalStix;istick++){                  
          attachStigs(istick,"id",orderCart[istick]);                
    }   
    numbStix();                
}
function numbStix() {
    containerplus.appendChild(document.createTextNode(""));
    var input = document.createElement("input");
    containerplus.appendChild(input);
    input.type = "number";
    input.id = "sticknum";
    input.name = "sticknumb";
    input.value = realTotal;
    input.style.display = "";
    document.getElementById('mc').innerHTML = "Your stick count so far (branches and twigs):"+realTotal;    
}
function attachStigs(stick, dancing, matilda) {
    containerplus.appendChild(document.createTextNode(""));
    var input = document.createElement("input");
    containerplus.appendChild(input);
    input.type = "text";
    input.id = "stick"+dancing+stick;
    input.name = "stick"+dancing+stick;
    input.value = matilda;
    input.style.display = "";
}

//view stock item
 function myViewItem(littlejohn,littlerobin) {
    document.getElementById("idviewgo").value = littlejohn;
    document.getElementById("idviewitem").value = littlerobin;
    document.getElementById("myFormViewGo").submit();
  }
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>
<style type="text/css">
  html  {
    font-family: 'Roboto';
  }
  body { 
   min-width:640px;   
   background-color: white;
  }
  h1 {
    font-size: 30px;
    color: white;
  }
  .center {
    text-align: center;
  }
  .controller {
    background-color:   lightgray;
    width: 420px;
  }
  .triangle {
    width: 0; 
    height: 0; 
    border-top: 40px solid transparent;
    border-bottom: 40px solid transparent;  
    border-left: 40px solid lightgray;
    position:   relative; 
    left: 400px;
    top: -52px;
}

  .transbox {
    border: solid 1px black;
    background-image: url('images/Firewood1.jpg');
    background-repeat: repeat-x;
    opacity: 0.9;
}
 
  .nextline {
    clear: left;
  }
  .inputin {
    width: 40px;
  }
  main {
    margin-left: 50px;
    margin-right: 50px;
    z-index: 0.9;
  }
  .clientstuff {
    display: none;
  }

 
  .great {
     background: linear-gradient(
        to left,
        rgb(255, 184, 0, 1),
        orangered
      );
    width: 300px;    
    position: relative;
    top: 500px;
    right: 500px;
  }
  tr:hover {
    background-color: lightgray;
  }
  input:hover {
    background-color: lightgray;
  }
  table {
    width :50%;
    min-width: 450px;
  }
  .ore {
    position:   relative; 
    
    left: 150px;
  }
  .total {
    border-top: gray 2px solid;
    background-color: lightgray;
  }
  .smallgreen {
    font-size: 11px;
    color: green;
  }
   .purchbtn {
    position: relative;   
    background-color: orange;
    background: linear-gradient(
        to left,
        orange,
        orange
      );
    color: white;
    border: white 2px solid;
    cursor: pointer;
    box-shadow: 2px 2px 2px gray;
  }
  .btn {

  }
  .moveleft {
    left: 20px;
  }
  .purchbtn:hover, .sendbtn:hover, .benbtn:hover {
    color: white;
    background: linear-gradient(
          to left,
          rgb(255, 184, 0, 1),
          orangered
        );
    border: orange 2px solid;
  }
  .benbtn {
     left: 75px;
    position: relative;
    background-color: orange;
    background: linear-gradient(
        to left,
        orange,
        orange
      );
    color: white;
    border: white 2px solid;    
  }
  .sendbtn {       
    background-color: orange;
  }
  label {
    width: 150px;
  }
  .glyphicon-pencil {
    color: orange;
  }
  .clientstuff {
    box-shadow: 2px 2px 2px gray;
    padding: 5px;
    width: 450px;
    border-radius: 4px;    
  }
  #wishstuff {
    box-shadow: 2px 2px 2px gray;
    padding: 5px;
    width: 450px;
    border-radius: 4px;
    display: none;
  }
  .orange {
    color: orange;
  }
  .righthand {
      position: fixed;
      bottom: 40px;
      right: 100px;
      box-shadow: 3px 3px 3px gray;
      padding: 3px;
      background-color: lightgray;

      color: white;
      border: white 2px solid;
          cursor: pointer;
  }
  .righthand:hover{
      background-color: orange;
  }
  .wishbutton {
    background-color: orange;
    cursor: pointer;
  }
  .ahead {
    display: block;
    background-color: lightgray;
    border: solid white 2px;
    box-shadow: 1px 1px 1px gray;
    padding: 3px;
    position: relative;
    top: -20px;
    width: 300px;

  }
</style>