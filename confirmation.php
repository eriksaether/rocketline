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
    margin-right: 20px;
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
  .postSel {
    display: none;
  }
  .dsvSel {
    display: none;
  }
  .inputaddr {
    width: 300px;
  }
  .moveleft {
    left: 100px;
    position: relative;
  }
  .red {
    color: red;
  }
  .gladness {
    background-color: gray;
  }
  .dsvframe {
    position: relative;
    left: 500px; 
    top: -500px;
    width: 580px;
    height: 440px;
  }
  .dselect {
    width: 300px;
  }
  @media(max-width: 1100px) {
    .dsvframe {      
      left: 400px;      
    }    
  }
  @media(max-width: 950px) {
    .dsvframe {      
      left: 0px;
      top: 0px;
    }    
  }

    }
  select {
    max-width: 250px;
  }
  .ahead {
    display: block;
    background-color: lightgray;
    border: solid white 2px;
    box-shadow: 1px 1px 1px gray;
    padding: 3px;
    position: relative;
    top: -20px;
    width: 340px;
  }
  .leftblock {
    width: 400px;
    margin-right: 5px;
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
  <h4>Delivery</h4>

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

  //get some clients
  $clientcounter=0;
  $sqlq = "SELECT * FROM clients WHERE commid = '$useride' ";    
  $resultq = $connex->query($sqlq);  
  while($row = $resultq->fetch_assoc()) {
         $clientcounter = $clientcounter+1;
         $businessname[$clientcounter] = $row["businessname"];
         $clientid[$clientcounter] = $row["clientid"];
         $clientemail[$clientcounter] = $row["emailaddr"];
  }

  // Grab the profile data from the database
  $queryaddr = "SELECT name, type, lineone, linetwo, linethree, postcode FROM addresses WHERE agentid = '" . $_SESSION['userid'] . "'";
  $resultaddr = mysqli_query($connex, $queryaddr);
   $row_cnt = $resultaddr->num_rows;
   if($row_cnt=0) {
      echo "You have not loaded an address yet.";
   }
   else{
     $addrcount=0; $type=array();
     $linename=array("");
     $lineone=array("");
     $linetwo=array("");
     $linethree=array(""); 
     $postcode=array(""); 
     while ($rower = mysqli_fetch_array($resultaddr)) {
       
       
       if($rower['type']=="Delivery"){
            $linename[0] = $rower['name'];
            $lineone[0] = $rower['lineone'];
            $linetwo[0] = $rower['linetwo'];
            $linethree[0] = $rower['linethree'];
            $postcode[0] = $rower['postcode'];
            
       }
       if($rower['type']=="Home"){
            $linename[1] = $rower['name'];
            $lineone[1] = $rower['lineone'];
            $linetwo[1] = $rower['linetwo'];
            $linethree[1] = $rower['linethree'];
            $postcode[1] = $rower['postcode'];
            
       }
       if($rower['type']=="Work"){
            $linename[2] = $rower['name'];
            $lineone[2] = $rower['lineone'];
            $linetwo[2] = $rower['linetwo'];
            $linethree[2] = $rower['linethree'];
            $postcode[2] = $rower['postcode'];
               
       }   
       $addrcount = $addrcount+1;     
     }

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

  $counter=0;
  $itemcount =0;
  $stocksung=0;
  $realtotal =0;
  $subtotal=0;
  $deliveryfee=0; //start with zero, js function to set values..
  $total =0;

  $margintotal = 0;
  $totalweight =0;
  $totalstockvol =0;
  //show cart info......
  require_once('viewgo.php');// ...........................>>
  if($loaded == 0){
    //list cart for user
    $sqlq = "SELECT * FROM orders WHERE userid = $useride && (orderstatus ='Cart' OR orderstatus ='')";  
    $resultq = $connex->query($sqlq);
    

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
           $supid = $row["supplierid"];

           if ($row["stockid"]>0) {
               $sqltm = "SELECT * FROM stocktypes WHERE stockid = '".$stocksung."' ";
               $resultstm = $connex->query($sqltm);
               while($rowmen = $resultstm->fetch_assoc()) {             
                 $stockname[$counter] = $rowmen['stockname'];
                 $stockprice[$counter] = $rowmen['retailprice'];
                 $stockweight[$counter] = $rowmen['weight'];
                 $stockwidth[$counter] = $rowmen['width'];
                 $stockheight[$counter] = $rowmen['height'];
                 $stockdepth[$counter] = $rowmen['depth'];

                 $costprice[$counter] = $rowmen['costprice'];
                 $contribution = $stockprice[$counter] - $costprice[$counter];
                //echo $stockname[$counter] , $stockweight[$counter] ;
               }
           }
           $amount = $quantity[$counter] * $stockprice[$counter];
           $margin = $quantity[$counter] * $contribution;
           $realtotal = $realtotal + $amount;
           $amount = number_format((float)$amount, 2, '.', '');
        
           echo "<tr style='border-bottom: solid 1px lightgray;'>";
           echo"<td ></td>"; 
           echo "<td style='word-wrap: break-word; cursor:pointer;' onclick='myViewItem(".$stocksing[$counter].", ".$detailid[$counter].");'>".$stockname[$counter]."</td><td>".$stockcolour[$counter]."</td><td>".$stocksize[$counter]."</td><td id='".$counter."'>&nbsp;&nbsp;&nbsp;&nbsp;". $quantity[$counter]."</td><td></td><td ALIGN=RIGHT>".$stockprice[$counter]."</td>";
           echo" <td ALIGN=RIGHT>".$amount."</td>";
                 
           echo "</tr>";           
           
           $subtotal = number_format((float)$realtotal, 2, '.', '');
           $margintotal = $margintotal + $margin;
           //total
           $total = $subtotal + $deliveryfee;
           //item count
           $itemcount = $itemcount+$quantity[$counter];
           //weight
           $stockwei = $stockweight[$counter]*$quantity[$counter];
           $totalweight = $totalweight + $stockwei; 
           //volume
           $stockvol = $stockwidth[$counter]*$stockheight[$counter]*$stockdepth[$counter] * $quantity[$counter];
           $totalstockvol = $totalstockvol + $stockvol;
           //max metrics
           if($stockwidth[$counter] < $stockheight[$counter] AND $stockwidth[$counter] < $stockdepth[$counter]){
                $minx[$counter] = $stockwidth[$counter];
                if($stockheight[$counter] < $stockdepth[$counter]){
                  $miny[$counter] = $stockheigth[$counter];
                  $minz[$counter] = $stockdepth[$counter];
                }
                else{
                  $miny[$counter] = $stockdepth[$counter];
                  $minz[$counter] = $stockheight[$counter];
                }
           }
           else if($stockheight[$counter] < $stockwidth[$counter] AND $stockheight[$counter] < $stockdepth[$counter]){
                $minx[$counter] = $stockheigth[$counter];
                if($stockwidth[$counter] < $stockdepth[$counter]){
                  $miny[$counter] = $stockwidth[$counter];
                  $minz[$counter] = $stockdepth[$counter];
                }
                else{
                  $miny[$counter] = $stockdepth[$counter];
                  $minz[$counter] = $stockwidth[$counter];
                }
           }
           else {
                $minx[$counter] = $stockdepth[$counter];
                if($stockheight[$counter] < $stockwidth[$counter]){
                  $miny[$counter] = $stockheight[$counter];
                  $minz[$counter] = $stockwidth[$counter];
                }
                else{
                  $miny[$counter] = $stockwidth[$counter];
                  $minz[$counter] = $stockheight[$counter];
                }
           }

           //get some suppliers
           $sqls = "SELECT * FROM suppliers WHERE supplierid = '$supid' ";
           $resultsu = $connex->query($sqls);
           $scounter = 0;
           while($rowsu = $resultsu->fetch_assoc()) {

                $scounter = $scounter+1;
                $suppliername[$scounter] = $rowsu["businessname"];
                //echo $suppliername[$scounter];
                $supplierid[$scounter] = $rowsu["supplierid"];
                $queryaddr = "SELECT * FROM addresses WHERE supplierid = '$supplierid[$scounter]' && type='Delivery' ";    
                $resultaddr = mysqli_query($connex, $queryaddr);
                while ($rower = mysqli_fetch_array($resultaddr)) {       
                  

                  $lineonesup[$scounter] = $rower['lineone'];
                  $linetwosup[$scounter] = $rower['linetwo'];
                  $linethreesup[$scounter] = $rower['linethree'];
                  $postcodesup[$scounter] = $rower['postcode'];
                }
           }
    }
    echo "<tr class='total'><td></td><td>Subtotal</td><td></td><td></td><td></td><td></td><td></td><td ALIGN=RIGHT id='subtotal'>".$subtotal."</td><td></td><td></td></tr>";
    echo "<tr ><td></td><td>Admin charge</td><td colspan='3' class='orange'></td><td></td><td></td><td ALIGN=RIGHT id='delfee'>0.00</td><td></td><td></td></tr>";
    echo "<tr class='total'><td></td><td>Total</td><td></td><td></td><td></td><td></td><td></td><td ALIGN=RIGHT id='tot'>".$subtotal."</td><td></td><td></td></tr>";
    echo"</table>";
    echo"<br><i><span>* Deliveries for low weight items are free! Your cart weight is ".$totalweight."kg.<span></i>";
    $connex->close();
    if($total<0.5){ echo "<br><br><p class='red'>Please check your order. There is nothing in the cart.</p>";
                  exit();}
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
      $js_cnt = json_encode($itemcount);
      echo "var itemCount = ". $js_cnt . ";\n";  

      $js_cars = json_encode($counter);
      echo "var totalStix = ". $js_cars . ";\n";      

      $js_mars = json_encode($detailid);
      echo "var orderCart = ". $js_mars . ";\n";  

      $js_subt = json_encode($realtotal);
      echo "var subTotal = ". $js_subt . ";\n"; 

      $js_tot = json_encode($deliveryfee);
      echo "var delfee = ". $js_tot . ";\n"; 

      $js_marg = json_encode($margintotal);
      echo "var marginTotal = ". $js_marg . ";\n";

       $js_q = json_encode($quantity);
      echo "var quantity = ". $js_q . ";\n";

      $js_0 = json_encode($linename);
      echo "var lineName = ". $js_0 . ";\n"; 

       $js_1 = json_encode($lineone);
      echo "var lineOne = ". $js_1 . ";\n"; 

       $js_2 = json_encode($linetwo);
      echo "var lineTwo = ". $js_2 . ";\n"; 

       $js_3 = json_encode($linethree);
      echo "var lineThree = ". $js_3 . ";\n"; 

       $js_4 = json_encode($postcode);
      echo "var postCode = ". $js_4 . ";\n"; 

       $wayt = json_encode($totalweight);
      echo "var totalweight = ". $wayt . ";\n"; 

       $wayt1 = json_encode($minx);
      echo "var minX = ". $wayt1 . ";\n"; 

       $wayt2 = json_encode($miny);
      echo "var minY = ". $wayt2 . ";\n"; 

       $wayt3 = json_encode($minz);
      echo "var minZ = ". $wayt3 . ";\n"; 
        ?>
</script>


<?php 
if($loaded == 0 && isset($_COOKIE['firstname']) ){

  echo "<dir id='tester'></dir>
  <div class='controller'>    <button class='purchbtn' >Delivery</button><span class='ore'></span></div>
  <div class='triangle'> </div>";
  echo "<div class='leftblock'>";
  echo "<div class='ahead'><b>Please select your delivery option: </b>";
  echo "<select id='mainSel' onchange='mainSel();'>
        <option>No transport needed</option> 
        <option>DSV address</option>      
        <option>Postlink</option>      
        <option hidden>Postlink</option>      
        </select><br>";

  echo "<div class='dsvSel' id='dsvSelBig'><br>Select DSV address:";
  echo "<br><select id='dsvSel' class='dselect' onchange='dsvResult();'>
        <optgroup label='DSV Global Transport'></optgroup>
        <option>DSV  Locker - Engen 45th CC</option>
        <option>DSV  Locker - Engen Bottelary Motors</option>
        <option>DSV  Locker - Engen La Lucia</option>
        <option>DSV  Locker - Engen Woodhurst CC</option>
        <option>DSV Locker -  Engen Protea Heights</option>
        <option>DSV Locker -  Engen Tyger Waterfront</option>
        <option>DSV Locker - Ballito Convenience Centre</option>
        <option>DSV Locker - Barbeque Downs</option>
        <option>DSV Locker - BKB Beaufort West</option>
        <option>DSV Locker - BKB Port Elizabeth</option>
        <option>DSV Locker - Droomers Garage</option>
        <option>DSV Locker - DSV Distribution Centurion (Staff Only)</option>
        <option>DSV Locker - Engen Aerofill Convenience Centre</option>
        <option>DSV Locker - Engen Airports Convenience Centre</option>
        <option>DSV Locker - Engen Allandale</option>
        <option>DSV Locker - Engen Bakenkop</option>
        <option>DSV Locker - Engen Bassonia</option>
        <option>DSV Locker - Engen Beachway Auto Centre</option>
        <option>DSV Locker - Engen Belaphil</option>
        <option>DSV Locker - Engen Blouberg Motors</option>
        <option>DSV Locker - Engen Bonamanzi</option>
        <option>DSV Locker - Engen Bonza Bay Convenience Centre</option>
        <option>DSV Locker - Engen Brackenfell Service Centre</option>
        <option>DSV Locker - Engen Brackenview</option>
        <option>DSV Locker - Engen Brentwood Park</option>
        <option>DSV Locker - Engen Brentwood Park 2</option>
        <option>DSV Locker - Engen Brightstar Convenience Centre</option>
        <option>DSV Locker - Engen Bryanston CC</option>
        <option>DSV Locker - Engen Buffalo River 1 Stop</option>
        <option>DSV Locker - Engen C & B Motors</option>
        <option>DSV Locker - Engen Cape Gate</option>
        <option>DSV Locker - Engen Cape Road Convenience Centre</option>
        <option>DSV Locker - Engen Carnival Station</option>
        <option>DSV Locker - Engen Centurion Park</option>
        <option>DSV Locker - Engen Chelsea Village Centre</option>
        <option>DSV Locker - Engen Clayville North</option>
        <option>DSV Locker - Engen Codonia Quick Stop</option>
        <option>DSV Locker - Engen Cornwall View</option>
        <option>DSV Locker - Engen Cosmopolitan</option>
        <option>DSV Locker - Engen Curie Park Quick Stop</option>
        <option>DSV Locker - Engen De Bron Convenience Centre</option>
        <option>DSV Locker - Engen Del Judor Service Center</option>
        <option>DSV Locker - Engen Delfi</option>
        <option>DSV Locker - Engen Detroit Motors</option>
        <option>DSV Locker - Engen Dumor</option>
        <option>DSV Locker - Engen Duneden</option>
        <option>DSV Locker - Engen Durban North</option>
        <option>DSV Locker - Engen Durban Road Convenience Centre</option>
        <option>DSV Locker - Engen Durbanville</option>
        <option>DSV Locker - Engen Eastlake Motors</option>
        <option>DSV Locker - Engen Eastway Motors</option>
        <option>DSV Locker - Engen Edgemead</option>
        <option>DSV Locker - Engen Emmarentia</option>
        <option>DSV Locker - Engen Erasmusrand</option>
        <option>DSV Locker - Engen Etienne Convenience</option>
        <option>DSV Locker - Engen Evander Garage</option>
        <option>DSV Locker - Engen Faerie Glen</option>
        <option>DSV Locker - Engen Fairfield Motors</option>
        <option>DSV Locker - Engen Fairway CC</option>
        <option>DSV Locker - Engen False Bay</option>
        <option>DSV Locker - Engen Fifth Avenue</option>
        <option>DSV Locker - Engen Flamwood</option>
        <option>DSV Locker - Engen Florida Glen</option>
        <option>DSV Locker - Engen Fontana Service Station</option>
        <option>DSV Locker - Engen Galleria Convenience Centre</option>
        <option>DSV Locker - Engen Gants CC</option>
        <option>DSV Locker - Engen George Storrar</option>
        <option>DSV Locker - Engen Glenugie CC</option>
        <option>DSV Locker - Engen Go Go Convenience Centre</option>
        <option>DSV Locker - Engen Golden Harvest</option>
        <option>DSV Locker - Engen Golden Quickstop</option>
        <option>DSV Locker - Engen Greenacres Auto Centre</option>
        <option>DSV Locker - Engen GT Service Station</option>
        <option>DSV Locker - Engen Guys SS</option>
        <option>DSV Locker - Engen Hazeldean Convenience Centre</option>
        <option>DSV Locker - Engen Helderberg</option>
        <option>DSV Locker - Engen Heritage CC</option>
        <option>DSV Locker - Engen Hibberdene </option>
        <option>DSV Locker - Engen Hillside Motors</option>
        <option>DSV Locker - Engen Hughes</option>
        <option>DSV Locker - Engen Impala Petroport Convenience</option>
        <option>DSV Locker - Engen Iris Motors</option>
        <option>DSV Locker - Engen Jack Street</option>
        <option>DSV Locker - Engen Jacksons Convenience Centre</option>
        <option>DSV Locker - Engen Janmy Dienste</option>
        <option>DSV Locker - Engen JJ van Ryneveld</option>
        <option>DSV Locker - Engen K90 S\/S</option>
        <option>DSV Locker - Engen Kemptonpark</option>
        <option>DSV Locker - Engen Klerksdorp Convenience Centre</option>
        <option>DSV Locker - Engen Kritzinger</option>
        <option>DSV Locker - Engen Kwena Park Service Station</option>
        <option>DSV Locker - Engen La Rochelle Service Station</option>
        <option>DSV Locker - Engen Linksfield</option>
        <option>DSV Locker - Engen Lonehill</option>
        <option>DSV Locker - Engen Lyttelton Motors</option>
        <option>DSV Locker - Engen Main Road Convenience Centre</option>
        <option>DSV Locker - Engen Mangaung 1 Stop</option>
        <option>DSV Locker - Engen Meadowridge</option>
        <option>DSV Locker - Engen Medwood</option>
        <option>DSV Locker - Engen Merriespruit</option>
        <option>DSV Locker - Engen Midway Mews</option>
        <option>DSV Locker - Engen Monresa</option>
        <option>DSV Locker - Engen Monte Vista Motors</option>
        <option>DSV Locker - Engen Mountainview</option>
        <option>DSV Locker - Engen Musgrave Convenience Centre</option>
        <option>DSV Locker - Engen Old Mill</option>
        <option>DSV Locker - Engen Oranje Service Centre</option>
        <option>DSV Locker - Engen Orkney CC</option>
        <option>DSV Locker - Engen Palm Valley</option>
        <option>DSV Locker - Engen Panda Convenience Centre</option>
        <option>DSV Locker - Engen Parktown</option>
        <option>DSV Locker - Engen Pavilion CC</option>
        <option>DSV Locker - Engen Pegasus MTRS</option>
        <option>DSV Locker - Engen Pellissier </option>
        <option>DSV Locker - Engen Penford Motors</option>
        <option>DSV Locker - Engen Polo Pony</option>
        <option>DSV Locker - Engen Rabie</option>
        <option>DSV Locker - Engen Regal Motors</option>
        <option>DSV Locker - Engen Ruwari Service Station</option>
        <option>DSV Locker - Engen Sarnia</option>
        <option>DSV Locker - Engen Saxenburg Service Station</option>
        <option>DSV Locker - Engen Settlers Way</option>
        <option>DSV Locker - Engen Silver Lakes Con Cen</option>
        <option>DSV Locker - Engen Simjees CC</option>
        <option>DSV Locker - Engen Skystop Convenience Centre</option>
        <option>DSV Locker - Engen Southway</option>
        <option>DSV Locker - Engen Springlake Convenience Centre </option>
        <option>DSV Locker - Engen Strijdompark</option>
        <option>DSV Locker - Engen Summerfields</option>
        <option>DSV Locker - Engen Sunningdale CC</option>
        <option>DSV Locker - Engen Sunset Beach Service Station</option>
        <option>DSV Locker - Engen Tahero</option>
        <option>DSV Locker - Engen Technopark</option>
        <option>DSV Locker - Engen The Junction Convenience</option>
        <option>DSV Locker - Engen Tokai Retail</option>
        <option>DSV Locker - Engen Tony Watson</option>
        <option>DSV Locker - Engen Trade Route</option>
        <option>DSV Locker - Engen Umfula 1 Stop┬á┬á┬á┬á┬á┬á┬á</option>
        <option>DSV Locker - Engen Van Buuren</option>
        <option>DSV Locker - Engen Waterfall </option>
        <option>DSV Locker - Engen Waterfront S\/Station</option>
        <option>DSV Locker - Engen Watermeyer MTRS</option>
        <option>DSV Locker - Engen Wavecrest Convenience Centre</option>
        <option>DSV Locker - Engen Welgemoed</option>
        <option>DSV Locker - Engen William Nicol CC</option>
        <option>DSV Locker - Engen Willowcrest</option>
        <option>DSV Locker - Engen Woodlands</option>
        <option>DSV Locker - Engen Zambezi Quick Stop</option>
        <option>DSV Locker - K'S Convenience Centre</option>
        <option>DSV Locker - Mine Own CC</option>
        <option>DSV Locker - Paradyskloof Motors</option>
        <option>DSV Locker - Pineways Convenience Centre</option>
        <option>DSV Locker - Plettenberg Bay 1 Stop</option>
        <option>DSV Locker - Rally Motors</option>
        <option>DSV Locker - Richards Bay Convenience Centre</option>
        <option>DSV Locker - Riverhorse Valley</option>
        <option>DSV Locker - Roadside Service Station</option>
        <option>DSV Locker - Rudan 1 Stop</option>
        <option>DSV Locker - Siemens Midrand (Staff Only)</option>
        <option>DSV Locker - Vodaworld Midrand 1</option>
        <option>DSV Locker - Vodaworld Midrand 2</option>
        <option>DSV Locker Engen Albertinia Diensstasie</option>
        <option>DSV Locker Engen George Eco Stop (Pty) Ltd</option>
        <option>DSV Locker Engen Heidelberg 1 Stop</option>
        <option>DSV Locker Engen Sedgefield 1 Stop</option>
        <option>DSV Locker Engen Stilbaai Service Station</option>
        <option>DSV Locker Engen Willie Hough Multi Motors</option>
        <option>DSV Locker -Shell Tutuni</option>

  </select>
  <br>You can go to this website for more information: <button class='gladness'><a href='http://www.locker.za.dsv.com/how-much-does-it-cost'>DSV locker</a></button></div>";

  echo "<div class='postSel' id='postSelBig'><br>Select Postlink option:<br><select id='postSel' class='moveleft' onclick='postSel();'>
        <option value='nextdaylocal'>Same city next day</option>
        <option value='majornextday'>Any Major city next day</option>
        <option value='outlying2days'>Outlying 2 days</option>   
        <option value='outlying4days'>Outlying 4 days</option>      
  </select></div>";

  echo "<span id='clientnoteme'>You will need to know the supplier addresses or have the delivery already organised, ok?</span><br>";
  echo "<form id='clientnote' method='post' action='checkout.php'><br>";
  echo "<input hidden name='lineone' value='0'>";
  echo "<input hidden name='linetwo' value='0'>";
  echo "<input hidden name='linethree' value='0'>";
  echo "<input hidden name='postcode' value='0'>";
  echo "<input hidden id='delcostno' name='delcost' value='0'>";
  echo "<input type='hidden' name='delfeeinput' value='5'>";
  echo "<button class='benbtn'>Confirm</button></form>";
  echo "</div>"; //complete box


  //delivery address ...........................................>>
  echo "<form id='nominatedaddr' class='ahead' method='post' action='checkout.php'>";
  echo "<span>Deliver to Address:</span><br>";
  echo "<label>Type   :</label>";
  echo "<select id='typeaddress' name='typeaddress' onchange='differentAddress();'>
  <option value='Delivery'>Delivery</option>
  <option value='Home'>Home</option>
  <option value='Work'>Work</option>
  </select><br>";
  echo "<label>Name       :</label><input class='inputaddr' id='linenamead' name='linename' value='".$linename[0]."'><br>";
  echo "<label>line one   :</label><input class='inputaddr' id='lineonead' name='lineone' value='".$lineone[0]."'><br>";
  echo "<label>line two   :</label><input class='inputaddr' id='linetwoad' name='linetwo' value='".$linetwo[0]."'><br>";
  echo "<label>line three :</label><input class='inputaddr' id='linethreead' name='linethree' value='".$linethree[0]."'><br>";
  echo "<label>postal code:</label><input class='inputaddr' id='postcodead' name='postcode' value='".$postcode[0]."'><br>";
  echo "<label>Cost:       </label><input id='delcost' name='delcost' value='0'><br>";
  echo "<input type='hidden' id='delfeeinput' name='delfeeinput' >";
  echo "<button class='benbtn'>Confirm delivery</button></form>";
  echo "</div>"; //close leftblock

}
?>

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
Suggestions:<span id="response" ></span><span id="mc"></span><span id="demo"></span>

<iframe id="dsvframe" class="dsvframe" src="https://www.google.com/maps/d/embed?mid=1GXMTHiKj-4cbio6evKAGgXvAXz0&ll=-29.272823591509184%2C25.240144999999984&z=5" ></iframe>

<input type="text" id="inputsoso" name="inputsoso" onchange="loadDoc();" value="" hidden>
<p id="demo2"></p>
<p id="demo3"></p>

</main>

</body>
</html>

<script type="text/javascript">

var oldabc=0;
var thetotal =0;
var quants = [];
var dsvAddress = ["McDonalds Fourways, William Nicol Drive and Fourways Boulevard, Fourways", "Engen Lonehill, Lone Close and Lonehill Boulevard, Lonehill, 2191", "Engen Bryanston, c/o Pytchley & 11 Sloane Rd , Epsom Downs, 2021", "Engen Main Road, c/o Main & Bruton Road , Bryanston, 2021","Builders Warehouse Rivonia (Rivonia), Leeukop Road & Rivonia Road, Rivonia, 2157","Sasol Stormvoel, Leeukop Road & Rivonia Road, Rivonia, 2157"];
var splitAddress = "";

var postlink = {
  "nextdaylocal" : {
    "2"  : 109,
    "3"  : 129,
    "4"  : 135,
    "5"  : 145,
    "10"  : 205,
    "15"  : 225,
    "20"  : 245,
    "25"  : 270

  },
  "majornextday" : {
    "2"  : 169,
    "3"  : 199,
    "4"  : 239,
    "5"  : 289,
    "10"  : 569,
    "15"  : 739,
    "20"  : 929,
    "25"  : 1149
  },
    "outlying2days" : {
    "2"  : 199,
    "3"  : 249,
    "4"  : 299,
    "5"  : 349,
    "10"  : 629,
    "15"  : 819,
    "20"  : 999,
    "25"  : 1249
  },
  "outlying4days" : {
    "2"  : 149,
    "3"  : 169,
    "4"  : 199,
    "5"  : 219,
    "10"  : 269,
    "15"  : 319,
    "20"  : 349,
    "25"  : 399
  }
};
var dsvCosts = {
  "Pak85" : {
    "minx"  : 15,
    "miny"  : 15,
    "minz"  : 20
  },
  "Pak95" : {
    "minx"  : 9,
    "miny"  : 26,
    "minz"  : 39
  },
  "Pak120" : {
    "minx"  : 19,
    "miny"  : 30,
    "minz"  : 40
  },
   "PakExpress" : {
    "minx"  : 1,
    "miny"  : 27,
    "minz"  : 35
  }  
};
var percyweight = Math.round(totalweight);
var locationgrp = "nextdaylocal";

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

function mainSel() {
  document.getElementById("dsvSelBig").style.display = "none";  
  document.getElementById("postSelBig").style.display = "none";    
  document.getElementById("clientnote").style.display = "none"; 
  document.getElementById("clientnoteme").style.display = "none"; 
  document.getElementById("dsvframe").style.display = "none";  
  var mainSel = document.getElementById("mainSel").selectedIndex;
  //dsv courier...
  if(mainSel == 1 && totalweight <= 5){
     dsvResult();
     perdVolume();
     document.getElementById("delcost").readOnly = true; 
     document.getElementById("nominatedaddr").style.display = "block"; 
     delConseq();
     document.getElementById("dsvframe").style.display = "block";  
     document.getElementById("typeaddress").style.display = "none";
  }
  if(mainSel == 1 && totalweight > 5){
    document.getElementById("nominatedaddr").style.display = "none";
    document.getElementById("clientnoteme").style.display = "block"; 
    document.getElementById("clientnoteme").innerHTML = "Not able to deliver. Package too large. Please select another option.";    
    document.getElementById("typeaddress").style.display = "none";
  }
  

 //postlink via agent address
 if(mainSel == 2){
    document.getElementById("postSelBig").style.display = "block"; 
    document.getElementById("nominatedaddr").style.display = "block"; 
    postSel();
    document.getElementById("linenamead").value = lineName[0]; document.getElementById("linenamead").readOnly = false;
    document.getElementById("lineonead").value = lineOne[0]; document.getElementById("lineonead").readOnly = false;
    document.getElementById("linetwoad").value = lineTwo[0]; document.getElementById("linetwoad").readOnly = false;
    document.getElementById("linethreead").value = lineThree[0]; document.getElementById("linethreead").readOnly = false; 
    document.getElementById("postcodead").value = postCode[0]; document.getElementById("postcodead").readOnly = false;  
    document.getElementById("typeaddress").style.display = "inline";      
 }
 //postlink via nominated address
 if(mainSel == 3) {
    document.getElementById("postSelBig").style.display = "block";
    document.getElementById("nominatedaddr").style.display = "block";  
    postSel();
    document.getElementById("linenamead").value = ""; document.getElementById("linenamead").readOnly = false;
    document.getElementById("lineonead").value = ""; document.getElementById("lineonead").readOnly = false;
    document.getElementById("linetwoad").value = ""; document.getElementById("linetwoad").readOnly = false;
    document.getElementById("linethreead").value = ""; document.getElementById("linethreead").readOnly = false; 
    document.getElementById("postcodead").value = ""; document.getElementById("postcodead").readOnly = false; 
    document.getElementById("typeaddress").style.display = "inline";      
 }
 if(mainSel == 0){
    document.getElementById("typeaddress").style.display = "inline"; 
    document.getElementById("nominatedaddr").style.display = "none"; 
    document.getElementById("clientnote").style.display = "block";   
    document.getElementById("clientnoteme").style.display = "block"; 
    document.getElementById("clientnoteme").innerHTML = "You will need to know the supplier addresses or have the delivery already organised, ok?";     
    delfee = 0;
    document.getElementById("delcost").value = delfee;
    delConseq(); 
 }

}

//allocate new delivery charge
function delConseq() {
    //delLimitCost()
    document.getElementById("delcost").value = delfee.toFixed(2);
    document.getElementById("delfee").innerHTML = delfee.toFixed(2);
    document.getElementById("delfeeinput").value = delfee.toFixed(2);
    thetotal = subTotal+delfee;
    document.getElementById("tot").innerHTML = thetotal.toFixed(2);
}
//check delivery cost against margin to play with
function delLimitCost() {
  var limitDelivery = marginTotal/4;
  if(delfee < limitDelivery) {
    delfee = 0;
  }
}
//change delivery sales charges for postlink
function postSel() {  
  locationgrp = document.getElementById("postSel").value;
  var postSel = document.getElementById("postSel").selectedIndex;
  
  percyWeight();
  delConseq();
  document.getElementById("response").innerHTML = " Postlink next day delivery for "+totalweight+"kg is R"+postlink[locationgrp][Math.round(percyweight)]+".";
}

//calculate perceived weight for postlink
function  percyWeight() {
  percyweight = Math.round(totalweight);
  if(percyweight<=2){percyweight = 2}
  else if (percyweight<=10){percyweight = 10;}
  else if (percyweight<=15){percyweight = 15;}
  else if (percyweight<=20){percyweight = 20;}
  else if (percyweight<=25){percyweight = 25;}
  if (percyweight>25){percyweight = 25;}
  delfee = postlink[locationgrp][Math.round(percyweight)];  
}

var sumSox =0;
var minXs =0;
var minYs =0;
var minZs =0;
var delSug = "<br>";
//calculate perceived weight for postlink
function  perdVolume() {
  delfee =  0;
  delSug = "<br>";
  //lets check first if a single object fits
  for(i=1; i<=totalStix; i++){
    for(j=1; j<=quantity[i]; j++){
      dsvUp(minX[i], minY[i], minZ[i]);
      sumSox = parseInt(minX[i]) + sumSox;
      minXs = Math.max(parseInt(minX[i]), minXs);
      minYs = Math.max(parseInt(minY[i]), minYs);
      minZs = Math.max(parseInt(minZ[i]), minZs);      
    }
  }
  //lets check if multiple objects fit..

  //dsvUp(minXs,minYs,minZs);
  document.getElementById("response").innerHTML = delSug;  
}
function dsvUp(x,y,z){
  if(x <= dsvCosts["PakExpress"]["minx"] && y <= dsvCosts["PakExpress"]["miny"] && z <= dsvCosts["PakExpress"]["minz"]){
      delSug = delSug+"Go Pak Express! Stock dimensions: "+"Height:"+x+" Width:"+y+" Breadth:"+z+"cm fits into box "+dsvCosts["PakExpress"]["minx"]+"x"+dsvCosts["PakExpress"]["miny"]+"x"+dsvCosts["PakExpress"]["minz"]+".<br>"; 
      delfee = delfee+105;
  }
  else {
      if(x <= dsvCosts["Pak95"]["minx"] && y <= dsvCosts["Pak95"]["miny"] && z <= dsvCosts["Pak95"]["minz"]){
          delSug = delSug+"Go Pak 95! Stock dimensions: "+"Height:"+x+" Width:"+y+" Breadth:"+z+"cm fits into box "+dsvCosts["Pak95"]["minx"]+"x"+dsvCosts["Pak95"]["miny"]+"x"+dsvCosts["Pak95"]["minz"]+".<br>";
          delfee = delfee+95;
      }
      else {
        if(x <= dsvCosts["Pak85"]["minx"] && y <= dsvCosts["Pak85"]["miny"] && z <= dsvCosts["Pak85"]["minz"]){
            delSug = delSug+"Go Pak 85! Stock dimensions: "+"Height:"+x+" Width:"+y+" Breadth:"+z+"cm fits into box "+dsvCosts["Pak85"]["minx"]+"x"+dsvCosts["Pak85"]["miny"]+"x"+dsvCosts["Pak85"]["minz"]+".<br>";
            delfee = delfee+85;
        }
        else {
          if(x <= dsvCosts["Pak120"]["minx"] && y <= dsvCosts["Pak120"]["miny"] && z <= dsvCosts["Pak120"]["minz"]){
              delSug = delSug+"Go Pak 120! Stock dimensions: "+"Height:"+x+" Width:"+y+" Breadth:"+z+"cm fits into box "+dsvCosts["Pak120"]["minx"]+"x"+dsvCosts["Pak120"]["miny"]+"x"+dsvCosts["Pak120"]["minz"]+".<br>";
              delfee = delfee+120;
          }
          else {
               delfee = 99999;
               delSug = "DSV cannot deliver this item.";
          }            
        }          
      }
  }
}
function splitzaAddress(splinter) {  
  splitzaAddress = splinter;
}
function loadDoc() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        showResult(xhttp.responseXML);
    }
    };
    xhttp.open("GET", "DSVlockers.xml", true);
    xhttp.send();
}
function showResult(xml) {

    var txt = "";
    path = "//name"
    if (xml.evaluate) {
        var nodes = xml.evaluate(path, xml, null, XPathResult.ANY_TYPE, null);
        var result = nodes.iterateNext();
        while (result) {
            txt += result.childNodes[0].nodeValue + "<br>";
            result = nodes.iterateNext();
        } 
    // Code For Internet Explorer
    } else if (window.ActiveXObject || xhttp.responseType == "msxml-document") {
        xml.setProperty("SelectionLanguage", "XPath");
        nodes = xml.selectNodes(path);
        for (i = 0; i < nodes.length; i++) {
            txt += nodes[i].childNodes[0].nodeValue + "<br>";
        }
    }
    document.getElementById("demo3").innerHTML = txt;
    document.getElementById("demo2").innerHTML = "test";

    
}
function differentAddress() {
  var selectBox = document.getElementById("typeaddress");
  var selectedValue = selectBox.selectedIndex;
  
  if(typeof lineOne[selectedValue] !== 'undefined') {
    document.getElementById("linenamead").value = lineName[selectedValue];
    document.getElementById("lineonead").value = lineOne[selectedValue];
    document.getElementById("linetwoad").value = lineTwo[selectedValue];
    document.getElementById("linethreead").value = lineThree[selectedValue];
    document.getElementById("postcodead").value = postCode[selectedValue];
  }
  else {
    document.getElementById("linenamead").value = "";
    document.getElementById("lineonead").value = "";
    document.getElementById("linetwoad").value = "";
    document.getElementById("linethreead").value = "";    
    document.getElementById("postcodead").value = "";
  }
  
}
mainSel();

