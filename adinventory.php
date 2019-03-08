<?php
  $useride = 0; $test = 0;

   header("Cache-Control: no cache");
   session_cache_limiter("private_no_expire");
   require_once("head.php");
     
 
?>
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
    background-image: url('images/floor.jpg');
    background-repeat: repeat-x;
    opacity: 0.9;
    border: solid 1px black;
}

.space {
  
    padding: 3px;
    width: 500px;
}
nav {
    background-image: url('images/wood.jpg');
    border: white 2px solid;
    background-repeat: repeat-x;
    float: left;
    box-shadow: 2px 2px 2px;

    background-color: lightgray;
    width: 450px;
    border-radius: 5px;
    color: white;
    height: 55px;
    padding: 2px;
    
}
.navi {
    height: 55px;
}
.graydisplay {
  display: inline-block;
  margin-left: 5px;
  margin-top: 3px;
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
.redrover{
  color: red;
}
.workoutblue {
  width: 250px;
}
input:hover {
  background-color: lightgray;
}
textarea:hover {
  background-color: lightgray;
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
.usersearch {
  width: 250px;
  height: 23px;
  color:  black;
  margin-left: 60px;
}
.usersearch:hover {
   width: 250px;
  height: 23px;
  color:  black;
  margin-left: 60px;
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
.leonardo {
  width: 300px;
}
.line {
    display: none;
    border-radius: 25px;
    border: orange solid 7px;
    left: 28%;
    top: 80px;
    position: fixed;            
      min-width:640px;        /* Suppose you want minimum width of 1000px */
      width: auto !important;  /* Firefox will set width as auto */
      width:640px;            /* As IE6 ignores !important it will set width as 1000px; */
    z-index: 1;
}
@media (max-width: 1200px) {
  .line {
    left: 20%;
  }
}
@media (max-width: 800px) {
  .line {
     left: 20px;
     top: 80px;
     min-width:600px;        /* Suppose you want minimum width of 1000px */
      width: auto !important;  /* Firefox will set width as auto */
      width:600px;            /* As IE6 ignores !important it will set width as 1000px; */
  }
  .skinny {
      width: 120px;
  }
}
@media (max-width: 640px) {
  select {
    width: 100px;
  }
}

@media (max-height: 800px) {
  .line {
      top: 10px;
      width: 600px;
  }
  body {
    height: 800px;
  }
}


.loader, .attributor, .brander, .artist {
    display: none;
    border-radius: 25px;
    border: orange solid 7px;
    left: 20%;
    top: 140px;
    position: fixed;            
      min-width:340px;        /* Suppose you want minimum width of 1000px */
      width: auto !important;  /* Firefox will set width as auto */
      width:340px;            /* As IE6 ignores !important it will set width as 1000px; */
    z-index: 0.9;
    background-color: lightgray;
    padding: 20px;
}
.setter, .attrsetter {
       display: none;
    border-radius: 25px;
    border: orange solid 7px;
    left: 20%;
    top: 150px;
    position: fixed;            
      min-width:340px;        /* Suppose you want minimum width of 1000px */
      width: auto !important;  /* Firefox will set width as auto */
      width:340px;            /* As IE6 ignores !important it will set width as 1000px; */
    z-index: 1;
    background-color: lightgray;
    padding: 20px;
}
.setter {
 
}
.gtypes {
    width: 100px;
}
.graycell {
  background-color: lightgray;
}

.recorder {
    padding: 10px;
    position: relative;
    border-radius: 20px;
    border: white solid 5px;
    background-color: lightgray;
    z-index: 1;
}
.picbox{
  border: 2px solid white;
  height: 80px;

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
.weight {
  width: 70px;
}
h3 {
  clear: both;
}
.stockdescribe {
  width: 300px;
  height: 50px;
}
@media (max-width: 600px){
  .stockdescribe {
    width: 250px;
    height: 50px;
}

}
</style>
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
.chosen:nth-child(4) {
     color: yellow;
  }
</style>
<body>
  <?php require_once("shoulders.php"); ?>
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

  <nav>
  <form action="adinventory.php" method="get"  class="navi">
  <span class="space">  
    <span class="leftpad"><b>Find your risky stock:</b></span><br />
    <input type="text" id="usersearch" class="usersearch" name="usersearch" value="<?php echo $user_search; ?>"/>
    <input type="submit" name="submit" value="Submit" />
  </span>
  </form>
  </nav>

    
    <!--- controller/selector -->
  <form action='' id='myTaskView' method='post' class="graydisplay">
      <div>
        <span class="red" onclick="showRecorder(1);" >Load stock</span>   
      </div>
      <br>    
  </form>
   <form class="graydisplay">
      <div >
        <span class="red" onclick="showBrander(1);" >Load Brand</span>   
      </div>
      <br>
  </form>
     <form class="graydisplay">
      <div >
        <span class="red" onclick="showArtist(1);" >Load Artist</span>   
      </div>
      <br>
  </form>
  <form class="graydisplay" action='' id='loadfeatures' method='post'>
      <div>
        <span class="red" onclick="showLoader(1);" >Load Choice</span>   
      </div>
      <br>
  </form>
  <form class="graydisplay">
      <div >
        <span><a href="adinventory.php" class="red">Refresh</a></span>
      </div>
      <br>
  </form>

  <h3>Stock - Search Results</h3>
<?php
$loaded =0;
$bidchecked="";
if (isset($_COOKIE['firstname']) && isset($_COOKIE['username']) ) {

  //........................................................................start of functions.............
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
    $search_query = "SELECT * FROM stocktypes";

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
        $where_list[] = "description LIKE '%$word%' OR stockname LIKE '%$word%'";
      }
    }
    $where_clause = implode(' OR ', $where_list);

    // Add the keyword WHERE clause to the search query
    if (!empty($where_clause)) {
      $search_query .= " WHERE $where_clause";
    }

    // Sort the search query using the sort setting
    switch ($sort) {
    // Ascending by Stock Name
    case 1:
      $search_query .= " ORDER BY stockname";
      break;
    // Descending by Stock Name
    case 2:
      $search_query .= " ORDER BY stockname DESC";
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
      $sort_links .= '<td></td><td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=2">Stock Name</a></td><td>Description</td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=2.5">Category</a></td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Subcategory</a></td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=5">Date Posted</a></td><td></td><td></td>';
      break;
    case 2.5:
      $sort_links .= '<td></td><td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">Stock Name</a></td><td>Description</td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=2.6">Category</a></td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Subcategory</a></td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=5">Date Posted</a></td><td></td><td></td>';
      break;
    case 3:
      $sort_links .= '<td></td><td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">Stock Name</a></td><td>Description</td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=2.5">Category</a></td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=4">Subcategory</a></td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Date Posted</a></td><td></td><td></td>';
      break;
    case 5:
      $sort_links .= '<td></td><td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">Stock Name</a></td><td>Description</td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=2.5">Category</a></td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Subcategory</a></td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=6">Date Posted</a></td><td></td><td></td>';
      break;
    default:
      $sort_links .= '<td></td><td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">Stock Name</a></td><td>Description</td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=2.5">Category</a></td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Subcategory</a></td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=5">Date Posted</a></td><td></td><td></td>';
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


  // Connect to the database................>>
  require_once('basecamp/connectvars2.php');
  require_once('appvars.php');
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    // Check connection
  if ($dbc->connect_error) {
      die("Connection failed: " . $dbc->connect_error);
  }



  //add feature group
  if (isset($_POST['feattype'])) {
    $feattype = cleanMyPost($_POST['feattype']);

    // Make sure there is no similar types that are the same
       $sqlcats = "SELECT * FROM featoptgroups WHERE groupname LIKE '%{$feattype}%'";
       $resultcats= mysqli_query($dbc, $sqlcats);
       if (mysqli_num_rows($resultcats) == FALSE) {

         $qcat =  "INSERT INTO featoptgroups (groupname) VALUES ('$feattype')";
         mysqli_query($dbc, $qcat);    
         echo '<span>Feature Type inserted. </span>';     
       }
  }
  //add feature name
  if (isset($_POST['attrholder']) AND isset($_POST['typeholder'])) {
    $attrholder = cleanMyPost($_POST['attrholder']);
    $typeholder = cleanMyPost($_POST['typeholder']);

    // Make sure there is no similar types that are the same
       $sqlcats = "SELECT * FROM featoptnames WHERE optionname LIKE '%{$attrholder}%'";
       $resultcats= mysqli_query($dbc, $sqlcats);
       if (mysqli_num_rows($resultcats) == FALSE) {

         $qcat =  "INSERT INTO featoptnames (optionname, groupid) VALUES ('$attrholder','$typeholder')";
         mysqli_query($dbc, $qcat);    
         echo '<span>Feature option inserted. </span>';     
       }
  }
  // grab feature data  
  $querytype = "SELECT * FROM featoptgroups";    
  $resultype = mysqli_query($dbc, $querytype);
  $row_cnt = $resultype->num_rows;
  if($row_cnt=0) {
      echo "Houston, we have a problem. ";
  }
  else{
      $ktype = 0;
      while ($rowtype = mysqli_fetch_array($resultype)) {
          $ktype = $ktype+1;
          $typenames[$ktype] = $rowtype["groupname"]; 
          $typeid[$ktype] = $rowtype["groupid"]; 
          $typeide = $rowtype["groupid"];// allow for creation of option dimensions below

          $queryset = "SELECT * FROM featoptnames WHERE groupid = '$typeide' ORDER BY optionid Asc"; 
          $resultset = mysqli_query($dbc, $queryset);
        
           $kset = 0;
           while ($rowset = mysqli_fetch_array($resultset)) {
              $kset = $kset+1;
              $optionids[$typeide][$kset] = $rowset["optionid"];                            
              $optionnames[$typeide][$kset] = $rowset["optionname"];                          
           }
           $countsets[$typeide] =  $kset; //count for each set how items..
      }
  }
    ?>
  <script>
  <?php 
  $js_array = json_encode($typenames);
  echo "var typeNames = ". $js_array . ";\n";
  $js_array = json_encode($typeid);
  echo "var typeIds = ". $js_array . ";\n";

  $js_array = json_encode($optionnames);
  echo "var optionNames = ". $js_array . ";\n";
  $js_array = json_encode($optionids);
  echo "var optionIds = ". $js_array . ";\n";
  $js_array = json_encode($countsets);
  echo "var countOptions = ". $js_array . ";\n";


  ?>
  </script>
  <?php 
      // ready edit stock attributes  ..>>
  if (!empty($_POST["stockidu"])) {    
    $stockidoo = $_POST["stockidu"];    
    
    if ($stockidoo > 0) { 
          $sqledit = "SELECT * FROM stocktypes WHERE stockid='$stockidoo'";            
          $editstock = $dbc->query($sqledit);         
          while($rowe = $editstock->fetch_assoc()) {
                   $stockide = $rowe["stockid"];
                   $stockname = $rowe["stockname"];
                   $stockcode = $rowe["stockcode"];                   
                   $stockchoice1 = $rowe["choiceone"];       
                   $stockchoice2 = $rowe["choicetwo"];                        
          }
    }
  }
    // ready edit stock attributes  ..>>
  if (!empty($_POST["stockidoo"])) {
    $typeidone=0;
    $stockidid= $_POST["stockidoo"]; //stock to edit
    $typeidone = $_POST["typeidoo"];
    
    
    if ($stockidid > 0) { 
          $sqledit = "SELECT * FROM stocktypes WHERE stockid=$stockidid";            
          $editstock = $dbc->query($sqledit);         
          while($rowe = $editstock->fetch_assoc()) {
                   $stockide = $rowe["stockid"];
                   $stockname = $rowe["stockname"];
                   $stockcode = $rowe["stockcode"];                   
                   $stockchoice1 = $rowe["choiceone"];       
                   $stockchoice2 = $rowe["choicetwo"];                        
          }
          //echo $typeidone;
          $sqlinfo = "SELECT * FROM featmappings WHERE stockid='$stockidid' AND groupid = '$typeidone' ORDER BY optionid Asc";            
          $resultinfo = $dbc->query($sqlinfo); 
          $atcount = 0;       
          while($rowee = $resultinfo->fetch_assoc()) {
                   $atcount = $atcount + 1;
                   $checkedname[$atcount] = $rowee["optionname"];
                   $checkedid[$atcount] = $rowee["optionid"];                   
          }
          //find gr
          if($atcount > 0 || $typeidone > 0) {
              //m is main option list, n is what we are checking
              for($m=1;$m<=$countsets[$typeidone];$m++) {
                  $danceid[$m] = $optionids[$typeidone][$m];
                  $dancename[$m] = $optionnames[$typeidone][$m];
                  $dancemove[$m] = 0;
                  for($n=1;$n<=$atcount;$n++) {                       
                       if($optionids[$typeidone][$m]==$checkedid[$n]) {
                             $dancemove[$m] = $dancemove[$m]+1;
                       }                                 
                  }               
              }
              $js_array = json_encode($typeidone);  
              $js_array1 = json_encode($danceid);
              $js_array2 = json_encode($dancename);
              $js_array3 = json_encode($dancemove);          
              echo"<script>";
              echo "var typeIDone = ". $js_array . ";\n";
              echo "var danceId = ". $js_array1 . ";\n";
              echo "var danceName = ". $js_array2 . ";\n";
              echo "var danceMove = ". $js_array3 . ";\n";          
              echo"</script>";
          }          
    }    
  }

  //determine tool..
  if (isset($_POST['tool'])) {
    $tool = $_POST['tool'];  
    $js_tool = json_encode($tool);
    echo"<script>";
    echo "var toolUsed = ". $js_tool . ";\n";
    echo"</script>";
    echo $tool;
  }

   //load stock on to db if posted
  if (isset($_POST['brandname'])) {

    $brandname = cleanMyPost($_POST['brandname']);
    $supplieridb = $_POST['supplieridb'];
    $brandide = 0;
    require_once("loadbrand.php");
  }
     //load stock on to db if posted
  if (isset($_POST['artistname'])) {

    $artistname = cleanMyPost($_POST['artistname']);
    $supplieridb = $_POST['supplieridb'];
    $artistdescribe = cleanMyPost($_POST['artistdescribe']);
    $artistide = 0;
    require_once("loadartist.php");
  }

   $activechecked = "checked";
   $auctionyesid = 0;
  //load stock on to db if posted
  if (isset($_POST['stockname'])) {
    
    $stockide = 0; //default as zero
    $stockide = $_POST['stockide']; //will be blank on first load
    if(isset($_POST['active'])){
      $active = $_POST['active']; //will be blank on first load
      $activechecked = "checked";
    }
    else {
      $active = 0;
    }
    $stockname = cleanMyPost($_POST['stockname']);
    $stockcode = cleanMyPost($_POST['stockcode']);
    $stockdescribe = cleanMyPost($_POST['stockdescribe']);
    $stockcategory = cleanMyPost($_POST['category']);
    $stocksubcategory = cleanMyPost($_POST['subcategory']);
    $stockapps = cleanMyPost($_POST['stockapps']);
    $stockspecs = cleanMyPost($_POST['stockspecs']);
    $stockchoice1 = $_POST['choice1']; //echo $stockchoice1;
    $stockchoice2 = $_POST['choice2']; //echo $stockchoice2;    
    $retailprice = $_POST['retailprice']; 
    $costprice = $_POST['costprice']; 
    $weight = $_POST['weight']; 
    $width = $_POST['width']; 
    $height = $_POST['height']; 
    $depth = $_POST['depth']; 
    $supplierid = $_POST['supplierid']; 
    $brandid = $_POST['brandid']; 
    $artistid = $_POST['artistid']; 

    if(isset($_POST['bidsyes'])) {
         $auctionyesid = $_POST['bidsyes'];  }                  
    if($auctionyesid == 1){
         $bidchecked = "checked";         
    }
    else{
        $bidchecked = "";
    }
    if(isset($_POST['stockide'])){
        $queryd = "DELETE FROM transactbids WHERE stockid = '$stockide'";
        $resultd = mysqli_query($dbc, $queryd);   
    }
    
    //load stock item
    require_once("loadstock.php");
    
  }

  //delete a stock..................>>
  if (!empty($_POST["idnum"])) {
    $idnum= $_POST["idnum"];
    if ($idnum > 0) { 

      $sqlch = "SELECT stockid FROM orders WHERE stockid=$idnum"; 
      $stockch = $dbc->query($sqlch);     
      $countorders =0;    
      while($rowch = $stockch->fetch_assoc()) { 
          $countorders  = $countorders+1;
      }
      if($countorders<1) {
          //delete pics first....
          $sqlpic = "SELECT picture,gridpic FROM stocktypes WHERE stockid=$idnum"; 
          $stockpic = $dbc->query($sqlpic);           
          while($rowpic = $stockpic->fetch_assoc()) { 
              $file_pic = $rowpic["picture"]; 
              $grid_pic = $rowpic["gridpic"]; 
              // using unlink() function to delete a file 
              if(isset($file_pic)){
                unlink(MM_UPLOADPATH .$file_pic);
              }
              
              // using unlink() function to delete a file 
              if(isset($grid_pic)){
                unlink(MM_UPLOADPATH .$grid_pic);
              }
          }          
            
          $sqldel = "DELETE FROM stocktypes WHERE stockid=$idnum";
          if (mysqli_query($dbc, $sqldel)) {
            echo "stock deleted successfully. ";
          } else {
            echo "Error deleting record: " . mysqli_error($dbc);
          }
          $sqldelkpas = "DELETE FROM featmappings WHERE stockid=$idnum";
          if (mysqli_query($dbc, $sqldelkpas)) {
            echo "Specification's also gone. ";
          } else {
            echo "Error deleting record: " . mysqli_error($dbc);
          }
      }
      else {
        echo "Cannot delete, this stock item is in process.";
      }
           
    }
    $_POST["idnum"]=0;
  }

  // edit stock
  if (!empty($_POST["idedit"])) {
    $idedit= $_POST["idedit"];


    if ($idedit > 0) { 
          $sqledit = "SELECT * FROM stocktypes WHERE stockid=$idedit";             

          $editstock = $dbc->query($sqledit);         
          while($rowe = $editstock->fetch_assoc()) {
                   $stockide = $rowe["stockid"];
                   $active = $rowe['active'];
                   if($active ==1){
                      $activechecked = "checked";
                   }
                   else {
                      $activechecked = "";
                   }

                   $stockname = $rowe["stockname"];
                   $stockcode = $rowe["stockcode"];
                   $stockdescribe = $rowe["description"];                   
                   $stockcategory = $rowe["category"]; 
                   $stocksubcategory = $rowe["subcategory"]; 
                   $stockapps = $rowe["applications"];    
                   $stockspecs = $rowe["specifications"];    
                   
                   $retailprice = $rowe["retailprice"]; 
                   $old_picture = $rowe['picture'];
                   $gridpic = $rowe['gridpic'];
                   if (empty($rowe['picture'])) {
                       $old_picture = "nopic.jpg";
                   }

                   $retailprice = $rowe['retailprice'];
                   $costprice = $rowe['costprice'];
                   $weight = $rowe['weight'];
                   $width = $rowe['width'];
                   $height = $rowe['height'];
                   $depth = $rowe['depth'];
                   $supplierid = $rowe['supplierid'];
                   $brandid = $rowe['brandid'];
                   $artistid = $rowe['artistid'];
                   $auctionyesid = $rowe['auctionyesid'];
                   
                   if($rowe['auctionyesid'] == 1)
                   {
                        $bidchecked = "checked";
                   }
                   else {
                        $bidchecked = "";
                   }                   

                   
                   $stockchoice1 = $rowe["choiceone"];
                   $stockchoice2 = $rowe["choicetwo"];
          }  


    }
    $_POST["idedit"]=0;

  }

  //catch and load stock attributes  ..>>
  if (!empty($_POST["setcount"])) {
    $sticki = ['sticki0','sticki1', 'sticki2', 'sticki3', 'sticki4', 'sticki5', 'sticki6', 'sticki7', 'sticki8', 'sticki9', 'sticki10', 'sticki11', 'sticki12', 'sticki13', 'sticki14', 'sticki15', 'sticki16','sticki17','sticki18', 'sticki19', 'sticki20', 'sticki21', 'sticki22', 'sticki23', 'sticki24', 'sticki25', 'sticki26', 'sticki27', 'sticki28', 'sticki29', 'sticki30'];
    $stickn = ['stickn0', 'stickn1', 'stickn2', 'stickn3', 'stickn4', 'stickn5', 'stickn6', 'stickn7', 'stickn8', 'stickn9', 'stickn10', 'stickn11', 'stickn12', 'stickn13', 'stickn14', 'stickn15', 'stickn16','stickn17', 'stickn18', 'stickn19', 'stickn20', 'stickn21', 'stickn22', 'stickn23', 'stickn24', 'stickn25', 'stickn26', 'stickn27', 'stickn28', 'stickn29', 'stickn30'];
    $idedit = $_POST['stockattredit'];   //stockid 
    $setcount = $_POST["setcount"]; //setcount
    $groupid = $_POST["attrtypeholder"]; //setcount    

    $sqldel = "DELETE FROM featmappings WHERE stockid='$idedit' AND groupid ='$groupid'";
    if (mysqli_query($dbc, $sqldel)) {
      echo "Old item attributes deleted. ";
    } else {
      echo "Error deleting record: " . mysqli_error($dbc);
    }

    for($i=1;$i<= $setcount;$i++) {
        if(isset($_POST[$sticki[$i]])){
          $setattr[$i] = 1; //checked items
          $setattrid[$i] = $_POST[$stickn[$i]]; //featoptids..
          //check option name
          $sqlopt = "SELECT optionname FROM featoptnames WHERE optionid = '$setattrid[$i]'";
          $resultopt = $dbc->query($sqlopt);
          while($rowopt = $resultopt->fetch_assoc()) {
              $optionnaam = $rowopt["optionname"];
          }
          
            $qcat =  "INSERT INTO featmappings (stockid,optionid,optionname,groupid) VALUES ('$idedit', '$setattrid[$i]', '$optionnaam', '$groupid')";
            mysqli_query($dbc, $qcat);                    
        }
        else {
          
        }        
    }
    echo "New item attributes added. ";
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
    $colorin ="";
    if($row['active'] == 0){$colorin = "redrover";}
    echo '<tr class="results '.$colorin.'">';
      echo '<td  width="30px" style="cursor:pointer; "  class="glyphend" onclick="myDelete('.$row['stockid'].');" >x&nbsp;</td>';   
    echo '<td valign="top" style="cursor:pointer;" width="18%" onclick="myView('.$row['stockid'].');">' . $row['stockname'] . '</td>';
    echo '<td valign="top" style="cursor:pointer;" width="50%" onclick="myView('.$row['stockid'].');">' . substr($row['description'], 0, 75) . '...</td>';
    echo '<td valign="top" width="9%" >' . $row['category'] . '</td>';
    echo '<td valign="top" width="9%" >' . $row['subcategory'] . '</td>';
    echo '<td valign="top" width="12%">' . substr($row['creationdate'], 0, 10) . '</td>';
       echo '<td  width="30px" ><button ><span style="cursor:pointer; " class="glyphicon glyphicon-pencil" onclick="myEdit('.$row['stockid'].');"></span></button></td>';
       echo '<td  width="30px" ><button><span style="cursor:pointer;" class="glyphicon glyphicon-equalizer" onclick="myAttrEdit('.$row['stockid'].');"></span></button></td>';
       

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
  exit();
}
?>
<!--- recorder -->
<div class="line" id="line">
<div class="recorder" id="recorder">
    <form enctype="multipart/form-data" id="myFormLoader" method="post" action="adinventory.php" > 
      
      <input type="hidden" name="stockide" value="<?php if (!empty($stockide)) echo $stockide; ?>">

      <label>Stock Name:</label><input type="text" id="workoutblue" class="" required name="stockname" value="<?php if (!empty($stockname)) echo $stockname; ?>">&nbsp;<br>
      <label>Stockcode:</label><input type="text" id="" class="" name="stockcode" value="<?php if (!empty($stockcode)) echo $stockcode; ?>"><br>

      <input type="hidden" name="old_picture" value="<?php if (!empty($old_picture)) echo $old_picture; ?>" />
      <label for="screenshot">The photo:</label>
      <input type="file" id="screenshot" name="screenshot" />
      <input type="hidden" name="MAX_FILE_SIZE" value="1200000" />
      <?php 
        if (!empty($old_picture)) {
          echo '<img class="picbox" src="' . MM_UPLOADPATH . $old_picture . '" alt="Stock Picture" />';
        }
      ?>
      <label for="stockdescribe">Stock Description</label> 
        <textarea name="stockdescribe" form="myFormLoader" class="stockdescribe" rows="2" cols="55" required ><?php if (!empty($stockdescribe)) echo $stockdescribe; ?></textarea>

      <input name="tool" value="" hidden><br>
      
      <label>Category:</label><input type="text" required name="category" class="skinny" value="<?php if (!empty($stockcategory)) echo $stockcategory; ?>">      
     
      <label>Subcategory:</label><input type="text" name="subcategory" class="skinny" value="<?php if (!empty($stocksubcategory)) echo $stocksubcategory; ?>"><br>
      
      <label>Retail price:</label><input type="text" required name="retailprice" class="skinny" value="<?php if (!empty($retailprice)) echo $retailprice; ?>">
      <label>Cost price:</label><input type="text" required name="costprice" class="skinny" value="<?php if (!empty($costprice)) echo $costprice; ?>"><br>

      
            
      <br>
      <label for="">Applications</label> 
        <textarea name="stockapps" form="myFormLoader"  rows="1" cols="45" ><?php if (!empty($stockapps)) echo $stockapps; ?></textarea><br>
      <label for="">Specifications</label> 
        <textarea name="stockspecs" form="myFormLoader"  rows="1" cols="45" ><?php if (!empty($stockspecs)) echo $stockspecs; ?></textarea><br>

      <label>Customer</label><br>
      <label>Choice 1:</label>
      <select name="choice1" >
        <option value="None">Choose</option>
      <?php 
        $sqlcat = "SELECT * FROM featoptgroups";  
        $resultcat = $dbc->query($sqlcat); 
        
        while($rowcat = $resultcat->fetch_assoc()) {           
            if($rowcat['groupid'] == $stockchoice1) {
              echo"<option value='".$rowcat['groupid']."' selected>".$rowcat['groupname']."</option>";
            }
            else {
              echo"<option value='".$rowcat['groupid']."'>".$rowcat['groupname']."</option>";
            }         
        }
        
      ?>
      </select>        
      <label>Choice 2:</label>
      <select name="choice2" >
        <option value="None">Choose</option>
      <?php 
        $sqlcat2 = "SELECT * FROM featoptgroups";  
        $resultcat2 = $dbc->query($sqlcat2); 
        
        while($rowcat2 = $resultcat2->fetch_assoc()) {           
            if($rowcat2['groupid'] == $stockchoice2) {
              echo"<option value='".$rowcat2['groupid']."' selected>".$rowcat2['groupname']."</option>";
            }
            else {
              echo"<option value='".$rowcat2['groupid']."'>".$rowcat2['groupname']."</option>";
            }         
        }
        
      ?>
      </select><br>

      <label>Brand:</label>
      <select name="brandid" >
        <option value=0>Not needed</option>
      <?php 
        $sqlbrands = "SELECT * FROM brandnames ";  
        $resultbands = $dbc->query($sqlbrands); 
        
        while($rowsbats = $resultbands->fetch_assoc()) {           
            if($rowsbats['brandid'] == $brandid) {

              echo"<option value='".$rowsbats['brandid']."' selected>".$rowsbats['brandname']."</option>";
            }
            else {
              echo"<option value='".$rowsbats['brandid']."'>".$rowsbats['brandname']."</option>";
            }         
        }
        
      ?>
      </select>
      
      <label>Artist:</label>
      <select name="artistid" >
        <option value=0>Not needed</option>
      <?php 
        $sqlartists = "SELECT * FROM artists ";  
        $resultarts = $dbc->query($sqlartists); 
        
        while($rowarms = $resultarts->fetch_assoc()) {           
            if($rowarms['artistid'] == $artistid) {

              echo"<option value='".$rowarms['artistid']."' selected>".$rowarms['artistname']."</option>";
            }
            else {
              echo"<option value='".$rowarms['artistid']."'>".$rowarms['artistname']."</option>";
            }         
        }
        
      ?>
      </select>
      <br>
      <label>Supplier:</label>
      <select name="supplierid" >
        <option value=0>Not needed</option>
      <?php 
        $sqlcats = "SELECT * FROM suppliers ";  
        $resultcats = $dbc->query($sqlcats); 
        
        while($rowscats = $resultcats->fetch_assoc()) {           
            if($rowscats['supplierid'] == $supplierid) {

              echo"<option value='".$rowscats['supplierid']."' selected>".$rowscats['businessname']."</option>";
            }
            else {
              echo"<option value='".$rowscats['supplierid']."'>".$rowscats['businessname']."</option>";
            }         
        }
        
      ?>
      </select><br>
      <b>Weight:</b>
    <!-- <select>
      <option value="0.1">Less than 100grams</option>
      <option value="1">Less than 1kg</option>
      <option value="2">Less than 2kg</option>
      <option value="5">Less than 5kg</option>
      <option value="10">Less than 10kg</option>
      <option value="20">Less than 20kg</option>
      <option value="50">More than 20kg</option>
    </select> -->
      <input type="number" required min="0.01" step="0.01" name="weight" class="weight" value="<?php if (!empty($weight)) echo $weight; ?>">kg <b>Vol:</b>
      <b>W:</b><input type="number" class="weight" name="width" value="<?php if (!empty($width)) echo $width; ?>">x
      <b>H:</b><input type="number" class="weight"  name="height" value="<?php if (!empty($height)) echo $height; ?>">x
      <b>B:</b><input type="number"class="weight"  name="depth" value="<?php if (!empty($depth)) echo $depth; ?>">cm
      <br>
      <?php echo"<input type='checkbox' name='active' value='1' ".$activechecked.">"; ?>Active
      <?php echo"<input type='checkbox' name='bidsyes' value='1' ".$bidchecked.">"; ?>Allow auction bids<br>
      <input  type="submit" value="Save stock" id="submitred" name="submit" onclick=" return myRelax();" /><input type="button" id="reset" onclick="blueDisplayClear(); blueDisplayClear();"  value="Clr" > 
      <input type="button" id="exit" name="" class="exit" value="X" onclick="tRecorder = 0; showRecorder(0);">
    </form>
</div>
</div>

<form class="loader" id="loader" action="" method="post"><!-- new group attribute loader -->
  <table>
      <tr><td><input type="text" name="feattype" placeholder="Add type "></td><td><button>Add</button></tr>
      <?php 
          $typo =0;
          for($k=1;$k <= $ktype;$k++){
              $typo = intval($typeid[$k]);
              echo "<tr style='width: 100px;'><td class='graycell'>".$typenames[$k]."</td><td><input type='button' value='edit' onclick='setLoader(1,".$typo.");'></td></tr>";
          }
      ?>
  </table>
      <input name="tool" value="loader" hidden><br>
      <div id="typecontainer"></div>
      <input type="button" id="exit" name="" class="exit" value="X" onclick="tRecorder = 0; showLoader(0);"> 
</form>
<form class="setter" id="setloader" action="" method="post"><!-- new attribute loader -->   
      <div id="grpholder">hello we have a problem</div> 
      <table id="setTable">
      <tr><td width="170px"></td><td></td><td></td></tr>
      </table>
      <input type='number' id='typeholder' name='typeholder' style='width: 40px' hidden>
      <input name="tool" value="setloader" hidden><br>      
      <input type="button" id="exit" name="" class="exit" value="X" onclick="stRecorder = 0; setLoader(0,0);">
</form>
<form id="brander" enctype="multipart/form-data" class="brander" action="" method="post">
      <label>Brand name</label>
      <input type="text" name="brandname">   
      <input type="file" name="brandpic">
      <input type="hidden" name="MAX_FILE_SIZE" value="6000000" />
      <label>Supplier:</label>
      <select name="supplieridb" >
        <option value=0>Choose supplier</option>
      <?php 
        $sqlcats = "SELECT * FROM suppliers ";  
        $resultcats = $dbc->query($sqlcats); 
        
        while($rowscats = $resultcats->fetch_assoc()) {           
            if($rowscats['supplierid'] == $supplierid) {

              echo"<option value='".$rowscats['supplierid']."' selected>".$rowscats['businessname']."</option>";
            }
            else {
              echo"<option value='".$rowscats['supplierid']."'>".$rowscats['businessname']."</option>";
            }         
        }
        
      ?>
      </select><br>
      <button>submit</button>
      <input type="button" id="exit" name="" class="exit" value="X" onclick="sBrander = 0; showBrander(0);">
</form>
<form id="artist" enctype="multipart/form-data" class="brander" action="" method="post">
      <label>Artist</label>
      <input type="text" name="artistname">   
      <input type="file" name="artistpic">
      <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
      <label>Supplier:</label>
      <select name="supplieridb" >
        <option value=0>Choose supplier</option>
      <?php 
        $sqlcats = "SELECT * FROM suppliers ";  
        $resultcats = $dbc->query($sqlcats); 
        
        while($rowscats = $resultcats->fetch_assoc()) {           
            if($rowscats['supplierid'] == $supplierid) {

              echo"<option value='".$rowscats['supplierid']."' selected>".$rowscats['businessname']."</option>";
            }
            else {
              echo"<option value='".$rowscats['supplierid']."'>".$rowscats['businessname']."</option>";
            }         
        }
        
      ?>
      </select><br>
        <textarea form="artist" class="leonardo" name="artistdescribe"></textarea><br>
      <button>submit</button>
      <input type="button" id="exit" name="" class="exit" value="X" onclick="sBrander = 0; showArtist(0);">
</form>

<form class="attributor" id="attributor" action="" method="post"><!-- prep: choose group for stock item -->  
      <div ><b><?php if(isset($stockname)){ echo $stockname. " " .$stockcode;} ?></b></div><br>
      <label>Choice:</label>
      <select name="typeidoo" >
        <option value="None">Choose</option>
      <?php 
        $sqlcat1 = "SELECT * FROM featoptgroups WHERE groupid='$stockchoice1'";  
        $resultcat1 = $dbc->query($sqlcat1);         
        while($rowcat1 = $resultcat1->fetch_assoc()) {              
              echo"<option value='".$rowcat1['groupid']."' selected>".$rowcat1['groupname']."</option>";                     
        }

        $sqlcat2 = "SELECT * FROM featoptgroups WHERE groupid='$stockchoice2'";  
        $resultcat2 = $dbc->query($sqlcat2);         
        while($rowcat2 = $resultcat2->fetch_assoc()) {              
              echo"<option value='".$rowcat2['groupid']."' selected>".$rowcat2['groupname']."</option>";                     
        }
        
      ?>
      </select> 
      <input name="tool" value="attrsetter" hidden><br>
      <input type="text" name="stockidoo" value="<?php echo $stockide; ?>" hidden>
      <div id="typecontainer"></div>
      <button>submit</button>
      <input type="button" id="exit" name="" class="exit" value="X" onclick="atRecorder = 0; showAttributor(0);"> 
</form>
<form class="attrsetter" id="attrsetter" action="" method="post"><!-- Get ready to load stock attributes...-->
      <div id="gripholder"></div> 
      <table id="setattrTable">
      <tr><td width="170px"></td><td></td><td></td></tr>
      </table>
      <input type='number' id='attrtypeholder' name='attrtypeholder' hidden>
      <input name="tool" value="attrsetter" hidden><br>      
      <input id="setcount" name="setcount" hidden><br> 
      <input name="stockattredit" value="<?php echo $stockide; ?>" hidden><br> 
      <button>Save</button>
      <input type="button" id="exit" name="" class="exit" value="X" onclick="attrRecorder = 0; setAttrSetter(0,0);">
</form>

</main>
</body>
<form action='adinventory.php' id='myFormEdit' method='post'>
    <input type='number' id='idedit' name='idedit' style='width: 40px' hidden>
    <input name="tool" value="stockloader" hidden>
</form>
<form action='adinventory.php' id='myFormAttrEdit' method='post'>
    <input type='number' id='idattredit' name='stockidu' style='width: 40px' hidden>    
    <input name="tool" value="attributor" hidden>
</form>
<form action='adinventory.php' id='myFormDel' method='post'>
  <input type='number' id='iddelete' name='idnum' style='width: 40px' hidden>
</form>
<form action='stockitem.php' id='myFormView' method='post'>
    <input type='number' id='idviewer' name='idviewgo' style='width: 40px' hidden>    
</form>
<div id="hero"></div>

<?php  mysqli_close($dbc); ?>
</html>



<script >
var tRecorder = 0;
var stRecorder = 0;
var slRecorder = 0;
var sBrander = 0;
var atRecorder = 0;
var artRecorder = 0;
var attrRecorder = 0;
var quantumA = 0; //art var
var quantumQ = 0;
var quantumP = 0;
var quantumR = 0;
var carLoaded = 0;
  //if task posts, tell the good news and correctly set time and recorder toggles
  function startMe() {
      //recorder is main inventory loader
      if (toolUsed == "stockloader") {
          showRecorder(1);
      }
      else {
          tRecorder = 1;
      }
      if (toolUsed == "loader" ) {
          showLoader(1);
      }
      else {
          stRecorder = 1;
      }
      if (toolUsed == "artistloader" ) {
          showArtist(1);
      }
      else {
          artRecorder = 1;
      }
      if (toolUsed == "setloader") {
          setLoader(1, typeIDone);
      }
      else {
          slRecorder = 1;
      }
      if (toolUsed == "attributor" ) {
          showAttributor(1);
      }
      else {
          atRecorder = 1;
      }
      if (toolUsed == "attrsetter" ) {
          setAttrSetter(1,typeIDone);
      }
      else {
          atRecorder = 1;
      }

  }
  //show inventory details
  function showRecorder(quantumQ) {
      var yourUl = document.getElementById("line");
      if (tRecorder == 1 | quantumQ == 1) {
          yourUl.style.display = 'block';}
      else {
          yourUl.style.display = 'none';}              
      tRecorder = 1 - tRecorder;
  }
    //show recorder
  function showLoader(quantumP) {
      var yourUl = document.getElementById("loader");       
      if (stRecorder == 1 | quantumP == 1) {
          yourUl.style.display = 'block';}          

      else {
          yourUl.style.display = 'none';}              
      stRecorder = 1 - stRecorder;
  }
    //show recorder
  function showBrander(quantumP) {
      var yourUl = document.getElementById("brander");       
      if (sBrander == 1 | quantumP == 1) {
          yourUl.style.display = 'block';}          

      else {
          yourUl.style.display = 'none';}              
      sBrander = 1 - sBrander;
  }
     //show recorder
  function showArtist(quantumP) {
      var yourUl = document.getElementById("artist");       
      if (artRecorder == 1 | quantumA == 1) {
          yourUl.style.display = 'block';}          

      else {
          yourUl.style.display = 'none';}              
      artRecorder = 1 - artRecorder;
  }
  function setLoader(quantumR, typeR) {
      
      var yourUl = document.getElementById("setloader");
      var yourUlg = document.getElementById("grpholder");
      var yourUls = document.getElementById("typeholder");      
      var table = document.getElementById("setTable");
      if ( quantumR == 1) {
          yourUl.style.display = 'block';
          yourUls.value = typeR;
          yourUlg.innerHTML = typeNames[typeR];
          table.innerHTML = "<table ><tr><td></td><td ><input type='text' id='tagit' name='attrholder' required></td><td ><button>Add<button></td></tr></table> ";     
          
          for(i=1;i<=countOptions[typeR];i++) {

              rowt = table.insertRow(i);
              var cell1 = rowt.insertCell(0);
              var cell2 = rowt.insertCell(1);
              var cell3 = rowt.insertCell(2);              
              cell1.innerHTML = i;
              cell2.innerHTML = "<span >"+optionNames[typeR][i]+"</span>";     
              cell3.innerHTML = "<input type='button'  id='"+i+"' value='-' >";           
              
              cell2.style.background = "gray";
          }                    

      }      
      else {
          yourUl.style.display = 'none';}
      carLoaded = typeR;              
      slRecorder = 1 - slRecorder;
  }
    //show recorder
  function showAttributor(quantumP) {
      var yourUl = document.getElementById("attributor");       
      if (atRecorder == 1 | quantumP == 1) {
          yourUl.style.display = 'block';}          

      else {
          yourUl.style.display = 'none';}              
      atRecorder = 1 - atRecorder;
  }
  //show stock item attributes selected
  function setAttrSetter(quantumR, typeR) {
      
      var yourUI = document.getElementById("attrsetter"); //shower      
      var yourUItid = document.getElementById("attrtypeholder");  //size, color, etc          
      var yourUItname = document.getElementById("gripholder"); //name for type
      var yourUIsCount =  document.getElementById("setcount");
      var table = document.getElementById("setattrTable"); //table
      if (attrRecorder == 1 | quantumR == 1) {
          yourUI.style.display = 'block';
          yourUItid.value = typeR;
          yourUItname.innerHTML = typeNames[typeR];
          yourUIsCount.value  = countOptions[typeR];
          table.innerHTML = "<table ><tr><td></td><td ></td><td ></td></tr></table> ";     
          
          for(i=1;i<=countOptions[typeR];i++) {

              rowt = table.insertRow(i);
              var cell1 = rowt.insertCell(0);
              var cell2 = rowt.insertCell(1);
              var cell3 = rowt.insertCell(2);              
              var cell4 = rowt.insertCell(3);   
              var cell5 = rowt.insertCell(4);   
              cell1.innerHTML = i;
              cell2.innerHTML = "<span style='width: 100px;'>"+danceName[i]+"</span>";     
              cell3.innerHTML = "<input type='number'  name='stickn"+i+"'   value='"+danceId[i]+"' hidden>"; 
              if(danceMove[i]>0) {
                  cell4.innerHTML = "<input type='checkbox'  name='sticki"+i+"' checked >";           
              }
              else{
                cell4.innerHTML = "<input type='checkbox'  name='sticki"+i+"'  >";           
                
              }
              cell5.innerHTML = "<span></span>";   
              
              cell2.style.background = "gray";
          }                    

      }      
      else {
          yourUI.style.display = 'none';}
      carLoaded = typeR;              
      attrRecorder = 1 - attrRecorder;
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