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
        box-shadow: 2px 2px 2px gray;
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

  .red {
    color: red;
  }
  .invisible {
    display: none;
  }

</style>

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
  <h4>Payment Choice</h4>

<?php
$loaded =0;
$delfee = 0;

if (isset($_COOKIE['firstname']) && isset($_COOKIE['username']) ) {

  $firstname = $_COOKIE['firstname'];
  require_once('appvars.php');
  require_once('basecamp/connectvars2.php');
  $connex = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  // Check connection
  if ($connex->connect_error) {
      die("Connection failed: " . $connex->connect_error);
  } 

  $ok =0;
  //check user rights and get details
  $sqlup = "SELECT * FROM theshopuser WHERE userid = $useride";
  $resultup = $connex->query($sqlup);
  while($row = $resultup->fetch_assoc()) {    
      $lastname = $row["lastname"];
      $email = $row["emailaddr"]; //$email name used as can be via credit card or invoice
      $telephone = $row["telephone"];    
  }
  //not sure why this is here?
  if(empty($_POST['delfeeinput'])){$delfee =0; }

  //catch some address info
  if(isset($_POST['lineone']) && isset($_POST['linetwo']) && isset($_POST['linethree']) ){
    if(isset($_POST['nameline'])){
      $nameline = $_POST['nameline'];} 
      else{$nameline = "";}
    $lineone = $_POST['lineone'];
    $linetwo = $_POST['linetwo'];
    $linethree = $_POST['linethree'];
    $postcode = $_POST['postcode'];
    $delfee = $_POST['delfeeinput'];
    $ok = 1;
  }
  else{
    $lineone = "The address is missing. Please check.";
    $linetwo = "<button><a href='confirmation.php'>Ok</a></button>";
    $linethree = "";
    $postcode = "";
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

  if(isset($_POST['clientinfo'])) { $clientide = $_POST['clientinfo']; }
  // I think this was for duplicates
  for($j=1;$j<=$clientcounter;$j++){
      if($clientid[$j] == $clientide){
           $clientemailaddr = $clientemail[$j];
      }
  }
  //if client invoicing then lets do this!!
  //echo $clientide;
  if($clientide > 0.5 ) {   
    $subtotal = $_POST['subtotal'];
    $delfee = $_POST['deliveryfee'];
    $total = $_POST['total']; 
    $firetype = "Clientinvoice";
    //update cart info to order
    require_once('firestarter.php');// ...........................>>
    echo "Order loaded.";
    $loaded =1;
  }
  
  //show cart info......
  require_once('viewgo.php');// ...........................>>

  ?>
<script>
  <?php     
      $js_cars = json_encode($counter);
      echo "var totalStix = ". $js_cars . ";\n";      

      $js_mars = json_encode($detailid);
      echo "var orderCart = ". $js_mars . ";\n";  

      $js_del = json_encode($delfee);
      echo "var delfee = ". $js_del . ";\n";  
  ?>
</script>
  <?php 

  if($loaded == 0){
    //list cart for user
    $sqlq = "SELECT * FROM orders WHERE userid = $useride && (orderstatus ='Cart' OR orderstatus ='')";  
    $resultq = $connex->query($sqlq);
    $counter=0;
    $stocksung=0;
    $realsubtotal =0;
    $realtotal =0;
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
                 $stockdes[$counter] = $rowmen['description'];               
               }
               $thestockname = $stockname[$counter];
               $thestockdes = $stockdes[$counter];

           }
           $amount = $quantity[$counter] * $stockprice[$counter];
           $realsubtotal = $realsubtotal + $amount;
           $viewamount = number_format($quantity[$counter] * $stockprice[$counter],2);
        
           echo "<tr style='border-bottom: solid 1px lightgray;'>";
           echo"<td ></td>"; 
           echo "<td style='word-wrap: break-word; cursor:pointer;' onclick='myViewItem(".$stocksing[$counter].", ".$detailid[$counter].");'>".$stockname[$counter]."</td><td>".$stockcolour[$counter]."</td><td>".$stocksize[$counter]."</td><td id='".$counter."'>&nbsp;&nbsp;&nbsp;&nbsp;". $quantity[$counter]."</td><td></td><td ALIGN=RIGHT>".$stockprice[$counter]."</td>";
           echo" <td ALIGN=RIGHT>".$viewamount."</td>";
                 
           echo "</tr>";
           $viewsubtotal = number_format((float)$realsubtotal, 2, '.', '');
           $viewsubtotal = number_format($realsubtotal,2);
          
           $realtotal = $realsubtotal + $delfee; //need to decide delivery charge rules
           $viewtotal = number_format((float)$realtotal, 2, '.', '');
           //this thing is not behaving..
           $viewtotal = number_format($realtotal,2);
    }
    echo "<tr class='total'><td></td><td>Subtotal</td><td></td><td></td><td></td><td></td><td></td><td ALIGN=RIGHT>".$viewsubtotal."</td><td></td><td></td></tr>";
    echo "<tr ><td></td><td>Admin charge</td><td colspan='3' class='orange'></td><td></td><td></td><td ALIGN=RIGHT>".number_format($delfee,2)."</td><td></td><td></td></tr>";
    echo "<tr class='total'><td></td><td>Total</td><td></td><td></td><td></td><td></td><td></td><td ALIGN=RIGHT>".$viewtotal."</td><td></td><td></td></tr>";
    echo"</table>";
    echo"<br>";
    echo "<i>* Delivery for some local orders less than 1kg are free!</i>";
    $connex->close();
    if($realsubtotal<0.5){ echo "<br><br><p class='red'>Please check your order. There is nothing in the cart.</p>";
                  exit();}
  }
  else {
    echo"Your cart is now empty.";
  }
}
else {
  echo "You are not logged in.";
}


