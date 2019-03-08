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

.chosen2:nth-child(1) {
  color: yellow;
}
</style>
<body>
  <header>
  <?php require_once("heading.php"); ?>
  <main>
  <?php require_once("footspoor.php"); ?>

  <h4>My Wishlist</h4>
<?php
  $searcher = 0;
  $searchstatus = "Wishlist";  

if (isset($_COOKIE['firstname']) && isset($_COOKIE['username']) ) {

  require_once('appvars.php');
  require_once('basecamp/connectvars2.php');
  $connex = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  // Check connection
  if ($connex->connect_error) {
      die("Connection failed: " . $connex->connect_error);
  } 

       //used featureslist framework to load qty         
        if (isset($_POST['idthumb']) && isset($usernaam)) {

            $idedit= $_POST["idthumb"];
            $thumbit= $_POST["thumbit"];
            $queryvee = "UPDATE orders SET qty = '$thumbit' WHERE detailid = '$idedit' ";
            mysqli_query($connex, $queryvee);
            echo "Order:".$idedit." quantity updated to ".$thumbit;
        }

       //used featureslist framework to load jobid
        if (isset($_POST['idwish']) && isset($usernaam)) {

            $idedit= $_POST["idwish"];
            $thumbit= $_POST["thumbit"];
            $queryvee = "UPDATE orders SET wishref = '$thumbit' WHERE detailid = '$idedit' ";
            mysqli_query($connex, $queryvee);
            echo "Item:".$idedit." updated to ".$thumbit;
        }
        //used featureslist framework to load order info         
        if (isset($_POST['idstatus']) && isset($usernaam)) {

            $idedit= $_POST["idstatus"];
            $orderstatus= $_POST["pointerit"];
            $queryvee = "UPDATE orders SET orderstatus = '$orderstatus' WHERE detailid = '$idedit' ";
            mysqli_query($connex, $queryvee);

            echo "Order:".$idedit." status updated to cart.";
            $thumbit = '';

            //add if cart and allocate to wish name given...
            if($orderstatus == "cart") {
                $cartname = "General";
                $cartup =  "INSERT INTO carts(userid, name) VALUES ('$useride', '$cartname')";
                    if ($connex->query($cartup) === TRUE) {
                       echo "<br>Cart added.";
                    } 
                    else {
                        echo "Error: " . $cartname. "<br>" . $connex->error;
                    }
            }
        }      

        // sql to delete a record
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

  $up =0;
  //check user rights
  $sqlup = "SELECT * FROM theshopuser WHERE userid = $useride";
  $resultup = $connex->query($sqlup);
  while($row = $resultup->fetch_assoc()) {
    if($row['userstatus'] == "superuser") {
        $up = 1;
        echo"<span style='float: right; color: red;'>Admin view</span>";
    }
  }
  //load brands
    $sql4 = "SELECT * FROM brandnames";      
    $result4 = $connex->query($sql4);      
    while($row4 = $result4->fetch_assoc()) { 
        $brandid = $row4["brandid"];
        $brandname[$brandid] = $row4["brandname"];
        $brandpic[$brandid] = $row4["brandpic"];
    }
    
  require_once('viewgo.php');// ...........................>>

  //fetch the list from the database 
  if($up>0) {    
    $sqlq = "SELECT * FROM orders WHERE orderstatus = '$searchstatus' ORDER BY wishref";      
  }
  else {
    $sqlq = "SELECT * FROM orders WHERE userid = '$useride' && orderstatus = '$searchstatus' ORDER BY wishref";
  }

  $resultq = $connex->query($sqlq);
  $counter=0;
  $stocksung=0;
  $subtotal =0;
  $total =0;

  echo "<table width='100%' style='  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'> ";
  echo "<tr  style='border-bottom-style: outset; background-color: lightgray;'><td width='20px' ></td><td>Wishlist&nbsp;</td><td></td><td>Line&nbsp;</td><td >Stock&nbsp;item&nbsp;</td><td width='50px' >Quantity&nbsp;</td><td></td><td class='center'>Price</td><td class='center'>Amount</td><td class='statusinfo' width='100px'>Status</td><td></td></tr>";

  while($row = $resultq->fetch_assoc()) {

         $counter = $counter+1;
         $detailid[$counter] = $row["detailid"];
         $moonid[$counter] = $row["moonid"];
         $wishname[$counter] = $row["wishref"];
         $buyerid[$counter] = $row["userid"];
         $commid[$counter] = $row["commid"];
         $quantity[$counter] = $row["qty"];
         $ordertime[$counter] = $row["laststamp"];       
         $orderstatus[$counter] = $row["orderstatus"];    
         $stocksing[$counter] = $row["stockid"];
         $stocksung = $row["stockid"];

         if ($row["stockid"]>0) {
            $stockname[$counter] = "Item deleted. ";
            $stockprice[$counter] = 0;
             $sqltm = "SELECT * FROM stocktypes WHERE stockid = '".$stocksung."' ";
             $resultstm = $connex->query($sqltm);
             while($rowmen = $resultstm->fetch_assoc()) {             
               $stockname[$counter] = $rowmen['stockname'];
               $stockprice[$counter] = $rowmen['retailprice'];
              }
         }
         $amount = $quantity[$counter] * $stockprice[$counter];
      
         echo "<tr style='border-bottom: solid 1px lightgray;'>";
         echo"<td style='cursor:pointer; color: red; background-color: ghostwhite;'  onclick='myDelete(".$detailid[$counter].");' >x</td>";
         echo "<td id='changewish".$counter."' >".$wishname[$counter]."</td><td  onclick='laSoon(".$counter.",".$detailid[$counter].", \"$wishname[$counter]\");' class='pencil glyphicon glyphicon-pencil'></td>";
         echo "<td class='light'>".$detailid[$counter]."</td><td style='word-wrap: break-word; cursor: pointer;' onclick='myViewItem(".$stocksing[$counter].",".$detailid[$counter].");'>".$stockname[$counter]."</td>";
         echo "<td id='".$counter."'>". $quantity[$counter]."</td><td  class='pencil glyphicon glyphicon-pencil' onclick='laGoon(".$counter.",".$detailid[$counter].", ". $quantity[$counter].");'></td>";
         echo "<td ALIGN=RIGHT>".$stockprice[$counter]."</td><td ALIGN=RIGHT>".$amount."</td>";  
         if ($up >1){
         echo "<td><select id='selstate".$counter."' name='selstate' onchange='changeColor(".$counter.");'>";
               echo "<option value='".$orderstatus[$counter]."'>".$orderstatus[$counter]."</option>";
               echo "<option value='Paid'>Paid</option>";
               echo "<option value='Delivered'>Delivered</option>";
               echo "<option value='Complete'>Complete</option>";
               echo "<option value='Returned'>Returned</option>";
         echo "</select></td>";}
         else { echo "<td>".$orderstatus[$counter]."</td>";}
         echo "<td onclick='formEditStatus(".$counter.", ".$detailid[$counter].");' id='changecolor".$counter."' class='cart glyphicon glyphicon-shopping-cart'></td></tr>";
         $subtotal = $subtotal + $amount;
  }
  echo "<tr class='total'><td></td><td>Subtotal</td><td></td><td></td><td></td><td></td><td></td><td></td><td ALIGN=RIGHT>".$subtotal."</td><td></td><td></td><td></td></tr>";
  echo"</table>";  
  
  $sqlnotif = "SELECT * FROM orders WHERE moonid = '$useride' && orderstatus = '$searchstatus'";  
  $resultnotif = $connex->query($sqlnotif);
  $counter=0;
  $stocksung=0;
  $subtotal =0;
  $total =0;
  echo "<br>";
  echo "<h4>Others Wishes</h4>";
  echo "<table width='100%' style='  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'> ";
  echo "<tr  style='border-bottom-style: outset; background-color: lightgray;'><td width='20px' ></td><td>Wishlist&nbsp;</td><td></td><td>Line&nbsp;</td><td >Stock&nbsp;item&nbsp;</td><td width='50px' >Quantity&nbsp;</td><td></td><td class='center'>Price</td><td class='center'>Amount</td><td class='statusinfo' width='100px'>Status</td><td></td></tr>";

  while($row = $resultnotif->fetch_assoc()) {

         $counter = $counter+1;
         $detailid[$counter] = $row["detailid"];
         $moonid[$counter] = $row["moonid"];
         $wishname[$counter] = $row["wishref"];
         $buyerid[$counter] = $row["userid"];
         $commid[$counter] = $row["commid"];
         $quantity[$counter] = $row["qty"];
         $ordertime[$counter] = $row["laststamp"];       
         $orderstatus[$counter] = $row["orderstatus"];    
         $stocksing[$counter] = $row["stockid"];
         $stocksung = $row["stockid"];

         if ($row["stockid"]>0) {
            $stockname[$counter] = "Item deleted. ";
            $stockprice[$counter] = 0;
             $sqltm = "SELECT * FROM stocktypes WHERE stockid = '".$stocksung."' ";
             $resultstm = $connex->query($sqltm);
             while($rowmen = $resultstm->fetch_assoc()) {             
               $stockname[$counter] = $rowmen['stockname'];
               $stockprice[$counter] = $rowmen['retailprice'];
              }
         }
         $amount = $quantity[$counter] * $stockprice[$counter];
      
         echo "<tr style='border-bottom: solid 1px lightgray;'>";
         echo"<td ></td>";
         echo "<td id='changewish".$counter."' >".$wishname[$counter]."</td><td></td>";
         echo "<td class='light'>".$detailid[$counter]."</td><td style='word-wrap: break-word; cursor: pointer;' onclick='myViewItem(".$stocksing[$counter].",".$detailid[$counter].");'>".$stockname[$counter]."</td>";
         echo "<td id='".$counter."'>". $quantity[$counter]."</td><td></td>";
         echo "<td ALIGN=RIGHT>".$stockprice[$counter]."</td><td ALIGN=RIGHT>".$amount."</td>";  
         if ($up >1){
         echo "<td>".$orderstatus[$counter]."</td>";}
         else { echo "<td>".$orderstatus[$counter]."</td>";}
         echo "<td  id='changecolor".$counter."' class='cart glyphicon glyphicon-shopping-cart'></td></tr>";
         $subtotal = $subtotal + $amount;
  }
  echo "<tr class='total'><td></td><td>Subtotal</td><td></td><td></td><td></td><td></td><td></td><td></td><td ALIGN=RIGHT>".$subtotal."</td><td></td><td></td><td></td></tr>";
  echo"</table>";

  $connex->close();
}
else {
  echo "You are not logged in.";
}
//<embed src="buyerlist.php" width="300" height="400">
?>
<form action='wish.php' id='myFormViewGo' method='post'>
  <input type='number' id='idviewgo' name='idviewgo' style='width: 40px' hidden>
  <input type='number' id='idviewitem' name='idviewitem' style='width: 40px' hidden>
