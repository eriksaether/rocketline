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