<?php include '../view/header.php'; ?>
<?php
require_once('../model/comic_db.php');
require_once('../model/database.php');

if (filter_input(INPUT_POST, "searchType")) {
    $searchType = filter_input(INPUT_POST, "searchType");
} else if (filter_input(INPUT_GET, "searchType")) {
    $searchType = filter_input(INPUT_GET, "searchType");
}

switch ($searchType) {
    case "title":
        $justByTitle = true;
        if (filter_input(INPUT_POST, "title")) {
            $title = filter_input(INPUT_POST, "title");
        } elseif (filter_input(INPUT_GET, "title")) {
            $title = filter_input(INPUT_GET, "title");
        }
        
        if (filter_input(INPUT_POST, "numC")) {
            $comparasionN = filter_input(INPUT_POST, "numC");
        } elseif (filter_input(INPUT_GET, "numC")) {
            $comparasionN = filter_input(INPUT_GET, "numC");
        } else {
            $comparasionN = "=";
        }
        
        if (filter_input(INPUT_POST, "number")) {
            $number = (double) filter_input(INPUT_POST, "number");
            $justByTitle = false; //marker for later use
            if ($number == '') {
                $justByTitle = true;
            }
        } elseif (filter_input(INPUT_GET, "num")) {
            $number = (double) filter_input(INPUT_GET, "num");
            $justByTitle = false;
        } 
        break;
    case "date":
        
        if (filter_input(INPUT_POST, "date")) {
            $comparasionD = filter_input(INPUT_POST, "date");
        } elseif (filter_input(INPUT_GET, "date")) {
            $comparasionD = filter_input(INPUT_GET, "date");
        } else {
            $comparasionD = '=';
        }
        
        if (filter_input(INPUT_POST, "month")) {
            $month = (int) filter_input(INPUT_POST, "month");
        } elseif (filter_input(INPUT_GET, "month")) {
            $month = (int) filter_input(INPUT_GET, "month");
        }

        if (filter_input(INPUT_POST, "year")) {
            $year = (int) filter_input(INPUT_POST, "year");
        } elseif (filter_input(INPUT_GET, "year")) {
            $year = (int) filter_input(INPUT_GET, "month");
        }
        break;
    case "name":
        if (filter_input(INPUT_POST, "name")) {
            $name = filter_input(INPUT_POST, "name");
        } else if (filter_input(INPUT_GET, "name")) {
            $name = filter_input(INPUT_GET, "name");
        }
        break;
}


// find out how many rows are in the table 
switch ($searchType) {
    case "title":
        $keyword = $title;
        $title = get_keyword($keyword);
        if ($justByTitle == true) {
            $results = get_comicsNumFromTitle($title);
        } else {
            $results = get_comicsNumFromTitleAndNum($title, $comparasionN, $number);
        }
        break;
    case "date":
        $results = get_comicsNumFromDate($comparasionD, $month, $year);
        break;
    case "name":
        $results = get_comicsNumFromName($name);
        break;
    default:
        $results = get_comicsNum();
}

$numrows = $results->fetchColumn();

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
$sort = 'stuff';
switch ($searchType) {
    case "title":
        if ($justByTitle == true) {
            $comics = get_comicsFromTitle($offset, $rowsperpage, $title);
            $h1 = $numrows.' comics with <b>'.$title.'</b> in the <b>Title</b>.';
        } else {
            $comics = get_comicsFromTitleAndNum($offset, $rowsperpage, $title, $number, $comparasionN);
            switch ($comparasionN) {
            case ">=":
                $h = "more than";
                break;
            case "=": 
                $h = "equals";
                break;
            case "<=":
                $h = "less than";
                break;
        }
            $h1 = $numrows.' comics with <b>'.$title.'</b> in the <b>title</b> & comic <b>number</b> '.$h.' '.$number;
        }
        break;
    case "date":
        $comics = get_comicsFromDate($offset, $rowsperpage, $comparasionD, $month, $year);
        //Get MonthName from monthNum
            $resultsM = getMonth_FromMonthNum($month);
            $comicMonth = $resultsM->fetch();
            $monthName = $comicMonth['monthFull'];
        switch ($comparasionD) {
            case ">=":
                $q = "after";
                break;
            case "=": 
                $q = "in";
                break;
            case "<=":
                $q = "before";
                break;
        }
        $h1 = $numrows.' comics that released <b class="bolden">'.$q.'</b> "'.$monthName.' '.$year.'"';
        break;
    case "name":
        $comics = get_comicsFromName($offset, $rowsperpage, $name);
        $h1 = $numrows.' comics that have '.$name.' as a creator';
        break;
}



?>

<div id="main">
    <h1>Comic List: <?php echo $h1; ?></h1>
    <?php include 'searchForm.php'; ?>
    <br />
    <?php include '../view/table.php'; ?>

    <br /><br />






    <div id="middle">
<?php
/* * ****  build the pagination links ***** */
// range of num links to show
$range = 3;

