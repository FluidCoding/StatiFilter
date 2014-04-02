<?php
require 'vars.php';
$match_id_Url = "http://api.steampowered.com/IDOTA2Match_570/GetMatchDetails/V001/?match_id=";
/**
	Lookup match data from the match id
*/
function lookupMatch($match_id){
	global $match_id_Url, $api_key_url;
	$file_path = "Matches/".$match_id.".json";
	$results = "";
	$match_data = "";
	
	// Check if game data is cached already
	if(file_exists($file_path)){
		$fp = fopen($file_path, "r");
		$results = fread($fp, filesize($file_path));
		$match_data = json_decode($results);
		printMatch($match_data);
		fclose($fp);
	}
	else{
		// Get game data from valve
		$ch = curl_init();
		$url = $match_id_Url . $match_id . $api_key_url;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		// Do work with results
		$results = curl_exec($ch);
		$match_data = json_decode($results);
		printMatch($match_data);
		curl_close($ch);
		// Cache game data
		saveMatch($results, $match_id);
	}
}

/**
	Save Match to file
*/
function saveMatch($mData, $m_id){
	if(!file_exists("Matches/".$m_id.".json")){
		$fp = fopen("Matches/".$m_id.".json", "w");
		fwrite($fp, $mData);
		fclose($fp);
	}
}

/**
	Print Match to webpage
*/
function printMatch($mData){
	$eStr = "";
	echo "<div id='match'>";

	$winner = "Radiant";
	if($mData->result->radiant_win == false){
		$winner = "Dire";
	}

	echo "<p> Winner: ". $winner . "<br>";

	echo "<div id='teams'>";
		echo "<table>
   			<caption id='rd'>The Radiant</caption>
   				<thead>
      				<tr>
         				<th>Hero</th>
         				<th>Player</th>
         				<th>Level</th>
         				<th>Kills</th>
         				<th>Deaths</th>
         				<th>Assists</th>
         				<th>Last Hits</th>
         				<th>Denies</th>
         				<th>Gold</th>
         				<th>Gold/Min</th>
         				<th>XP/Min</th>
         				<th>Gold Spent</th>
         				<th>Hero Damage</th>
         				<th>Tower Damage</th>
         				<th>Hero Healing</th>
      				</tr></thead><tbody>";
   		for($i=0; $i<5; $i++){
   			echo "<tr>" . "<td>" . $mData->result->players[$i]->hero_id  . "</td>";
      		
			echo("<td>" . $mData->result->players[$i]->account_id . "</td>" ); 
			echo("<td>" . $mData->result->players[$i]->level . "</td>");
			echo("<td>" . $mData->result->players[$i]->kills . "</td>");
			echo("<td>" . $mData->result->players[$i]->deaths . "</td>");
			echo("<td>" . $mData->result->players[$i]->assists . "</td>");
			echo("<td>" . $mData->result->players[$i]->last_hits . "</td>");
			echo("<td>" . $mData->result->players[$i]->denies . "</td>");
			echo("<td>" . $mData->result->players[$i]->gold . "</td>");
			echo("<td>" . $mData->result->players[$i]->gold_per_min . "</td>");
			echo("<td>" . $mData->result->players[$i]->xp_per_min . "</td>");
			echo("<td>" . $mData->result->players[$i]->gold_spent . "</td>");
			echo("<td>" . $mData->result->players[$i]->hero_damage . "</td>");
			echo("<td>" . $mData->result->players[$i]->tower_damage . "</td>");
			echo("<td>" . $mData->result->players[$i]->hero_healing . "</td>");

			/*echo("<tr>" . "<td>" . $mData->result->players[$i]->item_0 . "</td>");
			echo("<tr>" . "<td>" . $mData->result->players[$i]->item_1 . "</td>");
			echo("<tr>" . "<td>" . $mData->result->players[$i]->item_2 . "</td>");
			echo("<tr>" . "<td>" . $mData->result->players[$i]->item_3 . "</td>");
			echo("<tr>" . "<td>" . $mData->result->players[$i]->item_4 . "</td>");
			echo("<tr>" . "<td>" . $mData->result->players[$i]->item_5 . "</td>");
*/
      		echo "</tr>";
      		}
      		echo "</tbody></table>";
		
	echo "<br><br>";

	echo "<div id='teams'>";
		echo "<table>
   			<caption id='dr'>The Dire</caption>
   				<thead>
      				<tr>
         				<th>Hero</th>
         				<th>Player</th>
         				<th>Level</th>
         				<th>Kills</th>
         				<th>Deaths</th>
         				<th>Assists</th>
         				<th>Last Hits</th>
         				<th>Denies</th>
         				<th>Gold</th>
         				<th>Gold/Min</th>
         				<th>XP/Min</th>
         				<th>Gold Spent</th>
         				<th>Hero Damage</th>
         				<th>Tower Damage</th>
         				<th>Hero Healing</th>
      				</tr></thead><tbody>";
   		for($i=5; $i<10; $i++){
   			echo "<tr>" . "<td>" . $mData->result->players[$i]->hero_id  . "</td>";
      		
			echo("<td>" . $mData->result->players[$i]->account_id . "</td>" ); 
			echo("<td>" . $mData->result->players[$i]->level . "</td>");
			echo("<td>" . $mData->result->players[$i]->kills . "</td>");
			echo("<td>" . $mData->result->players[$i]->deaths . "</td>");
			echo("<td>" . $mData->result->players[$i]->assists . "</td>");
			echo("<td>" . $mData->result->players[$i]->last_hits . "</td>");
			echo("<td>" . $mData->result->players[$i]->denies . "</td>");
			echo("<td>" . $mData->result->players[$i]->gold . "</td>");
			echo("<td>" . $mData->result->players[$i]->gold_per_min . "</td>");
			echo("<td>" . $mData->result->players[$i]->xp_per_min . "</td>");
			echo("<td>" . $mData->result->players[$i]->gold_spent . "</td>");
			echo("<td>" . $mData->result->players[$i]->hero_damage . "</td>");
			echo("<td>" . $mData->result->players[$i]->tower_damage . "</td>");
			echo("<td>" . $mData->result->players[$i]->hero_healing . "</td>");

			/*echo("<tr>" . "<td>" . $mData->result->players[$i]->item_0 . "</td>");
			echo("<tr>" . "<td>" . $mData->result->players[$i]->item_1 . "</td>");
			echo("<tr>" . "<td>" . $mData->result->players[$i]->item_2 . "</td>");
			echo("<tr>" . "<td>" . $mData->result->players[$i]->item_3 . "</td>");
			echo("<tr>" . "<td>" . $mData->result->players[$i]->item_4 . "</td>");
			echo("<tr>" . "<td>" . $mData->result->players[$i]->item_5 . "</td>");
*/
      		echo "</tr>";
      		}
   		echo "</tbody></table>";
	echo "</div>";
	echo "</div>";
}

// Do some sanitation on input
// If web request is post 
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$m_id = $_POST["match"];
	lookupMatch($m_id);
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