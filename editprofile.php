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

  // Grab the profile data from the POST
if (isset($_COOKIE['firstname']) && isset($_COOKIE['username']) ) {

  require_once('appvars.php');
  require_once('basecamp/connectvars2.php');
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  // Check connection
  if ($dbc->connect_error) {
      die("Connection failed: " . $dbc->connect_error);
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

  if (isset($_POST['submit'])) {
    // Grab the profile data from the POST
    $firstname = cleanMyPost($_POST['firstname']);    
    $lastname = cleanMyPost($_POST['lastname']);    
    $birthdate = date($_POST['birthdate']);
    $city = cleanMyPost($_POST['city']);
    $state = cleanMyPost($_POST['state']);
    $telephone = cleanMyPost($_POST['telephone']);    
    $emailaddr = cleanMyPost($_POST['emailaddr']);    
    $latinname = cleanMyPost($_POST['latinname']);

    $linetwopost = ""; $linethreepost =""; $postcodepost="";
    $typeaddress = cleanMyPost($_POST['typeaddress']);

    $nameaddr = cleanMyPost($_POST['nameaddr']);
    $lineonepost = cleanMyPost($_POST['lineone']);
    $linetwopost = cleanMyPost($_POST['linetwo']);
    $linethreepost = cleanMyPost($_POST['linethree']);
    $postcodepost = cleanMyPost($_POST['postcode']);
   
    $vehicle = cleanMyPost($_POST['postcode']);

    $error = false;

    // Validate and move the uploaded picture file, if necessary
    if (!empty($new_picture)) {
      if ((($new_picture_type == 'image/gif') || ($new_picture_type == 'image/jpeg') || ($new_picture_type == 'image/pjpeg') ||
        ($new_picture_type == 'image/png')) && ($new_picture_size > 0) && ($new_picture_size <= MM_MAXFILESIZE) &&
        ($new_picture_width <= MM_MAXIMGWIDTH) && ($new_picture_height <= MM_MAXIMGHEIGHT)) {
        if ($_FILES['file']['error'] == 0) {
          // Move the file to the target upload folder
          $target = MM_UPLOADPATH . basename($new_picture);
          if (move_uploaded_file($_FILES['new_picture']['tmp_name'], $target)) {
            // The new picture file move was successful, now make sure any old picture is deleted
            if (!empty($old_picture) && ($old_picture != $new_picture)) {
              @unlink(MM_UPLOADPATH . $old_picture);
            }
          }
          else {
            // The new picture file move failed, so delete the temporary file and set the error flag
            @unlink($_FILES['new_picture']['tmp_name']);
            $error = true;
            echo '<p class="error">Sorry, there was a problem uploading your picture.</p>';
          }
        }
      }
      else {
        // The new picture file is not valid, so delete the temporary file and set the error flag
        @unlink($_FILES['new_picture']['tmp_name']);
        $error = true;
        echo '<p class="error">Your picture must be a GIF, JPEG, or PNG image file no greater than ' . (MM_MAXFILESIZE / 1024) .
          ' KB and ' . MM_MAXIMGWIDTH . 'x' . MM_MAXIMGHEIGHT . ' pixels in size.</p>';
      }
    }

    // Update the profile data in the database
    if (!$error) {
      if (!empty($firstname) && !empty($lastname) && !empty($birthdate) && !empty($city) && !empty($state) ) {
        $userstatus = "active";
        
        require_once('basecamp/superman.php');
        // Only set the picture column if there is a new picture
        if (!empty($new_picture)) {
          $query = "UPDATE theshopuser SET firstname = '$firstname', lastname = '$lastname', emailaddr = '$emailaddr', " .
            " birthdate = '$birthdate', city = '$city', state = '$state', latinname = '$latinname', userstatus = '$userstatus', picture = '$new_picture' WHERE userid = '" . $_SESSION['userid'] . "'";
        }
        else {          
          $query = "UPDATE theshopuser SET firstname = '$firstname', lastname = '$lastname', emailaddr = '$emailaddr', " .
            " birthdate = '$birthdate', city = '$city', state = '$state', telephone = '$telephone',  latinname = '$latinname' , userstatus = '$userstatus' WHERE userid = '" . $_SESSION['userid'] . "'";
        }        
        mysqli_query($dbc, $query);
        // Confirm success with the user
        echo '<span>Your profile has been successfully updated. </span>';

         $row_cnt=0;
      
        $queryaddress = "SELECT * FROM addresses WHERE agentid = '$useride' AND type = '$typeaddress'";      
        if ($result=mysqli_query($dbc,$queryaddress)) {
          // Return the number of rows in result set
          $row_cnt=mysqli_num_rows($result);
        }

        //update address info or insert if need be
        if(isset($lineonepost)) {      

            if ($row_cnt<0.1) {
              $qaddress =  "INSERT INTO addresses (name, type, lineone, linetwo, linethree, postcode, agentid) VALUES ('$nameaddr', '$typeaddress', '$lineonepost', '$linetwopost', '$linethreepost', '$postcodepost','$useride')";
              mysqli_query($dbc, $qaddress);
              echo"Address added.";
            }
            elseif ($row_cnt>0.1)  {
              $qaddress = "UPDATE addresses SET name='$nameaddr', type = '$typeaddress', lineone = '$lineonepost', linetwo = '$linetwopost', linethree = '$linethreepost', postcode = '$postcodepost', agentid = '$useride' WHERE  agentid = '$useride' AND type = '$typeaddress'";
              mysqli_query($dbc, $qaddress);
              echo"Address also updated.";
            }  
            else {
              echo"Error uploading address.";
            }          
            
        }
      }
      else {
        echo '<p class="error">You must enter all of the profile data (the picture is optional).</p>';
      }
    }
  } // End of check for form submission
  
    // Grab the profile data from the database
    $query = "SELECT firstname, lastname, emailaddr, birthdate, city, state, telephone, latinname, picture FROM theshopuser WHERE userid = '" . $_SESSION['userid'] . "'";
    $data = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($data);

    if ($row != NULL) {
      $firstname = $row['firstname'];
      $lastname = $row['lastname'];      
      $birthdate = $row['birthdate'];
      if($birthdate == 0) {
        echo "Please input your birthday in the format e.g. 1989-01-31.";
      }
      $city = $row['city'];
      $state = $row['state'];
      $telephone = $row['telephone'];
      $emailaddr = $row['emailaddr'];

      $latinname = $row['latinname'];
      $old_picture = $row['picture'];
    }

     // Grab the profile data from the database
     $queryaddr = "SELECT * FROM addresses WHERE agentid = '" . $_SESSION['userid'] . "'";
     $resultaddr = mysqli_query($dbc, $queryaddr);
     $row_cnt = $resultaddr->num_rows;
     if($row_cnt=0) {
        echo "You have not loaded an address yet.";
     }
     else{
       $addrcount=0; $type=array();
       $linename=array("");
       $lineone=array("");
       $linetwo=array("");
       $linethree=array(""); 
       $postcode=array(""); 
       while ($rower = mysqli_fetch_array($resultaddr)) {
         $addrcount = $addrcount+1;

         if($rower['type']=="Delivery"){
              $linename[0] = $rower['name'];
              $lineone[0] = $rower['lineone'];
              $linetwo[0] = $rower['linetwo'];
              $linethree[0] = $rower['linethree'];
              $postcode[0] = $rower['postcode'];
           
         }
         if($rower['type']=="Home"){
              $linename[1] = $rower['name'];
              $lineone[1] = $rower['lineone'];
              $linetwo[1] = $rower['linetwo'];
              $linethree[1] = $rower['linethree'];
              $postcode[1] = $rower['postcode'];
              
         }
         if($rower['type']=="Work"){
              $linename[2] = $rower['name'];
              $lineone[2] = $rower['lineone'];
              $linetwo[2] = $rower['linetwo'];
              $linethree[2] = $rower['linethree'];
              $postcode[2] = $rower['postcode'];
              
         }        
       }
    }
  

  mysqli_close($dbc);

?>
  <br>
  <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MM_MAXFILESIZE; ?>" />
    <fieldset>
      <legend>Personal Information</legend>
      <label for="firstname">First name:</label>
      <input type="text" id="firstname" name="firstname" value="<?php if (!empty($firstname)) echo $firstname; ?>" /><br />
      <label for="lastname">Last name:</label>
      <input type="text" id="lastname" name="lastname" value="<?php if (!empty($lastname)) echo $lastname; ?>" /><br />

      <label for="birthdate">Birth date:</label>
      <input type="text" id="birthdate" name="birthdate" value="<?php if (!empty($birthdate)) echo $birthdate; else echo 'YYYY-MM-DD'; ?>" /><br />
      <label for="city">City:</label>
      <input type="text" id="city" name="city" value="<?php if (!empty($city)) echo $city; ?>" /><br />
      <label for="state">State:</label>
      <input type="text" id="state" name="state" value="<?php if (!empty($state)) echo $state; ?>" /><br />
      <label for="telephone">Telephone number:</label>
      <input type="" name="telephone" value="<?php if (!empty($telephone)) echo $telephone; ?>"><br>
       
      <label for="">Your Commission id:</label><?php if (!empty($useride)) echo $useride; ?><br>
      <span onclick="showAddress();"><u>Add Address:</u></span><br>
      <label for="typeaddress">Type:</label>      
      <select name="typeaddress" id="typeaddress" onchange="differentAddress();">
        <option value="Delivery">Delivery</option>
        <option value="Home">Home</option>
        <option value="Work">Work</option>
      </select><br>
      <label for="name">Name:</label>
      <input type="" id="namead" name="nameaddr" value="<?php if (!empty($linename[0])) echo $linename[0]; ?>"><br>
      <label for="lineone">Line 1:</label>
      <input type="" id="lineonead" name="lineone" value="<?php if (!empty($lineone[0])) echo $lineone[0]; ?>"><br>
      <label for="linetwo">Line 2:</label>
      <input type="" id="linetwoad" name="linetwo" value="<?php if (!empty($linetwo[0])) echo $linetwo[0]; ?>"><br>
      <label for="linethree">Line 3:</label>
      <input type="" id="linethreead" name="linethree" value="<?php if (!empty($linethree[0])) echo $linethree[0]; ?>"><br>
      <label for="postcode">Postal code</label>
      <input type="" id="postcodead" name="postcode" value="<?php if (!empty($postcode[0])) echo $postcode[0]; ?>"><br>      
      
      <label for="supplieryes">Notify orders: email:</label>
      <input type="checkbox" name="vehicle" value="supplier"><br>
      <label for="emailaddr">Email address:</label>
      <input type="" name="emailaddr" value="<?php if (!empty($emailaddr)) echo $emailaddr; ?>"><br>
      <label for="latinname">Your secret words:</label>
      <input type="text" id="latinname" name="latinname" value="<?php if (!empty($latinname)) echo $latinname; ?>" ><a class="latin" href="https://a-z-animals.com/animals/scientific/">e.g. Latin words..</a><br />
    </fieldset>
    <input type="submit" value="Save Profile" name="submit" /> 
  </form>
  
<?php 
  }
  else {
    echo"You are not logged in.";
  }
?>
</body> 
</html>
<script>
   <?php     
          $js_cars = json_encode($linename);
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
</script>
<style>
  fieldset { 
    width: 580px;
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
  min-width:660px;        /* Suppose you want minimum width of 1000px */
  width: auto !important;  /* Firefox will set width as auto */
  width:660px; 
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