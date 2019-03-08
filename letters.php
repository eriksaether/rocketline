<?php
  $useride = 0;

   header("Cache-Control: no cache");
  session_cache_limiter("private_no_expire");
?>

<!DOCTYPE html >
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shop Admin</title>
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
<?php  
   // Grab the sort setting and search keywords from the URL using GET
   if(isset($_GET['sort'])) {
     $sort = $_GET['sort'];}
   else{
     $sort =  6;
   }
   if(isset($_GET['usersearch'])){
     $user_search = $_GET['usersearch'];}
     else{
     $user_search =  "";    
   }

   ?>
  <!--- navigation and controllers........................................................................... -->
  <nav>
  <form action="letters.php" method="get"  class="navi">
  <span class="space">  
    <span class="leftpad"><b>Find a special letter:</b></span><br />
    <input type="text" id="usersearch" class="usersearch" name="usersearch" value="<?php echo $user_search; ?>"/>
    <input type="submit" name="submit" value="Submit" />
  </span>
  </form>
  </nav>
    
    <!--- control buttons -->
  <form action='' id='myTaskView' method='post' class="graydisplay">
      <div>
        <span class="red" onclick="showRecorder(1);" >Load Letter</span>   
      </div>
      <br>    
  </form>

  <form class="graydisplay">
      <div >
        <span><a href="letters.php" class="red">Refresh</a></span>
      </div>
      <br>
  </form>


  <h3>Letters - Search Results</h3>
