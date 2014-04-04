<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 'On');
	require 'vars.php';
	require 'steamauth/steamauth.php';

	$user_Url = "https://api.steampowered.com/IDOTA2Match_570/GetMatchHistory/V001/?key=".$API_KEY;
function lookupUser($u_id, $start_match){
	global $user_Url;
	$url = "";
	$page = 0;
	if($start_match == 0){
		$url = $user_Url . "&account_id=" . $u_id;                 //76561197990498989";   
	}else{
		$url = $user_Url . "&account_id=" . $u_id . "&start_at_match_id=" . $start_match;
	}

	$file_path = "Players/" . $u_id."(" . $page . ").json";
//	$match_data = "";
//	if(file_exists($file_path)){
	if(false){
		$fp = fopen($file_path, "r");
		$results = fread($fp, filesize($file_path));
		$match_data = json_decode($results);
//		print_search_results($match_data);
		//printMatch($match_data);
		fclose($fp);
	}
	else{
		// Get game data from valve
		$ch = curl_init();
		//$url = $match_id_Url . $match_id . $api_key_url;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		// Do work with results
		$results = curl_exec($ch);
		$match_data = json_decode($results);
		//printMatch($match_data)
		curl_close($ch);
		print_search_results($match_data);
		// Cache game data
		$fp = fopen($file_path, "w");
		fwrite($fp, $results);
		fclose($fp);
	}
	return $match_data;
}  
function clearPlayerCache(){
	$files = glob('Players/*'); // get all file names
	foreach($files as $file){ // iterate files
		if(is_file($file))
    		unlink($file); // delete file
	}
}

function gatherMatches($steamprofile){
	global $user_Url;
	$page = lookupUser($steamprofile['steamid'], 0);
	$matches = array();
	echo "Steam Id: " . $steamprofile['steamid'] . "<br>";
	$test = 0;
	$num_matches = 0;
	while($page->result->results_remaining > 1){
		$i = 0;
		while($i < $page->result->num_results){
			array_push($matches, $page->result->matches[$i]->match_id);
			$i++;
			$num_matches++;
		}
		$page = lookupUser($steamprofile['steamid'], $matches[$num_matches-1]);
		$test++;
		echo "Matches Remaining: " . $page->result->results_remaining . "<br>";
		sleep(1);
	}
	echo "Matches found: " . $num_matches . "<br>";
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

    	//Protected content
    	//echo "Yo MoFuckin Steam ID is: " . $steamprofile['steamid'] . "<br>";
    	echo '<img src="'.$steamprofile['avatarmedium'].'" title="" alt="" /><br>';
    	echo "Whatsup " . $steamprofile['personaname'] . "?!</br>";
    	// Checkout some stats
    	// Setup user index
		//lookupUser($steamprofile['steamid']);
    	gatherMatches($steamprofile);
    //	clearPlayerCache();
//    	logoutbutton();
	}   
?>