
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cients</title>

  <!-- Responsive viewport tag, tells small screens that it's responsive -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Normalize.css, a cross-browser reset file 
  <link href="https://cdnjs.cloudflare.com/ajax/libs/normalize/4.1.1/normalize.css" rel="stylesheet">-->

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">


</head>
<style>

.chosen2:nth-child(3) {
  color: yellow;
}
</style>
<body>
  <?php require_once("heading.php"); ?>
  <main>
  <?php require_once("profilenav.php"); ?> 

<?php

$searcher = 0;
$searchstatus = "";


 // Grab the profile data from the POST
if (isset($_COOKIE['firstname']) && isset($_COOKIE['username']) ) {

  require_once('appvars.php');
  require_once('basecamp/connectvars2.php');
  $connex = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  // Check connection
  if ($connex->connect_error) {
      die("Connection failed: " . $connex->connect_error);
  } 
  $loadclient = 0;

      

  // sql to delete a line record
  if (!empty($_POST["iddelete"])) {
    $idnum= $_POST["iddelete"];
    if ($idnum > 0) { 
     
      $sqldel = "DELETE FROM subcategories WHERE subid=$idnum";
      if (mysqli_query($connex, $sqldel)) {
        echo "categories deleted successfully. ";
      } else {
        echo "Error deleting record: " . mysqli_error($connex);
      }
           
    }
    $_POST["iddelete"]=0;
  }
    //edit category
  if (!empty($_POST["idedit"])) {
         $idedit=$_POST["idedit"];
         $catname=$_POST["subname"];
         $qcat = "UPDATE subcategories SET name = '$catname' WHERE subid = '$idedit'";
         mysqli_query($connex, $qcat);    
         echo "<span>Category updated. </span>";    
  }

  $up =0;
  
  //check user rights
  $sqlup = "SELECT * FROM theshopuser WHERE userid = $useride";
  $resultup = $connex->query($sqlup);
  while($row = $resultup->fetch_assoc()) {
    if($row['userstatus'] == "superuser") {
        $up = 1;
        echo"<span style='float: right; color: red;'>Admin view</span>";
    }
  }
  

 

  //start main listing..
  $counter=0;
    //fetch the list from the database 
  //check privileges
  if($up>0) {
    $sqlq = "SELECT * FROM subcategories ORDER by name";
    //check if searching
    if($searcher > 0) {
        $sqlq = "SELECT * FROM subcategories ORDER by name";
    }
  }
  else {  
    $sqlq = "SELECT * FROM subcategories ORDER by name";
    //check if searching
    if($searcher > 0) {
        $sqlq = "SELECT * FROM subcategories ORDER by name";
    } 
  }
  $resultq = $connex->query($sqlq);
  echo "<h4>subcategory Information</h4>";
  echo "<table width='100%' style='  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'> ";
  echo "<tr  class='top'><td width='20px' ></td><td width='240px'>category name</td><td>catid</td><td></td></tr>";

  $businessname = array();
 
  while($row = $resultq->fetch_assoc()) {

         $counter = $counter+1;
         $subname[$counter] = $row["name"];
         $subid[$counter] = $row["subid"];                       
         $catid[$counter] = $row["catid"];
      
         echo "<tr style='border-bottom: solid 1px lightgray;' id='therow".$counter."'>";
         if($up >-1) {
            echo "<td class='redx' onclick='myDelete(".$subid[$counter].");'>x</td>";
         }
         else{
            echo "<td></td>";
         }
         echo "<td id='clientnamo".$counter."'>".$subname[$counter]."</td>";
         echo "<td>".$catid[$counter]."</td>";
         if($up >-1) {
            echo "<td style='cursor: pointer;' onclick='laGoon(".$counter.",".$subid[$counter].");'><button>edit</button></td>";
         }
         echo "</tr>";
         
  }
  $connex->close();
  if($counter <1) {echo "<tr><td></td><td>You have no clients<td></tr>";}

  //ready new input
  echo "<tr><form action='clients.php' id='myFormAdder' method='post'><td></td>";
  echo "<td><input type='text' class='busname' name='subname' value='";
  if(isset($businessnaam) ){echo $businessnaam;}
  echo "'>"."</td>";
   echo "<td></td>";
  echo "<td><input type='button' class='addbutton' value='add' onclick='this.form.submit();'></td></form></tr>";
  
  echo "<tr class='total'><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>";
  echo"</table>";  
}
else {
  echo "You are not logged in.";
}
//<embed src="buyerlist.php" width="300" height="400">
?>

<form action='subcategories.php' id='myFormDel' method='post'>
  <input type='number' id='iddelete' name='iddelete' style='width: 40px' hidden>
