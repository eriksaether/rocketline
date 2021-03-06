<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shop Floor</title>

  <link type="text/css" href="main.css?version=1" rel="stylesheet">
  <!-- Responsive viewport tag, tells small screens that it's responsive -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Normalize.css, a cross-browser reset file -->
  <link href="" rel="stylesheet">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
  

</head>
<?php 
   // header("Cache-Control: no cache");
  //session_cache_limiter("private_no_expire");
?>
<style>
.chosen:nth-child(3) {
  color: yellow;
}
html  {
  font-family: 'Roboto';
}

body {  
  min-width:600px;        /* Suppose you want minimum width of 1000px */
  width: auto !important;  /* Firefox will set width as auto */
  width:600px;            /* As IE6 ignores !important it will set width as 1000px; */
      background-color: white;
}
main {
  margin-left: 45px;
  margin-right: 40px;
  color: red;
}
h1 {
  font-size: 30px;
  color: white;
}
.center {
  text-align: center;
}
.left {
  padding-left: 10px;
}

.transbox {
    background-image: url('images/fern-tile-green.jpg');
    background-repeat: repeat-x;
    opacity: 0.9;
    border: solid 1px black;
}
.transbox {
  min-width: 560px;
}
nav {
    background-image: url('images/wood.jpg');
    border: white 2px solid;
    background-repeat: repeat-x;
    box-shadow: 2px 2px 2px;
}
nav {
    background-color: lightgray;
    display: inline-block;
    width: 500px;
    border-radius: 5px;
    color: white;
    padding: 2px;
    text-align: center;
    margin-bottom: 5px;
}
/* search section inside nav */
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
$searchcat = "";
$searchsubcat = "";
$searcher = 0;
//category search only
if (isset($_POST['mydrop']) && $_POST['mydrop'] !== "All") {
  $idcategory = $_POST['mydrop'];
  $searcher = 1;
}
if (isset($_POST['mydroplet']) && $_POST['mydroplet'] !== "All"){
  $idsubcategory = $_POST['mydroplet']; //great, set to post
  $searcher = 2;  
}
//category search only
if (isset($_POST['idviewbrand']) && $_POST['idviewbrand'] !== 0) {
  $idbrand = $_POST['idviewbrand'];
  $searcher = 4;
}
//category search only
if (isset($_POST['idviewartist']) && $_POST['idviewartist'] !== 0) {
  $idartist = $_POST['idviewartist'];
  $searcher = 5;
}

