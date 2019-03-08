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

.chosen2:nth-child(3) {
  color: yellow;
}
</style>
<body>
  <?php require_once("heading.php"); ?>
  <main>
  <?php require_once("footspoor.php"); ?>
<?php


if (isset($_COOKIE['firstname']) && isset($_COOKIE['username']) ) {

  require_once('appvars.php');
  require_once('basecamp/connectvars2.php');
  $connex = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  // Check connection
  if ($connex->connect_error) {
      die("Connection failed: " . $connex->connect_error);
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
        $searcher = 0;
        $searchstatus = "";
        if($up>0){            
            if (isset($_POST['searchstatus']) && $_POST['searchstatus'] !== "All") {
                  $searchstatus = $_POST['searchstatus'];
                  $searcher = 1;
            }
            echo "<nav><form action='' method='post'>Search by agent: ";
            echo "<select class='finder'  name='searchstatus'>";
            echo "<option value='All'>All</option>";

            $sqloptions = "SELECT * FROM theshopuser";
            $resultoptions = $connex->query($sqloptions);
            $optioncount =0;
            while($rowoptions = $resultoptions->fetch_assoc()) {
                 
                    $optioncount = $optioncount+1;
                    $name[$optioncount] = $rowoptions['firstname'];
                    $salesman[$optioncount] = $rowoptions['userid'];
                    echo "<option value='".$salesman[$optioncount]."'>".$name[$optioncount]."</option>";
                
            }               
                
                echo "</select>";
                echo "<button>go</button>";
                echo "</form></nav><br>";
        }
        else {
          echo"<h4>Orders</h4>";
        }

        // sql to delete a record
        if (!empty($_POST["idnum"])) {
          $idnum= $_POST["idnum"];
          if ($idnum > 0) { 

            $sqldelorder = "DELETE FROM ppeorders WHERE orderid=$idnum";
            if (mysqli_query($connex, $sqldelorder)) {
              echo "Order deleted successfully. ";
            } else {
              echo "Error deleting record: " . mysqli_error($connex);
            }
           
            $sqldel = "DELETE FROM orders WHERE orderid=$idnum";
            if (mysqli_query($connex, $sqldel)) {
              echo "Order details deleted successfully. ";
            } else {
              echo "Error deleting detail: " . mysqli_error($connex);
            }
                 
          }
          $_POST["idnum"]=0;
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
    
  $sqlq = "SELECT * FROM ppeorders WHERE commid = '$useride' ";

  //fetch the list from the database 
  //check privileges
  if($up>0) {
    $sqlq = "SELECT * FROM ppeorders"; 
    //check if searching
    if($searcher > 0 AND $searcher < 8) {
        $sqlq = "SELECT * FROM ppeorders WHERE commid = '$searchstatus' ";
    }
  }   

  $resultq = $connex->query($sqlq);
  $counter=0;
  $stocksung=0;
  $subtotal =0;
  $totalorders =0;

  echo "<table width='100%' style='  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'> ";
  echo "<tr  style='border-bottom-style: outset; background-color: lightgray;'><td width='20px' ></td><td>Order&nbsp;date</td><td>Order&nbsp;</td><td>Client&nbsp;Ref.&nbsp;</td><td>Client&nbsp;name</td><td >Agent&nbsp;</td><td></td><td >Amount</td><td class='statusinfo' width='100px'>Status</td><td></td></tr>";
  
  //retrieve orders  
  while($row = $resultq->fetch_assoc()) {

         $orderstatus="Incomplete";
         $oldstate=0;
         $itemtotal=0;
         $ordertotal =0;
         $counter = $counter+1;
         //main order info
         $orderid[$counter] = $row["orderid"];         
             
         $sqlqu = "SELECT * FROM orders WHERE orderid = '$orderid[$counter]'"; 
         $resultqu = $connex->query($sqlqu);
         $lines=0; 
         $linecount=0; 
         $orderstate = 0;
         $delfee = 0;
         while($rowqu = $resultqu->fetch_assoc()) {
             
             $lines=$lines+1;
             $quantity = $rowqu["qty"]; 
             $idupdateit = $rowqu["orderid"]; 
             $orderstatement = $rowqu["orderstatus"]; 
            
             if($orderstatement == "Complete") {
                $orderstate = 1;
                $linecount = $linecount + 1;
             }                        

             $stocksung = $rowqu["stockid"];
             if ($rowqu["stockid"]>0) {
                
                $stockprice = 0;
                 $sqltm = "SELECT retailprice FROM stocktypes WHERE stockid = '".$stocksung."'";
                 $resultstm = $connex->query($sqltm);
                 while($rowtm = $resultstm->fetch_assoc()) {                                
                   $stockprice = $rowtm['retailprice'];
                 }
             }
             
             $itemtotal = $quantity * $stockprice;
   
             $ordertotal = $ordertotal+$itemtotal;
         }
         //order stage
         if($orderstate == 1 AND $linecount == $lines){
                $orderstatus = "Complete";

                $queryknee = "UPDATE ppeorders SET orderstatus = 'Completed' WHERE orderid = '$idupdateit'";
                mysqli_query($connex, $queryknee);
         }
         //client info
         $clientid[$counter] = $row["clientid"];
         $clientref[$counter] = $row["clientref"];        
         $queryVic = "SELECT businessname FROM clients WHERE clientid = '$clientid[$counter]'";
         $resultVic = $connex->query($queryVic);
         $busname = "";
         while($rowV = $resultVic->fetch_assoc()) { 
             $busname = $rowV['businessname'];
         }
         //agent info
         $commid[$counter] = $row["commid"];
         $queryu = "SELECT * FROM theshopuser WHERE userid = '$commid[$counter]'";
         $resultu = $connex->query($queryu);
         while($rowu = $resultu->fetch_assoc()) { 
             $name = $rowu['firstname'];
         }
         $ordertime[$counter] = $row["orderstamp"];
         echo "<tr>";
         if($up>0) {
              echo"<td style='cursor:pointer; color: red; background-color: ghostwhite;'  onclick='myDelete(".$orderid[$counter].");' >x</td>";
         }
         else {
              echo "<td></td>";
         }
         $delfee = $row["deliveryfee"]; 
         $ordertotal = $ordertotal +$delfee;
         if($clientref[$counter]=="Eft"){
            $busname = $name;
         }
         echo"<td style='cursor:pointer;' onclick='myView(".$orderid[$counter].");'>".substr($ordertime[$counter],0,10)."</td><td style='cursor:pointer;' onclick='myView(".$orderid[$counter].");'>".$orderid[$counter]."</td><td style='cursor:pointer;' onclick='myView(".$orderid[$counter].");'>".$clientref[$counter]."</td><td style='cursor:pointer;' onclick='myView(".$orderid[$counter].");'>".$busname."</td><td>".$name."</td><td></td><td ALIGN=RIGHT>".$ordertotal."</td>";
         echo"<td class='orderstat' style='cursor:pointer;' onclick='myView(".$orderid[$counter].");'>".$orderstatus."</td><td></td><td></td></tr>";
         $totalorders =$totalorders+ $ordertotal;

  }
  echo "<tr class='total'><td></td><td>Total orders</td><td></td><td></td><td></td><td></td><td></td><td ALIGN=RIGHT>".$totalorders."</td><td></td><td></td><td></td></tr>";
  echo"</table>";
  
  $connex->close();
}
else {
  echo "You are not logged in.";
}
//<embed src="buyerlist.php" width="300" height="400">
?>

<form action='ordersdetail.php' id='myFormView' method='post'>
  <input type='number' id='idview' name='ordnumber' style='width: 40px' hidden>
</form>

<form action='' id='myFormDel' method='post'>
  <input type='number' id='iddelete' name='idnum' style='width: 40px' hidden>
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
  .orderstat {
    color: orange;
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

  tr:hover {
    background-color: orange;
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
</style>