//if not yet loaded ready payment choices..>>
if($loaded == 0 && isset($_COOKIE['firstname'])){

  echo "<dir id='tester'></dir>
  <div class='controller'>    <button class='purchbtn'>Payment Choice</button><span class='ore'></span>       
  </div>
  <div class='triangle'> </div>";
  if($ok == 1) {echo "Delivery address: ".$nameline.$lineone.", ".$linetwo.", ".$linethree.", ".$postcode;}
  ?>
  <br>
  <form method="post" action="eftpayment.php">  
    <input type="text" name="subtotal" value="<?php echo $realsubtotal;?>" hidden>
    <input type="text" name="deliveryfee" value="<?php echo $delfee;?>" hidden>
    <input type="text" name="total" value="<?php echo $realtotal; ?>" hidden>    
    <div id='caneft'></div>
    <button  class='benbtn' onclick="runTwigSubmit('caneft');">EFT Payment</button>
  </form>

  <form action="signature.php" method="POST">
  <input type="hidden" name="merchant_id" value="10390562">
  <input type="hidden" name="merchant_key" value="xqav686l2lbye">
  <input type="hidden" name="return_url" value="https://www.rocketline.com/success.php">
  <input type="hidden" name="cancel_url" value="https://www.rocketline.com/cancel.php">
  <input type="hidden" name="notify_url" value="https://www.rocketline.com/notify.php">
  <?php 
  echo "<input type='hidden' name='name_first' value='".$firstname."'>
  <input type='hidden' name='name_last' value='".$lastname."'>
  <input type='hidden' name='email_address' value='".$email."'>
  <input type='hidden' name='cell_number' value='".$telephone."'>";
  echo "<input type='hidden' name='m_payment_id' value='01AB'>
  <input type='hidden' name='amount' value='".$realtotal."'>";
  echo "<input type='hidden' name='item_name' value='". $thestockname."'>
  <input type='hidden' name='item_description' value='". $thestockdes."'>";
  //<input type="hidden" name="custom_int1" value="2">
  //<input type="hidden" name="custom_str1" value="Extra order information">
  echo "<input type='hidden' name='email_confirmation' value='1'>";
  echo "<input type='hidden' name='confirmation_address' value='".$email."'>";
  echo "<input type='hidden' name='payment_method' value='eft'>";
  echo "<input type='hidden' name='passphrase' value='finalcountdown'>";
  echo "<input type='hidden' name='signature' value='f103e22c0418655fb03991538c51bfd5'>";
  echo "<br><button class='benbtn invisible'>Credit Card Payment</button>";
}
?>
</form>
<br>

<!-- choose client for {{invoicing}} -->
<form id="clientstuff" class="clientstuff" action="checkout.php" method="post">
  <div id='container'></div>
  <br>
  <label><b>Client:</b></label>
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
  <br><label>Client ref.:</label><input type="text" name="clientref" placeholder="e.g. purchase order number" required>
  <br>
  <input type="text" name="subtotal" value="<?php echo $realsubtotal;?>" hidden>
  <input type="text" name="deliveryfee" value="<?php echo $delfee;?>" hidden>
  <label>Amount</label><input type="text" name="total" value="<?php echo $realtotal; ?>" readOnly>
  <button  class="sendbtn" onclick="runTwigSubmit('container');">Submit for Invoicing</button>
</form>

<!-- view stuff -->
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
<?php 
if($loaded == 0){

 echo "<button class='righthand' onclick='onAllocate()' >Invoice</button>";
}
?>
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

function  onAllocate() {     
    var x = document.getElementById("clientstuff");
    if(x.style.display == "block"){
      x.style.display = "none";
    } 
    else {
      x.style.display = "block";
    }
}

//from cart to order
function runTwigSubmit(tennisball) {    
    if(totalStix>0){
       var container = document.getElementById(tennisball);
      //createAnArk();
      addAirFields();   
      return true; 
    }
    else{ return false;} 
}
function addAirFields(){    
    stick = 0;
    realTotal = totalStix;  
    //ready count of sticks for upload    
    while (container.hasChildNodes()) {
        container.removeChild(container.lastChild);
    }
    //run all sticks available and check which ones have data to upload to db
    for (istick=1;istick<=totalStix;istick++){                  
          attachStix(istick,"id",orderCart[istick]);                
    }   
    numStix();                
}
function numStix() {
    container.appendChild(document.createTextNode(""));
    var input = document.createElement("input");
    container.appendChild(input);
    input.type = "number";
    input.id = "sticknum";
    input.name = "sticknum";
    input.value = realTotal;
    input.style.display = "none";
    document.getElementById('mc').innerHTML = "Your stick count so far (branches and twigs):"+realTotal;    
}

function attachStix(stick, dancing, matilda) {
    container.appendChild(document.createTextNode(""));
    var input = document.createElement("input");
    container.appendChild(input);
    input.type = "text";
    input.id = "stick"+dancing+stick;
    input.name = "stick"+dancing+stick;
    input.value = matilda;
    input.style.display = "none";
}
</script>
