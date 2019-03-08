     
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
.chosen:nth-child(2) {
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
$idcategory = 0;
$idsubcategory = 9999; 
$searchcritter = "";
$searcher = 0;
if (isset($_POST['mydrop']) && $_POST['mydrop'] !== "All") {
  $idcategory = $_POST['mydrop'];
  $searcher = 1;
}
if (isset($_POST['mydroplet']) && $_POST['mydroplet'] !== "All"){
  $idsubcategory = $_POST['mydroplet']; //great, set to post
  $searcher = 2;  
}
if (isset($_POST['insearch']) ){
  $searchcritter = $_POST['insearch']; //great, set to post
  $searcher = 3;  
}
 

$sqlcat = "SELECT * FROM categories";  
$resultcat = $connex->query($sqlcat);  
?>
<nav>
<form action="index.php" method="post"  class="navi">
<label>Categories</label>
<select name="mydrop" class="mydrop" onchange="this.form.submit();">
  <option value="All">All</option>
<?php 
  
  while($row = $resultcat->fetch_assoc()) { 
    if($row['catid'] == $idcategory) {
      $searchcat = $row['name'];
      echo"<option value='".$row['catid']."' selected>".$row['name']."</option>";
    }
    else {
      echo"<option value='".$row['catid']."'>".$row['name']."</option>";
    }
  }
  
?>
</select>
</form>

<form action="index.php" method="post" class="navi">
<label>Subcategories</label>
<select name="mydroplet" class="mydrop" onchange="this.form.submit();">  
   <option value="All">All</option>
  <?php

  if($idcategory > 0 ) {     
    
    //fetch the list from the database 
    $sql3 = "SELECT * FROM subcategories WHERE catid = '$idcategory'";  
    $result3 = $connex->query($sql3);  
    while($row = $result3->fetch_assoc()) { 
        if($row['subid'] == $idsubcategory) {
          $searchsubcat = $row['name'];
          echo"<option value='".$row['subid']."' selected>".$row['name']."</option>";
        }
        else {
          echo"<option value='".$row['subid']."'>".$row['name']."</option>";
        }
    }
  }
  ?>
</select>
<input name="mydrop" value="<?php echo $idcategory; ?>" hidden>
</form>
<form class="navi" action='toprocket.php' method="post">Search <br>
  <input type="text" name="insearch" class="insearch">
  <button class="inbutton">go</button>
</form>
</nav>
<br>
<?php

// Grab the profile data from the database to ready php catches
if (!empty($_POST['idviewgo'])) {
  
  $itemid = $_POST['idviewgo'];
  $stampid=0;
  //grab item info
  $query3 = "SELECT * FROM letters WHERE letterid = $itemid";    
  $result3 = mysqli_query($connex, $query3);
  
  while ($rower3 = mysqli_fetch_array($result3)) {
      
      echo"<div class='displayitem' id='displayitem'>";
      echo "<div class='displaybox' id='displaybox'>";
      echo "<h4>To: Father Christmas</h4></b>";
      echo "<h4><b>Subject: ".$rower3['lettername']."</h4></b>";
            //display brand...............................
      if($stampid > 0){ 
          echo "<div class='trimbrand'><img  class='displaybrand' src='" . MM_UPLOADPATH . $stamppic .
        "' alt='Item Picture' ></div>";
      }
      //pciture....................................
      if (!empty($rower3['picture'])) {
         echo "<img  class='displaypic' src='" . MM_UPLOADPATH . $rower3['picture'] .
        "' alt='Item Picture' >";
        }
      else {
         echo "<img class='displaypic' src='" . MM_UPLOADPATH . "bulb.png" .
        "' alt='Item Picture'>";
        }

      //display text................................
      
      
      echo "<p><br>".$rower3['description']."</p>";
      

      echo "<br>Quantity:&nbsp;&nbsp;<input name='inputqty' type='number' id='inputqty' class='inputqty' value='1'>";
      echo "<br><br><b>Estimated price: R".$rower3['askprice'];
      echo "</b><br><br>Add to :<button onclick='addCart(".$rower3['letterid'].");'>Cart</button>";
      echo "<button onclick='addWishlist(".$rower3['letterid'].");'>Wish</button>";
      echo "<a href='toprockets.php'><span class='redx' onclick='stopShow();'>X</span></a>";
      echo "</p></div>";
      echo "</div>";
      
    }
    $searcher=9;
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


  </main>
  <form action='toprockets.php' id='myFormView' method='post'>
    <input type='number' id='idviewgo' name='idviewgo' style='width: 40px' hidden>
  </form>
  <form action='index.php' id='myFormAdd' method='post'>
    <input type='number' id='idstockadd' name='idstockadd' style='width: 40px' hidden>
    <input type='number' id='qtyadd' name='qtyadd' style='width: 40px' hidden>
    <input type='text' id='colouradd' name='colouradd' value="0" style='width: 40px' hidden>
    <input type='text' id='sizeadd' name='sizeadd' value="0" style='width: 40px' hidden>
    <input type='text' id='statusupdate' name='statusupdate' style='width: 40px' hidden>
  </form>
</body>
</html>

<script>
function myView(littlejohn) {
    document.getElementById("idviewgo").value = littlejohn;
    document.getElementById("myFormView").submit();
}
function addCart(littlejohn) {
    addSpice();    
    document.getElementById("idstockadd").value = littlejohn;    
    document.getElementById("statusupdate").value = "Cart";
    document.getElementById("myFormAdd").submit();
}
function addWishlist(littlejohn) {
    addSpice();    
    document.getElementById("idstockadd").value = littlejohn;    
    document.getElementById("statusupdate").value = "Wishlist";
    document.getElementById("myFormAdd").submit();
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
}
</script>

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
  min-width:600px;        /* Suppose you want minimum width of 1000px */
  width: auto !important;  /* Firefox will set width as auto */
  width:600px;            /* As IE6 ignores !important it will set width as 1000px; */
 background: linear-gradient(
      to bottom,
      rgba(0, 0, 0, 0),
      rgba(20, 20, 20,0.1)
    )
 height: 100%;
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

.displayitem {
  position: absolute;
  background-color: ghostwhite;
  opacity: 1;
  display: box;
  width: 800px;  
  display: block;
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
  width: 650px;
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
.displaybox {
  margin-left: 30px;
  float: left;
  width: 500px;   
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
  height: 100px;
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


p {
  color: maroon;
}
label {
  width: 125px;
}
.redx {
  color: red;
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
@media screen and (max-width: 1100px) {
  .displayitem {
    left: 20px;
    width: 96%;
  }
  .displaybox {
    padding-top: 30px;
    width: 300px;
 
  }
  .displaypic {
    width: 600px;
  }
  .trimbrand {
   left: 550px;
  }
}
@media screen and (max-width: 820px) {
  .displayitem {
    left: 20px;
    width: 96%;
    min-width: 580px;

  }
  .displaybox {
    padding-top: 30px;
    width: 300px;
  }
  .displaypic {
    width: 500px;
    
    margin-left: 0px;
  }
  .trimbrand {
   left: 330px;
  }
  .redx {
    clear: left;
  }
}
@media screen and (max-width: 550px) {
   .displaypic {
      width: 500px;
      height: 50%;

  }
    .trimbrand {
   left: 200px;
  }

}


</style>

