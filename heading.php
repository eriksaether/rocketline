	<?php 
    
      session_start();      
      $firstname = "";
      $login = 0;
      // If the session vars aren't set, try to set them with a cookie
      if (!isset($_SESSION['userid'])) {
        if ( isset($_COOKIE['userid']) && isset($_COOKIE['username']) && isset($_COOKIE['firstname']) ) {
          $_SESSION['userid'] = $_COOKIE['userid'];
          $_SESSION['username'] = $_COOKIE['username'];
          $_SESSION['firstname'] = $_COOKIE['firstname'];
          
        }
      }
      if(!empty($_COOKIE['userid'])) {
        $useride = $_COOKIE['userid'];
        $usernaam = $_COOKIE['username'];
        $firstname = $_COOKIE['firstname'];
        $login = 1;
      }
   ?>
   <?php require_once("linkmenu.php"); ?>
  <header>
    <div  class="headernav"><div class="transbox">
    <h3><span class="shopman">Rocketline</span></h3>
    <span class="spaceman">         
      <a href="<?php echo $linkmenu; ?>index.php" class="chosen">Shop</a>                                  
      <a href="<?php echo $linkmenu; ?>orders.php" class="chosen">Foot's&nbsp;Spoor</a> 
       <a href="<?php echo $linkmenu; ?>toprockets.php" class="chosen">Valley</a>  
       <a href="<?php echo $linkmenu; ?>adinventory.php" class="chosen">Rockery</a>  
      <?php 
        if(isset($useride)) {echo "<a id='usernaam' href='".$linkmenu."editprofile.php' class='chosen'>&#9924;".$firstname."</a>";}
        else {echo "<a id='usernaam' href='".$linkmenu."login.php' class='chosen'>My Stuff</a>";}
      ?>  

         
    </span>
    </div></div>
	</header>

  <style type="text/css">
    .chosen {
      color: white;
    }
    .thechosen {
      color: yellow;
    }
    .headernav {
        background-color: orange;
        color: white;
        padding: 5px;
        margin-top: -10px;
        border-bottom: 2px black solid;
        display: contain;

    }
    .shopman {
      display: block;
      width: 250px;
      padding: 3px; 
      padding-left: 50px;
      font-family: 'Nasalization Free', arial;
    }
    .spaceman {  
      margin-top: -20px;
      display: inline-block;
      float: right;
      width: 500px;
      background-color: gray;
      padding: 3px;
      border-radius: 3px;
      margin-right: 30px;
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
  </style>