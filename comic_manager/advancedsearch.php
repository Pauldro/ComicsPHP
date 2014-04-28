<?php 
require_once('../model/database.php');
require_once('../model/comic_db.php');

$months = getAll_months();
?>

<?php include '../view/header.php'; ?>
<div id="main">
    <h1>Search Comics</h1>
    <form action="comic_searched.php" method="post" class="blue form">
        <input type="hidden" name="searchType" value="title" />
        <h2> By title with or without number </h2><br />
        <label>Title</label>
        <div class="fancyInput">
            <input type="input" name="title" placeholder="title" />
        </div>
        
        <label class="space">Number</label>
        <div class="fancyInput">
            <input type="number" name="number" placeholder="Number" /><br />
        </div><br /><br />
        
        <input type="radio" name="numC" value="="><label>Equal to Number</label><br />
        <input type="radio" name="numC" value=">="><label>Greater Than or Equal to Number</label><br />
        <input type="radio" name="numC" value="<="><label>Less than or Equal to</label><br /><br />
        <input type="submit" class="pulse" value="Search" /><br />
    </form>
    <br />
    <form action="comic_searched.php" method="post" class="blue form">
        <input type="hidden" name="searchType" value="name" />
        <h2>By Artist or Writer Name</h2><br />
        
        <label>Writer or Artist Name</label>
        
        <div class="fancyInput">
            <input type="input" name="name" placeholder="Name" />
        </div>
        <input type="submit" class="pulse" value="Search" /><br /><br />
    </form>
    <br />
    <form action="comic_searched.php" method="post" class="blue form">
        <input type="hidden" name="searchType" value="date" />
        
        <h2>By Date</h2><br />
        <input type="radio" name="date" value="="><label>ON</label><br />
        <input type="radio" name="date" value=">="><label>After</label><br />
        <input type="radio" name="date" value="<="><label>Before</label><br />
        
        <label>Month: </label>
        <select name="month">
            <?php foreach ($months as $month) : ?>
                <option value="<?php echo $month['monthNum']; ?>">
                    <?php echo $month['monthFull']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <label>Year: </label>
        <div class="fancyInput">
            <input type="number" name="year" size="5" min="1938" max="<?php echo date("Y"); ?>" /> 
        </div><br />
        <input type="submit" class="pulse" value="Search" /><br /><br />
    </form>
    <p><a href="index.php?action=list_comics">View Comic List</a></p>
    
</div>
<?php include '../view/footer.php'; ?>