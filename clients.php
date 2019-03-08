
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
.chosen2:nth-child(2) {
  color: yellow;
}

</style>
<body>
  <?php require_once("heading.php"); ?>
  <main>
  <?php require_once("footspoor.php"); ?> 

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

  if (isset($_POST['businessname'])) {
      
    // Grab the profile data from the POST
    $businessnaam = cleanMyPost($_POST['businessname']);
    $contactname = cleanMyPost($_POST['contactname']);
    $telephone = cleanMyPost($_POST['telephone']);
    $emailaddr = cleanMyPost($_POST['emailaddr']);
    $vatnumber = cleanMyPost($_POST['vatnumber']);

    

    if (!empty($businessnaam) && !empty($contactname) && !empty($telephone) ) {

      // Make sure someone isn't already registered using this username
      $query = "SELECT * FROM clients WHERE businessname = '$businessnaam'";
      $data = mysqli_query($connex, $query);
      if (mysqli_num_rows($data) == 0) {
        // The username is unique, so insert the data into the database
        $query = "INSERT INTO clients (businessname, contactname, telephone, emailaddr, vatnumber,  commid, joindate) VALUES ('$businessnaam', '$contactname','$telephone', '$emailaddr', '$vatnumber',  $useride, NOW())";
        if(mysqli_query($connex, $query)) {
          // Confirm success with the user
          echo '<p class="mirror">Your new account has been successfully created.</p>';  
          $loadclient = 1;
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
    else {
      echo '<p class="mirror">You must enter all of the data.</p>';
    }
  }

       //update database with client line correction
        if (isset($_POST['clientname']) && isset($usernaam)) {

            $idedit= cleanMyPost($_POST["thumbit"]);
            $clientname= cleanMyPost($_POST["clientname"]);
            $contactname= cleanMyPost($_POST["contactname"]);
            $telephone= cleanMyPost($_POST["telephone"]);
            $emailaddr = cleanMyPost($_POST["emailaddr"]);
            $vatnumber= cleanMyPost($_POST["vatnumber"]);
            $address= cleanMyPost($_POST["address"]);
            $postcode= cleanMyPost($_POST["postcode"]);
            
            $queryvee = "UPDATE clients SET businessname = '$clientname', contactname = '$contactname', telephone = '$telephone', emailaddr = '$emailaddr', vatnumber = '$vatnumber', address = '$address', postcode = '$postcode' WHERE clientid = '$idedit' ";
            mysqli_query($connex, $queryvee);
            echo "Client:".$idedit." updated.";
        }        

        // sql to delete a line record
        if (!empty($_POST["iddelete"])) {
          $idnum= $_POST["iddelete"];
          if ($idnum > 0) { 
           
            $sqldel = "DELETE FROM clients WHERE clientid=$idnum";
            if (mysqli_query($connex, $sqldel)) {
              echo "Client deleted successfully. ";
            } else {
              echo "Error deleting record: " . mysqli_error($connex);
            }
                 
          }
          $_POST["iddelete"]=0;
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
  
  if (isset($_POST['lineone'])) {

      $linetwo = ""; $linethree =""; $postcode="";
      $typeaddress = cleanMyPost($_POST['typeaddress']);
      $nameinfo = cleanMyPost($_POST['nameinfo']);
      $lineone = cleanMyPost($_POST['lineone']);
      $linetwo = cleanMyPost($_POST['linetwo']);
      $linethree = cleanMyPost($_POST['linethree']);
      $postcode = cleanMyPost($_POST['postcode']);
      $clientid = cleanMyPost($_POST['clientid']);      
      $row_cnt=0;
      
      $queryaddress = "SELECT * FROM addresses WHERE clientid = '$clientid' AND type = '$typeaddress'";      
      if ($result=mysqli_query($connex,$queryaddress)) {
        // Return the number of rows in result set
        $row_cnt=mysqli_num_rows($result);
      }                    

      //update address info or insert if need be
      if(isset($lineone)) { 
          if ($row_cnt<0.1) {
            $qaddress =  "INSERT INTO addresses (type, name, lineone, linetwo, linethree, postcode, clientid) VALUES ('$typeaddress', '$nameinfo', '$lineone', '$linetwo', '$linethree', '$postcode', '$clientid')";
            mysqli_query($connex, $qaddress);
            echo"Address added.";
          }     
          elseif ($row_cnt>0.1)  {
            $qaddress = "UPDATE addresses SET  type = '$typeaddress', name = '$nameinfo', lineone = '$lineone', linetwo = '$linetwo', linethree = '$linethree', postcode = '$postcode' WHERE clientid = '$clientid' AND type = '$typeaddress'";
            mysqli_query($connex, $qaddress);
            echo"Address edited.";
          } 
          
           
          else {
            echo"Error uploading address.";
          }          
          
      }
  }

  // Grab the profile data to see address
  if (isset($_POST['idviewgo'])) {
    
    $name = "";
    $lineone = "";
    $linetwo = "";
    $linethree = "";
    $postcode = "";

    $clientid = $_POST['idviewgo'];
    
    //grab item info
    $queryaddr = "SELECT * FROM addresses WHERE clientid = '$clientid'";    
    $resultaddr = mysqli_query($connex, $queryaddr);
    $row_cnt = $resultaddr->num_rows;
    if($row_cnt=0) {

    }
    else{
      $addrcount=0; $type=array();
      $name=array("");
      $lineone=array("");
      $linetwo=array("");
      $linethree=array(""); 
      $postcode=array(""); 
      while ($rower = mysqli_fetch_array($resultaddr)) {
        $addrcount = $addrcount+1;

        if($rower['type']=="Delivery"){
             $name[0] = $rower['name'];
             $lineone[0] = $rower['lineone'];
             $linetwo[0] = $rower['linetwo'];
             $linethree[0] = $rower['linethree'];
             $postcode[0] = $rower['postcode'];
          
        }
        if($rower['type']=="Invoice"){
             $name[1] = $rower['name'];
             $lineone[1] = $rower['lineone'];
             $linetwo[1] = $rower['linetwo'];
             $linethree[1] = $rower['linethree'];
             $postcode[1] = $rower['postcode'];
             
        }
        if($rower['type']=="HQ"){
             $name[2] = $rower['name'];
             $lineone[2] = $rower['lineone'];
             $linetwo[2] = $rower['linetwo'];
             $linethree[2] = $rower['linethree'];
             $postcode[2] = $rower['postcode'];
             
        }        
      }
      echo $linetwo[2];
    }       
    echo "<form class='displaybox' id='displaybox' action='clients.php' method='post'><b><u>Address</u><br><br></b>";
    echo "<label for='typeaddress'>Type:</label><select id='typeaddress' name='typeaddress' onchange='differentAddress();'>";        
    echo "<option value='Delivery'>Delivery</option>";
    echo "<option value='Invoice'>Invoice</option>";
    echo "<option value='HQ'>HQ</option>";
    echo "</select><br><br>";      
    echo "<label for='name'>Name:</label><input type='text' class='displayin' id='namead' name='nameinfo' value='".$name[0]."'><br>";        
    echo "<label for='lineone'>Line 1:</label><input class='displayin' type='text' id='lineonead' name='lineone' value='".$lineone[0]."'><br>";      
    echo "<label for='linetwo'>Line 2:</label><input class='displayin' type='text' id='linetwoad' name='linetwo' value='".$linetwo[0]."'><br>";    
    echo "<label for='linethree'>Line 3:</label><input class='displayin' type='text' id='linethreead' name='linethree' value='".$linethree[0]."'><br>";    
    echo "<label for='postcode'>Postcode:</label><input class='displayin' type='number' id='postcodead' name='postcode' value='".$postcode[0]."'><br>";  
    echo "<input hidden type='number' name='clientid' value='".$clientid."'>"; 
    echo "<br><button>Add</button>";
    echo "<span class='redx' onclick='stopShow();'>x</span>";
    echo "</form>";            
  }


  //start main listing..
  $counter=0;
    //fetch the list from the database 
  //check privileges
  if($up>0) {
    $sqlq = "SELECT * FROM clients";
    //check if searching
    if($searcher > 0) {
        $sqlq = "SELECT * FROM clients WHERE orderstatus = '$searchstatus' ";
    }
  }
  else {  
    $sqlq = "SELECT * FROM clients WHERE commid = '$useride' ";
    //check if searching
    if($searcher > 0) {
        $sqlq = "SELECT * FROM clients WHERE commid = '$useride'  AND orderstatus = '$searchstatus' ";
    } 
  }
  $resultq = $connex->query($sqlq);
  echo "<h4>Client Information</h4>";
  echo "<table width='100%' style='  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'> ";
  echo "<tr  class='top'><td width='20px' ></td><td width='240px'>Business name</td><td>&nbsp;Commid&nbsp;</td><td>VAT number</td><td >Contact person</td><td>Telephone</td><td>Email</td><td >Addr.</td><td ></td><td class='statusinfo' width='100px'>Status</td><td></td></tr>";

  $businessname = array();
  $contactname = array();
  $vatnumber = array();
  $telephone = array();
  $emailaddr = array();
  $address = array();
  $postcode = array();
  $status = array();
  while($row = $resultq->fetch_assoc()) {

         $counter = $counter+1;
         $businessname[$counter] = $row["businessname"];
         $clientid[$counter] = $row["clientid"];
         $commsid[$counter] = $row["commid"];
         $contactname[$counter] = $row["contactname"];              
         $vatnumber[$counter] = $row["vatnumber"];
         $telephone[$counter] = $row["telephone"];       
         $emailaddr[$counter] = $row["emailaddr"];   
         $address[$counter] = $row["address"];
         $postcode[$counter] = $row["postcode"];
         $status[$counter] = $row["status"];                 
      
         echo "<tr style='border-bottom: solid 1px lightgray;' id='therow".$counter."'>";
         if($up ==1) {
            echo "<td class='redx' onclick='myDelete(".$clientid[$counter].");'>x</td>";
         }
         else{
            echo "<td></td>";
         }
         echo "<td id='clientnamo".$counter."'>".$businessname[$counter]."</td>";
         echo "<td id='clientido".$counter."'>".$commsid[$counter]."</td>";
         echo "<td id='vatnumero".$counter."'>".$vatnumber[$counter]."</td>";
         echo "<td id='contactnamo".$counter."'>".$contactname[$counter]."</td>";         
         echo "<td id='telephono".$counter."'>".$telephone[$counter]."</td>";
         echo "<td id='emailaddro".$counter."' class='emailit'>".$emailaddr[$counter]."</td>";
         echo "<td id='addresso".$counter."' onclick='myView(".$clientid[$counter].");' class='glyphicon glyphicon-home orange' style='cursor:pointer; color: gray;'></td>";
         echo "<td id='postcodo".$counter."'></td>";
         echo "<td id='statuso".$counter."'>".$status[$counter]."</td>";
         echo "<td style='cursor:pointer; color: gray;' class='glyphicon glyphicon-pencil' onclick='laGoon(".$counter.",".$clientid[$counter].");'></td>";
         echo "</tr>";
         
  }
  $connex->close();
  if($counter <1) {echo "<tr><td></td><td>You have no clients<td></tr>";}

  //ready new input
        echo "<tr><form action='clients.php' id='myFormAdder' method='post'><td></td>";
        echo "<td><input type='text' class='busname' name='businessname' value='";
        if(isset($businessnaam) ){echo $businessnaam;}
        echo "'>"."</td>";
        echo "<td></td>";
        echo "<td><input type='text' name='vatnumber'></td>";
        echo "<td><input type='text' name='contactname'></td>";        
        echo "<td><input type='text' name='telephone'></td>";
        echo "<td><input type='text' name='emailaddr'></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td><input type='text'></td>";        
        echo "<td><input type='button' class='addbutton' value='add' onclick='this.form.submit();'></td></form></tr>";
  
  echo "<tr class='total'><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
  echo"</table>";  
}
else {
  echo "You are not logged in.";
}
//<embed src="buyerlist.php" width="300" height="400">
?>

<form action='clients.php' id='myFormDel' method='post'>
  <input type='number' id='iddelete' name='iddelete' style='width: 40px' hidden>
</form>

<form action='clients.php' id='myFormEdit' method='post' hidden>
  <input id="thumbit" type="text" name="thumbit">
  <input id="clientname" type="text" name="clientname">
  <input id="contactname" type="text" name="contactname">
  <input id="vatnumber" type="text" name="vatnumber">
  <input id="telephone" type="text" name="telephone">
  <input id="emailaddr" type="text" name="emailaddr">
  <input id="address" type="text" name="address">
  <input id="postcode" type="text" name="postcode">
  <input id="status" type="text" name="status">
</form>
<dir id="tester"></dir>

</main>

  <form action='clients.php' id='myFormView' method='post'>
    <input type='number' id='idviewgo' name='idviewgo' style='width: 40px' hidden>
  </form>
</body>
</html>
<script>
   <?php     
          $js_cars = json_encode($name);
          echo "var lineName = ". $js_cars . ";\n";      

          $js_mars = json_encode($lineone);
          echo "var lineOne = ". $js_mars . ";\n";  

          $js_mars = json_encode($linetwo);
          echo "var lineTwo = ". $js_mars . ";\n"; 

          $js_mars = json_encode($linethree);
          echo "var lineThree = ". $js_mars . ";\n"; 

          $js_mars = json_encode($postcode);
          echo "var postCode = ". $js_mars . ";\n"; 

      ?>
</script>

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
function differentAddress() {
  var selectBox = document.getElementById("typeaddress");
  var selectedValue = selectBox.selectedIndex;
  
  if(typeof lineOne[selectedValue] !== 'undefined') {
    document.getElementById("namead").value = lineName[selectedValue];
    document.getElementById("lineonead").value = lineOne[selectedValue];
    document.getElementById("linetwoad").value = lineTwo[selectedValue];
    document.getElementById("linethreead").value = lineThree[selectedValue];
    document.getElementById("postcodead").value = postCode[selectedValue];
  }
  else {
    document.getElementById("namead").value = "";
    document.getElementById("lineonead").value = "";
    document.getElementById("linetwoad").value = "";
    document.getElementById("linethreead").value = "";    
    document.getElementById("postcodead").value = "";
  }
  

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
    var contactnamo = "contactnamo"+abc;
    var vatnumero = "vatnumero"+abc;
    var telephono = "telephono"+abc;
    var emailaddro = "emailaddro"+abc;
    var addresso = "addresso"+abc;
    var postcodo = "postcodo"+abc;
    var statuso = "statuso"+abc;

    var urhere = "therow"+abc; 

    var clientname =  document.getElementById(clientnamo).innerHTML;    
    var contactname =  document.getElementById(contactnamo).innerHTML;
    var vatnumber =  document.getElementById(vatnumero).innerHTML;    
    var telephone =  document.getElementById(telephono).innerHTML;
    var emailaddr =  document.getElementById(emailaddro).innerHTML;
    var address =  document.getElementById(addresso).innerHTML;
    var postcode =  document.getElementById(postcodo).innerHTML;
    var status =  document.getElementById(statuso).innerHTML;
    
    document.getElementById(urhere).innerHTML = "<tr><td><input type='number' name='idthumb'  value='"+urhere+"' hidden></td>"+"<td><input type='text' id='clientnameso' class='busname' value='"+clientname+"'></td><td><input type='number' id='clientidso' name='thumbit' value='"+littlejohn+"' hidden>"+littlejohn+"</td><td><input type='text' name='vatnumber' id='vatnumberso' value='"+vatnumber+"'></td><td><input type='text' id='contactnameso' name='contactname' value='"+contactname+"'></td><td><input type='text' id='telephoneso' name='telephone' value='"+telephone+"'></td><td><input type='text' id='emailaddrso' name='emailaddr' value='"+emailaddr+"'></td><td></td><td></td><td><input type='text' id='statusso' name='status' value='"+status+"'></td><td><button class='glyphicon glyphicon-ok smallgreen' onclick='addSubmit();'></button></td></tr>"; 
    
}
function addSubmit() {    
    var vatnum = document.getElementById("vatnumberso").value;
    if(vatnum.length == 10 || vatnum == 0){

      document.getElementById("thumbit").value = document.getElementById("clientidso").value;
      document.getElementById("clientname").value = document.getElementById("clientnameso").value;
      document.getElementById("contactname").value = document.getElementById("contactnameso").value;
      document.getElementById("vatnumber").value = document.getElementById("vatnumberso").value;
      document.getElementById("telephone").value = document.getElementById("telephoneso").value;
      document.getElementById("emailaddr").value = document.getElementById("emailaddrso").value;

      document.getElementById("status").value = document.getElementById("statusso").value;

      document.getElementById("myFormEdit").submit();
    }
    else {
      alert("VAT number is not correct. Please check or refresh page to update later.");
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
    background-image: url('images/beach.jpg');
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
    width :78%;
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