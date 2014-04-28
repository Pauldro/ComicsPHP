<?php require_once('../model/comic_db.php');

$id = $_POST['id'];

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


//Get all from tables
$artists = getAll_artists();
$writers = getAll_writers();
$months = getAll_months();



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




?>

<?php include '../view/header.php'; ?>
<div id="main">
    <h1>Edit Comic: <?php echo ($new52 == 'Y' ? 'New 52: ' : '') . $title.' #'.$num ; ?></h1>
    <form action="index.php" method="post" class="blue half" id="aligned">
        <input type="hidden" name="action" value="update_comic" />
        <input type="hidden" name="id" value="<?php echo $id; ?>" />
        
        <label>Title:</label>
        <div class="fancyInput smallerFI">
        	<input type="input" name="title" value="<?php echo $title; ?>" />
        </div><br />

        <label>Number: </label>
        <div class="fancyInput smallerFI smallIn">
        	<input type="input" name="num" value="<?php echo $num; ?>"  />
        </div><br />
        
        <label>Publisher: </label>
        <div class="fancyInput smallerFI">
        	<input type="input" name="publisher" value="<?php echo $publisher; ?>" /> 
        </div><br />
        
        <label>Writer: </label>
        <select name="writer">
            <option selected value="<?php echo $writerN; ?>">
                <?php echo $writerName; ?>
            </option>
            <?php foreach ($writers as $writer) : ?>
                <option value="<?php echo $writer['writerNum']; ?>">
                    <?php echo $writer['name']; ?>
                </option>
            <?php endforeach; ?>
        </select><br />
        
        <label>Artist: </label>
        <select name="artist">
            <option selected value="<?php echo $artistN; ?>">
                <?php echo $artistName; ?>
            </option>
            <?php foreach ($artists as $artist) : ?>
                <option value="<?php echo $artist['artistNum']; ?>">
                    <?php echo $artist['name']; ?>
                </option>
            <?php endforeach; ?>
        </select><br />
             
        <label>Month: </label>
        <select name="month">
            <option selected value="<?php echo $month; ?>">
                <?php echo $monthName; ?>
            </option>
            <?php foreach ($months as $monthly) : ?>
                <option value="<?php echo $monthly['monthNum']; ?>">
                    <?php echo $monthly['monthFull']; ?>
                </option>
            <?php endforeach; ?>
        </select><br />
        
        <label>Year: </label>
        <div class="fancyInput smallerFI smallIn">
        	<input type="input" name="year" value="<?php echo $year; ?>" /> 
        </div><br />
        
        <label>New52? </label>
        <div class="fancyInput smallerFI smallIn">
        	<input type="input" name="new52" value="<?php echo $new52; ?>" /> 
        </div><br />
        
        <label>Annual?</label>
        <div class="fancyInput smallerFI smallIn">
        	<input type="input" name="annual" value="<?php echo $annual; ?>" /> 
        </div><br /><br />

        <label>&nbsp;</label>
        <input type="submit" class="pop" id="update" value="Update Comic" />
        <br />  <br />
    </form>
    <p><a href="index.php?action=list_comics">View Comic List</a></p>

</div>
<?php include '../view/footer.php'; ?>