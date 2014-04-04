<?php
/**
	This is a wrapper class for a Dota Player
*/
require 'vars.php';
class player{

	private $steamObj;	// Contains user steam data
	private $matches;	// Array of match id's
	private $user_Url;	// Url to perform a match history query
	private $match_id_Url; // Url to access match data
	private $player_sum_Url;	// Url to get user summary
	private $dota_id;	// 32bit steam id returned in GetMatchHistory
	private $wins;
	private $losses;
	private $num_matches;

	function __construct($sObj){
		global $API_KEY;
		$this->steamObj = $sObj;
		$this->matches = array();
		$this->user_Url = "https://api.steampowered.com/IDOTA2Match_570/GetMatchHistory/V001/?key=" . $API_KEY;
		$this->match_id_Url = "http://api.steampowered.com/IDOTA2Match_570/GetMatchDetails/V001/?key=" . $API_KEY . "&match_id=";
		$this->player_sum_Url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=" . $API_KEY . "&steamids=";
		$this->dota_id = $sObj['steamid'] - 76561197960265728;
//		echo 'Player Instantiated.<br>';
	}

	/**
		Get the last 500 matches of this player
	*/
	public function gatherMatches(){
		// Do first lookup on last 100 matches or so
		$page = $this->lookupUser(0);
		
		// Counts number of matches found
		$num_matches = 0;
		$test = 0;	// Used to prevent bugs\infinite loops for now
		// Loop while theres still more matches to gather
		while($page->result->results_remaining > 1 && $test<7){
			$i = 0;
			// Itterate through match results TODO: (check that it's actually hitting all matches)
			while($i < $page->result->num_results){
				array_push($this->matches, $page->result->matches[$i]->match_id);
				$i++;
				$num_matches++;
			}
			// Request another page
			$page = $this->lookupUser($this->matches[$num_matches-1]);	
			// Pop last entry off to prevent duplicats from start_match
			array_pop($this->matches);
			$num_matches--;
			//echo ">> " . count($this->matches) . "<br>";
			//echo "Matches Remaining: " . $page->result->results_remaining . "<br>";
			sleep(1);	// Dont kill valve servers yet
			$test++;
		}
	}

	/**
		@param $start_match: match id to start returning from
		@return json decoded object containing match id's and other players
			account id's
		Query the dota api and return player history, save match id's to cache
	*/
	public function lookupUser($start_match=0){
//		global $user_Url;
		$url = "";
		// If first page 
		if($start_match == 0){
			$url = $this->user_Url . "&account_id=" . $this->steamObj['steamid'];                 //76561197990498989";   
		}else{	// start from specific last match id
			$url = $this->user_Url . "&account_id=" . $this->steamObj['steamid'] . "&start_at_match_id=" . $start_match;
		}

		$file_path = "Players/" . $this->steamObj['steamid'] . ".json";
	
	//	$match_data = "";
		// No caching for now
	//	if(file_exists($file_path)){
		if(false){
			$fp = fopen($file_path, "r");
			$results = fread($fp, filesize($file_path));
			$match_data = json_decode($results);
//			print_search_results($match_data);
			//printMatch($match_data);
			fclose($fp);
		}
		else{
			// Get game data from valve
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

			// Do work with results
			$results = curl_exec($ch);
			$match_data = json_decode($results);
			curl_close($ch);
			//print_search_results($match_data);
			// Cache game data
		//	$fp = fopen($file_path, "w");
	//		fwrite($fp, $results);
//			fclose($fp);
		}
		return $match_data;
	}  

/**
	Lookup match data from the match id
*/
public function lookupMatch($match_id){
//	global $match_id_Url, $api_key_url;
	$file_path = "Matches/".$match_id.".json";
	$results = "";
	$match_data = "";
	
	// Check if game data is cached already
	if(file_exists($file_path)){
		$fp = fopen($file_path, "r");
		$results = fread($fp, filesize($file_path));
		$match_data = json_decode($results);
		$this->printMatch($match_data);
		fclose($fp);
	}
	else{
		// Get game data from valve
		$ch = curl_init();
		$url = $this->match_id_Url . $match_id;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		// Do work with results
		$results = curl_exec($ch);
		$match_data = json_decode($results);
		$this->printMatch($match_data);
		curl_close($ch);
		// Cache game data
		$this->saveMatch($results, $match_id);
	}
}

/**
	Save Match to file
*/
private function saveMatch($mData, $m_id){
	if(!file_exists("Matches/".$m_id.".json")){
		$fp = fopen("Matches/".$m_id.".json", "w");
		fwrite($fp, $mData);
		fclose($fp);
	}
}

/**
	Convert 32bit steamid to steam personaname
	@param $s_id steam id to convert
	@return steam persona (anonymous included by const)  
*/
private function get_players_persona($s_id){
	if($s_id == "4294967295")
		return "Anonymous";
	if($s_id == $this->dota_id)
		return $this->steamObj['personaname'];
	$url = $this->player_sum_Url . ($s_id + 76561197960265728);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		// Do work with results
	$results = curl_exec($ch);
	$player_data = json_decode($results);
	return $player_data->response->players[0]->personaname;
	curl_close($ch);
}

private function getHero($h_id){


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

	// Radiant Data
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
//      				echo $this->steamObj['steamid'];
   		for($i=0; $i<5; $i++){
   			echo "<tr>" . "<td>" . $mData->result->players[$i]->hero_id  . "</td>";
			
			echo "<td>" . $this->get_players_persona($mData->result->players[$i]->account_id) . "</td>";
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

	// Dire data
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
      		
      		echo "<td>" . $this->get_players_persona($mData->result->players[$i]->account_id) . "</td>";
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




	/**
		Get number of matches indexed
	*/
	public function get_num_matches(){
		return count($this->matches);
	}

	/**
		Get match id
		@param $id the index of the match id
		@return the match id at given index
	*/
	public function get_match($i){
		if($i< count($this->matches))
			return $this->matches[$i];
		else
			return 0;
	}


	/**
		print all the match id's on record for this player
		@param $num_matches number of matches to print default is all
	*/
	public function printMatches( $num_matches = 0 ){ //count($this->matches) ){
		//$num_matches = count($this->matches);
		$i=0;
		print_r($this->matches);
		/*
		while($i<$num_matches){
			echo $this->matches[$i] . "<br>";
			$i++;
		}
		*/
	}

	public function print_search_results($player_data){
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


}
?>