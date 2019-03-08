<?php
// Grab the profile data from the database to ready php catches
if (isset($_POST['idviewartist'])) {

  $artistid = $_POST['idviewartist'];
 
  $query2 = "SELECT * FROM artists WHERE artistid = $artistid";    
  $result2 = mysqli_query($connex, $query2);
  
    while ($rower = mysqli_fetch_array($result2)) {
      
      echo"<div class='displayitem' id='displayitem' >";
      //picture....................................................
      if (!empty($rower['artistpic'])) {
         echo "<div class='trimpic'><img  ondblclick='myViewee(".$rower['artistid'].");' class='displaypic' src='" . MM_UPLOADPATH . $rower['artistpic'] .
        "' alt='Item Picture' ></div>";
        }
       else {
         echo "<div class='trimpic'><img class='displaypic' src='" . MM_UPLOADPATH . "88.png" .
        "' alt='Stock Picture' ondblclick='myViewee(".$rower['artistid'].");'></div>";
        }
       //text box ........................ ........................
      echo "<div class='displaybox' id='displaybox'>";
        echo "<div ><br><b>Artist: ".$rower['artistname']."</b>";

        echo "<br>".$rower['description']."<br>";        
              
        echo "<span class='dashes' onmouseover='showOne(0,5);' onmouseout='showOff(0,5);'>Info</span>";        
        echo "<span class='redx' onclick='stopShow();'>x</span>";
       
        echo "</div>";
      echo "</div>";
      
    }
}
?>
<style>

.picbox {  
  box-sizing: border-box;
  margin-bottom: 10px;  
  height: 200px;
  overflow: contain;
}

.displayitem {
  position: absolute;
  background-color: white;
  opacity: 1;
  display: box;
  width: 720px;
  height: 388px;
  top: 120px;
  left: 80px;
  border: orange solid 3px;
  border-radius: 6px;
  padding: 5px;
  box-shadow: 2px 2px 2px; 
  font-family: arial;
    z-index: 1;
}
.displaypic {
  cursor: pointer;
  vertical-align: !important middle;
  min-height: 100%;  
  max-width: 150%; 
  height: 300px;  
  position: relative;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);    
  background-color: white;  
}
.displaypic:hover {
  border: white solid 0px;
}
.displaybrand {
  max-width: 70px;
  max-height: 35px;
}

.trimpic {
  width: 350px;
  height: 350px;
  overflow: hidden;  
  align-content: center;
    border: 2px solid lightgray;
    align-content: center;
    margin-left: 10px;
    margin-top: 10px;
    transition: transform .5s ease-in-out;
    border-radius: 50%;
}
.trimpic:hover {
  transform: scale(1.1);
}
.displaybox {
  position: relative;
  left: 400px;
  top: -350px;
  display: box;
  width: 300px;
  height: 350px;
}


.displayitem:hover {
  box-shadow: 4px 4px 4px;
}
@media screen and (max-width: 800px) {
  .displayitem {
    left: 30px;
  }

}
.dashes {
  position: relative;
  max-width: 20px;
  padding: 5px;
  padding-top: 3px;
  padding-bottom: 3px;
  color: white;
  background-color: orange;
  border-radius: 3px;
  margin: 1px;
  top: 50px;
  cursor: pointer;
}
.dashes:hover {
  background-color: black;
}
.info {
  visibility: hidden;

  position: absolute;
    top: 30px;      

    right: 0px;
    background-color: white;
    color: maroon;
    padding-left: 20px;
    padding-right: 20px;
    visibility: hidden;
    opacity: 1;
    border-radius: 1px;
}
.dash-text-info {
  color: maroon;
}
.dash-text {
  visibility: hidden;
  position: absolute;
    top: 35px;      
    width: 260px;
    height: 300px;
    background-color: white;
    color: maroon;
    padding-left: 20px;
    padding-right: 20px;
    visibility: hidden;
    opacity: 1;
    border-radius: 1px;
}

.redx {
  color: red;
  margin-left: 65px;
  background-color: white;
  border: 1px gray solid;
  width: 25px;
  height: 25px;
  box-sizing: border-box;
  border-radius: 3px;
  cursor: pointer;
  position: absolute;
  top: 320px;
  text-align: center;
}
.redx:hover{
  background-color: red;
  color: white;
}
.inputqty {
  width: 40px;
  background-color: orange;
  color: white;
}
button {
  background-color: ghostwhite;
  border: ghostwhite solid 1px;
  cursor: context-menu;
}
label {
  width: 105px;
}
.light {
  color: gray;
  font-size: 12px;
}
</style>
<script type="text/javascript">
 
function myViewGo(littlejohn) {
  document.getElementById("idviewgo").value = littlejohn;
  document.getElementById("myFormViewGo").submit();
}
 
function myViewArtist(littlejohn) {
  document.getElementById("idviewartist").value = littlejohn;
  document.getElementById("myFormViewArtist").submit();
}
function stopShow() {
  document.getElementById("displayitem").style.display = "none";
}

  function showOne(theCard, theDash) {
    var theBoulder = theDash;
    
    var elephant = document.getElementsByClassName('displaybox')[0];
    elephant.children[theBoulder].style.visibility = "visible";
    
    
  }
  function showOff(theCard, theDash) {
    var theBoulder = theDash;
    
    var elephant = document.getElementsByClassName('displaybox')[0];
    
    elephant.children[theBoulder].style.visibility = "hidden";    
    
  }

</script>