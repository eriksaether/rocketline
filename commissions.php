
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>commissions</title>

  <!-- Responsive viewport tag, tells small screens that it's responsive -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Normalize.css, a cross-browser reset file 
  <link href="https://cdnjs.cloudflare.com/ajax/libs/normalize/4.1.1/normalize.css" rel="stylesheet">-->

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">


</head>
<style>

.chosen2:nth-child(2) {
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

  if (isset($_POST['commissionname'])) {

      
    // Grab the profile data from the POST
    $commissionname = cleanMyPost($_POST['commissionname']);
    $commrate = $_POST['commnumber'];
    $commbase = $_POST['commbase'];
    $commstatus = cleanMyPost($_POST['commstatus']);
    

    if (!empty($commissionname) && empty($_POST['thumbit']) ) {

      // Make sure someone isn't already registered using this username
      $query = "SELECT * FROM commissions WHERE commissionname = '$commissionname'";
      $data = mysqli_query($connex, $query);
      if (mysqli_num_rows($data) == 0) {
        // The username is unique, so insert the data into the database
        $query = "INSERT INTO commissions (commissionname, commrate, commbase, commstatus, ownerid, startdate) VALUES ('$commissionname', '$commrate', '$commbase', '$commstatus', $useride, NOW())";
        if(mysqli_query($connex, $query)) {
          // Confirm success with the user
          echo '<p class="mirror">Your new account has been successfully created.</p>';  
        }
        else {
          echo '<p class="mirror">Error loading data.</p>';
        }    
      }
      else {
        // An account already exists for this username, so display an error message
        echo '<p class="mirror">An account already exists for this username. Please use a different address.</p>';        
      }
    }
    
  }
 

       //update database with commission line correction
        if (isset($_POST['commissionname']) && isset($_POST['thumbit']) && isset($usernaam)) {

            $idedit= $_POST["thumbit"];
            $commissionname= cleanMyPost($_POST["commissionname"]);
            
            
            $queryvee = "UPDATE commissions SET commissionname = '$commissionname', commrate = '$commrate', commbase = '$commbase', commstatus = '$commstatus'  WHERE commid = '$idedit' ";
            mysqli_query($connex, $queryvee);
            echo "commission:".$idedit." updated.";
        }        

        // sql to delete a line record
        if (!empty($_POST["iddelete"])) {
          $idnum= $_POST["iddelete"];
          if ($idnum > 0) { 
           
            $sqldel = "DELETE FROM commissions WHERE commid=$idnum";
            if (mysqli_query($connex, $sqldel)) {
              echo "Item deleted successfully. ";
            } else {
              echo "Error deleting record: " . mysqli_error($connex);
            }
                 
          }
          $_POST["iddelete"]=0;
        }

  $up =0;
  $commid =9999;
  //check user rights
  $sqlup = "SELECT userstatus FROM theshopuser WHERE userid = $useride";
  $resultup = $connex->query($sqlup);
  while($row = $resultup->fetch_assoc()) {
    if($row['userstatus'] == "superuser") {
        $up = 1;
    }
  }
  
  
 

  echo "<h4>Commission Information</h4>";

  //start main listing..
  $counter=0;
    //fetch the list from the database 
  //check privileges
  if($up>0) {
    $sqlq = "SELECT * FROM commissions";
    echo"<span style='float: right; color: red;'>Admin view</span>";
    //check if searching
    if($searcher > 0) {
        $sqlq = "SELECT * FROM commissions WHERE commstatus = '$searchstatus' ";
    }
  }
  else {  
    $sqlq = "SELECT * FROM commissions WHERE ownerid = '$useride'";
    //check if searching
    if($searcher > 0) {
        $sqlq = "SELECT * FROM commissions WHERE ownderid = '$useride' AND commstatus = '$searchstatus'";
    } 
  }
  $resultq = $connex->query($sqlq);
  
  echo "<table width='100%' style='  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'> ";
  echo "<tr  class='top'><td width='20px' ></td><td width='240px'>commission name</td><td>RuleId&nbsp;</td><td>Comm%</td><td class='' width='100px'>Basis</td><td class='statusinfo' width='100px'>Status</td><td></td><td></td></tr>";

  $commissionname = array();  
  $commstatus = array();
  $commrate = array();
  $commbase = array();
  while($rowq = $resultq->fetch_assoc()) {

         $counter = $counter+1;
         $commissionname[$counter] = $rowq["commissionname"];         
         $commissionid[$counter] = $rowq["commid"];         
         $commbase[$counter] = $rowq["commbase"];
         $commrate[$counter] = $rowq["commrate"];
         $commstatus[$counter] = $rowq["commstatus"];                 
      
         echo "<tr style='bcomm-bottom: solid 1px lightgray;' id='therow".$counter."'>";
         if($up ==1) {
            echo "<td class='redx' onclick='myDelete(".$commissionid[$counter].");'>x</td>";
         }
         else{
            echo "<td></td>";
         }
         echo "<td id='commissionnamo".$counter."'>".$commissionname[$counter]."</td>";
         echo "<td id='commissionido".$counter."'>".$commissionid[$counter]."</td>";
         echo "<td id='commnumero".$counter."'>".$commrate[$counter]."</td>";
         echo "<td>".$commbase[$counter]."</td>"; 
         echo "<td id='statuso".$counter."'>".$commstatus[$counter]."</td>";
         
         echo "<td style='cursor:pointer; color: gray;' class='glyphicon glyphicon-pencil' onclick='laGoon(".$counter.",".$commissionid[$counter].");'></td>";
         echo "</tr>";
         
  }
  $connex->close();
  if($counter <1) {echo "<tr><td></td><td>You have no commissions<td></tr>";}

  //ready new input
        echo "<tr><form action='commissions.php' id='myFormAdder' method='post'><td></td>";
        echo "<td><input type='text' class='busname' name='commissionname'></td>";
        echo "<td></td>";
        echo "<td><input type='text' name='commnumber'></td>";
        echo "<td><select name='commbase'><option>Revenue</option><option>Margin</option></select></td>"; 
        echo "<td><input type='text' name='commstatus' value=' '></td>";        
        echo "<td><input type='button' class='addbutton' value='add' onclick='this.form.submit();'></td></form></tr>";
  
  echo "<tr class='total'><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
  echo"</table>"; 
  ?> <a class='red' href='adsearch.php'>Administer stock.</a><?php 
}
else {
  echo "You are not logged in.";
}
//<embed src="buyerlist.php" width="300" height="400">
?>
<p>Please note for all commissions paid. The margin for the product will be determined and if there is no margin, no commission will be paid or the commission will be limited to the margin amount. Be careful of commissions on revenue.</p>
<form action='commissions.php' id='myFormDel' method='post'>
  <input type='number' id='iddelete' name='iddelete' style='width: 40px' hidden>
