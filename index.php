<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shop Floor</title>
  <link href="http://allfont.net/allfont.css?fonts=nasalization-free" rel="stylesheet" type="text/css" />

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
.chosen:nth-child(1) {
  color: yellow;
}
html  {
  font-family: 'Roboto';
}

body {  

  min-width:640px;        /* Suppose you want minimum width of 1000px */
  width: auto !important;  /* Firefox will set width as auto */
  width:640px;            /* As IE6 ignores !important it will set width as 1000px; */
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
//brand search
if (isset($_GET['idviewbrand']) && $_GET['idviewbrand'] !== 0) {
  $idbrand = $_GET['idviewbrand'];
  $searcher = 4;
  if($idbrand == "All") {
    $searcher = 4.9;
  }
}
//artist search or both if need be
if (isset($_GET['idviewartist']) && $_GET['idviewartist'] !== 0) {
  $idartist = $_GET['idviewartist'];
  $searcher = 5;
  if($idartist == "All") {
    $searcher = 5.9;
  }
  if (isset($_GET['idviewbrand']) && $_GET['idviewbrand'] !== 0) {
      $idbrand = $_GET['idviewbrand'];
      $searcher = 6;
  }
}

//echo $searcher;
if (isset($_GET['insearch']) ){
  $searchcritter = $_GET['insearch']; //great, set to post
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

  //echo $searchcat;
  $sqlcat = "SELECT * FROM categories ORDER by name";  
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

<form action="index.php" method="get" class="navi">
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
<form class="navi" action='index.php' method="get">Search <br>
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
    <form action='' id='myTaskView' method='post' class="graydisplay">
      <div>
        <?php if (isset($useride)) {echo"<span class='red'><a href='index.php?idviewartist=0&idviewbrand=0'>Stuff</a></span>"; }
        else{ echo"<span class='red'><a href='index.php?idviewartist=0&idviewbrand=0'>Stuff</a></span>"; } ?>
      </div>
      <br>    
    </form>
<br>

<?php

//echo $searchcat." ".$searchsubcat;
  ?>
<script>
  <?php  
    
      $js_mars = json_encode($login);
      echo "var loggedIn = ". $js_mars . ";\n";       
    
  ?>
</script>
<?php 

//show stock item.........................>>
require_once('viewgo.php');
if (isset($_GET['idviewgo'])) {
    
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
       $artsearch = "SELECT * FROM artists WHERE artistname LIKE '%{$searchcritter}%'";
       $resultart = mysqli_query($connex, $artsearch);
          while ($rowa = mysqli_fetch_array($resultart)) {  
              $idartist = $rowa["artistid"];
              $searcher =5;
          } 
       $brandsearch = "SELECT * FROM brandnames WHERE brandname LIKE '%{$searchcritter}%'";
       $resultbra = mysqli_query($connex, $brandsearch);
          while ($rowb = mysqli_fetch_array($resultbra)) {  
              $brandid = $rowb["brandid"];
              $searcher =4;
          } 
       $user_search = " WHERE stockname LIKE '%{$searchcritter}%' OR description LIKE '%{$searchcritter}%' OR category LIKE '%{$searchcritter}%' OR subcategory LIKE '%{$searchcritter}%'";
  }
  if($searcher==4) {
       $user_search = " WHERE brandid = '$idbrand'";
  }
  if($searcher==4.9) {
       $user_search = " WHERE brandid > '0'";
  }
  if($searcher==5) {
       $user_search = " WHERE artistid = '$idartist'";
  }
  if($searcher==5.9) {
       $user_search = " WHERE artistid > '0'";
  }
  if($searcher==6) {
       $user_search = " WHERE artistid = '0' AND brandid = '0'";
  }
  elseif($searcher==2) {
       $user_search = " WHERE subcategory = '$searchsubcat'";
  }
  elseif($searcher==2.5) {
       $user_search = " WHERE category = '$searchcat' AND subcategory = '$searchsubcat'";   
  }

  elseif($searcher==1) {
       $user_search = " WHERE category = '$searchcat'";        
  }
  elseif ($searcher==0) {
       $user_search = " WHERE active = 1  order by artistid DESC, brandid DESC ";
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
 <div id="message" class="message" onclick="hideMessage();"></div>
 <div id="disqus_thread"></div>

  </main>
   <div class='righthand righturn'><span class="black">i</span><a href="letters.php">Letterbox</a></div>

   <form action='stockitem.php' id='myFormViewer' method='get'>
    <input type='number' id='idviewfocus' name='boxid' style='width: 40px' hidden>
    <input type='text' name='mydrop' value='<?php echo $searchcat; ?>' hidden>
    <input type='text' name='mydroplet' value='<?php echo $searchsubcat; ?>' hidden>
  </form>
  <form action='index.php' id='myFormViewGo' method='get'>
    <input type='number' id='idviewgo' name='idviewgo' hidden>
    <input type='text' name='mydrop' value='<?php echo $searchcat; ?>' hidden>
    <input type='text' name='mydroplet' value='<?php echo $searchsubcat; ?>' hidden>

  </form>
  
  <form action='index.php' id='myFormAdd' method='post'>
    <input type='number' id='idstockadd' name='idstockadd' style='width: 40px' hidden>
    <input type='number' id='qtyadd' name='qtyadd' style='width: 40px' hidden>
    <input type='number' id='notify' name='notify' value="0" style='width: 40px' hidden>
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
function goPlease() {
    document.getElementById("stockchange").style.display = "none";
}
function hideMessage() {
    document.getElementById("message").style.display = "none";
}
</script>

<script>

/**
*  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
*  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/

var disqus_config = function () {
this.page.url = 'https://www.rocketline.co.za/index.php';  // Replace PAGE_URL with your page's canonical URL variable
this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
};

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
.graydisplay {
  display: inline-block;
  background-color: lightgray;
  border-radius: 4px;
  width: 120px;
  padding: 10px;
  padding-bottom: 0px;
  height: 48px;
  border: 4px white solid;
}
.red {
  background-color: orange;
  
}
.red:hover {
  background-color: white;  
}
@media(max-width: 800px){
  .graydisplay {
    width: 99px;
    height: 44px;
  }
  .red {
    width: 80px;
    position: relative;
    left: -10px;
    top: -2px;
  }
}
.righthand {
  position: fixed;
  top: 160px;
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
  border: 1px ghostwhite solid;
  box-shadow: 1px 1px 1px;
  border-radius: 3px;
  color: gray;
  font-family:  arial;
  text-shadow: black 3px;
  z-index: 0.1;
  margin-bottom: 10px;

}
.box:hover {
  transform: scale(1.15);

  transition: transform 0.5s ease-in-out;
  z-index: 9999 !important;
}

.trimbox {
  z-index: 0.1;
  width: 97%;  
  height: 280px;
  overflow: hidden;  
  margin-top: 5px;
  margin-bottom: 5px;
  margin-left: 5px;
  margin-right: 5px;
  display: block;
}
@media(max-height: 1000px) {
  .trimbox {
        height: 240px;
  }
}

.picboxes {
  border: white solid 2px;
  box-sizing: border-box;  
  margin-bottom: 10px;  
  vertical-align: !important middle;
  z-index: -0.1;

  position: relative; 
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);   
  align-content: center;
  transition: transform 0.5s ;
}
.picboxes img {
  z-index: 0.1;
}
.picboxes:hover {      
  z-index: 111;  
  
   transform: translate(-50%, -50%) scale(1.2);
   
}
.boxtext {
  background-color: orange;
}
.boxtext:hover {
  background-color: gray;
  color: white;
}

.mrprice{ color: white; float: right; margin-right:10px; max-width: 66px;}
.box:hover .mrprice{ color: orange; }



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
 /*  main box
   */
.col-4 {
  float: left;
  width: 24%;
  min-width: 300px; 
  padding-left: 5px;
  padding-right: 5px;
  margin-right: 5px;
  margin-left: 5px;
  margin-bottom: 10px;
}
@media(max-width: 1330px){
  .col-4 {
    min-width: 31.5%; 
  }
}
@media(max-width: 1050px){
  .col-4 {
    min-width: 280px; 
  }
}
@media(max-width: 980px){
  .col-4 {
    min-width: 47%;  
  }
}
@media(max-width: 700px){
  .col-4 {
    min-width: 280px;  
  }
  
}
@media(max-width: 700px){
  .blow {
    margin-left: 5px;
  }
}

/* picbox */
.col-12 {
  float: left;
  max-width: 280px;
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
.stockchange {
  position: fixed;
  top: 200px;
  left: 250px;
  background-color: lightgray;
  padding: 30px;
  box-shadow: 2px 2px 2px gray;
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