<?php
$loaded =0;
if (isset($_COOKIE['firstname']) && isset($_COOKIE['username']) ) {

  //.............................................................order and search functions...............//
  //clean any posts to this file
  function cleanMyPost($poster) {
    $critter = $poster; //great, set to post    
    $arr = explode("(", $critter);
    $critter = implode(" ", $arr);
    $arr = explode(")", $critter);
    $critter = implode(" ", $arr);
    $arr = explode("'", $critter);
    $critter = implode(" ", $arr);
    $arr = explode("-", $critter);
    $critter = implode(" ", $arr);
    $arr = explode("\\", $critter);
    $critter = implode(" ", $arr);
    return $critter;
  }

  // This function builds a search query from the search keywords and sort setting
  function build_query($user_search, $sort) {
    $search_query = "SELECT * FROM letters";

    // Extract the search keywords into an array
    $clean_search = str_replace(',', ' ', $user_search);
    $search_words = explode(' ', $clean_search);
    $final_search_words = array();
    if (count($search_words) > 0) {
      foreach ($search_words as $word) {
        if (!empty($word)) {
          $final_search_words[] = $word;
        }
      }
    }

    // Generate a WHERE clause using all of the search keywords
    $where_list = array();
    if (count($final_search_words) > 0) {
      foreach($final_search_words as $word) {
        $where_list[] = "description LIKE '%$word%' OR lettername LIKE '%$word%'";
      }
    }
    $where_clause = implode(' OR ', $where_list);

    // Add the keyword WHERE clause to the search query
    if (!empty($where_clause)) {
      $search_query .= " WHERE $where_clause";
    }

    // Sort the search query using the sort setting
    switch ($sort) {
    // Ascending by Letter Name
    case 1:
      $search_query .= " ORDER BY lettername";
      break;
    // Descending by Letter Name
    case 2:
      $search_query .= " ORDER BY lettername DESC";
      break;
    // Ascending by Category
    case 2.5:
      $search_query .= " ORDER BY category";
      break;
     // Ascending by Category
    case 2.6:
      $search_query .= " ORDER BY category DESc";
      break;
    // Ascending by Subcategory
    case 3:
      $search_query .= " ORDER BY subcategory";
      break;
    // Descending by Subcategory
    case 4:
      $search_query .= " ORDER BY subcategory DESC";
      break;
    // Ascending by date posted (oldest first)
    case 5:
      $search_query .= " ORDER BY creationdate";
      break;
    // Descending by date posted (newest first)
    case 6:
      $search_query .= " ORDER BY creationdate DESC";
      break;
    default:
      // No sort setting provided, so don't sort the query
    }

    return $search_query;
  }

  // This function builds heading links based on the specified sort setting
  function generate_sort_links($user_search, $sort) {
    $sort_links = '';

    switch ($sort) {
    case 1:
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=2">Letter Name</a></td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=2.5">Category</a></td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Subcategory</a></td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=5">Date Posted</a></td><td></td><td></td><td></td>';
      break;
    case 2.5:
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">Letter Name</a></td><td>Description</td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=2.6">Category</a></td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Subcategory</a></td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=5">Date Posted</a></td><td></td><td></td><td></td>';
      break;
    case 3:
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">Letter Name</a></td><td>Description</td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=2.5">Category</a></td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=4">Subcategory</a></td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Date Posted</a></td><td></td><td></td><td></td>';
      break;
    case 5:
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">Letter Name</a></td><td>Description</td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=2.5">Category</a></td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Subcategory</a></td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=6">Date Posted</a></td><td></td><td></td><td></td>';
      break;
    default:
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">Letter Name</a></td><td>Description</td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=2.5">Category</a></td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Subcategory</a></td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=5">Date Posted</a></td><td></td><td></td><td></td>';
    }

    return $sort_links;
  }

  // This function builds navigational page links based on the current page and the number of pages
  function generate_page_links($user_search, $sort, $cur_page, $num_pages) {
    $page_links = '';

    // If this page is not the first page, generate the "previous" link
    if ($cur_page > 1) {
      $page_links .= '<a class="pagelinks" href="' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=' . $sort . '&page=' . ($cur_page - 1) . '"><-Previous</a> ';
    }
    else {
      $page_links .= '<- ';
    }

    // Loop through the pages generating the page number links
    for ($i = 1; $i <= $num_pages; $i++) {
      if ($cur_page == $i) {
        $page_links .= ' ' . $i;
      }
      else {
        $page_links .= ' <a class="pagelinks" href="' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=' . $sort . '&page=' . $i . '"> ' . $i . '</a>';
      }
    }

    // If this page is not the last page, generate the "next" link
    if ($cur_page < $num_pages) {
      $page_links .= ' <a class="pagelinks"href="' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=' . $sort . '&page=' . ($cur_page + 1) . '">Next-></a>';
    }
    else {
      $page_links .= ' ->';
    }

    return $page_links;
  }

  //.....................................................end of functions....................................

  // Calculate pagination information
  if (isset($_GET['page'])) {$cur_page = cleanMyPost($_GET['page']);}
  else {$cur_page = 1;}
  $results_per_page = 12;  // number of results per page
  $skip = (($cur_page - 1) * $results_per_page);

  // Start generating the table of results
  echo '<table border="0" cellpadding="2">';

  // Generate the search result headings
  echo '<tr class="heading">';
  echo generate_sort_links($user_search, $sort);
  echo '</tr>';

  //connect to db
  require_once('basecamp/connectvars2.php');
  require_once('appvars.php');
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  if ($dbc->connect_error) {
      die("Connection failed: " . $dbc->connect_error);
  }
  

  //determine tool..
  if (isset($_POST['tool'])) {
    $tool = $_POST['tool'];  
    $js_tool = json_encode($tool);
    echo"<script>";
    echo "var toolUsed = ". $js_tool . ";\n";
    echo"</script>";
  }

 
  //load stock on to db if posted
  if (isset($_POST['lettername'])) {
    
    $letteride = $_POST['letteride']; //will be blank on first load
    $lettername = cleanMyPost($_POST['lettername']);    
    $letterdescribe = cleanMyPost($_POST['letterdescribe']);
    $lettercategory = cleanMyPost($_POST['category']);
    $lettersubcategory = cleanMyPost($_POST['subcategory']);
    $suppliertype = $_POST['suppliertype']; 

    $askprice = $_POST['askprice'];     

    require_once("loadletter.php");
  }

  //delete a stock..................>>
  if (!empty($_POST["idnum"])) {
    $idnum= $_POST["idnum"];
    if ($idnum > 0) { 
     
      $sqldel = "DELETE FROM letters WHERE letterid=$idnum";
      if (mysqli_query($dbc, $sqldel)) {
        echo "stock deleted successfully. ";
      } else {
        echo "Error deleting record: " . mysqli_error($dbc);
      }
           
    }
    $_POST["idnum"]=0;
  }
  // edit stock
  if (!empty($_POST["idedit"])) {
    $idedit= $_POST["idedit"];

    if ($idedit > 0) { 
          $sqledit = "SELECT * FROM letters WHERE letterid=$idedit";             

          $editstock = $dbc->query($sqledit);         
          while($rowe = $editstock->fetch_assoc()) {
                   $letteride = $rowe["letterid"];
                   $lettername = $rowe["lettername"];                   
                   $letterdescribe = $rowe["description"];                   
                   $lettercategory = $rowe["category"]; 
                   $lettersubcategory = $rowe["subcategory"];                    
                   
                   $askprice = $rowe["askprice"]; 
                   $old_picture = $rowe['picture'];
                   if (empty($rowe['picture'])) {
                       $old_picture = "nopic.jpg";
                   }

                   $askprice = $rowe['askprice'];
                
                   $suppliertype = $rowe['suppliertype'];
                   
          }  


    }
    $_POST["idedit"]=0;

  }

 

  // Query to get the total results 
  $query = build_query($user_search, $sort);
  $result = mysqli_query($dbc, $query);
  $total = mysqli_num_rows($result);
  $num_pages = ceil($total / $results_per_page);

  // Query again to get just the subset of results
  $query =  $query . " LIMIT $skip, $results_per_page";
  $result = mysqli_query($dbc, $query);
  while ($row = mysqli_fetch_array($result)) {
    echo '<tr class="results">';
    echo '<td valign="top" style="cursor:pointer;" width="28%" onclick="myView('.$row['letterid'].');">' . $row['lettername'] . '</td>';
    echo '<td valign="top" style="cursor:pointer;" width="40%" onclick="myView('.$row['letterid'].');">' . substr($row['description'], 0, 100) . '...</td>';
    echo '<td valign="top" style="cursor:pointer;" width="9%" onclick="myView('.$row['letterid'].');">' . $row['category'] . '</td>';
    echo '<td valign="top" style="cursor:pointer;" width="9%" onclick="myView('.$row['letterid'].');">' . $row['subcategory'] . '</td>';
    echo '<td valign="top" width="12%">' . substr($row['creationdate'], 0, 10) . '</td>';

       echo '<td  width="30px" style="cursor:pointer; " class="glyphicon glyphicon-pencil" onclick="myEdit('.$row['letterid'].');"></td>';
       echo '<td  width="30px" style="cursor:pointer;" class="glyphicon glyphicon-equalizer" onclick="myAttrEdit('.$row['letterid'].');"></td>';
       echo '<td  width="30px" style="cursor:pointer; "  class="glyphend" onclick="myDelete('.$row['letterid'].');" >x&nbsp;</td>';   

    echo '</tr>';
  } 
  echo '</table>';

  // Generate navigational page links if we have more than one page
  if ($num_pages > 1) {
    echo generate_page_links($user_search, $sort, $cur_page, $num_pages);
  }
 
}
else {
  echo "You are not logged in.";
}
?>
<!-- js loader stuff................................................................................. -->
<!--- recorder -->
<div class="line" id="line">
<div class="recorder" id="recorder">
    <form enctype="multipart/form-data" id="myFormLoader" method="post" action="letters.php" > 
      
      <input type="hidden" name="letteride" value="<?php if (!empty($letteride)) echo $letteride; ?>">
      <label>From:</label>
      <select name="suppliertype" >
        <option value=0>Me</option>
        <option value=1>Another</option>
      
      </select>
      <label>Subject:</label><input type="text" id="workoutblue" class="workoutblue" required name="lettername" value="<?php if (!empty($lettername)) echo $lettername; ?>"><br>      
      <label for="lineone">Letter</label> 
        <textarea name="letterdescribe" form="myFormLoader"  rows="2" cols="55" placeholder="Please explain to our website visitors what your dream wish is for. Also please explain your biggest obstacle to achieve this dream." required ><?php if (!empty($letterdescribe)) echo $letterdescribe; ?></textarea>

      <input name="tool" value="recorder" hidden><br>
      
      <label>Category:</label><input type="text" required name="category" value="<?php if (!empty($lettercategory)) echo $lettercategory; ?>">      
     
      <label>Subcategory:</label><input type="text" name="subcategory" value="<?php if (!empty($lettersubcategory)) echo $lettersubcategory; ?>"><br>
      
      <label>Budget:</label><input type="text" required name="askprice" value="<?php if (!empty($askprice)) echo $askprice; ?>"><br>
      

      <input type="hidden" name="old_picture" value="<?php if (!empty($old_picture)) echo $old_picture; ?>" />
      
      Photo or file:<input type="file" id="screenshot" name="screenshot" ><input type="hidden" name="MAX_FILE_SIZE" value="100000" >
      <?php if (!empty($old_picture)) {
        echo '<img class="picbox" src="' . MM_UPLOADPATH . $old_picture . '" alt="Stock Picture" />';
      } ?>      
      
      <input  type="submit" value="Save stock" id="submitred" name="submit" onclick=" return myRelax();" /><input type="button" id="reset" onclick="blueDisplayClear(); blueDisplayClear();"  value="Clr" ><input type="button" id="exit" name="" class="exit" value="X" onclick="tRecorder = 0; showRecorder(0);"><input type="button" id="reset" onclick="flipPage(1);"  class="flip" value="*" >
    </form>