</form>

<form action='subcategories.php' id='myFormEdit' method='post' hidden>
  <input id="idedit" type="text" name="idedit">
  <input id="subname" type="text" name="subname">
  
</form>
<dir id="tester"></dir>

</main>

  <form action='subcategories.php' id='myFormView' method='post'>
    <input type='number' id='idviewgo' name='idviewgo' style='width: 40px' hidden>
  </form>
</body>
</html>

<script type="text/javascript">

     
    
var oldabc=0;
var quants = [];

function myView(littlejohn) {
    document.getElementById("idviewgo").value = littlejohn;
    document.getElementById("myFormView").submit();
}
function stopShow() {
   document.getElementById("displaybox").style.display = "none";
}


function myDelete(littlejohn) {
    if (confirm("Are you sure Robinhood?") == true) {
      document.getElementById("iddelete").value = littlejohn;
      document.getElementById("myFormDel").submit();
    }   
}

function formEditStatus(abc, littlejohn) {
    abc = "selstate"+abc;
    selectedStatus = document.getElementById(abc).value; 

    document.getElementById("idstatus").value = littlejohn;    
    document.getElementById("pointerit").value = selectedStatus;
    document.getElementById("formEditStatus").submit();
}
function  changeColor(abc) {
    abc = "changecolor"+abc;
    document.getElementById(abc).style.color = "green";
}
//changer 
function laGoon(abc, littlejohn){
    var clientnamo = "clientnamo"+abc;   

    var urhere = "therow"+abc; 

    var clientname =  document.getElementById(clientnamo).innerHTML;        
    
    document.getElementById(urhere).innerHTML = "<tr><td><input type='number' name='idthumb'  value='"+urhere+"' hidden></td>"+"<td><input type='text' id='clientnameso' class='busname' value='"+clientname+"'></td><td><button class='glyphicon glyphicon-ok smallgreen' onclick='addSubmit("+littlejohn+");'></button></td></tr>"; 
    
}
function addSubmit(littlejohn) {    
    
      document.getElementById("idedit").value = littlejohn;
      document.getElementById("subname").value = document.getElementById("clientnameso").value;              

      document.getElementById("myFormEdit").submit(); 
}

</script>
<style type="text/css">
  html  {
    font-family: 'Roboto';
  }
  body { 
  min-width:920px;        /* Suppose you want minimum width of 1000px */
  width: auto !important;  /* Firefox will set width as auto */
  width:920px; 
  min-height: 700px;
   background: linear-gradient(
        to bottom,
        rgba(0, 0, 0, 0),
        rgba(30, 30, 30,0.2)
      )
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


  .finder {
    color: black;
     width: 70px;
    height: 23px;
  }
  button {
    height: 23px;
    vertical-align: bottom;
    color: darkgray;
  }
  .addbutton {
    width: 50px;
    color: darkgray;
  }
  .addbutton:hover {
    color:lightgreen;
  }

  .nextline {
    clear: left;
  }
  input {
    width: 100px;
  }
  input:hover {
    background-color: lightgray;
  }
   input.busname {
    width: 200px;
  }
  input.postcode {
    width: 50px;
  }
   input.address {
    width: 150px;
  }
  main {
    margin-left: 50px;
    margin-right: 50px;
    color: darkgray;
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
  .mirror {
    color: red;
  }
  tr:hover {
    background-color: lightgray;
  }
  table {
    width :400px;
    color: black;

  }
  select {
    background-color: ghostwhite;
  }
  .top {
    background-color: orange;
    border-bottom-style: outset; 
    color:white;
  }
  .total {
    border-top: gray 2px solid;
    background-color: orange;
  }
  .total:hover {
  
  }
  .smallgreen {
    font-size: 11px;
    color: green;
  }
   .purchbtn {
    position: relative;
    left: 250px;
    background-color: orange;
  }
  .displaybox {
    position: absolute;
    background-color:   lightgray;
    opacity: 1;
    display: box;
    width: 250px;
    height: 300px;
    top: 80px;
    right: 80px;
    border: lightgray solid 3px;
    border-radius: 6px;
    padding: 5px;
    box-shadow: 2px 2px 2px; 
    z-index: 1;
    color: black;
  }
  .displayin {
    width: 130px;
  }
  .displaybox:hover {
    box-shadow: 4px 4px 4px;
  }
  .redx {
    color: red;
    float: right;
    width: 15px;
    text-align: center;
    cursor: pointer;
  }
  label {
    width: 90px;
  }
  .emailit {
    max-width: 130px;
    overflow: hidden;
    font-size: 10px;
    color: red;
  }
</style>