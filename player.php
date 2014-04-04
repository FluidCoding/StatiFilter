<?php
/**
	This is a wrapper class for a Dota Player
*/
require 'vars.php';
class player{
	private $steamObj;
	public $matches;
	private $user_Url;

	function __construct($sObj){
		global $API_KEY;
		$this->steamObj = $sObj;
		$this->matches = array();
		$this->user_Url = "https://api.steampowered.com/IDOTA2Match_570/GetMatchHistory/V001/?key=" . $API_KEY;
		//echo 'Player Instantiated.<br>';
	}

	public function gatherMatches(){
		global $user_Url;
		$page = $this->lookupUser(0);
		$num_matches = 0;
		$test = 0;
//		while($test<3){
		while($page->result->results_remaining > 1){
			$i = 0;
			while($i < $page->result->num_results){
				array_push($this->matches, $page->result->matches[$i]->match_id);
				$i++;
				$num_matches++;
			}
			$page = $this->lookupUser($this->matches[$num_matches-1]);
			//echo ">> " . $this->matches[$num_matches] . "<br>";
			echo "Matches Remaining: " . $page->result->results_remaining . "<br>";
			sleep(1);
		}

		echo "Matches found: " . $num_matches . "<br>";
	}

	public function lookupUser($start_match){
		global $user_Url;
		$url = "";
		$page = 0;
		if($start_match == 0){
			$url = $this->user_Url . "&account_id=" . $this->steamObj['steamid'];                 //76561197990498989";   
		}else{
			$url = $this->user_Url . "&account_id=" . $this->steamObj['steamid'] . "&start_at_match_id=" . $start_match;
		}

		$file_path = "Players/" . $this->steamObj['steamid']."(" . $page . ").json";
	//	$match_data = "";
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
			//$url = $match_id_Url . $match_id . $api_key_url;
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

			// Do work with results
			$results = curl_exec($ch);
			$match_data = json_decode($results);
			curl_close($ch);
			//print_search_results($match_data);
			// Cache game data
			$fp = fopen($file_path, "w");
			fwrite($fp, $results);
			fclose($fp);
		}
		return $match_data;
	}  

	public function printMatches( $num_matches = 105 ){ //count($this->matches) ){
		//$num_matches = count($this->matches);
		$i=0;
		while($i<$num_matches){
			echo $this->matches[$i] . "<br>";
			$i++;
		}
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