if (isset($_POST['insearch']) ){
  $searchcritter = $_POST['insearch']; //great, set to post
  $searcher = 3;  
  $arr = explode("(", $searchcritter);
  $searchcritter = implode(" ", $arr);
  $arr = explode(")", $searchcritter);
  $searchcritter = implode(" ", $arr);
  $arr = explode("'", $searchcritter);
  $searchcritter = implode(" ", $arr);
  $arr = explode("-", $searchcritter);
  $searchcritter = implode(" ", $arr);
}
   // Grab the sort setting and search keywords from the URL using GET
   if(isset($_GET['sort'])) {
     $sort = $_GET['sort'];}
   else{
     $sort =  "";
   }
   if(isset($_GET['usersearch'])){
     $user_search = $_GET['usersearch'];}
     else{
     $user_search =  "";    
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

    
    
    //fetch the list from the database 
    $sql3 = "SELECT * FROM subcategories WHERE catid = '$idcategory'";  
    if($_POST['mydrop'] == "All" OR empty($_POST['mydrop'])) { $sql3 = "SELECT * FROM subcategories";  }
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
  
  ?>
</select>
<input name="mydrop" value="<?php echo $idcategory; ?>" hidden>
</form>
<form class="navi" action='index.php' method="post">Search <br>
  <input type="text" name="insearch" class="insearch">
  <button class="inbutton">go</button>
</form>

</nav>
   <!--- controller/selector -->
    <form action='' id='myTaskView' method='post' class="graydisplay">
      <div>
        <?php if (isset($useride)) {echo"<span class='red'><a class='glyphicon glyphicon-shopping-cart' href='cart.php'></a></span>"; }
        else{ echo"<span class='red'><a href='signup.php'>Register</a></span>";} ?>
      </div>
      <br>    
    </form>
        <form action='' id='myTaskView' method='post' class="graydisplay">
      <div>
        <?php if (isset($useride)) {echo"<span class='red'><a class='glyphicon glyphicon-star' href='wish.php'></a></span>"; }
        else{ echo"<span class='red'><a href='login.php'>Login</a></span>";} ?>
      
      </div>
      <br>    
    </form>
    <form action='' id='myTaskView' method='post' class="graydisplay">
      <div>
        <?php if (isset($useride)) {echo"<span class='red'><a href='brands.php'>Brands</a></span>"; }
          else{ echo"<span class='red'><a href='brands.php'>Brands</a></span>";} ?>
      </div>
      <br>    
    </form>
     <form action='' id='myTaskView' method='post' class="graydisplay">
      <div>
        <?php if (isset($useride)) {echo"<span class='red'><a href='artists.php'>Artists</a></span>"; }
        else{ echo"<span class='red'><a href='artists.php'>Artists</a></span>"; } ?>
      </div>
      <br>    
    </form>
<br>

<?php



//show stock item.........................>>
require_once('viewgo.php');
if (isset($_POST['idviewgo'])) {
    $searcher=9;
}

//show search results.................>>
$counter=0;
echo "<div class='blow'>" ;

  $counter=$counter+1;
  $modit = $counter%3;
  $sql2 = "SELECT * FROM stocktypes";
  $sqlst = "WHERE brandid > 0";

  if ($modit == 1) {echo "<div class='row'>" ;}
      //fetch the list from the database 
  if($searcher==9) {
       $user_search = "";
  }
  if($searcher==3) {
       $user_search = " WHERE stockname LIKE '%{$searchcritter}%' OR description LIKE '%{$searchcritter}%' OR category LIKE '%{$searchcritter}%' OR subcategory LIKE '%{$searchcritter}%'";
  }
  if($searcher==4) {
       $user_search = " WHERE brandid = '$idbrand'";
  }
  if($searcher==5) {
       $user_search = " WHERE artistid = '$idartist'";
  }
  elseif($searcher==2) {
       $user_search = " WHERE subcategory = '$searchsubcat'";        
  }

  elseif($searcher==1) {
       $user_search = " WHERE category = '$searchcat'";        
  }
  elseif ($searcher==0) {
       $user_search = " WHERE brandid = 0 AND artistid = 0 ORDER BY category DESC";
  }  
  $sql2 =  $sql2 . $user_search;

  // Calculate pagination information
  if (isset($_GET['page'])) {$cur_page = $_GET['page'];}
  else {$cur_page = 1;}

  $results_per_page = 30;  // number of results per page
  $skip = (($cur_page - 1) * $results_per_page); 
  $result = mysqli_query($connex, $sql2);  
  $total = mysqli_num_rows($result);
  $num_pages = ceil($total / $results_per_page);  

  //display some results
  // Query again to get just the subset of results
  $sql2 =  $sql2 . " LIMIT $skip, $results_per_page";
  $result = mysqli_query($connex, $sql2);

  while ($row = mysqli_fetch_array($result)) {   
      
      echo "<div class='col-4'>";      
        echo "<div class='box' style='cursor:pointer;'>";
        
        if (!empty($row['gridpic'])) {
         echo "<div class='trimbox'><img class='col-12 picboxes' src='" . MM_UPLOADPATH . $row['gridpic'] .
        "' alt='Stock Picture' onclick='myViewee(".$row['stockid'].");'></div>";
        }
        elseif (!empty($row['picture'])) {
         echo "<div class='trimbox'><img class='col-12 picboxes' src='" . MM_UPLOADPATH . $row['picture'] .
        "' alt='Stock Picture' onclick='myViewee(".$row['stockid'].");'></div>";
        }
        else {
         echo "<div class='trimbox'><img class='col-12 picboxes' src='" . MM_UPLOADPATH . "88.png" .
        "' alt='Stock Picture' onclick='myViewee(".$row['stockid'].");'></div>";
        }
        
          echo "<div class='boxtext' onclick='myViewGo(".$row["stockid"].");'><span class='left'><b>".substr($row['stockname'],0,22)."</b></span><span class='center mrprice' >R".$row['retailprice']."</span></div>"; 
          echo " <iframe width='230' src='https://www.youtube.com/embed/tgbNymZ7vqY'>
</iframe>";
        echo"</div>"; 
      echo"</div>"; 

           
  }

  if ($modit % 3 == 1) {echo"</div>";}  
  
echo "</div>" ;
$urhere = "index.php";
require_once('pager.php');
// Generate navigational page links if we have more than one page
if ($num_pages > 1) {
  $sort = 0;
  echo generate_page_links($urhere, $user_search, $sort, $cur_page, $num_pages);
  
}

//add stock item to cart.............>>
require_once('stockadd.php');

$connex->close();

//<embed src="buyerlist.php" width="300" height="400">

?>
 

  </main>
   <div class='righthand righturn'><span class="black">i</span><a href="letters.php">Letterbox</a></div>
   <form action='stockitem.php' id='myFormViewer' method='get'>
    <input type='number' id='idviewfocus' name='boxid' style='width: 40px' hidden>
  </form>
  <form action='index.php' id='myFormViewGo' method='post'>
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
    document.getElementById("idview").value = littlejohn;
    document.getElementById("myFormView").submit();
}
function myViewee(littlejohn) {
    document.getElementById("idviewfocus").value = littlejohn;
    document.getElementById("myFormViewer").submit();
}

