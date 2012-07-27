<?php
class Hud_Collector extends Hud_PokerHUD {
	private $historique_path = "";

    function  __construct($historique_path, $data_path = null) {
		file_exists($data_path) ? parent::__construct($data_path) : parent::__construct();
		
        if(!file_exists($historique_path))
			Throw new Exception('Collector.class.php : __construct('.$historique_path.'). Historique inexistant.');
        
		$this->historique_path = $historique_path;
    }
	
	function getHistoriquePath() {
		return $this->historique_path;
	}
	
	function getHands($delete_file = true) {
		$historique = file_get_contents($this->historique_path);
		if(!$historique || $historique == "")
			return 0;
		
		$historique_explode = explode('PokerStars Hand #', $historique);
		array_shift($historique_explode);
		
		foreach($historique_explode as $hand) {
			preg_match("/([0-9]*):/", $hand, $id_hand);
			$handfilename = $id_hand[1].'.txt';
			file_put_contents($this->data_path.'/'.$handfilename, trim('PokerStars Hand #'.$hand));
		}
		
		if ($delete_file)
			file_put_contents($this->getHistoriquePath(), '');
		
		return count($historique_explode);
	}
}