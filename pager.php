<?php

// This function builds navigational page links based on the current page and the number of pages
  function generate_page_links($urhere, $user_search, $sort, $cur_page, $num_pages) {
    $page_links = '';


    // If this page is not the first page, generate the "previous" link
    if ($cur_page > 1) {
      $page_links .= '<a  class="pagelinks orange" href="' . $urhere . '?usersearch=' . $user_search . '&sort=' . $sort . '&page=' . ($cur_page - 1) . '"><-Previous</a> ';
    }
    else {
      $page_links .= '<span class="pagelinks"><- ';
    }

    // Loop through the pages generating the page number links
    for ($i = 1; $i <= $num_pages; $i++) {
      if ($cur_page == $i) {
        $page_links .= ' ' . $i;
      }
      else {
        $page_links .= ' <a  class="pagelinks orange" href="' . $urhere . '?usersearch=' . $user_search . '&sort=' . $sort . '&page=' . $i . '"> ' . $i . '</a>';
      }
    }

    // If this page is not the last page, generate the "next" link
    if ($cur_page < $num_pages) {
      $page_links .= ' <a class="pagelinks orange" href="' . $urhere . '?usersearch=' . $user_search . '&sort=' . $sort . '&page=' . ($cur_page + 1) . '">Next-></a></span>';
    }
    else {
      $page_links .= ' -></span>';
    }

    echo $page_links;
  }

  ?>
<style>
.pagelinks {  
  color: darkgray;
  height: 15px;
}

.orange:hover {  
  color: orange;
}
</style>