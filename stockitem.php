    
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shop Floor</title>

  <!-- Responsive viewport tag, tells small screens that it's responsive -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Normalize.css, a cross-browser reset file -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/normalize/4.1.1/normalize.css" rel="stylesheet">

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
    
<?php 

  require_once('appvars.php');
  require_once('basecamp/connectvars2.php');

$connex = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
// Check connection
if ($connex->connect_error) {
    die("Connection failed: " . $connex->connect_error);
} 


//sort out categories search
$brandid  = 0;
$idcategory = 0;
$idsubcategory = 9999; 
$searchcritter = "";
$searchcat = "All";
$searchsubcat = "All";
$searcher = 0;
//category search only
if (isset($_GET['mydrop']) && $_GET['mydrop'] !== "All") {
  $searchcat = $_GET['mydrop'];
  $searcher = 1;
}
//search subcat only
if (isset($_GET['mydroplet']) && $_GET['mydroplet'] !== "All"){
  $searchsubcat = $_GET['mydroplet']; 
  $searcher = 2;  
}
//search cat and subcat
if ((isset($_GET['mydrop']) && $_GET['mydrop'] !== "All") && (isset($_GET['mydroplet']) && $_GET['mydroplet'] !== "All")) {
  $searchsubcat = $_GET['mydroplet']; 
  $searchcat = $_GET['mydrop'];
  $searcher = 2.5;  
} 
 
//echo $searcher;
$sqlcat = "SELECT * FROM categories";  
$resultcat = $connex->query($sqlcat);  
?>
<nav>
<form action="index.php" method="get"  class="navi">
<label>Categories</label>
<select name="mydrop" class="mydrop" onchange="this.form.submit();">
  <option value="All">All</option>
<?php 
  
  while($row = $resultcat->fetch_assoc()) { 
    if($row['name'] == $searchcat) {
      $searchcat = $row['name'];
      $searchcatid = $row['catid']; //useful for subcat listing
      echo"<option value='".$row['name']."' selected>".$row['name']."</option>";
    }
    else {
      echo"<option value='".$row['name']."'>".$row['name']."</option>";
    }
  }
  
?>
</select>
</form>

<form id="mydroplet" action="index.php" method="get" class="navi">
<label>Subcategories</label>
<select name="mydroplet" class="mydrop" onchange="this.form.submit();">  
   <option value="All">All</option>
  <?php
    
    $idsubcatlast = "";
    //fetch the list from the database 
    
    if($_GET['mydrop'] == "All" OR empty($_GET['mydrop'])) { 
        $sql3 = "SELECT * FROM subcategories ORDER by name";  }
    else {
        $sql3 = "SELECT * FROM subcategories WHERE catid = '$searchcatid' ORDER by name";  
    }
    $result3 = $connex->query($sql3);  
    while($row = $result3->fetch_assoc()) { 
        if($row['name'] == $searchsubcat) {
          $searchsubcat = $row['name'];
          $searchsubcatid = $row['subid'];
          echo"<option value='".$row['name']."' selected>".$row['name']."</option>";
        }
        else {
          if($row['name'] != $subcatlast) {
            echo"<option value='".$row['name']."'>".$row['name']."</option>";
          }
          $subcatlast = $row['name'];
        } //
    }
  
  ?>
</select>
<input name="mydrop" value="<?php echo $searchcat; ?>" hidden>
</form>
<form class="navi" action='index.php' method="post">Search <br>
  <input type="text" name="insearch" class="insearch">
  <button class="inbutton">go</button>
</form>
</nav>
<br>
<?php
if (!empty($_POST['idviewgo'])) {
  $itemid = $_POST['idviewgo'];
}
if (!empty($_GET['boxid'])) {
  $itemid = $_GET['boxid'];
}
//slight technical debt - 
$bidcount = 0;
$bidamount = 0;
$highbid[1] = 0;
$highbid[2] = 0;
$highbid[3] = 0;
$highbid[4] = 0;
$highid[1] = 0;
$highid[2] = 0;
$highid[3] = 0;
$highid[4] = 0;
if (!empty($_POST['bidamount'])) {
  $itemid = $_POST['bidstockid'];
  $bidamount = $_POST['bidamount']; 
}