// if not on page 1, don't show back links
if ($currentpage > 1) {
    // show << link to go back to page 1
    switch ($searchType) {
        case "title":
            $title = get_titleForLink($title);
            if ($justByTitle == true) {
                echo " <a href='{$_SERVER['PHP_SELF']}?searchType=$searchType&title=$title&currentpage=1'><<</a> ";
            } else {
                echo " <a href='{$_SERVER['PHP_SELF']}?searchType=$searchType&title=$title&num=$number&numC=$comparasionN&currentpage=1'><<</a> ";
            }
            break;
        case "date":
            echo " <a href='{$_SERVER['PHP_SELF']}?searchType=$searchType&date=$comparasionD&month=$month&year=$year&currentpage=1'><<</a> ";
            break;
        case "name":
            echo " <a href='{$_SERVER['PHP_SELF']}?searchType=$searchType&name=$name&currentpage=1'><<</a> ";
            break;
    }

    // get previous page num
    $prevpage = $currentpage - 1;
    // show < link to go back to 1 page
    switch ($searchType) {
        case "title":
            $title = get_titleForLink($title);
            if ($justByTitle == true) {
                echo " <a href='{$_SERVER['PHP_SELF']}?searchType=$searchType&title=$title&currentpage=$prevpage'><</a> ";
            } else {
                echo " <a href='{$_SERVER['PHP_SELF']}?searchType=$searchType&title=$title&num=$number&numC=$comparasionN&currentpage=$prevpage'><</a> ";
            }
            break;
        case "date":
            echo " <a href='{$_SERVER['PHP_SELF']}?searchType=$searchType&&date=$comparasionD&month=$month&year=$year&currentpage=$prevpage'><</a> ";
            break;
        case "name":
            echo " <a href='{$_SERVER['PHP_SELF']}?searchType=$searchType&name=$name&currentpage=$prevpage'><</a> ";
            break;
    }
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

            switch ($searchType) {
                case "title":
                    $title = get_titleForLink($title);
                    if ($justByTitle == true) {
                        echo " <a href='{$_SERVER['PHP_SELF']}?searchType=$searchType&title=$title&currentpage=$x'>$x</a> ";
                    } else {
                        echo " <a href='{$_SERVER['PHP_SELF']}?searchType=$searchType&title=$title&num=$number&numC=$comparasionN&currentpage=$x'>$x</a> ";
                    }
                    break;
                case "date":
                    echo " <a href='{$_SERVER['PHP_SELF']}?searchType=$searchType&date=$comparasionD&month=$month&year=$year&currentpage=$x'>$x</a> ";
                    break;
                case "name":
                    echo " <a href='{$_SERVER['PHP_SELF']}?searchType=$searchType&name=$name&currentpage=$x'>$x</a> ";
                    break;
            }
        } // end else
    } // end if 
} // end for
// if not on last page, show forward and last page links        
if ($currentpage != $totalpages) {
    // get next page
    $nextpage = $currentpage + 1;
    // echo forward link for next page 
    switch ($searchType) {
        case "title":
            $title = get_titleForLink($title);
            if ($justByTitle == true) {
                echo " <a href='{$_SERVER['PHP_SELF']}?searchType=$searchType&title=$title&currentpage=$nextpage'>></a> ";
            } else {
                echo " <a href='{$_SERVER['PHP_SELF']}?searchType=$searchType&title=$title&num=$number&numC=$comparasionN&currentpage=$nextpage'>></a> ";
            }
            break;
        case "date":
            echo " <a href='{$_SERVER['PHP_SELF']}?searchType=$searchType&date=$comparasionD&month=$month&year=$year&currentpage=$nextpage'>></a> ";
            break;
        case "name":
            echo " <a href='{$_SERVER['PHP_SELF']}?searchType=$searchType&name=$name&currentpage=$nextpage'>></a> ";
            break;
    }

    // echo forward link for lastpage
    switch ($searchType) {
        case "title":
            $title = get_titleForLink($title);
            if ($justByTitle == true) {
                echo " <a href='{$_SERVER['PHP_SELF']}?searchType=$searchType&title=$title&currentpage=$totalpages'>>></a> ";
            } else {
                echo " <a href='{$_SERVER['PHP_SELF']}?searchType=$searchType&title=$title&num=$number&numC=$comparasionN&currentpage=$totalpages'>>></a> ";
            }
            break;
        case "date":
            echo " <a href='{$_SERVER['PHP_SELF']}?searchType=$searchType&date=$comparasionD&month=$month&year=$year&currentpage=$totalpages'>>></a> ";
            break;
        case "name":
            echo " <a href='{$_SERVER['PHP_SELF']}?searchType=$searchType&name=$name&currentpage=$totalpages'>>></a> ";
            break;
    }
} // end if
/* * **** end build pagination links ***** */
?>
    </div><br /><br />


    <div class="button"><a href="index.php?action=comic_add_form"><span>+</span> Add Comic</a></div>

</div>

        <?php include '../view/footer.php'; ?>