</form>

<form action='mygridlist.php' id='myFormView' method='post'>
  <input type='number' id='idview' name='idnumb' style='width: 40px' hidden>
</form>

<form action='' id='myFormDel' method='post'>
  <input type='number' id='iddelete' name='idnum' style='width: 40px' hidden>
</form>

<form action='' id='formEditStatus' method='post'>
  <input type='number' id='idstatus' name='idstatus' style='width: 40px' hidden>
  <input type='text' id='pointerit' name='pointerit'  value='' hidden>  
</form>
<dir id="tester"></dir>

</main>
</body>
</html>


<script type="text/javascript">

var oldabc=0;
var quants = [];

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
function formEditStatus(abc, littlejohn) {   

    document.getElementById("idstatus").value = littlejohn;    
    document.getElementById("pointerit").value = "Cart";
    document.getElementById("formEditStatus").submit();
}
function  changeColor(abc) {
    abc = "changecolor"+abc;
    document.getElementById(abc).style.color = "green";
}

//qty changer - abc is id js tracker and littlejohn is detailid number
function laGoon(abc, littlejohn,newqty){
    document.getElementById("tester").innerHTML = "page order:"+abc+"detailid"+littlejohn;
  document.getElementById(abc).innerHTML = "<form action='' id='myFormEdit' method='post'><input style='width: 30px;' type='number' id='idthumb' name='idthumb'  value='"+littlejohn+"' hidden><input style='width: 30px;' type='number' id='thumbit' name='thumbit'  value='"+newqty+"'><button class='glyphicon glyphicon-ok smallgreen'  onclick='addSlogan(this.form.thumbit);'></button></form>";
  oldabc=abc;
  oldqty=newqty;  
}
function addSlogan(littlejohn) {
    newqty = getElementsByTagName('thumbit').value;
    document.getElementById("thumbit").value = newqty;    
    document.getElementById("myFormEdit").submit();
}