?>
<script>
  <?php     

      $js_mars = json_encode($itemid);
      echo "var itemid = ". $js_mars . ";\n";  
      echo "var appleCart = '?boxid='+itemid;\n";
  ?>
</script>

<?php 


// Grab the profile data from the database to ready php catches
if (!empty($_POST['idviewgo']) OR !empty($_GET['boxid']) OR !empty($_POST['bidamount'])) {

  $choice1=0;$choice2=0;
  //grab item info
  $query3 = "SELECT * FROM stocktypes WHERE stockid = $itemid";    
  $result3 = mysqli_query($connex, $query3);
  
  while ($rower3 = mysqli_fetch_array($result3)) {
      $brandid = $rower3["brandid"];
      $artistid = $rower3["artistid"];
      $brandname = "";
      $artistname = "";
      //load brands
          $sql4 = "SELECT * FROM brandnames WHERE brandid = '$brandid'";      
          $result4 = $connex->query($sql4);      
          while($row4 = $result4->fetch_assoc()) {               
              $brandname = $row4["brandname"];
              $brandpic = $row4["brandpic"];
          }
      //load brands
          $sql5 = "SELECT * FROM artists WHERE artistid = '$artistid'";      
          $result5 = $connex->query($sql5);      
          while($row5 = $result5->fetch_assoc()) {           
              $artistname = $row5["artistname"];
              $artistpic = $row5["artistpic"];
          }

      echo"<div class='displayitem inline' id='displayitem'>";
      echo "<div class='displaybox' id='displaybox'><h4><b>Product: ".$rower3['stockname'];
      echo "</h4></b>";
            //display brand...............................
      if($brandid > 0){ 
          echo "<div class='trimbrand'><img  class='displaybrand' src='" . MM_UPLOADPATH . $brandpic .
        "' alt='".$brandname."' ></div>";
      }
    
      //pciture....................................
      if (!empty($rower3['picture'])) {
         echo "<img  class='displaypic' src='" . MM_UPLOADPATH . $rower3['picture'] .
        "' alt='Picture not loaded.' >";
        }
      else {
         echo "<img class='displaypic' src='" . MM_UPLOADPATH . "images.jpg" .
        "' alt='Item Picture'>";
        }

      if ($artistid > 0){
          echo "<div class='trimartist' onclick='myViewArtist(".$artistid.");'><span class='artname'>".$artistname."</span><img  class='displayartist' src='" . MM_UPLOADPATH . $artistpic .
        "' alt='".$artistname."' ></div>";
      }

      //display text................................      
      
      echo "<p><br>".$rower3['description']."</p>";
      echo "<br><b>Applications:</b><br>";
      echo "<p>".$rower3['applications']."</p>";

      echo "<br><b>Specifications:</b><br>";
      echo "<p>".$rower3['specifications']."</p>";
      $choice1 = $rower3['choiceone']; 
      if($choice1>0){
        $choicename1 = "";              
          $sqlchoice = "SELECT * FROM featoptgroups WHERE groupid = $choice1";  
          $resultchoice = $connex->query($sqlchoice);           
          while($rowch = $resultchoice->fetch_assoc()) { 
              $choicename1 = $rowch["groupname"];
          }
        echo "<label>".$choicename1."</label>";
        echo "<select id='inputcolour'>";

          $sqlcat = "SELECT * FROM featmappings WHERE groupid = $choice1 AND stockid = $itemid";  
          $resultcat = $connex->query($sqlcat); 
          
          while($rowcat = $resultcat->fetch_assoc()) {  
              if($rowcat['optionname'] == $stockcolour) {
                echo"<option value='".$rowcat['optionname']."' selected>".$rowcat['optionname']."</option>";
              }
              else {
                echo"<option value='".$rowcat['optionname']."'>".$rowcat['optionname']."</option>";
              }         
          }

        echo "</select>";
      }
      else {echo "<input id='inputcolour' value='' hidden>";}
      $choice2 = $rower3['choicetwo'];
      if($choice2>0){   
        $choicename2 = "";             
          $sqlchoice = "SELECT * FROM featoptgroups WHERE groupid = $choice2";  
          $resultchoice = $connex->query($sqlchoice);           
          while($rowch = $resultchoice->fetch_assoc()) { 
              $choicename2 = $rowch["groupname"];
          }
        echo "<br><label>".$choicename2."</label>";
        echo "<select id='inputsize'>";

          $sqlcat2 = "SELECT * FROM featmappings WHERE groupid = $choice2 AND stockid = $itemid";  
          $resultcat2 = $connex->query($sqlcat2); 
        
          while($rowcat2 = $resultcat2->fetch_assoc()) {
              if($rowcat2['optionname'] == $stocksize) {
                echo"<option value='".$rowcat2['optionname']."' selected>".$rowcat2['optionname']."</option>";
              }
              else {
                echo"<option value='".$rowcat2['optionname']."'>".$rowcat2['optionname']."</option>";
              }         
        }

        echo "</select><br>";  
      }
      else {echo"<input id='inputsize' value='' hidden>";}
      echo "<br>Quantity:&nbsp;&nbsp;<input name='inputqty' type='number' id='inputqty' class='inputqty' value='1'>";
      echo "<br><br><b>Retail price: R".$rower3['retailprice'];
      echo "</b><br><br>Add to :<button onclick='addCart(".$rower3['stockid'].");'>Cart</button>";
      echo "<button onclick='addWishlist(".$rower3['stockid'].");'>Wish</button>";
      echo "<span class='redx' onclick='stopShow();'>X</span><br>";     
   
      
      echo "</div>";      
      echo "<div id='disqus_thread' class='disqus_thread'></div>";
         echo "<span id='disqus_border'><br><br></span>";
      echo "</div>";

      if($rower3['auctionyesid'] == 1) {

          if ($bidamount>0 && $useride > 0) {
            // Make sure the entry is not duplicated
            $query = "SELECT * FROM transactbids WHERE stockid = '$itemid' AND amount = '$bidamount' AND buyerid = '$useride'";
            $data = mysqli_query($connex, $query);
            if (mysqli_num_rows($data) == 0) { 
              //insert transaction
              $query = "INSERT INTO transactbids (stockid, buyerid, amount) VALUES ('$itemid', '$useride', '$bidamount')";
              if(mysqli_query($connex, $query)) {
                // Confirm success with the user
                echo '<p class="mirror">Your bid was added.</p>';  
              }
              else {
                echo '<p class="mirror">Error loading data.</p>';
              }  
            }
          }
          else {
            echo "Houston, we have a problem!";
          }
            
            //fetch the list from the database and re-sort, delete last entry
            $sqlbidchk = "SELECT * FROM transactbids WHERE stockid = '$itemid'";  
            $resultchk = $connex->query($sqlbidchk);  
            while($rowchk = $resultchk->fetch_assoc()) { 
              $bidcount = $bidcount +1; 
              $bid  = $rowchk["amount"];
              $buyerdude  = $rowchk["buyerid"];
              if($bid > $highbid[1]){
                $highbid[4] = $highbid[3];
                $highbid[3] = $highbid[2];
                $highbid[2] = $highbid[1];      
                $highbid[1] = $bid;
                $highid[4] = $highid[3];
                $highid[3] = $highid[2];
                $highid[2] = $highid[1];      
                $highid[1] = $buyerdude;      
              }
              elseif($bid > $highbid[2]){
                $highbid[4] = $highbid[3];
                $highbid[3] = $highbid[2];
                $highbid[2] = $bid;
                $highid[4] = $highid[3];
                $highid[3] = $highid[2];
                $highid[2] = $buyerdude;
              }
              elseif($bid > $highbid[3]){
                $highbid[4] = $highbid[3];
                $highbid[3] = $bid;
                $highid[4] = $highid[3];
                $highid[3] = $buyerdude;
              }
            } 

            if($highbid[4]>0){
              $query = "DELETE FROM transactbids WHERE stockid = '$itemid' AND amount = '$highbid[4]' LIMIT 1";
              if(mysqli_query($connex, $query)) {}
            }
            
          

          echo "<div class='inline bidding'>";
          echo "<table>";
          echo "<tr><b><u>Top bids</u><b><tr>";
          echo "<tr><td class='tablebuyer'><u>Buyer</u></td><td><u>Amount</u></td></tr>";
          //fetch the list from the database 
          for($j=1;$j<=3;$j++){
            if($highbid[$j]>0){echo "<tr><td  style='cursor:pointer;' onclick='myView(".$highid[$j].");'>Buyerid-".$highid[$j]."&nbsp;&nbsp;</td><td class='flotright'> ".$highbid[$j]."</td></tr>";}
          }
            
          
          echo "</table><br>";
          echo "<form action='' method='post'>";
          echo "<input type='text' name='bidstockid' value='".$itemid."' hidden>";
          echo "Bid:<input type='number' class='hellobid' name='bidamount' value='0'><button>Bid</button>";
          echo "</form>";
          echo "</div>";
      }
      
    }
    
    $searcher=9;
}

