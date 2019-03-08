<?php
  session_start();

  // If the session vars aren't set, try to set them with a cookie
  if (!isset($_SESSION['user_id'])) {
    if (isset($_COOKIE['user_id']) && isset($_COOKIE['username'])) {
      $_SESSION['user_id'] = $_COOKIE['user_id'];
      $_SESSION['username'] = $_COOKIE['username'];
      
    }
  }
  $useride = $_COOKIE['user_id'];
  $usernaam = $_COOKIE['username'];

  //check for cookies first
  $teaparty = $_COOKIE['idinvite'];
  $teamname = $_COOKIE['teamname'];


  // If the session vars aren't set, try to set them with a cookie
  if (!isset($_SESSION['idinvite'])) {
    if (isset($_COOKIE['idinvite']) && isset($_COOKIE['teamname'])) {
      $_SESSION['idinvite'] = $_COOKIE['idinvite'];
      $_SESSION['teamname'] = $_COOKIE['teamname'];
      
    }
  }

  //catch post for invite query and set cookie
  if (isset($_POST['idinvite'])) {
    
    $teaparty = $_POST['idinvite'];
    $teamname = $_POST['teamname'];  

    //set cookies from post
    setcookie('idinvite', $teaparty, time() + (60 * 10 ), '/');    // expires in 10 minutes
    setcookie('teamname', $teamname, time() + (60 * 10 ), '/');    // expires in 10 minutes
    $_SESSION['idinvite'] = $_COOKIE['idinvite'];
    $_SESSION['teamname'] = $_COOKIE['teamname'];
  }

  // Grab the sort setting and search keywords from the URL using GET
  $teasearch = $_GET['idinviting'];
  $teaname = $_GET['teamnaming'];
  $sort = $_GET['sort'];
  $user_search = $_GET['usersearch'];  

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Possible PAX</title>
  <link rel="stylesheet" type="text/css" href="style.css" >
  
  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
   <!-- Normalize.css, a cross-browser reset file -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/normalize/4.1.1/normalize.css" rel="stylesheet">
</head>
<body>
<h1 id="idheadline" class="footnmouth"><div style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</div><div>Team Search</div><div style="font-size:25px; cursor:pointer"><a id="usernaam" class="spaceman" href="https://www.recruitrobot.co.za/eteam/logout.php"><?php echo "&#9973;".$usernaam; ?></a></div></h1>

<form method="get" action="search.php">

<?php require_once('sidenav.html'); ?>
<main>
<?php 

  //deal with search get query - may no longer need since cookie put in place
  if (isset($teasearch)) {

      echo '<h3>Invite to: '.$teaname.'</h3>';
      echo '<input id="idinviting" name="idinviting" value="'.$teasearch.'"  hidden>';
      echo '<input id="teamnaming" name="teamnaming" value="'.$teaname.'"  hidden>';
      $teaparty = $teasearch;
  }
  //post and cookie method
  elseif (isset($teamname)) {
      echo '<h3>Invite to: '.$teamname.'</h3>';
      echo '<input id="idinviting" name="idinviting" value="'.$teaparty.'"  hidden>';
      echo '<input id="teamnaming" name="teamnaming" value="'.$teamname.'"  hidden>';

  }
  else {
      echo '<h3>People - Search</h3>';
  }

?>  
    <span for="usersearch">Find your team member:</span><br />
    <input type="text" id="usersearch" name="usersearch" value="<?php echo $user_search; ?>"/>    
    <input type="submit" name="submit" class="material-icons" style="color:red"  value="&#xe8a0;"/><br>
</form><br>

