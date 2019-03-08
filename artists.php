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
.chosen:nth-child(1) {
  color: yellow;
}
html  {
  font-family: 'Roboto';
}

body {  
  min-width:600px;        /* Suppose you want minimum width of 1000px */
  width: auto !important;  /* Firefox will set width as auto */
  width:600px;            /* As IE6 ignores !important it will set width as 1000px; */
      background: linear-gradient(
        to right,
        rgba(0, 0, 0, 0),
        rgba(50, 20, 0, 0.1)
      )
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
.trimbox {

  width: 220px;
  height: 220px;
  overflow: hidden;  
  border-radius: 50%;
  
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
<input name="mydrop" value="<?php echo $idcategory; ?>" hidden>
</form>
<form class="navi" action='brands.php' method="post">Search Artists<br>
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
        <?php if (isset($useride)) {echo"<span class='red'><a href='index.php?idviewartist=All'>All Artists</a></span>"; }
        else{ echo"<span class='red'><a href='index.php?idviewartist=All'>All Artists</a></span>"; } ?>
      </div>
      <br>    
    </form>
<br>

<?php
//load brands
    $sql4 = "SELECT * FROM artists";      
    $result4 = $connex->query($sql4);      
    while($row4 = $result4->fetch_assoc()) { 
        $brandid = $row4["artistid"];
        $brandname[$brandid] = $row4["artistname"];
        $brandpic[$brandid] = $row4["artistpic"];
    }


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
  $sql2 = "SELECT * FROM artists";

  if ($modit == 1) {echo "<div class='row'>" ;}
      //fetch the list from the database 
  if($searcher==9) {
       $user_search = "";
  }
  if($searcher==3) {
       $user_search = " WHERE artistname LIKE '%{$searchcritter}%'";
  }
  elseif($searcher==2) {
       $user_search = " WHERE subcategory = '$searchsubcat'";        
  }

  elseif($searcher==1) {
       $user_search = " WHERE category = '$searchcat'";        
  }
  elseif ($searcher==0) {
       $user_search = " ORDER BY artistname DESC";
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
        
        if (!empty($row['artistpic'])) {
         echo "<div class='trimbox'><img class='col-12 picboxes' src='" . MM_UPLOADPATH . $row['artistpic'] .
        "' alt='Stock Picture' onclick='myViewee(".$row['artistid'].");'></div>";
        }
        else {
         echo "<div class='trimbox'><img class='col-12 picboxes' src='" . MM_UPLOADPATH . "88.png" .
        "' alt='Stock Picture' onclick='myViewee(".$row['artistid'].");'></div>";
        }
        
          echo "<div class='boxtext' onclick='myViewArtist(".$row["artistid"].");'><span class='left'><b>".$row['artistname']."</b></span></div>"; 
        echo"</div>"; 
      echo"</div>"; 
           
  }

  if ($modit % 3 == 1) {echo"</div>";}  
  
echo "</div>" ;
$urhere = "artists.php";
require_once('pager.php');
// Generate navigational page links if we have more than one page
if ($num_pages > 1) {
  $sort = 0;
  echo generate_page_links($urhere, $user_search, $sort, $cur_page, $num_pages);
  
}

//view artist
require_once('viewartist.php');

$connex->close();

//<embed src="buyerlist.php" width="300" height="400">

?>


  </main>
  <form action='artists.php' id='myFormViewGo' method='post'>
    <input type='number' id='idviewgo' name='idviewgo' style='width: 40px' hidden>
  </form>
   <form action='artists.php' id='myFormViewArtist' method='post'>
    <input type='number' id='idviewartist' name='idviewartist' style='width: 40px' hidden>
  </form>
   <form action='index.php' id='myFormViewer' method='get'>
    <input type='number' id='idviewfocus' name='idviewartist' style='width: 40px' hidden>
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

.boxtext:hover {
  background-color: orange;
  color: white;
}

.mrprice{ color: white; float: right; margin-right:10px;}
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
  max-width: 1200px;
  margin: auto;
  
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

.col-4, .col-12 {
  float: left;

  /* Gutters:
   * Each column is padded by half-a-gutter on each side,
   *
   * Half a gutter is 10px, 10/960 (context) = 1.041666%
   *
   */
  padding-left: 5px;
  padding-right: 5px;
  margin-bottom: 10px;
}

/* Mobile defaults */
.col-4, .col-12 {
  min-width: 220px;
}


</style>