if(isset($_POST['qtyadd']) && $_POST['qtyadd'] < 0){
    echo "The quantity entered is negative. Oops!";
    $_POST['idstockadd'] = null;
}

// Grab the profile data from the database to ready php catches
if (isset($_POST['idstockadd']) && isset($_COOKIE['firstname']) && isset($_COOKIE['username']) ) {
  
  $itemidadd = $_POST['idstockadd'];
  $qtyadd = $_POST['qtyadd'];
  $orderstatus = $_POST['statusupdate'];



  $quest =  "INSERT INTO orders(userid, stockid, qty, orderstatus) VALUES ('$useride','$itemidadd', '$qtyadd', '$orderstatus')";  
      if ($connex->query($quest) === TRUE) {
         echo "<br>Item successfully added.";
      } 
      else {
          echo "Error: " . $itemidadd. "<br>" . $connex->error;
      }

}
elseif (isset($_POST['idstockadd']) && !isset($_COOKIE['firstname']) && !isset($_COOKIE['username']) ) {
  echo "You are not logged in.";
}


$connex->close();

//<embed src="buyerlist.php" width="300" height="400">
  ?>
<script>
  <?php  
    
      $js_mars = json_encode($login);
      echo "var loggedIn = ". $js_mars . ";\n";       
    
  ?>
