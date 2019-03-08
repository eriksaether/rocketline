<?php
// Grab the profile data from the database to ready php catches
if (isset($_POST['idviewgo']) || isset($_GET['idviewgo'])) {

  if(isset($_POST['idviewgo'])){
    $itemid = $_POST['idviewgo'];
  }
  if(isset($_GET['idviewgo'])){
    $itemid = $_GET['idviewgo'];
  }
  $stockqty = 1;
  //check if loading order related stock item.. Need to provide colour and size to below
  if (isset($_POST['idviewitem'])) {
   $idedit = $_POST['idviewitem'];
   
   $sqledit2 = "SELECT * FROM orders WHERE detailid='$idedit' ";
   $editstock2 = $connex->query($sqledit2);         
   while($rowe2 = $editstock2->fetch_assoc()) {                   
            $stockcolour = $rowe2["colour"];  
            $stocksize = $rowe2["size"]; 
            $stockqty = $rowe2["qty"]; 
   }
 
   
  }
  $choice1=0;$choice2=0;
  //grab item info
  $query2 = "SELECT * FROM stocktypes WHERE stockid = $itemid";    
  $result2 = mysqli_query($connex, $query2);
  
    while ($rower = mysqli_fetch_array($result2)) {
      $brandid = $rower["brandid"];
      $artistid = $rower["artistid"];

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

      echo"<div class='displayitem' id='displayitem' >";
      //picture....................................................
      if (!empty($rower['picture'])) {
         echo "<div class='trimpic'><img  ondblclick='myViewee(".$rower['stockid'].");' class='displaypic' src='" . MM_UPLOADPATH . $rower['picture'] .
        "' alt='Item Picture' ></div>";
        }
       else {
         echo "<div class='trimpic'><img class='displaypic' src='" . MM_UPLOADPATH . "88.png" .
        "' alt='Stock Picture' ondblclick='myViewee(".$rower['stockid'].");'></div>";
        }
       //text box ........................ ........................
      echo "<div class='displaybox' id='displaybox'>";
      echo "<span class='whitex'><b>".$rower['stockname']."</b></span>";
      if($brandid > 0){ 
          echo "<div class='trimbrand'><img  class='displaybrand' src='" . MM_UPLOADPATH . $brandpic .
        "' alt='".$brandname."' ></div>";
      }
      elseif ($artistid > 0){
          echo "<div class='trimartist' onclick='myViewArtist(".$artistid.");'><img  class='displayartist' src='" . MM_UPLOADPATH . $artistpic .
        "' alt='".$artistname."' ><span class='artname'>".$artistname."</span></div>";
      }
      else {
          echo "<div class='trimbrand'><img  class='displaybrand' src='' alt='' ></div>";
      }
      echo "<br>";
      echo "<span class='light'>".$rower['stockcode']."</span>";
      echo "<div class='dash-text-info' ><br><b>Order:</b><br>";
      $choice1 = $rower['choiceone'];   
      if($choice1>0){
        $choicename1 = ""; 
             
          $sqlchoice = "SELECT * FROM featoptgroups WHERE groupid = $choice1";  
          $resultchoice = $connex->query($sqlchoice);           
          while($rowch = $resultchoice->fetch_assoc()) { 
              $choicename1 = $rowch["groupname"];
          }   
        echo "<br><label>".$choicename1."</label>";
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
      $choice2 = $rower['choicetwo']; 
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
      echo "<br>Quantity:&nbsp;&nbsp;<input name='inputqty' type='number' id='inputqty' class='inputqty' value='".$stockqty."'>";
      echo "<br><br><b>Retail price: R".$rower['retailprice'];
      echo "</b><br><br>Add to :<button onclick='addCart(".$rower['stockid'].");'>Cart</button>";
      echo "<button onclick='addWishlist(".$rower['stockid'].");'>Wish</button>";
      echo "<button onclick='addWishlist(".$rower['stockid'].");'>Gift</button></div>";

      echo "<div class='dash-text' ><br>".$rower['description']."</div>";
      echo "<div class='dash-text' ><br><b>Applications:</b><br>".$rower['applications']."</div>";
      echo "<div class='dash-text' ><br><b>Specifications:</b><br>".$rower['specifications']."<br>";
      echo "<br><i>Weight approximately:".number_format((float)$rower['weight'], 1, '.', '')."kg</i><br>";
      echo "</div>";
      
      echo "<span class='dashes dash1' onmouseover='showOne(0,5);' onmouseout='showOff(0,5);'>Info</span><span class='dashes dash2' onmouseover='showOne(0,6);' onmouseout='showOff(0,6);'>Apps</span><span class='dashes dash3' onmouseover='showOne(0,7);' onmouseout='showOff(0,7);'>Specs</span>";      
      echo "<span class='redx' onclick='stopShow();'>x</span>";
     
      echo "</div>";
      echo "</div>";


    }
}
//view artist
//require_once('viewartist.php');
?>
<style>

.picbox {  
  box-sizing: border-box;
  margin-bottom: 10px;  
  height: 200px;
  overflow: contain;
}

.displayitem {
  position: fixed;
  background-color: white;
  opacity: 0.99;
  display: box;
  width: 720px;
  height: 388px;
  top: 130px;
  left: 60px;
  border: orange solid 3px;
  border-radius: 6px;
  padding: 5px;
  box-shadow: 2px 2px 2px  lightgray; 
  font-family: arial;
    z-index: 1;
}

.displaybrand {
  max-width: 70px;
  max-height: 35px;
}

.trimbrand {
  width: 70px;
  height: 35px;
  overflow: hidden;  
  align-content: center;
    border: 2px solid white;
    align-content: center;
   float: right;  
   transition: transform .5s ease-in-out;
   zoom: 1.5;
}
.trimbrand:hover {
  transform: scale(1.2);
}
.displayartist {
  
  vertical-align: !important middle;
  min-height: 50px;  
  max-width: 75px; 
  position: relative;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
.trimartist {
  border-radius: 10%;
  width: 100px;
  height: 100px;
  overflow: hidden;  
  align-content: center;
   float: right;  
   transition: transform 1s ease-in-out;
}
.trimartist:hover {
  transform: scale(2);
}

.trimpic {
  width: 350px;
  height: 350px;
  overflow: hidden;  
  align-content: center;
    border: 2px solid lightgray;
    align-content: center;
    margin-left: 10px;
    margin-top: 10px;
    transition: transform .5s ease-in-out;
}
.trimpic:hover {
  transform: scale(1.1);
}
.artname {
  position: relative;
  top: -20px;
  left: 10px;
  z-index: 0.9;
  color: orange;
}
.displaypic {
  cursor: pointer;
  vertical-align: !important middle;
  min-height: 100%;  
  max-width: 150%; 
  height: 300px;  
  position: relative;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);    
  background-color: white;
}
.displaypic:hover {
  border: white solid 0px;
}
.displaybox {
  position: relative;
  left: 400px;
  top: -350px;
  display: box;
  width: 270px;
  height: 350px;
  z-index: 0.9;
}


.displayitem:hover {
  box-shadow: 2px 2px 2px gray;
}
@media screen and (max-width: 800px) {
  .displayitem {
    left: 30px;
    width: 520px;
    height: 800px;
    display: block;
    padding-left: 50px;
    padding-right: 50px;
    padding-top: 20px;
    opacity: 1;
    position: absolute;
  }
  .displaybox {
    top: 0px;
    left: 50px;
  }

}
.dashes {
  position: absolute;
  width: 45px;
  padding: 5px;
  padding-top: 3px;
  padding-bottom: 3px;
  color: white;
  background-color: gray;
  border-radius: 3px;
  margin: 1px;
  top: 330px;
  cursor: pointer;
  text-align: center;
}
.dash1 {
  left: 15px;
}
.dash2 {
  left: 65px;
}
.dash3 {
  left: 115px;
}
.dashes:hover {
  background-color: orange;
}
.info {
  visibility: hidden;

  position: absolute;
    top: 30px;      

    right: 0px;
    background-color: white;
    color: maroon;
    padding-left: 20px;
    padding-right: 20px;
    visibility: hidden;
    opacity: 1;
    border-radius: 1px;
}
.dash-text-info {
  color: maroon;
}
.dash-text {
  visibility: hidden;
  position: absolute;
    top: 38px;      
    width: 280px;
    min-height: 290px;
    background-color: #ffc04c;
    color: maroon;
    padding-left: 20px;
    padding-right: 20px;
    visibility: hidden;
    opacity: 1;
    border-radius: 3px;
    border: orange 2px solid;
}
.whitex {
  color: orange;
}
.greyz {
  color: ghostwhite;
}
.redx {
  color: red;
  left: 220px;
  background-color: white;
  border: 1px gray solid;
  width: 25px;
  height: 25px;
  box-sizing: border-box;
  border-radius: 3px;
  cursor: pointer;
  position: absolute;
  top: 330px;
  text-align: center;
}
.redx:hover{
  background-color: red;
  color: white;
}
.inputqty {
  width: 40px;
  background-color: orange;
  color: white;
}
button {
  background-color: ghostwhite;
  border: ghostwhite solid 1px;
  cursor: context-menu;
}
label {
  width: 105px;
}
.light {
  color: lightgray;
  font-size: 12px;
}

</style>
<script type="text/javascript">
 
function myViewGo(littlejohn) {
  document.getElementById("idviewgo").value = littlejohn;
  document.getElementById("myFormViewGo").submit();
}
function stopShow() {
  document.getElementById("displayitem").style.display = "none";
}
function addCart(littlejohn) {
    if(checkLogin()){   
      addSpice();
      document.getElementById("statusupdate").value = "Cart";
      document.getElementById("idstockadd").value = littlejohn;
      document.getElementById("myFormAdd").submit();
    }
}
function addWishlist(littlejohn) {
    if(checkLogin()){   
      addSpice();
      document.getElementById("statusupdate").value = "Wishlist";
      document.getElementById("idstockadd").value = littlejohn;
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
function  checkLogin() {
    document.getElementById("message").style.display = "block";
    document.getElementById("message").innerHTML = loggedIn;
    if(loggedIn==1){
      return true;
    }
    if(loggedIn==0){      
      document.getElementById("message").innerHTML = "You need to login first. Ok? <button><a href='login.php'>Login</a></button>";
      document.getElementById("message").style.display = "block";
      return false;
    }
}


  function showOne(theCard, theDash) {
    var theBoulder = theDash;
    
    var elephant = document.getElementsByClassName('displaybox')[0];
    elephant.children[theBoulder].style.visibility = "visible";
    
    
  }
  function showOff(theCard, theDash) {
    var theBoulder = theDash;
    
    var elephant = document.getElementsByClassName('displaybox')[0];
    
    elephant.children[theBoulder].style.visibility = "hidden";   
    
  }


</script>