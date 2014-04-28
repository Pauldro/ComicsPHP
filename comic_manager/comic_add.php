<?php require_once('../model/comic_db.php');

//get All from each table
$artists = getAll_artists();
$writers = getAll_writers();
$months = getAll_months();

?>

<?php include '../view/header.php'; ?>
<div id="main">
    <h1>Add Comic</h1>
    <form action="." method="post" class="blue">
        <input type="hidden" name="action" value="add_comic" />
        
        <fieldset>
        	<legend>Basic Comic Info</legend>
			<label>Title:</label>
            <div class="fancyInput">
                <input type="input" name="title" placeholder="Comic Title" />
            </div>
            <label>Number: </label>
            <div class="fancyInput">
                <input type="number" name="num" id="small" placeholder=" Comic #" />
            </div>
        </fieldset><br /><br/>
        <fieldset>
        	<legend>Creator Details</legend>
            <label>Publisher: </label>
            <div class="fancyInput">
                <input type="input" name="publisher" placeholder="Publisher" /> 
            </div><br /><br />
        
            <label>Writer: </label>
            <select name="writer">
                <option selected>Writer Name</option>
                <?php foreach ($writers as $writer) : ?>
                    <option value="<?php echo $writer['writerNum']; ?>">
                        <?php echo $writer['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>&nbsp; &nbsp; &nbsp;
        
            <label>Artist: </label>
            <select name="artist">
                <option selected>Artist Name</option>
                <?php foreach ($artists as $artist) : ?>
                    <option value="<?php echo $artist['artistNum']; ?>">
                        <?php echo $artist['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </fieldset><br /><br />
        
        <fieldset>
        	<legend>Released</legend>
            <label>Month: </label>
            <select name="month">
                <?php foreach ($months as $month) : ?>
                    <option value="<?php echo $month['monthNum']; ?>">
                        <?php echo $month['monthFull']; ?>
                    </option>
                <?php endforeach; ?>
            </select> &nbsp; &nbsp; &nbsp;
            
            <label>Year: </label>
            <div class="fancyInput">
            	<input type="number" name="year" size="5" min="1938" max="<?php echo date("Y"); ?>" /> 
            </div>
        </fieldset><br /><br />
        
        <fieldset>
        	<legend>Comic Metadata</legend>
            <label>New52? </label>
            <input type="checkbox" class="effeckt-ckbox-ios7" id="new52ck" name="new52" /> 
            <div id="new52"></div>
            &nbsp; &nbsp; &nbsp;
            
            <label>Annual?</label>
            <input type="checkbox" class="effeckt-ckbox-ios7" id="annck" name="annual" /> 
            <div id="annual"></div>
        </fieldset><br /><br />

        <label>&nbsp;</label>
        <input type="submit" id="add" value="+ Add Comic" />
        <br />  <br />
    </form>
    <p><a href="index.php?action=list_comics">View Comic List</a></p>

</div>
<?php include '../view/footer.php'; ?>