</div>
<div class="frontpage" id="frontpage">
  <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
  <input type="button" id="reset" onclick="flipPage(0);"  class="flip" value="*" >
</div>
</div>


</main>
</body>
<!-- hidden input stuff...........................................................                -->
<form action='letters.php' id='myFormEdit' method='post'>
    <input type='number' id='idedit' name='idedit' style='width: 40px' hidden>
    <input name="tool" value="recorder" hidden>
</form>
<form action='letters.php' id='myFormAttrEdit' method='post'>
    <input type='number' id='idattredit' name='letteridu' style='width: 40px' hidden>    
    <input name="tool" value="attributor" hidden>
</form>
<form action='letters.php' id='myFormDel' method='post'>
  <input type='number' id='iddelete' name='idnum' style='width: 40px' hidden>
</form>
<form action='theletter.php' id='myFormView' method='post'>

    <input type='number' id='idviewer' name='idviewgo' style='width: 40px' hidden>    
</form>
<div id="hero"></div>

<?php  mysqli_close($dbc); ?>
</html>

<style type='text/css'>

html  {
  font-family: 'Roboto';
}

body {  
  min-width:640px;        /* Suppose you want minimum width of 1000px */
  width: auto !important;  /* Firefox will set width as auto */
  width:640px;            /* As IE6 ignores !important it will set width as 1000px; */
    /*   height: px; */
    background: linear-gradient(
          to right,
          
          rgba(150, 150, 150, 0.3)
        );
}

