<?php

  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require_once('basecamp/connectvars2.php');

  // Start the session
  session_start();

  // Clear the error message
  $error_msg = "";
  $secondswait = 3;

  // If the user isn't logged in, try to log them in
  if (!isset($_SESSION['userid'])) {
    if (isset($_POST['submit'])) {
      // Connect to the database
      $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

      // Grab the user-entered log-in data
      //$user_username = mysqli_real_escape_string($dbc, trim($_POST['username']));
      $user_latin = mysqli_real_escape_string($dbc, trim($_POST['latinname']));
      $email = mysqli_real_escape_string($dbc, trim($_POST['email']));

      if (!empty($user_latin)) {
        // Look up the username and password in the database
        $query = "SELECT userid, username, firstname FROM theshopuser WHERE latinname = '$user_latin'";
        $data = mysqli_query($dbc, $query);

        if (mysqli_num_rows($data) == 1) {
          // The log-in is OK so set the user ID and username session vars (and cookies), and redirect to the home page
          $row = mysqli_fetch_array($data);
          $_SESSION['userid'] = $row['userid'];
          $_SESSION['username'] = $row['username'];
          $_SESSION['firstname'] = $row['firstname'];
          setcookie('userid', $row['userid'], time() + (60 * 60 * 24 * 30), '/');    // expires in 30 days
          setcookie('username', $row['username'], time() + (60 * 60 * 24 * 30),'/');  // expires in 30 days
          setcookie('firstname', $row['firstname'], time() + (60 * 60 * 24 * 30),'/');  // expires in 30 days
          $home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/index.php';
          $this_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
                              
          header("Location: index.php");
          
        }
        else {
          // The username/password are incorrect so set an error message
          $error_msg = '<p class="mirror">We are not sure, we cant seem to recognise what you entered.</p>';
        }
      }
      else {
        // The username/password weren't entered so set an error message
        $error_msg = '<p class="mirror">Oops, we dont seem to be talking the same language.</p>';
      }
    }
  }
?>

<!DOCTYPE html >
<html  >
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>The PPE Shop - Log In</title>
  <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

</head>
<body>
  <h1 class="centerh1 white">Forget Me Not</h1>

<?php
  // If the session var is empty, show any error message and the log-in form; otherwise confirm the log-in
  if (empty($_SESSION['userid'])) {
    echo '<p class="error">' . $error_msg . '</p>';
?>
  <div class="formblock">
    
    <form class="formlines" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

        <label for="latinname">Latin name or secret key</label><br>
        <input placeholder="iamsilly name here" type="text" name='latinname' id='password'/><br>
        <label for="email">Or enter your email address to reset</label><br>
        <input placeholder="me@here.com" type="asswow" name='email' id='password'/><br>
        <input type="submit" value="Log In" name="submit" class="spacing buttonset"/>   
      <p class="forgetfont"><a href="login.php">I remember now! :)</a></p>
    </form>

  </div>

  <p class="loginbelow">If you don't have an account?<a href="signup.php">Sign Up</a>></p>



<?php
  }
  else {
    // Confirm the successful log-in
    
    echo('<p class="login">You are logged in as ' . $_SESSION['username'] . '.</p>');    
    $secondswait = 1;
    
    header("Refresh:3; url=mygridlist.php");
    echo"<a href='mygridlist.php'>Proceed.</a>";

  }

  //<label for="email">Email</label><br><input placeholder="foo@veryhighbar.com" type="text" name='email' id='email'/><br>
?>

</body>
</html>

<style type="text/css">

  body {
  
  color: black;
  margin-top: 20px;
  font-family: 'Roboto';
  background-color: orange;
    
}

.imageblock {
  display: block;
  margin-left: auto;
  margin-right: auto;
  height: 35px;
}

.centerh1 {
    text-align: center;
    font-weight: 33px;
    text-shadow: 2px 2px gray;
  }
.centerh2 {
    text-align: center;
    font-weight: 22px;
  }
.white {
  color: white;
}
.mirror {
  text-align: center;
}

.formblock  {
  margin-top: 20px;
  padding-top: 20px;
  padding-bottom: 20px;
  color: black;
  font-weight: 33px;
  margin: 0 auto;
  background-color: lightgray;
  text-align: left;
  border-radius: 5px;

}
@media (min-width: 490px) {
  .formblock  {
    width: 400px;
    padding-left: 40px;
    padding-right: 40px;
  }
  .formlines input {
    width: 200px;
  } 
  .buttonset  {
    width: 200px;
  }
}
@media (max-width: 490px) {
  .formblock  {
    padding-left: 20px;
    padding-right: 20px;
  }   
}

input:hover {
  background-color: lightblue;
}


.formlines label {
  margin-bottom: 5px;
  }

.formlines input {
  display: block;
  height: 40px;
  font-weight: 30px;

  padding-left: 10px;
  width: 90%;
}

.spacing {
  margin-top: 20px;
}

.buttonset  {
  display: block;
  background-color: lightblue;
  border: lightgreen;
  color: white;
  height: 40px;
  padding-left: 10px;
  width: 92%;
  border-radius: 5px;
}

.buttonset:hover {
  background-color: blue;
}

.forgetfont {
  color: gray;
  font-weight: 15px;
}

.loginbelow {
  color: white;
  text-align: center;
}
.inote {
  color: white;
}
a {
  color: white;
}
</style>