</script>

<style type='text/css'>
.graydisplay {
  display: inline-block;
  background-color: lightgray;
  border-radius: 4px;
  width: 120px;
  padding: 10px;
  padding-bottom: 0px;
  height: 44px;
  border: 4px white solid;
}
.red {
  background-color: orange;

}
.red:hover {
  background-color: white;  
}

.righthand {
  position: fixed;
  top: 140px;
  right: -15px;
  box-shadow: 3px 3px 3px gray;
  padding: 3px;
  background-color: red;
  border: lightgray solid 2px;
  color: white;
}
/*.righthand::shadow {
  width: 30px;
  height: 30px;
  border-radius: 5px;
}*/
.righturn {
    -webkit-transform: rotate(90deg);
    -moz-transform: rotate(90deg);
    -o-transform: rotate(90deg);
    -ms-transform: rotate(90deg);
    transform: rotate(90deg);
}
.black {
  margin-left: 5px;
  background-color: black;
  width: 20px;
  height: 20px;
  display: box;
}


button {
    background-color: orange;
    color: white;
    border: white 2px solid;
    cursor: pointer;
}
button:hover {
  background-color: black;
  color: white;
}
select {
  width: 100px;
  background-color: white;
  border-radius: 3px;
  color: black;
}
.inputin {
  width: 40px;
}

.box:hover {
  border: 1px orange solid;
  box-shadow: 3px 3px 3px gray;  
}
.box {
  background: linear-gradient(
        to left,
        white,
        white
      );
  display: block;
  border: 1px lightgray solid;
  box-shadow: 1px 1px 1px;
  border-radius: 3px;
  color: gray;
  font-family:  arial;
  text-shadow: black 3px;
}
.picboxes {
  border: white solid 2px;
  box-sizing: border-box;  
  margin-bottom: 10px;  
  vertical-align: !important middle;
  min-width: 100%;  
  max-height: 150%; 
  width: 200px;  
  position: relative; 
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);   
  align-content: center;
}
.boxtext {
  background-color: orange;
}
.boxtext:hover {
  background-color: gray;
  color: white;
}

.mrprice{ color: white; float: right; margin-right:10px;max-width: 66px}
.box:hover .mrprice{ color: orange; }

.trimbox {

  width: 220px;
  height: 230px;
  overflow: hidden;  
  margin-left: 5px;
  margin-right: 5px;
}


/* Grid measurements:
 *
 *   960px wide including 12 gutters (half gutters on both edges)
 *
 *   60px columns (12)
 *   20px gutters (two half-gutters + 11 full gutters, so 12 total)
 *
 *
 *   For smaller screens, we always want 20px of padding on either side,
 *   so 960 + 20 + 20 => 1000px
 *
 **/
.blow {
  max-width: 1220px;
  margin: auto;
  margin-left: 15px;
  
}
.blow::after {
  display: table;
  content: '';
}

/* Clearfix */

.row::after {
  display: table;
  content: '';
}

.row::before {
  clear: both;
}
/* main box */
.col-4 {
  float: left;
  width: 240px; 
  padding-left: 5px;
  padding-right: 5px;
  margin-right: 5px;
  margin-left: 5px;
  margin-bottom: 10px;
}

/* picbox */
.col-12 {
  float: left;
  max-width: 220px;
  padding-left: 5px;
  padding-right: 5px;
  margin-bottom: 5px;
  margin-top: 5px;
}
.letters {
  background-color: red;
  position: fixed;
  color: white;
}

</style>

