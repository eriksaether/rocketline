

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
.chosen:nth-child(3) {
  color: yellow;
}
</style>
<body>
  <?php require_once("heading.php"); ?>
  <main>
  <?php require_once("profilenav.php"); ?>

<?php
if (isset($_COOKIE['firstname']) && isset($_COOKIE['username']) ) {

  require_once('appvars.php');
  require_once('basecamp/connectvars2.php');
  $connex = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  // Check connection
  if ($connex->connect_error) {
      die("Connection failed: " . $connex->connect_error);
  } 

  $searcher = 0;
  $supplierid = 0;

  if (isset($_POST['supplierid']) ) {
        $supplierid = $_POST['supplierid'];        
        $searcher = 1;
  }
      ?>
      <nav><form action='suppliersorders.php' method='post'>Search by supplier: 
      <select name='supplierid' > <?php
      echo "  <option value=0>Not needed</option>";      

        $sqlcats = "SELECT * FROM suppliers ";  
        $resultcats = $connex->query($sqlcats); 
        
        while($rowscats = $resultcats->fetch_assoc()) {           
            if($rowscats['supplierid'] == $supplierid) {

              echo"<option value='".$rowscats['supplierid']."' selected>".$rowscats['businessname']."</option>";
            }
            else {
              echo"<option value='".$rowscats['supplierid']."'>".$rowscats['businessname']."</option>";
            }         
        }        
      ?>
      </select>
      <button>go</button>
      </form></nav><br>
      <h4>Supplier orders</h4>
      <?php

       //view order details only
       if (isset($_POST['ordnumber'])) {
          $orderide = $_POST['ordnumber'];
          $searcher =9;
          echo"<h4>Order number: ".$orderide.".</h4>";
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

  require_once('viewgo.php');// ...........................>>

  echo "<table width='100%' style='  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'> ";
      echo "<tr  style='border-bottom-style: outset; background-color: lightblue;'><td width='20px' ></td><td>Order date</td><td>Order&nbsp;</td><td>Line&nbsp;</td><td>Client Ref.&nbsp;</td><td>Supplier&nbsp;name</td><td >Agent&nbsp;</td><td >Stock item</td><td>Quantity</td><td></td><td>Price</td><td >Amount</td><td class='statusinfo' width='100px'>Status</td><td></td></tr>";

  $sqlsup = "SELECT supplierid from suppliers WHERE commid = $useride";
  if($up>0) {
      $sqlsup = "SELECT supplierid from suppliers ";
  }
  
  if($supplierid>0) {
        $sqlsup = "SELECT supplierid from suppliers WHERE supplierid ='$supplierid'";
  }
  $resultsup = $connex->query($sqlsup);
  while($rowss = $resultsup->fetch_assoc()) {
      $supplierid = $rowss["supplierid"];
  

      $sqlq = "SELECT * FROM orders WHERE supplierid = $supplierid And orderstatus <>'Cart'AND orderstatus <>'Wishlist'";
      $resultq = $connex->query($sqlq);
      $counter=0;
      $stocksung=0;
      $subtotal =0;
      $total =0;
      

      while($row = $resultq->fetch_assoc()) {

             $counter = $counter+1;
             $clientref[$counter] = $row["clientref"];
             $detailid[$counter] = $row["detailid"];
             $orderid[$counter] = $row["orderid"];

             $moonid[$counter] = $row["moonid"];
             $supplierid[$counter] = $row["supplierid"];
             $queryVic = "SELECT businessname FROM suppliers WHERE supplierid = '$supplierid[$counter]'";
             $resultVic = $connex->query($queryVic);
             $busname = "Supplier not indicated.";
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
             echo "<td>".$orderid[$counter]."</td><td class='light'>".$detailid[$counter]."</td><td>".$clientref[$counter]."</td><td>".$busname."&nbsp;</td><td>".$name."&nbsp;</td><td style='cursor:pointer;' onclick='myViewItem(".$stocksing[$counter].",".$detailid[$counter].");');'>".$stockname[$counter]."</td>";
             echo "<td id='".$counter."' ALIGN=CENTER>". $quantity[$counter]."</td>";
             if($up>0) {echo "<td style='cursor:pointer; color: lightgray;' class='glyphicon glyphicon-pencil' onclick='laGoon(".$counter.",".$detailid[$counter].", ". $quantity[$counter].");'></td>";}
             else {echo "<td></td>";}
             echo "<td ALIGN=RIGHT>".$stockprice[$counter]."</td><td ALIGN=RIGHT>".$amount."</td>";                
             echo "<td><select id='selstate".$counter."' name='selstate' onchange='changeColor(".$counter.");'>";
                   echo "<option value='".$orderstatus[$counter]."'>".$orderstatus[$counter]."</option>";
                    echo "<option value='Ordered'>Ordered</option>";
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
  }
  echo "<tr class='total'><td></td><td>Subtotal</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td ALIGN=RIGHT>".$subtotal."</td><td></td><td></td><td></td></tr>";
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
  min-width:640px;        /* Suppose you want minimum width of 1000px */
  width: auto !important;  /* Firefox will set width as auto */
  width:640px; 
  min-height: 640px;
   background: linear-gradient(
        to top,
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

  

  nav {
      background-color: lightgray;
      width: 630px;
      border-radius: 2px;
      color: white;
      padding: 2px;
      text-align: center;
  }
  nav.van {
      background-color: gray;
  }
  .navi {
      display: inline-block;
      width: 100px;
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
    color: black;
  }
  .total {
    border-top: gray 2px solid;
    background-color: lightblue;
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