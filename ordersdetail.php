
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

.chosen2:nth-child(4) {
  color: yellow;
}
</style>
<body>
  <?php require_once("heading.php"); ?>
  <main>
  <?php require_once("footspoor.php"); ?>

<?php
  $searcher = 0;
  $searchstatus = "";
  if (isset($_POST['searchstatus']) && $_POST['searchstatus'] !== "All") {
        $searchstatus = $_POST['searchstatus'];
        $searcher = 1;
  }
      echo "<nav><form action='' method='post'>Search by status: ";
      echo "<select class='finder'  name='searchstatus'>";
      echo "<option value='".$searchstatus."'>".$searchstatus."</option>";
      echo "<option value='All'>All</option>";
      echo "<option value='Ordered'>Ordered</option>";
      echo "<option value='Cancelled'>Cancelled</option>";
      echo "<option value='Invoiced'>Invoiced</option>";
      echo "<option value='Paid'>Paid</option>";
      echo "<option value='Requested'>Requested</option>";
      echo "<option value='Shipped'>Shipped</option>";      
      echo "<option value='Returned'>Returned</option>";
      echo "<option value='Refunded'>Refunded</option>";
      echo "</select>";
      echo "<button>go</button>";
      echo "</form></nav><br>";

if (isset($_COOKIE['firstname']) && isset($_COOKIE['username']) ) {

  require_once('appvars.php');
  require_once('basecamp/connectvars2.php');
  $connex = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  // Check connection
  if ($connex->connect_error) {
      die("Connection failed: " . $connex->connect_error);
  } 
       //view order details only
       if (isset($_POST['ordnumber'])) {
          $orderide = $_POST['ordnumber'];
          $searcher =9;
          echo"<h4>Order number: ".$orderide.".</h4>";
       }
       else {
          echo "<h4>Order Details</h4>";
       }
       //used featureslist framework to load order qty changes        
        if (isset($_POST['idthumb']) && isset($usernaam)) {

            $idedit= $_POST["idthumb"];
            $thumbit= $_POST["thumbit"];
            $queryvee = "UPDATE orders SET qty = '$thumbit' WHERE detailid = '$idedit' ";
            mysqli_query($connex, $queryvee);
            echo "Order:".$idedit." quantity updated to ".$thumbit;
        }
        //used featureslist framework to load order status change and act if necessary...>>         
        if (isset($_POST['idstatus']) && isset($usernaam)) {

            $idedit= $_POST["idstatus"];
            $zapit= $_POST["pointerit"];
            $queryvee = "UPDATE orders SET orderstatus = '$zapit' WHERE detailid = '$idedit' ";
            mysqli_query($connex, $queryvee);
            
            if ($zapit == "Requested") {
                  
                  //check order detail, then stock and find supplier info..
                  $sqlzap = "SELECT stockid FROM orders WHERE detailid = '$idedit' ";
                  $resultzap = $connex->query($sqlzap);
                  while($rowzap = $resultzap->fetch_assoc()) {
                      $stockthee = $rowzap["stockid"];
                  }
                  $sqlgap = "SELECT * FROM stocktypes WHERE stockid = '$stockthee'";
                  $resultgap = $connex->query($sqlgap);
                  while($rowgap = $resultgap->fetch_assoc()) {
                      $supplierthee = $rowgap["supplierid"];                      
                  }
                  $sqllap = "SELECT * FROM suppliers WHERE supplierid = '$supplierthee'";
                  $resultlap = $connex->query($sqllap);
                  while($rowlap = $resultlap->fetch_assoc()) {
                      $suppliertea = $rowlap["emailaddr"];                      
                  }
                  echo $suppliertea;
                  if(!empty($suppliertea)) {
                      echo "Email sent to supplier.";
                      $headers = "From: erik@recruitrobot.co.za";
                      mail("eriksaether@msn.com","My subject", "hello", $headers);                      
                  }
            }            
            echo "Order:".$idedit." status updated to ".$zapit;
            $zapit = '';
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
  //check privileges
  if($up>0) {
    $sqlq = "SELECT * FROM orders WHERE orderstatus <>'Cart'AND orderstatus <>'Wishlist' ORDER BY orderstamp"; 
    //check if searching
    if($searcher > 0 AND $searcher < 8) {
        $sqlq = "SELECT * FROM orders WHERE orderstatus = '$searchstatus' AND orderstatus <>'Wishlist'";
    }
    if($searcher == 9){
        $sqlq = "SELECT * FROM orders WHERE orderid = '$orderide'";
    }
  }
  else {  
    $sqlq = "SELECT * FROM orders WHERE userid = $useride And orderstatus <>'Cart'AND orderstatus <>'Wishlist'";
    //check if searching
    if($searcher > 0 AND $searcher < 8) {
        $sqlq = "SELECT * FROM orders WHERE userid = $useride AND orderstatus = '$searchstatus' AND orderstatus <>'Wishlist'";
    } 
    if($searcher == 9){
        $sqlq = "SELECT * FROM orders WHERE orderid = '$orderide'";
    }
  }

  $resultq = $connex->query($sqlq);
  $counter=0;
  $stocksung=0;
  $subtotal =0;
  $total =0;
  $deliveryfee =0;

  echo "<table width='100%' style='  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'> ";
  echo "<tr  style='border-bottom-style: outset; background-color: orange;'><td width='20px' ></td><td>Order date</td><td>Order&nbsp;</td><td>Line&nbsp;</td><td>Client&nbsp;Ref.&nbsp;</td><td>Client&nbsp;name</td><td >Agent&nbsp;</td><td >Stock&nbsp;item</td><td>Quantity</td><td></td><td>Price</td><td >Amount</td><td class='statusinfo' width='100px'>Status</td><td></td></tr>";

  while($row = $resultq->fetch_assoc()) {

         $counter = $counter+1;
         $clientref[$counter] = $row["clientref"];
         $detailid[$counter] = $row["detailid"];
         $orderid[$counter] = $row["orderid"];

         $moonid[$counter] = $row["moonid"];
         $queryVic = "SELECT businessname FROM clients WHERE clientid = '$moonid[$counter]'";
         $resultVic = $connex->query($queryVic);
         $busname = "Client deleted.";
         while($rowV = $resultVic->fetch_assoc()) { 
             $busname = $rowV['businessname'];
         }
         //..
         $buyerid[$counter] = $row["userid"];
         $queryu = "SELECT * FROM theshopuser WHERE userid = '$buyerid[$counter]'";
         $resultu = $connex->query($queryu);
         while($rowu = $resultu->fetch_assoc()) { 
             $name = $rowu['firstname'];
         }
         //..
         $commid[$counter] = $row["commid"];
         $quantity[$counter] = $row["qty"];
         $ordertime[$counter] = $row["orderstamp"];       
         $orderstatus[$counter] = $row["orderstatus"];    
         $stocksing[$counter] = $row["stockid"];

         $stocksung = $row["stockid"];
         if ($row["stockid"]>0) {
              $stockname[$counter] = "Item deleted. ";
              $stockprice[$counter] = 0;
             $sqltm = "SELECT * FROM stocktypes WHERE stockid = '".$stocksung."'";
             $resultstm = $connex->query($sqltm);
             while($rowmen = $resultstm->fetch_assoc()) {             
               $stockname[$counter] = $rowmen['stockname'];
               $stockprice[$counter] = $rowmen['retailprice'];
             }
         }

         $amount = $quantity[$counter] * $stockprice[$counter];
      
         echo "<tr style='border-bottom: solid 1px lightgray;'>";
         if($up>0) {
         echo"<td style='cursor:pointer; color: red; background-color: ghostwhite;'  onclick='myDelete(".$detailid[$counter].");' >x</td>";
         }
         else {echo "<td></td>";}
         echo"<td>".substr($ordertime[$counter],0,10)."&nbsp;</td>";
         if($clientref[$counter]=="Eft"){
            $busname = $name;
         }
         echo "<td>".$orderid[$counter]."</td><td class='light'>".$detailid[$counter]."</td><td>".$clientref[$counter]."</td><td>".$busname."&nbsp;</td><td>".$name."&nbsp;</td><td style='cursor:pointer;' onclick='myViewItem(".$stocksing[$counter].",".$detailid[$counter].");');'>".$stockname[$counter]."</td>";
         echo "<td id='".$counter."' ALIGN=CENTER>". $quantity[$counter]."</td>";
         if($up>0) {echo "<td style='cursor:pointer; color: lightgray;' class='glyphicon glyphicon-pencil' onclick='laGoon(".$counter.",".$detailid[$counter].", ". $quantity[$counter].");'></td>";}
         else {echo "<td></td>";}
         echo "<td ALIGN=RIGHT>".$stockprice[$counter]."</td><td ALIGN=RIGHT>".$amount."</td>";                
         echo "<td><select id='selstate".$counter."' name='selstate' onchange='changeColor(".$counter.");'>";
               echo "<option value='".$orderstatus[$counter]."'>".$orderstatus[$counter]."</option>";
                echo "<option value='Ordered'>Ordered</option>";
                echo "<option value='Cancelled'>Cancelled</option>";
                echo "<option value='Invoiced'>Invoiced</option>";
                echo "<option value='Paid'>Paid</option>";
                echo "<option value='Requested'>Requested</option>";
                echo "<option value='Shipped'>Shipped</option>";      
                echo "<option value='Returned'>Returned</option>";
                echo "<option value='Refunded'>Refunded</option>";
                echo "<option value='Complete'>Complete</option>";
         echo "</select></td>";
         echo "<td onclick='formEditStatus(".$counter.", ".$detailid[$counter].");' id='changecolor".$counter."' style='cursor:pointer; color: lightgray;' class='glyphicon glyphicon-pencil'></td></tr>";
         $subtotal = $subtotal + $amount;
  }
  echo "<tr class='total'><td></td><td>Subtotal</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td ALIGN=RIGHT>".$subtotal."</td><td></td><td></td><td></td></tr>";
  if($searcher == 9) { 
      $sqlqt = "SELECT deliveryfee FROM ppeorders WHERE orderid = '$orderide'";
      $resultqt = $connex->query($sqlqt);
      while($rowqt = $resultqt->fetch_assoc()) {
        $deliveryfee = $rowqt["deliveryfee"];
      }

      //if($subtotal < 1000) {$deliveryfee = 100;}
      echo "<tr class=''><td></td><td>Delivery fee</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td ALIGN=RIGHT>".$deliveryfee."</td><td></td><td></td><td></td></tr>";
      $total = $subtotal+$deliveryfee;
      echo "<tr class='total'><td></td><td>Total</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td ALIGN=RIGHT>".$total."</td><td></td><td></td><td></td></tr>";
    }
  echo"</table>";


  $connex->close();
}
else {
  echo "You are not logged in.";
}
//<embed src="buyerlist.php" width="300" height="400">
?>
<form action='ordersdetail.php' id='myFormViewGo' method='post'>
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
    abc = "selstate"+abc;
    selectedStatus = document.getElementById(abc).value; 

    document.getElementById("idstatus").value = littlejohn;    
    document.getElementById("pointerit").value = selectedStatus;
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
  min-width:720px;        /* Suppose you want minimum width of 1000px */
  width: auto !important;  /* Firefox will set width as auto */
  width:720px; 
  min-height: 700px;
   background: linear-gradient(
        to bottom,
        rgba(0, 0, 0, 0),
        rgba(30, 30, 30,0.2)
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
  tr {
    background-color: ghostwhite;
  }
  tr:hover {
    background-color: lightgray;
  }

  table {
    width :78%;
    color: black;
  }
  select {
    background-color: ghostwhite;
  }
  .total {
    border-top: gray 2px solid;
    background-color: orange;
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
  .light {
    color: orange;
  }
</style>