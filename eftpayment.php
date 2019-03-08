<style>
<style type="text/css">
  html  {
    font-family: 'Roboto';
  }
  body { 
   min-width:640px;   
   background-color: white;
  }
  h1 {
    font-size: 30px;
    color: white;
  }
  .center {
    text-align: center;
  }
  .controller {
    background-color:   lightgray;
    width: 420px;
  }
  .triangle {
    width: 0; 
    height: 0; 
    border-top: 40px solid transparent;
    border-bottom: 40px solid transparent;  
    border-left: 40px solid lightgray;
    position:   relative; 
    left: 400px;
    top: -52px;
}

  .transbox {
    border: solid 1px black;
    background-image: url('images/Firewood1.jpg');
    background-repeat: repeat-x;
    opacity: 0.9;
}
 
  .nextline {
    clear: left;
  }
  .inputin {
    width: 40px;
  }
  main {
    margin-left: 50px;
    margin-right: 20px;
    z-index: 0.9;
  }
  </style>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shop Cart</title>

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
  	<h4>EFT Payment</h4>
  	<?php 
  	$loaded =0;
  	if (isset($_COOKIE['firstname']) && isset($_COOKIE['username']) ) {  

  		$firstname = $_COOKIE['firstname'];
	  	require_once('appvars.php');
	  	require_once('basecamp/connectvars2.php');
	  	$connex = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	  	// Check connection
	  	if ($connex->connect_error) {
	  	    die("Connection failed: " . $connex->connect_error);
	  	} 

	  	$ok =0;
	  	$delfee =0;
	  	//check user rights
	  	$sqlup = "SELECT * FROM theshopuser WHERE userid = $useride";
	  	$resultup = $connex->query($sqlup);
	  	while($row = $resultup->fetch_assoc()) {    
	  	    $lastname = $row["lastname"];
	  	    
	  	    $telephone = $row["telephone"];    
	  	    $emailaddr = $row["emailaddr"];    
	  	}
	  	//get some order info...
	  	$counter=0;	  	
  		$sqlq = "SELECT * FROM orders WHERE userid = $useride && (orderstatus ='Cart' OR orderstatus ='')"; 
  		$resultq = $connex->query($sqlq);
  		while($row = $resultq->fetch_assoc()) {
       		$counter = $counter+1;
       		$detailid[$counter] = $row["detailid"]; 
       	}
       	//get some post details
       	$subtotal = $_POST['subtotal'];
    	$delfee = $_POST['deliveryfee'];
    	$total = $_POST['total']; 
    	$clientemailaddr = $emailaddr;
    	//get bank details...
		require_once('basecamp/bank.php');
       	//run firestarter if ready
	  	if (isset($_POST['sticknum']) || isset($_POST['sticknumb']) ) {
	  		$clientide = 0;
	  		//allow each item to be updated	  		
	  		$_POST['clientref']	= "Eft";
	  		$firetype = "Eft";
	  		//run firestarter.. 		
	  		require_once('firestarter.php'); 
	  		echo "Order Confirmed";
	  		$loaded = 1;
		}		
	}
	
  ?>
<script>
  <?php  
  	
      $js_cars = json_encode($counter);
      echo "var totalStix = ". $js_cars . ";\n";      

      $js_mars = json_encode($detailid);
      echo "var orderCart = ". $js_mars . ";\n";  

      $js_del = json_encode($delfee);
      echo "var delfee = ". $js_del . ";\n";  
    
  ?>
</script>

  	<div>
  		Payment details are as follows:
  		<br><br>
  		<table>
  			<tr ><td id='bankinfo1'><?php echo $accountname ?></td></tr>
  			<tr ><td id='bankinfo2'>Bank:&nbsp; <?php echo $bankname ?></td></tr>
  			<tr ><td id='bankinfo3'>Account:&nbsp;<?php echo $accountnum ?></td></tr>
  			<tr ><td id='bankinfo4'>Branch:&nbsp;&nbsp;&nbsp;<?php echo $bankbranch ?></td></tr>
  			<tr ><td id='bankinfo5'>Acc type:&nbsp;<?php echo $accounttype ?></td></tr>
  		</table>
  		<br>
  		<form action='' method='post'>
	  		<div id='container'></div>
	  		<input type="text" name="subtotal" value="<?php echo $subtotal;?>" hidden>
 			<input type="text" name="deliveryfee" value="<?php echo $delfee;?>" hidden>
  			<input type="text" name="total" value="<?php echo $total; ?>" hidden>
	  		
	  		<?php if($loaded==0) {
          echo "Please confirm:<br>";
          echo "I have noted the bank details and will EFT within 3 days. <button onclick='runTwigSubmit(\"container\");'>Confirmed.</button> <br>";
          echo "";
        } ?>
	  		
  		</form>
  		<?php 
  		if(Isset($orderiid)){
	  		echo"<i>The payment needs to reference the order number".$orderid."</i><br>
	  		<i>We will deliver stock once payment is confirmed.</i><br>";
  		}
  		?>
  	</div>
  	<div id='mc'></div>
  </main>
</body>
</html>

<script type="text/javascript">
//var mydata = JSON.parse(egdata);

//inactive for now
function showBank() {
	document.getElementById("bankinfo1").innerHTML = mydata[0].name;
	document.getElementById("bankinfo2").innerHTML = "Bank:   "+mydata[0].bank;
	document.getElementById("bankinfo3").innerHTML = "Account:"+mydata[0].account;
	document.getElementById("bankinfo4").innerHTML = "Branch: "+mydata[0].branch;
	document.getElementById("bankinfo5").innerHTML = "Type:   "+mydata[0].type;
	
}
//from cart to order
function runTwigSubmit(tennisball) {    
    if(totalStix>0){
       var container = document.getElementById(tennisball);
      //createAnArk();
      addAirFields();   
    return true; 
    }
    else{ return false;} 
}

function addAirFields(){    
    stick = 0;
    realTotal = totalStix;  
    //ready count of sticks for upload    
    while (container.hasChildNodes()) {
        container.removeChild(container.lastChild);
    }
    //run all sticks available and check which ones have data to upload to db
    for (istick=1;istick<=totalStix;istick++){                  
          attachStix(istick,"id",orderCart[istick]);                
    }   
    numStix();                
}
function numStix() {
    container.appendChild(document.createTextNode(""));
    var input = document.createElement("input");
    container.appendChild(input);
    input.type = "number";
    input.id = "sticknum";
    input.name = "sticknum";
    input.value = realTotal;
    input.style.display = "none";
    document.getElementById('mc').innerHTML = "Your stick count so far (branches and twigs):"+realTotal;    
}

function attachStix(stick, dancing, matilda) {
    container.appendChild(document.createTextNode(""));
    var input = document.createElement("input");
    container.appendChild(input);
    input.type = "text";
    input.id = "stick"+dancing+stick;
    input.name = "stick"+dancing+stick;
    input.value = matilda;
    input.style.display = "none";
}
</script>