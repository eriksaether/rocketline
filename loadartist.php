<?php

if (!empty($artistname) ) {

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
    
    if(isset($_POST['artistpic'])) {
      $newartistpic = cleanMyPic($_POST['artistpic']);
    }
    if(isset($_POST['oldartistpic']) ) {
      $oldartistpic = mysqli_real_escape_string($dbc, trim($_POST['oldartistpic']));
    }
    $newartistpic = mysqli_real_escape_string($dbc, trim($_FILES['artistpic']['name']));  
    $error = false;
    
    if(!empty($newartistpic)) {      

      $new_picture_type = $_FILES['artistpic']['type'];
      $new_picture_size = $_FILES['artistpic']['size']; 
      list($new_picture_width, $new_picture_height) = getimagesize($_FILES['artistpic']['tmp_name']);
      $error = false;      

      //for future reference............
      define('CW_UPLOADPATH','pictures/');
      //ready pic loading
      $target_dir = "pictures/";
      $target_file = $target_dir . basename($_FILES["artistpic"]["name"]); 
      $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
      
      $image_info = getimagesize($_FILES["artistpic"]["tmp_name"]);
      $image_width = $image_info[0];
      $image_height = $image_info[1]; 
    }
    cleanMyPic($newartistpic);
    echo $newartistpic;

    // Validate and move the uploaded picture file, if necessary
    if (!empty($newartistpic)) {
      if ((($new_picture_type == 'image/gif') || ($new_picture_type == 'image/jpeg') || ($new_picture_type == 'image/pjpeg') ||
        ($new_picture_type == 'image/png')) && ($new_picture_size > 0) ) {
        if ($_FILES['artistpic']['error'] == 0) {
          // Move the file to the target upload folder
          $target = MM_UPLOADPATH . basename($newartistpic);
          if (move_uploaded_file($_FILES['artistpic']['tmp_name'], $target)) {
            // The new picture file move was successful, now make sure any old picture is deleted
            if (!empty($oldartistpic) && ($oldartistpic != $newartistpic)) {
              @unlink(MM_UPLOADPATH . $oldartistpic);
            }

            $filename = MM_UPLOADPATH . $_FILES["artistpic"]["name"];                
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
            @unlink($_FILES['artistpic']['tmp_name']);
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
        @unlink($_FILES['artistpic']['tmp_name']);
        $error = true;
        echo '<p class="error">Your picture must be a GIF, JPEG, or PNG image file no greater than ' . (MM_MAXFILESIZE / 1024) .
          ' KB and ' . MM_MAXIMGWIDTH . 'x' . MM_MAXIMGHEIGHT . ' pixels in size. Type: '.$new_picture_type .'</p>';
      }
    }


      // Check if the artist name is already loaded
      $queryj = "SELECT * FROM artists WHERE artistname = '$artistname'";
      $dataj= mysqli_query($dbc, $queryj);
      

      if (mysqli_num_rows($dataj) == 0) {

            // The stockname is new, so insert (add) the data into the database
            if (!empty($newartistpic)) {                  
              $queryaj = "INSERT INTO artists (artistname, artistpic, description, supplierid, userid) VALUES ('$artistname', '$newartistpic', '$artistdescribe', '$supplieridb', '$useride')";          
              echo "Picture uploaded: ".$newartistpic.". ";
            }
            else {          
              $queryaj = "INSERT INTO artists (artistname,  description, supplierid, userid) VALUES ('$artistname', '$artistdescribe', '$supplieridb', '$useride')";
            }

            if(mysqli_query($dbc, $queryaj)) {
              // Confirm success with the user
              echo '<p class="mirror">Your new artist has been successfully created.</p>';  
            }
            else {
              echo '<p class="mirror">Error loading artist:'.mysqli_error($dbc).'</p>';
            }    
      }
      else {

        // An account already exists for this stockname, so update info only        
        if (!empty($newartistpic)) {
          // Only set the picture column if there is a new picture 
          $queryupd = "UPDATE artistnames SET artistname = '$artistname', description = '$artistdescribe', supplierid  = '$supplieridb', artistpic = '$newartistpic' WHERE artistid = '$artistide'";
            echo "Picture uploaded: ".$newartistpic.". ";
        }
        else {          
          $queryupd = "UPDATE artistnames SET artistname = '$artistname', description = '$artistdescribe', supplierid  = '$supplieridb' WHERE artistid = '$artistide'";
            echo "All but picture loaded. ";
        }        
        //load some subitem info
        mysqli_query($dbc, $queryupd);
        echo '<span>Your artist has been successfully updated. </span>';
                     
      }

    }
    else {
      echo '<p class="mirror">You must enter all of the data. We need the artist name.</p>';
    }



?>