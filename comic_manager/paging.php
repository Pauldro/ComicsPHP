<?php include '../view/header.php'; ?>
<?php
// database connection info
$conn = mysql_connect('localhost:3308','root','') or trigger_error("SQL", E_USER_ERROR);
$db = mysql_select_db('comics',$conn) or trigger_error("SQL", E_USER_ERROR);

// find out how many rows are in the table 
$sql = "SELECT COUNT(*) FROM comics";
$result = mysql_query($sql, $conn) or trigger_error("SQL", E_USER_ERROR);
$r = mysql_fetch_row($result);
$numrows = $r[0];

// number of rows to show per page
$rowsperpage = 15;
// find out total pages
$totalpages = ceil($numrows / $rowsperpage);

// get the current page or set a default
if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
   // cast var as int
   $currentpage = (int) $_GET['currentpage'];
} else {
   // default page num
   $currentpage = 1;
} // end if

// if current page is greater than total pages...
if ($currentpage > $totalpages) {
   // set current page to last page
   $currentpage = $totalpages;
} // end if
// if current page is less than first page...
if ($currentpage < 1) {
   // set current page to first page
   $currentpage = 1;
} // end if

// the offset of the list, based on current page 
$offset = ($currentpage - 1) * $rowsperpage;

// get the info from the db 
$sql = "SELECT id, title AS Title, num AS '#', CONCAT( writers.firstName, ' ', writers.lastName ) AS Writer, CONCAT( artists.firstName, ' ', artists.lastName ) AS Artist, publisher AS Publisher, CONCAT( monthShort, ' ', year ) AS Date
              FROM comics, artists, writers, MONTH
              WHERE comics.writer = writers.writerNum
              AND comics.artist = artists.artistnum
              AND comics.month = month.monthNum
              ORDER BY title, num "
        . "LIMIT $offset, $rowsperpage";
$result = mysql_query($sql, $conn) or trigger_error("SQL", E_USER_ERROR);

?>

<div id="main">
<h1>Comic List</h1>
    <form action="comic_search.php" method="post">
        <label>Search</label>
        <input type="input" name="search" placeholder="title" />
        <input type="submit" value="Search" /><br /><br />
    </form>
        <form action="advanced_search.php" method="post">
            <input type="submit" value="Advanced Search" /><br /><br />
        </form>
    <table>
            <tr>
                <th>Title</th>
                <th>#</th>
                <th>Writer</th>
                <th>Artist</th>
                <th>Publisher</th>
                <th class="right">Released</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>

            </tr>
            <?php while ($list = mysql_fetch_assoc($result)) : ?>
            <tr>
                <td><?php echo $list['Title']; ?></td>
                <td><?php echo $list['#']; ?></td>
                <td><?php echo $list['Writer']; ?></td>
                <td><?php echo $list['Artist']; ?></td>
                <td><?php echo $list['Publisher']; ?></td>
                <td class="right"><?php echo $list['Date']; ?></td>
                <td><form action="." method="post">
                    <input type="hidden" name="action"
                           value="delete_comic" />
                    <input type="hidden" name="id"
                           value="<?php echo $list['id']; ?>" />
                    <input type="submit" value="Delete" />
                </form></td>
                <td><form action="." method="post">
                    <input type="hidden" name="action"
                           value="edit_comic" />
                    <input type="hidden" name="id"
                           value="<?php echo $list['id']; ?>" />
                    <input type="submit" value="Edit" />
                </form></td>
            </tr>
            <?php endwhile; ?>
</table>
<br />






<div id="middle">
<?php
/******  build the pagination links ******/
// range of num links to show
$range = 3;

// if not on page 1, don't show back links
if ($currentpage > 1) {
   // show << link to go back to page 1
   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=1'><<</a> ";
   // get previous page num
   $prevpage = $currentpage - 1;
   // show < link to go back to 1 page
   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$prevpage'><</a> ";
} // end if 

// loop to show links to range of pages around current page
for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
   // if it's a valid page number...
   if (($x > 0) && ($x <= $totalpages)) {
      // if we're on current page...
      if ($x == $currentpage) {
         // 'highlight' it but don't make a link
         echo " [<b>$x</b>] ";
      // if not current page...
      } else {
         // make it a link
         echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$x'>$x</a> ";
      } // end else
   } // end if 
} // end for
                 
// if not on last page, show forward and last page links        
if ($currentpage != $totalpages) {
   // get next page
   $nextpage = $currentpage + 1;
    // echo forward link for next page 
   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage'>></a> ";
   // echo forward link for lastpage
   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$totalpages'>>></a> ";
} // end if
/****** end build pagination links ******/
?>
</div><br /><br />


<div class="button"><a href="?action=comic_add_form"><span>+</span> Add Comic</a></div>

</div>

<?php include '../view/footer.php'; ?>

