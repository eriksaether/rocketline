
<!DOCTYPE html >
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Profile</title>

  <!-- Responsive viewport tag, tells small screens that it's responsive -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Normalize.css, a cross-browser reset file -->
  <link href="" rel="stylesheet">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">


</head>
<style>

.chosen2:nth-child(4) {
  color: yellow;
}
</style>
<body>  
 <?php require_once("heading.php"); ?>
<main>
  <?php require_once("profilenav.php"); ?>
<?php
$searcher = 0;
$useraka = "";
  // Grab the profile data from the POST
if (isset($_COOKIE['firstname']) && isset($_COOKIE['username']) ) {

  require_once('appvars.php');
  require_once('basecamp/connectvars2.php');
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  // Check connection
  if ($dbc->connect_error) {
      die("Connection failed: " . $dbc->connect_error);
  } 
  //view order details only
  if (isset($_POST['ordnumber'])) {
     $viewid = $_POST['ordnumber'];
     $searcher =9;     
  }  
  
    // Grab the profile data from the database
    
    if($searcher == 9){
        $query = "SELECT username, firstname, lastname, birthdate, city, state, telephone, latinname, picture FROM theshopuser WHERE userid = '" . $viewid. "'";
    }
    else {
        $query = "SELECT username, firstname, lastname, birthdate, city, state, telephone, latinname, picture FROM theshopuser WHERE userid = '" . $_SESSION['userid'] . "'";
        $viewid = $useride;
    }
    $data = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($data);

    if ($row != NULL) {
      
      $useraka = $row['username'];
      $firstname = $row['firstname'];
      $lastname = $row['lastname'];      
      $birthdate = $row['birthdate'];
      $city = $row['city'];
      $state = $row['state'];
      $telephone = $row['telephone'];
      $latinname = $row['latinname'];
      $old_picture = $row['picture'];      
    }


     // Grab the profile address data from the database
    $queryaddress = "SELECT lineone, linetwo, linethree, postcode FROM addresses WHERE agentid = '" . $viewid . "'";
    $data = mysqli_query($dbc, $queryaddress);
    $rowaddr = mysqli_fetch_array($data); 

    if ($rowaddr != NULL) {
      $lineone = $rowaddr['lineone'];
      $linetwo = $rowaddr['linetwo'];
      $linethree = $rowaddr['linethree'];
      $postcode = $rowaddr['postcode'];
    }
    else {
      echo '<p class="error">There was a problem accessing the address info.</p>';
    }
  

  mysqli_close($dbc);

  echo"<h4>User ".$viewid.": ".$firstname."</h4>";
?>
  <br>
  <form >    
    <fieldset>
      <legend>Personal Information</legend>
      <label for="firstname">First name:</label>
      <span type="text" id="firstname" name="firstname" value="" /><?php if (!empty($firstname)) echo $firstname; ?></span><br />
      <label for="lastname">Last name:</label>
      <span type="text" id="lastname" name="lastname" value="" /><?php if (!empty($lastname)) echo $lastname; ?></span><br />

      <label for="birthdate">Birth date:</label>
      <span type="text" id="birthdate" name="birthdate" value="" /><?php if (!empty($birthdate)) echo $birthdate; else echo 'YYYY-MM-DD'; ?></span><br />
      <label for="city">City:</label>
      <span type="text" id="city" name="city" value="" /><?php if (!empty($city)) echo $city; ?></span><br />
      <label for="state">State:</label>
      <span type="text" id="state" name="state" value="" /><?php if (!empty($state)) echo $state; ?></span><br />
      <label for="telephone">Telephone number:</label>
      <span type="" name="telephone" value=""><?php if (!empty($telephone)) echo $telephone; ?></span><br>
      <span onclick="showAddress();"><u>Add Address:</u></span><br>
      <label for="typeaddress">Type:</label>
      <select name="typeaddress">        
        <option value="Delivery">Delivery</option>
      </select><br>
      <label for="lineone">Line 1:</label>
      <span type="" name="lineone" value="">xxx</span><br>
      <label for="linetwo">Line 2:</label>
      <span type="" name="linetwo" value=""><?php if (!empty($linetwo)) echo $linetwo; ?></span><br>
      <label for="linethree">Line 3:</label>
      <span type="" name="linethree" value=""><?php if (!empty($linethree)) echo $linethree; ?></span><br>
      <label for="postcode">Postal code</label>
      <span type="" name="postcode" value=""><?php if (!empty($postcode)) echo $postcode; ?></span><br>      
      <label for="latinname">Your secret words:</label>
      <span type="text" id="latinname" name="latinname" value="" >Cant touch this.. MC Hammer</span><br />

    </fieldset>    
  </form>
<?php 
  }
  else {
    echo"You are not logged in.";
  }
?>
</body> 
</html>

<style>
  fieldset { 
    width: 650px;
    border-radius: 5px;
    box-shadow: 3px 3px 3px;    
  }
  label {
    width: 150px;
  }
  html  {
    font-family: 'Roboto';
  }
  body { 
  min-width:840px;        /* Suppose you want minimum width of 1000px */
  width: auto !important;  /* Firefox will set width as auto */
  width:840px; 
   background:  linear-gradient(
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

  .footnmouth {
      background-color: orange ;
      color: white;
      padding: 5px;
      margin-top: -10px;
      border-bottom: 2px black solid;
  }
  .transbox {
    border: solid 1px black;
    background-image: url('images/floor.jpg');
    background-repeat: repeat-x;
    opacity: 0.9;
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
  .shopman {
    display: block;
    width: 250px;
    padding: 3px;
  }

  .nextline {
    clear: left;
  }
  input:hover {
    background-color: lightgray;
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
  .latin {
    color: gray;
    width: 120px;
    
  }
  .latin:hover {
    color: blue;
  }
</style>