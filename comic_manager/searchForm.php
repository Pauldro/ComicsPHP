    <form action="comic_searched.php" class="blue half" id="searchbar" method="post">
        <label>Search</label>
        <div class="fancyInput">
            <input type="hidden" name="searchType" value="title" />
            <input type="input" name="title" placeholder="title" />
        </div>
        <input type="submit" class="pulse" value="Search" />
    </form>
    <form action="advancedsearch.php" class="blue quarter" id="advanced" method="post">
        <input type="submit" class="pulse" value="Advanced Search" />
    </form>