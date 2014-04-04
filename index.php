<?php

	error_reporting(E_ALL);
	ini_set('display_errors', 'On');
	require 'vars.php';
	require 'steamauth/steamauth.php';
	require 'player.php';

/**
	Use for maintenance DELTES ALL FILES in /Players/*
*/
function clearPlayerCache(){
	$files = glob('Players/*');
	foreach($files as $file){
		if(is_file($file))
    		unlink($file);
	}
}

/**
	Deletes ALL files in Matches directory
*/
function clearMatchCache(){
	$files = glob('Matches/*');
	foreach($files as $file){
		if(is_file($file))
    		unlink($file);
	}
}


	// Main Code
	if(!isset($_SESSION['steamid'])) {

    	echo "Log yo shit in son.";
    	steamlogin();
	}  
	else {
    	include ('steamauth/userInfo.php');
    	logoutbutton();
    	$p = new player($steamprofile);
    	$p->gatherMatches();

    	echo '<img src="'.$steamprofile['avatarmedium'].'" title="" alt="" /><br>';
    	echo "<h2>" . $steamprofile['personaname'] . "</h2><br>"; 
		echo "Matches Found: " . $p->get_num_matches() . "<br>";    	

		echo "Steam id: " . $steamprofile['steamid'] . "<br>";
		// Print Last game
		echo "Last game played: <br>";
		$p->lookupMatch($p->get_match(0));
		
		
    	//$p->printMatches();
    	//Protected content
    	//echo "Yo MoFuckin Steam ID is: " . $steamprofile['steamid'] . "<br>";
    	//echo "Whatsup " . $steamprofile['personaname'] . "?!</br>";
    	// Checkout some stats
    	// Setup user index
		//lookupUser($steamprofile['steamid']);
	}   
?>


<!DOCTYPE html>
<html>
<head>
	<title>Dota 2 Stats</title>
	<link rel="stylesheet" href="s.css">
</head>

<body>
<form name="matchinput" id="ui" action="search.php" method="post">
	<input type="text" placeholder="Enter Dota 2 Match ID..." name="match">
	<input type="submit" value="Search">
</form>

<form name="usninput" id="ui" action="us.php" method="post">
	<input type="text" placeholder="Enter Steam Id..." name="user">
	<input type="submit" value="Search">
</form>

</body>
</html>