<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 'On');
	require 'vars.php';
	require 'steamauth/steamauth.php';
	require 'player.php';

	$user_Url = "https://api.steampowered.com/IDOTA2Match_570/GetMatchHistory/V001/?key=".$API_KEY;

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

function print_search_results($player_data){
//	var_dump($player_data);
	//echo $player_data->result . "<br>";
	$i = 0;
	echo "Heres some match id's....Do shit.";
	echo $player_data->result->num_results . ' of ' . $player_data->result->total_results . "<br>";
	while($i<$player_data->result->num_results){
		echo $player_data->result->matches[$i]->match_id . "<br>";
		$i++;
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
    	$p->printMatches();
    	//Protected content
    	//echo "Yo MoFuckin Steam ID is: " . $steamprofile['steamid'] . "<br>";
    	//echo '<img src="'.$steamprofile['avatarmedium'].'" title="" alt="" /><br>';
    	//echo "Whatsup " . $steamprofile['personaname'] . "?!</br>";
    	// Checkout some stats
    	// Setup user index
		//lookupUser($steamprofile['steamid']);
	}   
?>