<?php


  // This function builds a search query from the search keywords and sort setting
  function build_query($user_search, $sort) {
    $search_query = "SELECT * FROM mismatchuser";

    // Extract the search keywords into an array
    $clean_search = str_replace(',', ' ', $user_search);
    $search_words = explode(' ', $clean_search);
    $final_search_words = array();
    if (count($search_words) > 0) {
      foreach ($search_words as $word) {
        if (!empty($word)) {
          $final_search_words[] = $word;
        }
      }
    }

    // Generate a WHERE clause using all of the search keywords
    $where_list = array();
    if (count($final_search_words) > 0) {
      foreach($final_search_words as $word) {
        $where_list[] = "username LIKE '%$word%'";
      }
    }
    $where_clause = implode(' OR ', $where_list);

    // Add the keyword WHERE clause to the search query
    if (!empty($where_clause)) {
      $search_query .= " WHERE $where_clause";
    }

    // Sort the search query using the sort setting
    switch ($sort) {
    // Ascending by job title
    case 1:
      $search_query .= " ORDER BY username";
      break;
    // Descending by job title
    case 2:
      $search_query .= " ORDER BY username DESC";
      break;
    // Ascending by state
    case 3:
      $search_query .= " ORDER BY city";
      break;
    // Descending by state
    case 4:
      $search_query .= " ORDER BY city DESC";
      break;
    // Ascending by date posted (oldest first)
    case 5:
      $search_query .= " ORDER BY join_date DESC";
      break;
    // Descending by date posted (newest first)
    case 6:
      $search_query .= " ORDER BY join_date ";
      break;
    default:
      $search_query .= " ORDER BY join_date DESC";
    }

    return $search_query;
  }

  // This function builds heading links based on the specified sort setting
  function generate_sort_links($user_search, $sort) {
    $sort_links = '';

    switch ($sort) {
    case 1:
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=2">User</a></td><td>Last Name</td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">City</a></td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=5">Joined</a></td>';
      break;
    case 3:
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">User</a></td><td>Last Name</td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=4">City</a></td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Joined</a></td>';
      break;
    case 5:
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">User</a></td><td>Last Name</td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">City</a></td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=6">Joined</a></td>';
      break;
    default:
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">User</a></td><td>Last Name</td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">City</a></td>';
      $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=5">Joined</a></td>';
    }

    return $sort_links;
  }

  // This function builds navigational page links based on the current page and the number of pages
  function generate_page_links($user_search, $sort, $cur_page, $num_pages) {
    $page_links = '';

    // If this page is not the first page, generate the "previous" link
    if ($cur_page > 1) {
      $page_links .= '<a href="' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=' . $sort . '&page=' . ($cur_page - 1) . '"><-</a> ';
    }
    else {
      $page_links .= '<- ';
    }

    // Loop through the pages generating the page number links
    for ($i = 1; $i <= $num_pages; $i++) {
      if ($cur_page == $i) {
        $page_links .= ' ' . $i;
      }
      else {
        $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=' . $sort . '&page=' . $i . '"> ' . $i . '</a>';
      }
    }

    // If this page is not the last page, generate the "next" link
    if ($cur_page < $num_pages) {
      $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=' . $sort . '&page=' . ($cur_page + 1) . '">-></a>';
    }
    else {
      $page_links .= ' ->';
    }

    return $page_links;
  }


  // Calculate pagination information
  $cur_page = isset($_GET['page']) ? $_GET['page'] : 1;
  $results_per_page = 15;  // number of results per page
  $skip = (($cur_page - 1) * $results_per_page);

  // Start generating the table of results
  echo '<table border="0" cellpadding="2" width="35%">';

  // Generate the search result headings
  echo '<tr class="heading">';
  echo generate_sort_links($user_search, $sort);
  echo '</tr>';




  // Connect to the database
  require_once('basecamp/connectvars2.php');
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);


  // Query to get the total results 
  $query = build_query($user_search, $sort);
  $result = mysqli_query($dbc, $query);
  $total = mysqli_num_rows($result);
  $num_pages = ceil($total / $results_per_page);

  // Query again to get just the subset of results
  $query =  $query . " LIMIT $skip, $results_per_page";
  $result = mysqli_query($dbc, $query);
  while ($row = mysqli_fetch_array($result)) {
    echo '<tr class="results">';
    
    if (isset($_SESSION['user_id'])) {
        echo '<td valign="top" width="7.5%" style="color: orange;"><a href="viewprofile.php?user_id=' . $row['user_id'] . '">' . $row['username'] . '</a></td>';
        echo '<td valign="top" width="15%">' . substr($row['last_name'], 0, 100) . '</td>';
        echo '<td valign="top" width="15%">' . $row['city'] . '</td>';
        echo '<td valign="top" width="50px" style="color: gray;">' . substr($row['join_date'], 0, 10) . '</td>';
    }
    else {
        echo '<td valign="top" width="7.5%" style="color: orange;">' . $row['username'] . '</td>';
        echo '<td valign="top" width="15%">' . substr($row['last_name'], 0, 100) . '</td>';
        echo '<td valign="top" width="15%">' . $row['city'] . '</td>';
        echo '<td valign="top" width="50px" style="color: gray;">' . substr($row['join_date'], 0, 10) . '</td>';
    }

    if (isset($teaparty)) {
      echo '<td ><input type="button" style="cursor:pointer; background-color: orange; color: white; " onclick="myInvited('.$row['user_id'].','.$teaparty.');" value="invite"></td>';
    }
    else {
      echo '';
    }
    echo '</tr>';
  } 
  echo '</table>';

  // Generate navigational page links if we have more than one page
  if ($num_pages > 1) {
    echo generate_page_links($user_search, $sort, $cur_page, $num_pages);
  }

  mysqli_close($dbc);
?>
</main>
<form action='viewprofile.php' id='myFormView' method='post'>
  <input type='number' id='idview' name='idnumb'  hidden>
</form>
<form action='teamprofile.php' id='myFormInvited' method='post'>
  <input type='number' id='idinvited' name='idinvited'  hidden>
  <input type='number' id='idviewgo' name='idviewgo' hidden>
</form>

</body>
</html>
<script type="text/javascript">

  //left menu open or close
function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}
function myView(littlejohn) {
    document.getElementById("idview").value = littlejohn;
    document.getElementById("myFormView").submit();
}
function myInvited(littlejohn, teaparty) {
    document.getElementById("idinvited").value = littlejohn;
    document.getElementById("idviewgo").value = teaparty;
    document.getElementById("myFormInvited").submit();
}
</script>
<style type="text/css">
main {
      padding-left: 60px;
      padding-right: 60px;
}ul
tr.heading {

  background-color: lightgray;
}
tr.results:hover {
    background-color: ghostwhite;
}

a {
  color: orange; text-decoration:none; 
}
a:visited { 
  text-decoration:none; 
}
#usernaam {
  color: gray;
}
main {
  padding-left: 10px;
}

.center {
  text-align: center;
}
</style>
