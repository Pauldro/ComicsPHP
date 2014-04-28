<?php
require('../model/database.php');
require('../model/comic_db.php');


if (filter_input(INPUT_POST, "action")) {
    $action = filter_input(INPUT_POST, "action");
} else if (filter_input(INPUT_GET, "action")) {
    $action = filter_input(INPUT_GET, "action");
} else {
    $action = 'list_comics';
}

switch ($action) {
    case 'edit_comic':
        include('edit_comic.php');
        break;
    case 'list_comics':
        include('comic_list.php');
        break;
    case 'comic_add_form': 
        include ('comic_add.php');
        break;
    case 'view_comic_info':
        include ('view_comic_info.php');
        break;
    case 'add_comic':
        $comicID = filter_input(INPUT_POST, "id");
        $title = filter_input(INPUT_POST, "title");
        $num = filter_input(INPUT_POST, "num");
        $publisher = filter_input(INPUT_POST, "publisher");
        $writer = filter_input(INPUT_POST, "writer");
        $artist = filter_input(INPUT_POST, "artist");
        $month = filter_input(INPUT_POST, "month");
        $year = filter_input(INPUT_POST, "year");
        
		$new = isset($_POST['new52']) ? 'Y' : 'N';
		$annual = isset($_POST['annual']) ? 'Y' : 'N';
       
        
		// Validate inputs
		if (empty($title) || empty($num) || empty($publisher) ) {
			$error = "Invalid comic data. Check all fields and try again.";
			include('../errors/error.php');
		} else {
			// If valid, add the product to the database
			require_once('../model/database.php');
			$query = "INSERT INTO comics
						 (title, num, publisher, writer, artist, month, year, new52, annual)
					  VALUES
						 ('$title', '$num', '$publisher' , '$writer', '$artist', '$month', '$year', '$new', '$annual')";
			$db->exec($query);
	
			 // Display the Product List page
			header("Location: .?action=list_comics");
		}
		break;
    case 'delete_comic':
        $comicID = filter_input(INPUT_POST,"id");

        // Delete the product
        delete_product($comicID);

        // Display the Product List page for the current category
        header("Location: .?action=list_comics");
        break;
	case 'update_comic':
    	$comicID = filter_input(INPUT_POST, "id");
        $title = filter_input(INPUT_POST, "title");
        $num = filter_input(INPUT_POST, "num");
        $publisher = filter_input(INPUT_POST, "publisher");
        $writer = filter_input(INPUT_POST, "writer");
        $artist = filter_input(INPUT_POST, "artist");
        $month = filter_input(INPUT_POST, "month");
        $year = filter_input(INPUT_POST, "year");
        
		$new = filter_input(INPUT_POST, "new52");
   		$annual = filter_input(INPUT_POST, "annual");
		
		//Validate inputs
		if (empty($title) || empty($num) || empty($publisher) ) {
			$error = "Invalid comic data. Check all fields and try again.";
			include('../errors/error.php');
		} else {
			// If valid, add the product to the database
			require_once('../model/database.php');
			$query = "UPDATE comics
					  SET title = '$title', num = $num, writer = $writer, artist = $artist, publisher = '$publisher', 
					  	month = $month, year = $year, new52 = '$new', annual = '$annual'
					  WHERE id = '$comicID'";
			$db->exec($query);
			
	
			 // Display the Product List page
			header("Location: .?action=view_comic_info&id=$comicID");
			break;
		}
}   

?>