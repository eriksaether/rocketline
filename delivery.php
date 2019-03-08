

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
.chosen:nth-child(5) {
  color: yellow;
}
</style>
<body>
  <?php require_once("heading.php"); ?>
  <main>
<br>

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

  $up =0;
  //check user rights
  $sqlup = "SELECT * FROM theshopuser WHERE userid = $useride";
  $resultup = $connex->query($sqlup);
  while($row = $resultup->fetch_assoc()) {
    if($row['userstatus'] == "superuser") {
        $up = 1;
    }
  }

  if(isset($_POST['lineone']) && isset($_POST['linetwo']) && isset($_POST['linethree']) ){
    $lineone = $_POST['lineone'];
    $linetwo = $_POST['linetwo'];
    $linethree = $_POST['linethree'];
    $postcode = $_POST['postcode'];
  }
  else{
    $lineone = "The address is missing. Please check.";
    $linetwo = "";
    $linethree = "";
    $postcode = "";
  }
  
  //show cart info......
  require_once('viewgo.php');// ...........................>>
  if($loaded == 0){
    //list cart for user
    $sqlq = "SELECT * FROM orders WHERE userid = $useride && (orderstatus ='Cart' OR orderstatus ='')";  
    $resultq = $connex->query($sqlq);
    $counter=0;
    $stocksung=0;
    $subtotal =0;
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
        
           echo "<tr style='border-bottom: solid 1px lightgray;'>";
           echo"<td ></td>"; 
           echo "<td style='word-wrap: break-word; cursor:pointer;' onclick='myViewItem(".$stocksing[$counter].", ".$detailid[$counter].");'>".$stockname[$counter]."</td><td>".$stockcolour[$counter]."</td><td>".$stocksize[$counter]."</td><td id='".$counter."'>&nbsp;&nbsp;&nbsp;&nbsp;". $quantity[$counter]."</td><td></td><td ALIGN=RIGHT>".$amount."</td>";
           echo" <td ALIGN=RIGHT>".$stockprice[$counter]."</td>";
                 
           echo "</tr>";
           $subtotal = $subtotal + $amount;
           if($subtotal > 1000){
              $deliveryfee=0;
           }
           else {
              $deliveryfee=100;
           }
           $total = $subtotal + $deliveryfee;
    }
    echo "<tr class='total'><td></td><td>Subtotal</td><td></td><td></td><td></td><td></td><td></td><td ALIGN=RIGHT>".$subtotal."</td><td></td><td></td></tr>";
    echo "<tr ><td></td><td>Admin charge</td><td colspan='3' class='orange'></td><td></td><td></td><td ALIGN=RIGHT>".$deliveryfee."</td><td></td><td></td></tr>";
    echo "<tr class='total'><td></td><td>Total</td><td></td><td></td><td></td><td></td><td></td><td ALIGN=RIGHT>".$total."</td><td></td><td></td></tr>";
    echo"</table>";
    echo"<br><i>* Delivery for orders more than R1000 are for free!</i>";
    $connex->close();
  }
  else {
    echo"Your cart is now empty.";
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

</script>

<?php 
if($loaded == 0){

echo "<dir id='tester'></dir>
<div class='controller'>    <button class='purchbtn'>Pay</button><span class='ore'></span>
     
</div>
<div class='triangle'> </div>";

echo"Address chosen:<br>";
echo "<label>line one   :</label>".$lineone;
echo "<br><label>line two   :</label>".$linetwo;
echo "<br><label>line three :</label>".$linethree;
echo "<br><label>postal code:</label>".$postcode;

}
?>
<br>
<!-- choose client for invoicing -->
<form id="clientstuff" class="clientstuff" action="cart.php" method="post">
  <div id='container'></div>
  <br>
  <label><b>Allocate to client:</b></label>
  <select name="clientinfo" required>
      <?php 
      if($clientcounter < 1) {
            echo "<option value=0>No clients. Add a client.</option>";
      }
      else {
          for($cancount = 1; $cancount <= $clientcounter; $cancount++) {
            echo"<option value='".$clientid[$cancount]."'>".$businessname[$cancount]."</option>";
          }
      }
      ?>
  </select>
  <br><label>Client reference:</label><input type="text" name="clientref" placeholder="e.g. purchase order number" required>
  <br>
  <input type="text" name="subtotal" value="<?php echo $subtotal;?>" hidden>
  <input type="text" name="deliveryfee" value="<?php echo $deliveryfee;?>" hidden>
  <input type="text" name="total" value="<?php echo $total;?>" hidden>
  <button  class="sendbtn" onclick="runTwigSubmit('container');">Submit to Shop</button>
</form>




<form action='cart.php' id='myFormViewGo' method='post'>
  <input type='number' id='idviewgo' name='idviewgo' style='width: 40px' hidden>
  <input type='number' id='idviewitem' name='idviewitem' style='width: 40px' hidden>
</form>
<form action='mygridlist.php' id='myFormView' method='post'>
  <input type='number' id='idview' name='idviewgo' style='width: 40px' hidden>
  <input type='number' id='detailid' name='detailid' style='width: 40px' hidden>
</form>


<span id="response"></span><span id="mc"></span>

</main>

</body>
</html>


<script type="text/javascript">

var oldabc=0;
var quants = [];


function myView(littlejohn) {
    document.getElementById("idview").value = littlejohn;
    document.getElementById("myFormView").submit();
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
    left: 150px;
    background-color: orange;
    background: linear-gradient(
        to left,
        orange,
        orange
      );
    color: white;
    border: white 2px solid;
        cursor: pointer;
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

</style>