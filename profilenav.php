 <nav class="van">
 	<a class="navi chosen2" href="suppliers.php">Suppliers</a>
 	<a class="navi chosen2" href="commissions.php">Comm&nbsp;Rules</a>
 	<a class="navi chosen2" href="commdetail.php">Sale&nbsp;Comm</a> 	
 	<a class="navi chosen2" href="editprofile.php">My Info</a>
 <?php  if (!isset($_SESSION['userid'])) {echo"<a class='navi chosen2' href='login.php'>login</a>";} 
            else {echo "<a class='navi chosen2' href='logout.php'>logout</a>";} ?>  
 </nav> 
<style>
	.chosen:nth-child(5) {
		color: yellow;
	}
	nav {
	    background-color: lightgray;
	    width: 525px;
	    border-radius: 2px;
	    color: white;
	    padding: 2px;
	    text-align: center;
	}
	nav.van {
	    background-color: gray;
	}
	.navi {
	    display: inline-block;
	    width: 100px;
	}
</style>