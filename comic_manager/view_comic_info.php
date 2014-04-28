<?php include '../view/header.php'; ?>
<?php require_once('../model/comic_db.php');


if (isset($_POST['id'])) {
	$id = $_POST['id'];
} else if (isset($_GET['id'])) {
	$id = $_GET['id'];
}

$results = get_comic_FromID($id);
$comic = $results->fetch(); 

$title = $comic['Title'];
$num = $comic['Num'];
$publisher = $comic['Publisher'];
$writerN = $comic['Writer'];
$artistN = $comic['Artist'];
$month = $comic['Month'];
$year = $comic['Year'];
$new52 = $comic['New52'];
$annual = $comic['Annual'];



//Get MonthName from monthNum
$resultsM = getMonth_FromMonthNum($month);
$comicMonth = $resultsM->fetch();
$monthName = $comicMonth['monthFull'];

//Get Artist from ArtistNum
$artistResults = getArtist_FromArtistNum($artistN);
$artistR = $artistResults->fetch();
$artistName = $artistR['name'];

//Get Writer from writerNum
$writerResult = getWriter_FromWriterNum($writerN);
$writerNa = $writerResult->fetch();
$writerName = $writerNa['name'];
$comic=array("id"=>$id,"Title"=>$title,"#"=>$num,"Writer"=>$writerName, "Artist"=>$artistName, "Date" =>$monthName.' '.$year, 
    "Publisher"=>$publisher, "New52?"=>$new52, "Annual?"=>$annual);
?>

<div id="main">
<h1>Comic Info for <?php echo $title.' #'.$num ;?></h1>
    <?php include 'searchForm.php'; ?>
    <br />
    <table>
            <tr>
                <th>Title</th>
                <th>#</th>
                <th>Writer</th>
                <th>Artist</th>
                <th>Publisher</th>
                <th>Released</th>
                <th>New52?</th>
                <th>Annual</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
            
            <tr>
                <td><?php echo $comic['Title']; ?></td>
                <td><?php echo preg_replace('/\.0+$/', '',$comic['#']); ?></td>
                <td><?php echo $comic['Writer']; ?></td>
                <td><?php echo $comic['Artist']; ?></td>
                <td><?php echo $comic['Publisher']; ?></td>
                <td><?php echo $comic['Date']; ?></td>
                <td><?php echo $comic['New52?']; ?></td>
                <td class="right"><?php echo $comic['Annual?']; ?></td>
                <td><form action="." method="post">
                    <input type="hidden" name="action"
                           value="delete_comic" />
                    <input type="hidden" name="id"
                           value="<?php echo $id; ?>" />
                    <input type="submit" class="delete" value="Delete" />
                </form></td>
                <td><form action="." method="post">
                    <input type="hidden" name="action"
                           value="edit_comic" />
                    <input type="hidden" name="id"
                           value="<?php echo $id; ?>" />
                    <input type="submit" class="edit" value="Edit" />
                </form></td>
            </tr>        
</table>
<br /> <br />






<div class="button"><a href="?action=comic_add_form"><span>+</span> Add Comic</a></div>
</div><br /><br />




<?php include '../view/footer.php'; ?>