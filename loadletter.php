<?php

if (!empty($lettername) && !empty($letterdescribe)) {
    
    $old_picture = mysqli_real_escape_string($dbc, trim($_POST['old_picture']));

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
    
    $new_picture = mysqli_real_escape_string($dbc, trim($_FILES['screenshot']['name']));    
    $error = false;

    if (strlen($new_picture)>1) {
      //test the pic file...............
      $_FILES['screenshot']['name'] = cleanMyPic($_FILES['screenshot']['name']);
      $new_picture = cleanMyPic($new_picture);

      $new_picture_type = $_FILES['screenshot']['type'];
      $new_picture_size = $_FILES['screenshot']['size']; 
      list($new_picture_width, $new_picture_height) = getimagesize($_FILES['screenshot']['tmp_name']);
      $error = false;      

      //for future reference............
      define('CW_UPLOADPATH','pictures/');
      //ready pic loading
      $target_dir = "pictures/";
      $target_file = $target_dir . basename($_FILES["screenshot"]["name"]); 
      $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
      
      $image_info = getimagesize($_FILES["screenshot"]["tmp_name"]);
      $image_width = $image_info[0];
      $image_height = $image_info[1]; 
    }    
    
    // Validate and move the uploaded picture file, if necessary
    if (strlen($new_picture)>1) {
      
      //validate if picture.............>>
      if ((($new_picture_type == 'image/gif') || ($new_picture_type == 'image/jpeg') || ($new_picture_type == 'image/pjpeg') ||
        ($new_picture_type == 'image/png')) && ($new_picture_size > 0) && ($new_picture_size <= MM_MAXFILESIZE) ) {
        if ($_FILES['screenshot']['error'] == 0) {
          // Move the file to the target upload folder
          $target = MM_UPLOADPATH . basename($new_picture);
          if (move_uploaded_file($_FILES['screenshot']['tmp_name'], $target)) {
            // The new picture file move was successful, now make sure any old picture is deleted
            if (!empty($old_picture) && ($old_picture != $new_picture)) {
              @unlink(MM_UPLOADPATH . $old_picture);
            }
          }
          else {
            // The new picture file move failed, so delete the temporary file and set the error flag
            @unlink($_FILES['screenshot']['tmp_name']);
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
        @unlink($_FILES['screenshot']['tmp_name']);
        $error = true;
        echo '<p class="error">Your picture must be a GIF, JPEG, or PNG image file no greater than ' . (MM_MAXFILESIZE / 1024) .
          ' KB and ' . MM_MAXIMGWIDTH . 'x' . MM_MAXIMGHEIGHT . ' pixels in size. Type: '.$new_picture_type .'</p>';
      }
    }


      // Check if the lettername is already loaded
      $queryj = "SELECT * FROM letters WHERE lettername = '$lettername'";
      $dataj= mysqli_query($dbc, $queryj);
      

      if (mysqli_num_rows($dataj) == 0) {

            // The lettername is new, so insert (add) the data into the database
            if (isset($new_picture)) {                  
              $queryaj = "INSERT INTO letters (lettername, description, category, subcategory, askprice, picture, suppliertype, ownerid) VALUES ('$lettername', '$letterdescribe', '$lettercategory', '$lettersubcategory', '$askprice', '$new_picture', '$suppliertype', '$useride')";          
              echo "Picture uploaded: ".$new_picture.". ";
            }
            else {          
              $queryaj = "INSERT INTO letters (lettername, description, category, subcategory, askprice, suppliertype, ownerid) VALUES ('$lettername', '$letterdescribe', '$lettercategory', '$lettersubcategory', '$askprice', '$suppliertype', '$useride')";
            }

            if(mysqli_query($dbc, $queryaj)) {
              // Confirm success with the user
              echo '<p class="mirror">Your new letter has been successfully created.</p>';  
            }
            else {
              echo '<p class="mirror">Error loading letter:'.mysqli_error($dbc).'</p>';
            }    
      }
      else {

        // An account already exists for this lettername, so update info only        
        if (strlen($new_picture)>1) {
          // Only set the picture column if there is a new picture 
          $queryupd = "UPDATE letters SET lettername = '$lettername', category = '$lettercategory', subcategory = '$lettersubcategory', description = '$letterdescribe', askprice  = '$askprice', suppliertype  = '$suppliertype', picture = '$new_picture' WHERE letterid = '$letteride'";
            echo "Picture uploaded: ".$new_picture.". ";
        }
        else {          
          $queryupd = "UPDATE letters SET lettername = '$lettername', category = '$lettercategory', subcategory = '$lettersubcategory', description = '$letterdescribe', askprice  = '$askprice', suppliertype  = '$suppliertype', WHERE letterid = '$letteride'";
            echo "All but picture loaded. ";
        }        
        //load some subitem info
        mysqli_query($dbc, $queryupd);
        echo '<span>Your letter item has been successfully updated. </span>';

        //grab item again
        $query3 = "SELECT * FROM letters WHERE lettername = '%$lettername%'";    
        $result3 = mysqli_query($dbc, $query3);
        
        while ($rower3 = mysqli_fetch_array($result3)) {
            $letteride = $rower3["letterid"];
        }
        if (!$result3) {
            printf("Error: %s\n", mysqli_error($dbc));
            exit();
        }


                   
      }
     // Make sure there is no cats that are the same (loaded twice in this file - same below)
       $sqlcats = "SELECT * FROM categories WHERE name LIKE '%{$lettercategory}%'";
       $resultcats= mysqli_query($dbc, $sqlcats);
       if (mysqli_num_rows($resultcats) == FALSE) {

         $qcat =  "INSERT INTO categories (name) VALUES ('$lettercategory')";
         mysqli_query($dbc, $qcat);    
         echo '<span>Category inserted. </span>';     
       }

       $sqlcatsview = "SELECT * FROM categories WHERE name LIKE '%{$lettercategory}%'";       
       $result3 = $dbc->query($sqlcatsview);  
       while($rowview = $result3->fetch_assoc()) { 
          $catview = $rowview["catid"];
       }

      // Make sure there is no subcats that are the same (loaded twice in this file - same below)
       $sqlsubs = "SELECT * FROM subcategories WHERE name LIKE '%{$lettersubcategory}%' AND catid = '$catview'";
       $resultsubs= mysqli_query($dbc, $sqlsubs);
       if (mysqli_num_rows($resultsubs) == FALSE) {

         $qsubcat =  "INSERT INTO subcategories (name, catid) VALUES ('$lettersubcategory', '$catview')";
         mysqli_query($dbc, $qsubcat);     
       }


    }
    else {
      echo '<p class="mirror">You must enter all of the data. We need a letter name and description.</p>';
    }




?>