//wishlist item changer - 
function laSoon(abc, littlejohn,newval){
  document.getElementById("tester").innerHTML = "page order:"+abc+"detailid"+littlejohn;    
  var abc = "changewish"+abc;
    
  document.getElementById(abc).innerHTML = "<form action='' id='myFormEdit' method='post'><input style='width: 30px;' type='number' id='idthumb' name='idwish'  value='"+littlejohn+"' hidden><input style='width: 50px;' type='text' id='thumbit' name='thumbit'  value='"+newval+"'><button class='glyphicon glyphicon-ok smallgreen'  onclick='editWish(this.form.thumbit);'></button></form>";
  oldabc=abc;
  oldval=newval;  
}
function editWish(littlejohn) {
    newqty = getElementsByTagName('thumbit').value;
    document.getElementById("thumbit").value = newqty;    
    document.getElementById("myFormEdit").submit();
}
 function myViewItem(littlejohn,littlerobin) {
    document.getElementById("idviewgo").value = littlejohn;
    document.getElementById("idviewitem").value = littlerobin;
    document.getElementById("myFormViewGo").submit();
  }
</script>
<style type="text/css">
  html  {
    font-family: 'Roboto';
  }
  body { 
  min-width:640px;        /* Suppose you want minimum width of 1000px */
  width: auto !important;  /* Firefox will set width as auto */
  width:640px; 
  min-height: 700px;
   background: linear-gradient(
        to bottom,
        rgba(0, 0, 0, 0),
        rgba(255, 165, 0,0.4)
      )
  }
  h1 {
    font-size: 30px;
    color: white;
  }
  .center {
    text-align: center;
  }


  .transbox {
    background-image: url('images/beach.jpg');
    background-repeat: repeat-x;
    opacity: 0.9;
    border: solid 1px black;
  } 
 
  .finder {
    color: black;
     width: 70px;
    height: 23px;
  }
  button {
    height: 23px;
    vertical-align: bottom;
    color: darkgray;
  }
  .pencil {
    cursor:pointer; 
    color: lightgray;
  }

  .nextline {
    clear: left;
  }
  .inputin {

  }
  main {
    margin-left: 50px;
    margin-right: 50px;
    color: darkgray;
  }
  a {
    color: white; text-decoration:none; 
    width: 90px;
    display: inline-block;
    text-align: center;
  }
  a:visited { 
    text-decoration:none; 
  }
  a:hover {
    color: yellow;
  }
  tr:hover {
    background-color: lightgray;
  }

  table {
    width :70%;
    color: black;
  }
  select {
    background-color: ghostwhite;
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
    left: 250px;
    background-color: orange;
  }
  .cart {
    cursor:pointer; color: lightgray;
  }
  .cart:hover {
    color: orange;
  }
    .glyphicon-pencil {
    color: orange;
  }
  a.red {
    color: red;
    width: 300px;
  }
    .light {
    color: orange;
  }
</style>