</script>
<?php 

?>

   <div id="message" class="message" onclick="hideMessage();"></div>
  </main>
  <form action='index.php' id='myFormView' method='post'>
    <input type='number' id='idviewgo' name='idviewgo' style='width: 40px' hidden>
  </form>
  <form action='index.php' id='myFormAdd' method='post'>
    <input type='number' id='idstockadd' name='idstockadd' style='width: 40px' hidden>
    <input type='number' id='qtyadd' name='qtyadd' style='width: 40px' hidden>
    <input type='text' id='colouradd' name='colouradd' value="0" style='width: 40px' hidden>
    <input type='text' id='sizeadd' name='sizeadd' value="0" style='width: 40px' hidden>
    <input type='text' id='statusupdate' name='statusupdate' style='width: 40px' hidden>
  </form>
  <form action='viewprofile.php' id='youFormView' method='post'>
   <input type='number' id='idview' name='ordnumber' style='width: 40px' hidden>
  </form>
  <br>

</body>
</html>

<script>
function myView(littlejohn) {
    document.getElementById("idviewgo").value = littlejohn;
    document.getElementById("myFormView").submit();
}
function addCart(littlejohn) {
  if(checkLogin()){   
    addSpice();    
    document.getElementById("idstockadd").value = littlejohn;    
    document.getElementById("statusupdate").value = "Cart";
    document.getElementById("myFormAdd").submit();
  }
}
function addWishlist(littlejohn) {
  if(checkLogin()){   
    addSpice();    
    document.getElementById("idstockadd").value = littlejohn;    
    document.getElementById("statusupdate").value = "Wishlist";
    document.getElementById("myFormAdd").submit();
  }
}

