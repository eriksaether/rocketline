<?php

if (!empty($brandname) && !empty($supplieridb)) {

    function cleanMyPic($poster) {
        if (strpos($poster, '.php') !== false) {
                echo 'oh..'; 
                $critter = $_POST['old_picture'];
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
    
    if(isset($_POST['brandpic'])) {
      $newbrandpic = cleanMyPic($_POST['brandpic']);
    }
    if(isset($_POST['oldbrandpic']) ) {
      $oldbrandpic = mysqli_real_escape_string($dbc, trim($_POST['oldbrandpic']));
    }
    $newbrandpic = mysqli_real_escape_string($dbc, trim($_FILES['brandpic']['name']));  
    $error = false;
    
    if(!empty($newbrandpic)) {      

      $new_picture_type = $_FILES['brandpic']['type'];
      $new_picture_size = $_FILES['brandpic']['size']; 
      list($new_picture_width, $new_picture_height) = getimagesize($_FILES['brandpic']['tmp_name']);
      $error = false;      

      //for future reference............
      define('CW_UPLOADPATH','pictures/');
      //ready pic loading
      $target_dir = "pictures/";
      $target_file = $target_dir . basename($_FILES["brandpic"]["name"]); 
      $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
      
      $image_info = getimagesize($_FILES["brandpic"]["tmp_name"]);
      $image_width = $image_info[0];
      $image_height = $image_info[1]; 
    }
    cleanMyPic($newbrandpic);
    echo $newbrandpic;

    // Validate and move the uploaded picture file, if necessary
    if (!empty($newbrandpic)) {
      if ((($new_picture_type == 'image/gif') || ($new_picture_type == 'image/jpeg') || ($new_picture_type == 'image/pjpeg') ||
        ($new_picture_type == 'image/png')) && ($new_picture_size > 0)  ) {
        if ($_FILES['brandpic']['error'] == 0) {
          // Move the file to the target upload folder
          $target = MM_UPLOADPATH . basename($newbrandpic);
          if (move_uploaded_file($_FILES['brandpic']['tmp_name'], $target)) {
            // The new picture file move was successful, now make sure any old picture is deleted
            if (!empty($oldbrandpic) && ($oldbrandpic != $newbrandpic)) {
              @unlink(MM_UPLOADPATH . $oldbrandpic);
            }

            $filename = MM_UPLOADPATH . $_FILES["brandpic"]["name"];                
            $width = $image_width;
            $height = $image_height;
            echo "Image size: " .$width."x".$height. ". ";

              //introduce new size for main pic
            if($width > 1000) {
              $newwidth = 1000;
              $aspectrio = $height/$width;                
              $newheight = intval($newwidth*$aspectrio);               
              $filehome = $filename;
              require("imgresizer.php");
              echo "Changed to: " .$newwidth."x".$newheight. ". ";
            }
          }
          else {
            // The new picture file move failed, so delete the temporary file and set the error flag
            @unlink($_FILES['brandpic']['tmp_name']);
            $error = true;
            echo '<p class="error">Sorry, there was a problem uploading your picture.</p>';
          }
        }
        else {
          echo "Picture loading error.";
        }
      }
      else {
        // The new picture file is not valid, so delete the temporary file and set the error flag
        @unlink($_FILES['brandpic']['tmp_name']);
        $error = true;
        echo '<p class="error">Your picture must be a GIF, JPEG, or PNG image file no greater than ' . (MM_MAXFILESIZE / 1024) .
          ' KB and ' . MM_MAXIMGWIDTH . 'x' . MM_MAXIMGHEIGHT . ' pixels in size. Type: '.$new_picture_type .'</p>';
      }
    }


      // Check if the brand name is already loaded
      $queryj = "SELECT * FROM brandnames WHERE brandname = '$brandname'";
      $dataj= mysqli_query($dbc, $queryj);
      

      if (mysqli_num_rows($dataj) == 0) {

            // The stockname is new, so insert (add) the data into the database
            if (!empty($newbrandpic)) {                  
              $queryaj = "INSERT INTO brandnames (brandname, brandpic, supplierid, userid) VALUES ('$brandname', '$newbrandpic', '$supplieridb', '$useride')";          
              echo "Picture uploaded: ".$newbrandpic.". ";
            }
            else {          
              $queryaj = "INSERT INTO brandnames (brandname, supplierid, userid) VALUES ('$brandname', '$supplieridb', '$useride')";
            }

            if(mysqli_query($dbc, $queryaj)) {
              // Confirm success with the user
              echo '<p class="mirror">Your new brand has been successfully created.</p>';  
            }
            else {
              echo '<p class="mirror">Error loading brand:'.mysqli_error($dbc).'</p>';
            }    
      }
      else {

        // An account already exists for this stockname, so update info only        
        if (!empty($newbrandpic)) {
          // Only set the picture column if there is a new picture 
          $queryupd = "UPDATE brandnames SET brandname = '$brandname', supplierid  = '$supplieridb', brandpic = '$newbrandpic' WHERE brandid = '$brandide'";
            echo "Picture uploaded: ".$newbrandpic.". ";
        }
        else {          
          $queryupd = "UPDATE brandnames SET brandname = '$brandname', supplierid  = '$supplieridb' WHERE brandid = '$brandide'";
            echo "All but picture loaded. ";
        }        
        //load some subitem info
        mysqli_query($dbc, $queryupd);
        echo '<span>Your brand has been successfully updated. </span>';
                     
      }

    }
    else {
      echo '<p class="mirror">You must enter all of the data. We need a stock name and description.</p>';
    }



?>