<?php

	error_reporting(E_ALL);
	ini_set('display_errors', 'On');
	require 'vars.php';
	require 'steamauth/steamauth.php';
	require 'player.php';
	$ADMIN_ID = 76561198048818422;
	//codinghs_dota_stat
	//codinghs_statman
	//!{([!stw*?0N
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
function printHeader(){
	echo <<<HEAD
	<!DOCTYPE html>
	<html>
	<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="s.css">
	</head>
	<body>
	<div class="wrapper">
HEAD;
}
function printLogin(){
	echo <<<LOGIN
	<div class="signin">
     		<form action="?login" method="post"> 
			<input type="image" src="http://cdn.steamcommunity.com/public/images/signinthroughsteam/sits_small.png"	      class="loginBtn">
     		</form>
    </div>
LOGIN;
}
function printFooter(){
	echo <<<FOOT
		</div>	
	</body>
	</html>
FOOT;
}

function printLogout(){
	echo <<<LOGOUT
	<form action="steamauth/logout.php" method="post">
		<input class="logout" value="Logout" type="submit" />
	</form>
LOGOUT;
}

	// Main Code
	if(!isset($_SESSION['steamid'])) {
		// Head of HTML Doc
		printHeader();
		// Setup the openid
    	steamlogin();
    	// Print login form
		printLogin();
		// End the html cleanly
		printFooter();
	}  
	else {
	   	include ('steamauth/userInfo.php');
	   	printHeader();

	   	printLogout();

	


    	// Hook that player object up to steamid
    	$p = new player($steamprofile);
   		// Mysql hook
		   	//codinghs_dota_stat
			//codinghs_statman
			//!{([!stw*?0N
    	mysql_connect("codinghs.com","codinghs_statman","!{([!stw*?0N","codinghs_dota_stat");
	   	// Check for user in db
    	$query = mysql_query("SELECT * FROM players WHERE steam_id='"$steamprofile['steam_id']'');
    	echo "<h2>";
    	echo $query; 	
    	/*
    		while($row=mysql_fetch_array($query,MYSQL_NUM)){
    			echo $row['steam_id'];
    			echo "<br>";
    		}
    	
    	
    	echo "</h2>";
    	*/
		// Update Matches
    	$p->gatherMatches();//    	echo '<img src="'.$steamprofile['avatarmedium'].'" title="" alt="" /><br>';

    	//$p->saveMatches($con);

 //   	echo "<h2>" . $steamprofile['personaname'] . "</h2><br>";
//		echo "Matches Found: " . $p->get_num_matches() . "<br>";

//		echo "Steam id: " . $steamprofile['steamid'] . "<br>";
		// Print Last game
		echo "Last game played: <br>";
		
		$p->lookupMatch($p->get_match(0));


		printFooter();

    	//$p->printMatches();
    	//Protected content
    	//echo "Yo MoFuckin Steam ID is: " . $steamprofile['steamid'] . "<br>";
    	//echo "Whatsup " . $steamprofile['personaname'] . "?!</br>";
    	// Checkout some stats
    	// Setup user index
		//lookupUser($steamprofile['steamid']);
	}   
?>

<form name="matchinput" id="ui" action="search.php" method="post">
	<input type="text" placeholder="Enter Dota 2 Match ID..." name="match">
	<input type="submit" value="Search">
</form>

<form name="usninput" id="ui" action="us.php" method="post">
	<input type="text" placeholder="Enter Steam Id..." name="user">
	<input type="submit" value="Search">
</form>