</form>

<form action='commissions.php' id='myFormEdit' method='post' hidden>
  <input id="thumbit" type="text" name="thumbit">
  <input id="commissionname" type="text" name="commissionname">  
  <input id="commnumber" type="text" name="commnumber">  
  <input id="commbase" type="text" name="commbase">  
  <input id="status" type="text" name="commstatus">
</form>
<dir id="tester"></dir>

</main>

  <form action='commissions.php' id='myFormView' method='post'>
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
    var commissionnamo = "commissionnamo"+abc;    
    var commnumero = "commnumero"+abc;    
    var statuso = "statuso"+abc;

    var urhere = "therow"+abc; 

    var commissionname =  document.getElementById(commissionnamo).innerHTML;    
    
    var commnumber =  document.getElementById(commnumero).innerHTML;    

    var status =  document.getElementById(statuso).innerHTML;
    
    document.getElementById(urhere).innerHTML = "<tr><td><input type='number' name='idthumb'  value='"+urhere+"' hidden></td>"+"<td><input type='text' id='commissionnameso' class='busname' value='"+commissionname+"'></td><td><input type='number' id='commissionidso' name='thumbit' value='"+littlejohn+"' hidden>"+littlejohn+"</td><td><input type='number' name='commnumber' id='commnumberso' value='"+commnumber+"'></td><td><select name='commbase' id='commbaseso'><option>Revenue<option><option>Margin</option><select></td><td><input type='text' id='statusso' name='status' value='"+status+"'></td><td><button class='glyphicon glyphicon-ok smallgreen' onclick='addSubmit();'></button></td></tr>"; 
    
}
function addSubmit() {    
    var commnum = document.getElementById("commnumberso").value;
    
    if(commnum < 25 || commnum > 0){

      document.getElementById("thumbit").value = document.getElementById("commissionidso").value;
      document.getElementById("commissionname").value = document.getElementById("commissionnameso").value;      
      document.getElementById("commnumber").value = document.getElementById("commnumberso").value;
      document.getElementById("commbase").value = document.getElementById("commbaseso").value;
      document.getElementById("status").value = document.getElementById("statusso").value;

      document.getElementById("myFormEdit").submit();
    }
    else {
      alert("comm number is not correct. Please check or refresh page to update later.");
    }
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
  min-height: 650px;
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
  .red {
    color: red;
  }


  .transbox {
    background-image: url('images/floor.jpg');
    background-repeat: repeat-x;
    opacity: 0.9;
    bcomm: solid 1px black;
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
    width :60%;
    color: black;    
  }
  @media screen and (max-width: 1100px) {
    table {
      zoom: 80%;
    }
  }
  select {
    background-color: ghostwhite;
  }
  .top {
    background-color: lightblue;
    bcomm-bottom-style: outset; 
    color:white;
  }
  .total {
    bcomm-top: gray 2px solid;
    background-color: lightblue;
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
    bcomm: lightgray solid 3px;
    bcomm-radius: 6px;
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