</script>
<script src="DSVFinder.json"></script>
<script>  
function dsvResult() {
        
    var dsvSel = document.getElementById("dsvSel").selectedIndex;
    document.getElementById("dsvSelBig").style.display = "block";    
    var linenamead = thedata.features[dsvSel].properties.Name;
    var dsvinfo = thedata.features[dsvSel].properties.Street_Address;
    var dsvinfo = dsvinfo.replace(/,/g, '@');
    
      var lines = 0;
      var lineaddr = ["","","",""]; 
      var startletter = -1;
      for(i=0;i<=dsvinfo.length;i++){
          var letter = dsvinfo.substring(i,i+1);
          if(letter === "@" || i == dsvinfo.length){
              
              lineaddr[lines] = dsvinfo.substring(startletter+1,i); 

              startletter=i+1;
              lines=lines+1;
          }      
      }      
    lineaddr[2] = thedata.features[dsvSel].properties.Town;
    if(lineaddr[2]==lineaddr[1]){lineaddr[2] = "";}
    lineaddr[3] = thedata.features[dsvSel].properties.Postal_Code;
    document.getElementById("linenamead").value = linenamead; document.getElementById("linenamead").readOnly = true;
    document.getElementById("lineonead").value = lineaddr[0]; document.getElementById("lineonead").readOnly = true;
    document.getElementById("linetwoad").value = lineaddr[1]; document.getElementById("linetwoad").readOnly = true;
    document.getElementById("linethreead").value = lineaddr[2]; document.getElementById("linethreead").readOnly = true; 
    document.getElementById("postcodead").value = lineaddr[3]; document.getElementById("postcodead").readOnly = true;  
}
</script>