h1 {
  font-size: 30px;
  color: white;
}
.center {
  text-align: center;
}

.transbox {
    background-image: url('images/snow.jpg');
    background-repeat: repeat-x;
    opacity: : 0.9;
    border: solid 1px black;
}

.space {
    display: inline-block;   
    padding: 3px;
    width: 500px;
}

nav {
    background-color: green;
    opacity: 0.7;
    width: 450px;
    border-radius: 5px;
    color: white;
    padding: 2px;

    border: white 2px solid;
    background-repeat: repeat-x;
    display: inline-block;
    box-shadow: 2px 2px 2px;
}
.navi {
    height: 75px;
}
.headernav {
  background-color: red;
}
.usersearch {
  width: 250px;
  height: 23px;
  color:  black;
  margin-left: 60px;
}
.usersearch {

}
.inbutton {
  width: 30px; 
  height: 23px;
  vertical-align: bottom;
}
.inbutton:hover {
  color: yellow;
}
.nextline {
  clear: left;
}
button {
    background-color: orange;
    color: white;
    border: white 2px solid;
    cursor: pointer;
}
button:hover {
  background-color: maroon;
  color: white;
}
select {
  width: 150px;
  background-color: white;
  border-radius: 3px;
  color: black;
}
.inputin {
  width: 40px;
}

main {
  margin-left: 50px;
  margin-right: 50px;

}
tr {
  background-color: white;    
  border-bottom: 1px solid lightgray;
}
tr:nth-child(1) {
  background-color: red;
  color: white;
}
tr:nth-child(2n+3) {
  background-color: red;
  color: white;
  opacity: 0.5;
}
table {
  border-radius: 15px;
}
.heading {
  background-color: lightgray;
}
.results:hover {
  background-color: orange;
  color: white;
}
.pagelinks {
  color: black;
}
.pagelinks:visited { 
  text-decoration:none; 
}
.pagelinks:hover {
  color: yellow;
}
.leftpad {
  padding-left: 90px;
}

