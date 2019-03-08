<!DOCTYPE html >
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta charset="UTF-8">
  <title>Best of best</title>
  <!-- Responsive viewport tag, tells small screens that it's responsive -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Normalize.css, a cross-browser reset file -->
  <link href="" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
</head>
<style>
.chosen2:nth-child(5) {
  color: yellow;
}

</style>
<body>  
  <?php require_once("heading.php"); ?>
<main>
   <?php require_once("footspoor.php"); ?>
<div style='text-align: center';>

  <table>
  <br>

  <?php
  $points =0;
  $nrank =0;
  $rank =0;
    // Grab the profile data from the POST
  if (isset($_COOKIE['firstname']) && isset($_COOKIE['username']) ) {

    require_once('appvars.php');
    require_once('basecamp/connectvars2.php');

    $connex = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    // Check connection
    if ($connex->connect_error) {
        die("Connection failed: " . $connex->connect_error);
    }

    echo"<th class='tablehead' colspan='3'>High Scores</th>";
    echo"<tr style='background-color: sienna'><td>Rank</td><td>Name</td><td>Score</td></tr>";
    $lastmonth = date("Y-m-d H:i:s", strtotime('-30 days')) ;
    $rank = 9999;
    $counter=0;
    $queryq = "SELECT * FROM orders WHERE (orderstatus = 'Ordered' OR orderstatus = 'Invoiced' OR orderstatus = 'Paid' OR orderstatus = 'Shipped' OR orderstatus = 'Complete') AND orderstamp > '$lastmonth' ORDER BY qty DESC LIMIT 20";
    $resultq = $connex->query($queryq);
    while($rowq = $resultq->fetch_assoc()) {
      $counter = $counter+1;
      $quantity[$counter] = $rowq["qty"];
      $player[$counter]  = $rowq['userid'];

      $queryu = "SELECT * FROM theshopuser WHERE userid = '$player[$counter]'";
      $resultu = $connex->query($queryu);
      while($rowu = $resultu->fetch_assoc()) { 
          $name = $rowu['firstname'];
          if($name == $firstname AND $rank > $counter){
            $rank = $counter;
          }
      }

      echo"<tr><td>".$counter."</td><td  style='cursor:pointer;' onclick='myView(".$player[$counter].");'>".$name."</td><td>".$rowq['qty']."</td></tr>";
    }



    echo "<span class='chosen'>Your ranking: ".$rank."</span>";     

  
    
    //close the connection
    $connex->close();}
  ?>

  </table>
  <a href='orders.php'>Start game again.</a>
</div>
</body>
<form action='viewprofile.php' id='myFormView' method='post'>
  <input type='number' id='idview' name='ordnumber' style='width: 40px' hidden>
</form>
</html>

<style type="text/css">
html  {
    font-family: 'Roboto';
  }
  body { 
  min-width:720px;        /* Suppose you want minimum width of 1000px */
  width: auto !important;  /* Firefox will set width as auto */
  width:720px; 
  min-height: 700px;
   background: linear-gradient(
        to bottom,
        rgba(255, 165, 0,0.9),
        rgba(255, 165, 0,0.9)
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
  input:hover {
    background-color: lightgray;
  }
  main {
    margin-left: 50px;
    margin-right: 50px;
    color: darkgray;
  }
	table, th, td {
				border: 2.5px solid lightgray;
				border-collapse: collapse;
				text-align: center;				
	}
	table {
		margin: auto;
	}
  th:hover {
    color: white;
  }

	tr:nth-child(even){background-color: gray}
	tr:nth-child(odd){background-color: darkgray}

	td {
		width: 150px;
		height: 35px;
    color: white;
	}
	.tablehead {
		font-size: 30px;
		height: 50px;
		background-color: maroon;
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
  
</style>
<script>
  function myView(littlejohn) {
    document.getElementById("idview").value = littlejohn;
    document.getElementById("myFormView").submit();
  }
</script>