function addSpice() {
    var robinhood1 = document.getElementById("inputqty").value;
    var robinhood2 = document.getElementById("inputcolour").value;
    var robinhood3 = document.getElementById("inputsize").value;
    
    document.getElementById("qtyadd").value = robinhood1;
    document.getElementById("colouradd").value = robinhood2;
    document.getElementById("sizeadd").value = robinhood3;
}
function stopShow() {
   document.getElementById("displayitem").style.display = "none";
   document.getElementById("mydroplet").submit();
}

function myView(littlejohn) {
  document.getElementById("idview").value = littlejohn;
  document.getElementById("youFormView").submit();
}
function  checkLogin() {
    document.getElementById("message").style.display = "block";
    document.getElementById("message").innerHTML = "Great choice!";
    if(loggedIn==1){
      return true;
    }
    if(loggedIn==0){      
      document.getElementById("message").innerHTML = "You need to login first. Ok? <button><a href='login.php'>Login</a></button>";
      document.getElementById("message").style.display = "block";
      return false;
    }
}
function hideMessage() {
    document.getElementById("message").style.display = "none";
}
</script>

<script>

/**
*  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
*  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/
/**
var disqus_config = function () {
this.page.url = 'https://www.rocketline.co.za/stockitem.php';  // Replace PAGE_URL with your page's canonical URL variable
this.page.identifier = appleCart; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
};
*/
(function() { // DON'T EDIT BELOW THIS LINE
var d = document, s = d.createElement('script');
s.src = 'https://www-rocketline-co-za.disqus.com/embed.js';
s.setAttribute('data-timestamp', +new Date());
(d.head || d.body).appendChild(s);
})();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>

<script type='text/javascript' src='//platform-api.sharethis.com/js/sharethis.js#property=5c54777583748d00113149ef&product=sop' async='async'></script>
<style type='text/css'>

html  {
  font-family: 'Roboto';
}
nav {
    background-image: url('images/wood.jpg');
    border: white 2px solid;
    background-repeat: repeat-x;
    box-shadow: 2px 2px 2px;
}

body {  
          /* As IE6 ignores !important it will set width as 1000px; */
 background: linear-gradient(
      to bottom,
      rgba(0, 0, 0, 0),
      rgba(20, 20, 20,0.1)
    ) 
 min-width: 640px;
}

h1 {
  font-size: 30px;
  color: white;
}
.center {
  text-align: center;
}
.transbox {
    background-image: url('images/floor.jpg');
    background-repeat: repeat-x;
    opacity: 0.9;
    border: solid 1px black;
    
}
.headernav {
  min-width: 640px;
}
nav {
    background-color: lightgray;
    width: 500px;
    border-radius: 5px;
    color: white;
    padding: 2px;
    text-align: center;
}
.navi {
    display: inline-block;
    width: 160px;
}
.insearch {
  width: 100px;
  height: 23px;
  color:  black;
}
.inbutton {
  width: 30px; 
  height: 23px;
  vertical-align: bottom;
}
.inbutton:hover {
  color: lightgreen;
}
.nextline {
  clear: left;
}
button {
    background-color: orange;
    color: white;
    border: white 2px solid;
}
button:hover {
  background-color: black;
  color: white;
}
select {
  width: 100px;
  background-color: ghostwhite;
  border-radius: 3px;
  color: black;
}
.inputin {
  width: 40px;
}
.inputqty {
  width: 40px;
  background-color: orange;
  color: white;
}
main {
  margin-left: 50px;
  margin-right: 50px;

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

.displaybox {
  padding-left: 20px;
  float: left;
  width: 50%;
  background-color: ghostwhite;
}
.displaybrand {
  max-width: 200px;
  max-height: 100px;
  background-color: white;
  position: relative;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);  
}
.trimbrand {
  width: 200px;
  height: 250px;
  top:  -40px;
  align-content: center;
    border: 2px solid white;
    align-content: center;
   position: relative;
   left: 750px; 
   transition: transform .5s ease-in-out;
   background-color: white;
}
.trimbrand:hover {
  transform: scale(1.1);
}
.displayartist {
  border-radius: 10%;
  vertical-align: !important middle;
  min-height: 120px;  
  max-width: 100px; 
  position: relative;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -40%);  
}
.trimartist {
  width: 150px;
  height: 220px;
  position: relative;
   left: 450px;    
  overflow: hidden;  
  align-content: center;
   float: right;  
   transition: transform 1s ease-in-out;
}
.trimartist:hover {
  transform: scale(1.8);
}
.artname {
  color: orange;
}
.bidding {
  position: fixed;
  left: 1070px;
  top: 350px;
  width: 220px;
  background-color: white;
  border: gray 3px solid;
  padding: 10px;
  color:  orange;
}
.mirror {
  position: absolute;
  left: 1070px;
  top: 550px;
}

