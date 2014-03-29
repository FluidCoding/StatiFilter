<?php
//$gameMode = {"All Pick", ""}
$api_key = "52BA3796AFE214AE81E250357A12791A";
$match_id_Url = "http://api.steampowered.com/IDOTA2Match_570/GetMatchDetails/V001/?match_id=";
$api_key_url = "&key=52BA3796AFE214AE81E250357A12791A";
//$match = [];
//$username = ""
/*
	Lookup match data from the match id
*/
function lookupMatch($match_id){
	global $match_id_Url, $api_key_url;
	echo ("Match ID: " . $match_id . "<br>");
	$ch = curl_init();

	//$ch = curl_init("https://api.steampowered.com/IDOTA2Match_570/GetMatchHistory/V001/?format=XML?match_id=".$match_id."&key=52BA3796AFE214AE81E250357A12791A");
//	$ch = curl_init("https://api.steampowered.com/IDOTA2Match_570/GetMatchHistory/V001/?match_id=582608489&key=52BA3796AFE214AE81E250357A12791A");


//	$fp = fopen("Matches/".$m_id.".json", "w");
//	curl_setopt($ch, CURLOPT_FILE, $fp);
	//curl_setopt($ch, CURLOPT_URL, "http://api.steampowered.com/IDOTA2Match_570/GetMatchDetails/V001/?match_id=582374635&key=52BA3796AFE214AE81E250357A12791A");
	$url = $match_id_Url . $match_id . $api_key_url;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

	$result = curl_exec($ch);
	$match_data = json_decode($result);
	printMatch($match_data);
	curl_close($ch);
//	fclose($fp);
//	return $match_data;

}
function printMatch($mData){
	$eStr = "";
	echo "<div id='match'>";
//	print_r($mData);
	$winner = "Radiant";
	if($mData->result->radiant_win == "false"){
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
		

	// Loop Through PLayers
      	/*
	for($i=0; $i<5;++$i){
		echo($mData->result->players[$i]->account_id . " " ); 
		echo($mData->result->players[$i]->hero_id . " ");
		echo($mData->result->players[$i]->kills . " ");
		echo($mData->result->players[$i]->deaths . " ");
		echo($mData->result->players[$i]->assists . " ");
		echo($mData->result->players[$i]->gold . " ");
		echo($mData->result->players[$i]->last_hits . " ");
		echo($mData->result->players[$i]->denies . " ");
		echo($mData->result->players[$i]->gold_per_min . " ");
		echo($mData->result->players[$i]->xp_per_min . " ");
		echo($mData->result->players[$i]->gold_spent . " ");
		echo($mData->result->players[$i]->hero_damage . " ");
		echo($mData->result->players[$i]->tower_damage . " ");
		echo($mData->result->players[$i]->hero_healing . " ");
		echo($mData->result->players[$i]->level . " ");

		echo($mData->result->players[$i]->item_0 . " ");
		echo($mData->result->players[$i]->item_1 . " ");
		echo($mData->result->players[$i]->item_2 . " ");
		echo($mData->result->players[$i]->item_3 . " ");
		echo($mData->result->players[$i]->item_4 . " ");
		echo($mData->result->players[$i]->item_5 . " ");
		echo "<br>";

	}
	// Dire Team
	echo ("<br><h2 id='dr'>Dire Team </h2><br>");
	for($i=5; $i<10;++$i){
		echo($mData->result->players[$i]->account_id . " " ); 
		echo($mData->result->players[$i]->hero_id . " ");
		echo($mData->result->players[$i]->kills . " ");
		echo($mData->result->players[$i]->deaths . " ");
		echo($mData->result->players[$i]->assists . " ");
		echo($mData->result->players[$i]->gold . " ");
		echo($mData->result->players[$i]->last_hits . " ");
		echo($mData->result->players[$i]->denies . " ");
		echo($mData->result->players[$i]->gold_per_min . " ");
		echo($mData->result->players[$i]->xp_per_min . " ");
		echo($mData->result->players[$i]->gold_spent . " ");
		echo($mData->result->players[$i]->hero_damage . " ");
		echo($mData->result->players[$i]->tower_damage . " ");
		echo($mData->result->players[$i]->hero_healing . " ");
		echo($mData->result->players[$i]->level . " ");

		echo($mData->result->players[$i]->item_0 . " ");
		echo($mData->result->players[$i]->item_1 . " ");
		echo($mData->result->players[$i]->item_2 . " ");
		echo($mData->result->players[$i]->item_3 . " ");
		echo($mData->result->players[$i]->item_4 . " ");
		echo($mData->result->players[$i]->item_5 . " ");

		echo "<br>";

	}
//	foreach $mData->result->players[account_id as $v {
//		echo($v . "<br>");
	*/
	echo "</div>";
	echo "</div>";
}

//$m_id = 582280690;
//$ch = curl_init("https://api.steampowered.com/IDOTA2Match_570/GetMatchHistory/V001/?format=XML?match_id=".$m_id."&key=52BA3796AFE214AE81E250357A12791A");

//$fp = fopen("GetMatchHistory/".$m_id.".xml", "w");
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$m_id = $_POST["username"];
	lookupMatch($m_id);
}




//fclose($fp);

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