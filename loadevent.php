<?php

if (!empty($eventname) ) {

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
    
    if(isset($_POST['eventpic'])) {
      $neweventpic = cleanMyPic($_POST['eventpic']);
    }
    if(isset($_POST['oldeventpic']) ) {
      $oldeventpic = mysqli_real_escape_string($dbc, trim($_POST['oldeventpic']));
    }
    $neweventpic = mysqli_real_escape_string($dbc, trim($_FILES['eventpic']['name']));  
    $error = false;
    
    if(!empty($neweventpic)) {      

      $new_picture_type = $_FILES['eventpic']['type'];
      $new_picture_size = $_FILES['eventpic']['size']; 
      list($new_picture_width, $new_picture_height) = getimagesize($_FILES['eventpic']['tmp_name']);
      $error = false;      

      //for future reference............
      define('CW_UPLOADPATH','pictures/');
      //ready pic loading
      $target_dir = "pictures/";
      $target_file = $target_dir . basename($_FILES["eventpic"]["name"]); 
      $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
      
      $image_info = getimagesize($_FILES["eventpic"]["tmp_name"]);
      $image_width = $image_info[0];
      $image_height = $image_info[1]; 
    }
    cleanMyPic($neweventpic);
    echo $neweventpic;

    // Validate and move the uploaded picture file, if necessary
    if (!empty($neweventpic)) {
      if ((($new_picture_type == 'image/gif') || ($new_picture_type == 'image/jpeg') || ($new_picture_type == 'image/pjpeg') ||
        ($new_picture_type == 'image/png')) && ($new_picture_size > 0) && ($new_picture_size <= MM_MAXFILESIZE) ) {
        if ($_FILES['eventpic']['error'] == 0) {
          // Move the file to the target upload folder
          $target = MM_UPLOADPATH . basename($neweventpic);
          if (move_uploaded_file($_FILES['eventpic']['tmp_name'], $target)) {
            // The new picture file move was successful, now make sure any old picture is deleted
            if (!empty($oldeventpic) && ($oldeventpic != $neweventpic)) {
              @unlink(MM_UPLOADPATH . $oldeventpic);
            }
          }
          else {
            // The new picture file move failed, so delete the temporary file and set the error flag
            @unlink($_FILES['eventpic']['tmp_name']);
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
        @unlink($_FILES['eventpic']['tmp_name']);
        $error = true;
        echo '<p class="error">Your picture must be a GIF, JPEG, or PNG image file no greater than ' . (MM_MAXFILESIZE / 1024) .
          ' KB and ' . MM_MAXIMGWIDTH . 'x' . MM_MAXIMGHEIGHT . ' pixels in size. Type: '.$new_picture_type .'</p>';
      }
    }


      // Check if the event name is already loaded
      $queryj = "SELECT * FROM garagesales WHERE eventname = '$eventname'";
      $dataj= mysqli_query($dbc, $queryj);
      

      if (mysqli_num_rows($dataj) == 0) {

            // The stockname is new, so insert (add) the data into the database
            if (!empty($neweventpic)) {                  
              $queryaj = "INSERT INTO garagesales (eventname, eventpic, expirydate, userid) VALUES ('$eventname', '$neweventpic', '$expirydate', '$useride')";          
              echo "Picture uploaded: ".$neweventpic.". ";
            }
            else {          
              $queryaj = "INSERT INTO garagesales (eventname, expirydate, userid) VALUES ('$eventname', '$expirydate','$useride')";
            }

            if(mysqli_query($dbc, $queryaj)) {
              // Confirm success with the user
              echo '<p class="mirror">Your new event has been successfully created.</p>';  
            }
            else {
              echo '<p class="mirror">Error loading event:'.mysqli_error($dbc).'</p>';
            }    
      }
      else {

        // An account already exists for this stockname, so update info only        
        if (!empty($neweventpic)) {
          // Only set the picture column if there is a new picture 
          $queryupd = "UPDATE garagesales SET eventname = '$eventname', expirydate = '$expirydate', eventpic = '$neweventpic' WHERE eventid = '$eventide'";
            echo "Picture uploaded: ".$neweventpic.". ";
        }
        else {          
          $queryupd = "UPDATE garagesales SET eventname = '$eventname', expirydate = '$expirydate' WHERE eventid = '$eventide'";
            echo "All but picture loaded. ";
        }        
        //load some subitem info
        mysqli_query($dbc, $queryupd);
        echo '<span>Your event has been successfully updated. </span>';
                     
      }

    }
    else {
      echo '<p class="mirror">You must enter all of the data. We need a stock name and description.</p>';
    }



?>