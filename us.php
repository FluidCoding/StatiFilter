<?php
require 'vars.php';
$user_Url = "https://api.steampowered.com/IDOTA2Match_570/GetMatchHistory/V001/?key=".$API_KEY;
function lookupUser($u_id){
global $user_Url;
	$url = $user_Url . "&account_id=" . $u_id;                 //76561197990498989";   
	$file_path = "Players/" . $u_id.".json";
	if(file_exists($file_path)){
		$fp = fopen($file_path, "r");
		$results = fread($fp, filesize($file_path));
		$match_data = json_decode($results);
		print_search_results($match_data);
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

}  

function print_search_results($player_data){
//	var_dump($player_data);
	//echo $player_data->result . "<br>";
	$i = 0;
	echo $player_data->result->num_results . ' of ' . $player_data->result->total_results . "<br>";
	while($i<$player_data->result->num_results){
		echo $player_data->result->matches[$i]->match_id . "<br>";
		$i++;
	}

}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$u_id = $_POST["user"];
	lookupUser($u_id);
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Dota 2 Stats</title>
	<link rel="stylesheet" href="s.css">

</head>
<body>

</body>
</html>