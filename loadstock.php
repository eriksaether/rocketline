<?php

if (!empty($stockname) && !empty($stockdescribe)) {
    
    if(isset($_POST['old_picture'])) {
      $old_picture = mysqli_real_escape_string($dbc, trim($_POST['old_picture']));
    }

    function cleanMyPic($poster) {
        if (strpos($poster, '.php') !== false) {
                echo 'oh..'; 
                $critter = $_POST['old_picture'];
                exit();
        }
        else {
                $critter = $poster;    
                $arr = explode(".", $critter);
                $picname = $arr[0];
                $endext = end($arr);
                $critter = $picname . "." . $endext;                
        }
        return $critter;  
    }
    function changePicName($poster, $thename) {
        
        $critter = $poster;    
        $arr = explode(".", $critter);
        $picname = $arr[0];
        $endext = end($arr);
        $critter = $thename . "." . $endext;        

        return $critter;  
    }
    //echo "oks so far";
    $new_picture = $_FILES['screenshot']['name'];    
    $error = false;
   
    // Validate and move the uploaded picture file, if necessary
    if (strlen($new_picture)>1) {

      //test the pic file...............
      $_FILES['screenshot']['name'] = cleanMyPic($_FILES['screenshot']['name']);
      
      $new_picture_type = $_FILES['screenshot']['type'];
      $new_picture_size = $_FILES['screenshot']['size']; 
     
      $error = false;      
     
      if(isset($_FILES["screenshot"]["tmp_name"])){
          $image_info = getimagesize($_FILES["screenshot"]["tmp_name"]);
          $image_width = $image_info[0];
          $image_height = $image_info[1];
      }
       
      
      //validate if picture.............>>
      if ((($new_picture_type == 'image/gif') || ($new_picture_type == 'image/jpeg') || ($new_picture_type == 'image/jpg') || ($new_picture_type == 'image/pjpeg') ||
        ($new_picture_type == 'image/png')) && ($new_picture_size > 0) ) {

          if ($_FILES['screenshot']['error'] == 0 ) {
          
              // Move the file to the target upload folder
              $target = MM_UPLOADPATH . basename($new_picture);
              //echo $target." ";
              if (move_uploaded_file($_FILES['screenshot']['tmp_name'], $target)) {
                // make sure any old picture is deleted if new picture added
                if (!empty($old_picture) && ($old_picture != $new_picture)) {
                  @unlink(MM_UPLOADPATH . $old_picture);
                }
                //ready to change image size..
                $gridpic = changePicName($_FILES['screenshot']['name'], $stockname);
                $gridhome = MM_UPLOADPATH . $gridpic;

                $filename = MM_UPLOADPATH . $_FILES["screenshot"]["name"];                
                $width = $image_width;
                $height = $image_height;
                echo "Image size: " .$width."x".$height. ". ";

                //introduce new size for grid 
                if($width > 600){$newwidth = 600;}  
                else{$newwidth = $width;}              
                $aspectrio = $height/$width;                
                $newheight = intval($newwidth*$aspectrio);               
                $filehome = $gridhome;
                require("imgresizer.php");

                //introduce new size for main pic
                if($width > 960) {
                  $newwidth = 960;
                  $aspectrio = $height/$width;                
                  $newheight = intval($newwidth*$aspectrio);               
                  $filehome = $filename;
                  require("imgresizer.php");
                  echo "Changed to: " .$newwidth."x".$newheight. ". ";
                }  
                $pictureloaded = 1;          
              }
              else {
                // The new picture file move failed, so delete the temporary file and set the error flag
                @unlink($_FILES['screenshot']['tmp_name']);
                $error = true;
                echo '<p class="error">Sorry, there was a problem uploading your pic.</p>';
                $pictureloaded = 0;
              }          
          }
          else {
            echo "Picture loading error. ";
            $pictureloaded = 0;
          }
      }
      else {
        // The new picture file is not valid, so delete the temporary file and set the error flag
        @unlink($_FILES['screenshot']['tmp_name']);
        $error = true;
        echo '<p class="error">Your picture must be a GIF, JPEG, or PNG image file not too large. Your file type: '.$_FILES['screenshot']['type'] .'</p>';
        $pictureloaded = 0;
      }
    }
    
    if(empty($gridpic)){ $gridpic=$new_picture;}     //get past old ways

    //make new if not loaded
    if ($stockide == 0) {

           // Check if the stockname accidently is already loaded
          $queryj = "SELECT * FROM stocktypes WHERE stockname = '$stockname' AND ownerid = '$useride'";
          $resultj= mysqli_query($dbc, $queryj);
          while ($rowern = mysqli_fetch_array($resultj)) {
              $stockide = $rowern["stockid"];
              echo "Item found looks the same as:".$stockide.". Please check. ";
              exit();
          }
          // The stockname is new, so insert (add) the data into the database
          if (strlen($new_picture)>1 AND $pictureloaded == 1) {      

            $queryaj = "INSERT INTO stocktypes (active, stockname, stockcode, description, applications, specifications, category, subcategory, choiceone, choicetwo, retailprice, costprice, weight, width, height, $depth, picture, gridpic, supplierid, brandid, artistid, auctionyesid, ownerid) VALUES ('$active', '$stockname', '$stockcode', '$stockdescribe', '$stockapps', '$stockspecs', '$stockcategory', '$stocksubcategory', '$stockchoice1', '$stockchoice2', '$retailprice', '$costprice', '$weight', '$width', '$height', '$depth', '$new_picture', '$gridpic', '$supplierid', '$brandid', '$artistid', '$auctionyesid', '$useride')";          
            echo "Picture saved: ".$new_picture.". ";
          }
          else {          
            $queryaj = "INSERT INTO stocktypes (active, stockname, stockcode, description, applications, specifications, category, subcategory,  choiceone, choicetwo, retailprice, costprice, weight, width, height, $depth, supplierid, brandid, artistid, auctionyesid, ownerid) VALUES ('$active', '$stockname', '$stockcode', '$stockdescribe', '$stockapps', '$stockspecs','$stockcategory', '$stocksubcategory', '$stockchoice1', '$stockchoice2', '$retailprice', '$costprice', '$weight', '$width', '$height', '$depth', '$supplierid', '$brandid', '$artistid', '$auctionyesid', '$useride')";
          }

          if(mysqli_query($dbc, $queryaj)) {
            // Confirm success with the user
            echo 'Your new stock has been successfully created.';  
          }
          else {
            echo '<p class="mirror">Error loading stock:'.mysqli_error($dbc).'</p>';
          }    
    }
    else {
         
          // An account already exists for this stockname, so update info only        
          if (strlen($new_picture)>1 AND $pictureloaded == 1) {
            // Only set the picture column if there is a new picture 
            $queryupd = "UPDATE stocktypes SET active = '$active', stockname = '$stockname', stockcode = '$stockcode', category = '$stockcategory', subcategory = '$stocksubcategory', choiceone = '$stockchoice1', choicetwo = '$stockchoice2', description = '$stockdescribe', applications = '$stockapps', specifications = '$stockspecs', retailprice  = '$retailprice', costprice  = '$costprice', weight  = '$weight', width  = '$width', height  = '$height', depth  = '$depth', supplierid  = '$supplierid', brandid  = '$brandid', artistid  = '$artistid', auctionyesid  = '$auctionyesid', picture = '$new_picture', gridpic = '$gridpic' WHERE stockid = '$stockide'";
              echo "Picture uploaded: ".$new_picture.". ";
              
          }
          else {          
            $queryupd = "UPDATE stocktypes SET active = '$active', stockname = '$stockname', stockcode = '$stockcode', category = '$stockcategory', subcategory = '$stocksubcategory', choiceone = '$stockchoice1', choicetwo = '$stockchoice2', description = '$stockdescribe', applications = '$stockapps', specifications = '$stockspecs', retailprice  = '$retailprice', costprice  = '$costprice', weight  = '$weight',  width  = '$width', height  = '$height', depth  = '$depth', supplierid  = '$supplierid', brandid  = '$brandid', artistid  = '$artistid', auctionyesid  = '$auctionyesid' WHERE stockid = '$stockide'";
              echo "All but picture loaded. ";
          }
          mysqli_query($dbc, $queryupd); 
          //load some subitem info
          if(mysqli_query($dbc, $queryupd)){
            echo '<span>The stock item: '.$stockide.' has been successfully updated. </span>';
          }
          else{
            printf("Error: %s\n", mysqli_error($dbc));
          }

          //grab item again
          $query3 = "SELECT * FROM stocktypes WHERE stockcode = '%$stockdescribe%'";    
          $result3 = mysqli_query($dbc, $query3);
          
          while ($rower3 = mysqli_fetch_array($result3)) {
              $stockide = $rower3["stockid"];
          }
          if (!$result3) {
              printf("Error: %s\n", mysqli_error($dbc));
              exit();
          }                 
     }
     // Make sure there is no cats that are the same (loaded twice in this file - same below)
     $sqlcats = "SELECT * FROM categories WHERE name LIKE '%{$stockcategory}%'";
     $resultcats= mysqli_query($dbc, $sqlcats);
     if (mysqli_num_rows($resultcats) == FALSE) {

       $qcat =  "INSERT INTO categories (name) VALUES ('$stockcategory')";
       mysqli_query($dbc, $qcat);    
       echo '<span>Category inserted. </span>';     
     }

     $sqlcatsview = "SELECT * FROM categories WHERE name LIKE '%{$stockcategory}%'";       
     $result3 = $dbc->query($sqlcatsview);  
     while($rowview = $result3->fetch_assoc()) { 
        $catview = $rowview["catid"];
     }

    // Make sure there is no subcats that are the same (loaded twice in this file - same below)
     $sqlsubs = "SELECT * FROM subcategories WHERE name LIKE '%{$stocksubcategory}%' AND catid = '$catview'";
     $resultsubs= mysqli_query($dbc, $sqlsubs);
     if (mysqli_num_rows($resultsubs) == FALSE) {

       $qsubcat =  "INSERT INTO subcategories (name, catid) VALUES ('$stocksubcategory', '$catview')";
       mysqli_query($dbc, $qsubcat);     
     }

}
else {
      echo '<p class="mirror">You must enter all of the data. We need a stock name and description.</p>';
}

?>