label {
  width: 100px;
}
textarea {
  position: relative;
  left: -3px;
}
textarea {
  height: 200px;  
  width: 510px;
}
.line {
    display: none;
    border-radius: 25px;
    border: orange solid 7px;
    left: 20%;
    top: 140px;
    position: fixed;            
      min-width:640px;        /* Suppose you want minimum width of 1000px */
      width: auto !important;  /* Firefox will set width as auto */
      width:640px;            /* As IE6 ignores !important it will set width as 1000px; */
    z-index: 1;
}

.gtypes {
    width: 100px;
}
.graycell {
  background-color: lightgray;
}
@media screen and (max-width: 800px) {
    .line {
      left: 50px;
    }
}
.recorder {
    padding: 10px;
    position: relative;
    border-radius: 20px;
    border: white solid 5px;
    background-color: lightgray;
    z-index: 1;
    width: 640px;
    height: 400px;
}
.frontpage {
    display: none;
    padding: 10px;
    position: relative;
    border-radius: 20px;
    border: white solid 5px;
    background-color: orange;
    width: 640px;
    height: 400px;
}
.picbox{
  border: 2px solid white;
  height: 80px;
}
.graydisplay {
  display: inline-block;
  background-color: lightgray;
  border-radius: 4px;
  width: 150px;
  padding: 10px;
  padding-bottom: 0px;
  height: 42px;  
}

.red {
  display: inline-block;
  width: 95%;
  background-color: gray;
  text-align: center;
  cursor: pointer;
}

.red:hover {
  background-color: red;
  color: white;
}
.workoutblue {
  width: 250px;
}
input:hover, textarea:hover {
  background-color: lightgray;
}
.exit {
  color: red;
  width: 25px;
  height: 25px;
  border-radius: 100%;
  background-color: lightgray;
}
.glyphend {
  color: red;
}
.glyphend:hover {
  color: white;
}
.glyphicon {
  color: gray;
}
.glyphicon:hover {
  color: white;  
}
.flip {
  background-color: gray;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  float: right;
}

</style>

<script >
var tRecorder = 0;
var stRecorder = 0;
var slRecorder = 0;
var sBrander = 0;
var atRecorder = 0;
var attrRecorder = 0;
var quantumQ = 0;
var quantumP = 0;
var quantumR = 0;
var carLoaded = 0;
  //if task posts, tell the good news and correctly set time and recorder toggles
  function startMe() {
    
      if (toolUsed == "recorder") {
          showRecorder(1);
      }
      else {
          tRecorder = 1;
      }
      if (toolUsed == "frontpage" ) {
          flipPage(1);
      }
      else {
          stRecorder = 1;
      }      

  }
  //show recorder
  function showRecorder(quantumQ) {
      var yourUl = document.getElementById("line");
      if (tRecorder == 1 | quantumQ == 1) {
          yourUl.style.display = 'block';}
      else {
          yourUl.style.display = 'none';}              
      tRecorder = 1 - tRecorder;
  }
    //show recorder
  function flipPage(quantumP) {
      var yourUl = document.getElementById("frontpage"); 
      var yourUs = document.getElementById("recorder");   
      if (quantumP == 1) {
          yourUl.style.display = 'block';
          yourUs.style.display = 'none';
        }          
      if (quantumP == 0) {
          yourUl.style.display = 'none';
          yourUs.style.display = 'block';//show recorder
        }                    
  }


function myDelete(littlejohn) {
    if (confirm("Are you sure you want to delete? Please check there are no outstanding orders!!") == true) {
      if (confirm("Are you sure you 200% sure!") == true) {

        document.getElementById("iddelete").value = littlejohn;
        document.getElementById("myFormDel").submit();
      }
    }   
}
function myEdit(littlejohn) {
      document.getElementById("idedit").value = littlejohn;
      document.getElementById("myFormEdit").submit();
}
function myAttrEdit(littlejohn) {
      
      document.getElementById("idattredit").value = littlejohn;
      document.getElementById("myFormAttrEdit").submit();
}
function myEditor(littlejohn) {
      document.getElementById("ideditor").value = littlejohn;
      document.getElementById("myFormEditor").submit();
}
function myView(littlejohn) {
      document.getElementById("idviewer").value = littlejohn;
      document.getElementById("myFormView").submit();
}

startMe();
</script>