.displayitem {
  position: absolute;
  background-color: ghostwhite;
  opacity: 1;
  width: 1000px;  
  top: 140px;
  left: 50px;
  border: white solid 3px;
  border-radius: 6px;
  padding: 5px;
  box-shadow: 2px 2px 2px; 
  font-family: arial;
  border: orange 2px solid;
}
.displayitem:hover {
  box-shadow: 4px 4px 4px;
}
.displaypic {  
  width: 880px;  
  top: -40px;
  position: relative;
  transition: transform 1.5s ease-in-out;
  border: 1px solid gray;
  background-color: white;
  margin-top: 40px;
  margin-left: 30px;
}
.displaypic:hover {
  transform: scale(1.4);  
}
@media screen and (max-width: 1300px) {
  .bidding {
    left: 800px;
    top: 250px;
    z-index: 1;
  }
  .displayitem {
    z-index: 0.9;
  }
}
@media screen and (max-width: 1100px) {
  .displayitem {
    left: 20px;
    width: 96%;
    min-width: 600px;
    margin-bottom: 50px;
    
  }
  .displaybox {
    padding-top: 30px;
    width: 300px; 
  }
  .displaypic {
    width: 800px;
    margin-left: 0px;
  }
  .trimbrand {
   left: 550px;
  }
  .trimartist {
    left: 350px;
  }
  .bidding {
    left: 70%;
  }
}
@media screen and (max-width: 920px) {

  .bidding {
    right: 20%;
    
  }
  .displayitem {
    left: 10px;  

  }
  .displaybox {
    padding-top: 30px;
    width: 300px;
  }
  .displaypic {   
    width: 680px;    
  }
  .trimbrand {
   left: 330px;
  }
  .trimartist {
    left: 150px;
  }
  .redx {
    clear: left;
  }
}
@media screen and (max-width: 760px) {
   .displaypic {
      width: 480px;
  }
 
  .trimbrand {
   left: 200px;
  }
 .bidding {
    left: 55%;
    
  }
}

.tablebuyer {
  width: 120px;
}
.hellobid {
  width: 100px;
  margin-left: 10px;
}
p {
  color: maroon;
}
label {
  width: 125px;
}
.redx {
  color: red;
  text-align: center;
  float: right;
  background-color: white;
  border: 1px gray solid;
  width: 25px;
  height: 25px;
  box-sizing: border-box;
  border-radius: 3px;
  cursor: pointer;
  text-shadow: center;
  
}
.redx:hover{
  background-color: red;
  color: white;
}
.flotright {
  float: right;
}
.disqus_thread {
  background-color: lightgray;
}
.message {
  display: none;
  background-color: red;
  color: white;
  border: 1px solid white;
  padding: 5px;
  position: fixed;
  left: 30%;
  bottom: 10%;
  width: 250px;
  cursor: pointer;
  text-align: center;
  box-shadow: 2px 2px 2px gray;
  z-index: 99;
}
</style>


