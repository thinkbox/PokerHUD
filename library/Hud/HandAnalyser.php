<?php
class Hud_HandAnalyser extends Hud_PokerHUD {
	private $id_hand;
	private $hand;
	private $seat_button;
	private $players_from_hand;
	private $seats_from_hand;

    function  __construct($id, $data_path = null) {
		file_exists($data_path) ? parent::__construct($data_path) : parent::__construct();
       	
        if(!file_exists($this->getDataPath().'/'.$id.'.txt')) {
			Throw new Exception('HandAnalyser.class.php : __construct('.$id.'). Id incorrect.');
        }
        
		$this->id_hand = $id;
		$this->hand = file_get_contents($this->getDataPath().'/'.$id.'.txt');
		
		preg_match("/Seat #([0-9]*) is the button/", $this->hand, $button);
		$this->seat_button = $button[1];
		
		preg_match_all("/Seat ([0-9]*): (.*?) \([0-9]* in chips\)/", $this->hand, $liste_joueurs);
		$seats = array();
		foreach ($liste_joueurs[2] as $key => $joueur) {
			$seats[$liste_joueurs[1][$key]] = $joueur;
		}
		$this->players_from_hand = $liste_joueurs;
		$this->seats_from_hand = $seats;
    }
	
	public function getIdHand() {
		return $this->id_hand;
	}
	
	public function getHand() {
		return $this->hand;
	}
	
	public function getPlayers() {
		$seat_button = $this->getSeatButton();
		
		$joueurs = array();
		$liste_joueurs = $this->players_from_hand;
		$seats = $this->seats_from_hand;
		foreach ($liste_joueurs[2] as $key => $joueur) {
			$joueurs[$key] = array('player' => $joueur, 'position' => 'middle');
			$seats[$liste_joueurs[1][$key]] = $joueur;
			if ($liste_joueurs[1][$key] == $seat_button)
				$key_button = $key;
		}
		
		$this->setPositions($joueurs, $seats, $key_button, $liste_joueurs);
		
		$toutes_actions = explode('*** HOLE CARDS ***', $this->hand);
		
		if (count($toutes_actions) > 1) {
			$actions_preflop = explode('*** FLOP ***', $toutes_actions[1]);
			$this->setActionsByEnchere($joueurs, $actions_preflop[0], 'preflop');
			
			if (count($actions_preflop) > 1) {
				$actions_flop = explode('*** TURN ***', $actions_preflop[1]);
				$this->setActionsByEnchere($joueurs, $actions_flop[0], 'flop');
				
				if (count($actions_flop) > 1) {
					$actions_turn = explode('*** RIVER ***', $actions_flop[1]);
					$this->setActionsByEnchere($joueurs, $actions_turn[0], 'turn');
					
					if (count($actions_turn) > 1) {
						$actions_river = explode('*** SHOW DOWN ***', $actions_turn[1]);
						$this->setActionsByEnchere($joueurs, $actions_river[0], 'river');
					} else {
						$this->setActionsByEnchere($joueurs, '', 'river');
					}
				}else {
					$this->setActionsByEnchere($joueurs, '', 'turn');
				$this->setActionsByEnchere($joueurs, '', 'river');
				}
			} else {
				$this->setActionsByEnchere($joueurs, '', 'flop');
				$this->setActionsByEnchere($joueurs, '', 'turn');
				$this->setActionsByEnchere($joueurs, '', 'river');
			}
		} else {
			$this->setActionsByEnchere($joueurs, '', 'preflop');
			$this->setActionsByEnchere($joueurs, '', 'flop');
			$this->setActionsByEnchere($joueurs, '', 'turn');
			$this->setActionsByEnchere($joueurs, '', 'river');
		}
		
		$infos_main = $this->getInfos();
		
		foreach ($joueurs as $key => $joueur) {
			$fold = false;
			foreach ($joueur as $item) {
				if (strpos($item, 'fold') !== false) {
					$fold = true;
					break;
				}
			}
			
			if ($fold === false) {
				$resultat = strpos($infos_main['winner'], $joueur['player']) === false ? 'lost' : 'won '.$infos_main['type of winning'];
			} else {
				$resultat = 'fold';
			}
			
			$joueurs[$key]['resultat'] = $resultat;
		}
		
		return $joueurs;
	}
	
	public function getInfos() {
		$infos = array();
		
		preg_match("/Level ([A-Z]*) \(([0-9]*)\/([0-9]*)\)/", $this->hand, $infos_top);
		$infos['level'] = $infos_top[1];
		$infos['small blind'] = $infos_top[2];
		$infos['big blind'] = $infos_top[3];
		
		preg_match_all("/.*: posts the ante ([0-9]*)/", $this->hand, $antes);
		$infos['ante'] = count($antes[1]) > 0 ? $antes[1][0] : 0;
		
		foreach ($this->players_from_hand[2] as $key => $joueur) {
			$this->seats_from_hand[$this->players_from_hand[1][$key]] = $joueur;
		}
		
		$winners = explode("Board ", $this->hand);
		preg_match_all("/Seat ([0-9]*): .* won/", $winners[count($winners)-1], $wins);
		
		$winner = '';
		$winning = 'at showdown';
		foreach ($wins[1] as $key => $win) {
			$winner .= $winner == '' ? '' : ', ';
			$winner .= $this->seats_from_hand[$wins[1][$key]];
		}
		if ($winner == '') {
			$winning = 'before showdown';
			preg_match_all("/Seat ([0-9]*): .* collected/", $winners[count($winners)-1], $wins);
			foreach ($wins[1] as $key => $win) {
				$winner .= $winner == '' ? '' : ', ';
				$winner .= $this->seats_from_hand[$wins[1][$key]];
			}
		}
		$infos['winner'] = $winner;
		$infos['type of winning'] = $winning;
		
		return $infos;
	}
	
	public function getSeatButton() {
		return $this->seat_button;
	}
	
	public function save($hand_mapper = null, $action_mapper = null) {
		$joueurs = $this->getPlayers();
		$infos = $this->getInfos();
		
		if ($hand_mapper === null) 
			$hand_mapper = new Application_Model_HandMapper();
		
		$id_hand = $hand_mapper->save(new Application_Model_Hand(array(
            'id_fichier'	=> $this->getIdHand(),
            'level'			=> $infos['level'],
            'sb'			=> $infos['small blind'],
            'bb'      		=> $infos['big blind'],
            'ante'			=> $infos['ante'],
            'winner'		=> $infos['winner'],
            'content'		=> $this->getHand()
		)));
		
		if ($action_mapper === null)
			$action_mapper = new Application_Model_ActionMapper();
		
		foreach ($joueurs as $joueur) {
			$action_mapper->save(new Application_Model_Action(array(
				'id_hand'			=> $id_hand,
				'name_player'		=> $joueur['player'],
				'position'			=> $joueur['position'], 
	    		'action_preflop'	=> $joueur['action preflop'],
	    		'action_flop'		=> $joueur['action flop'],
	    		'action_turn'		=> $joueur['action turn'],
	    		'action_river'		=> $joueur['action river'], 
	    		'resultat'			=> $joueur['resultat']
			)));
		}
		
		if ($id_hand !== false) {
			rename($this->getDataPath().'/'.$this->id_hand.'.txt', $this->getDataPath().'/treated/'.$this->id_hand.'.txt');
		}
	}
	
	private function setPositions(&$joueurs, $seats, $key_button, $liste_joueurs) {
		$nb_joueurs = count($joueurs);
		$joueurs[$key_button]['position'] = 'button';
		
		$nb_joueurs_apres_button = $nb_joueurs - 1 - $key_button;
		$nb_joueurs_avant_button = $key_button;
		$positions = array();
		
		if ($nb_joueurs_avant_button == 0) {
			$positions[$nb_joueurs-2] = 'hi-jack';
			$positions[$nb_joueurs-1] = 'cut-off';
		} elseif ($nb_joueurs_avant_button == 1) {
			$positions[$nb_joueurs-1] = 'hi-jack';
			$positions[$key_button-1] = 'cut-off';
		} else {
			$positions[$key_button-2] = 'hi-jack';
			$positions[$key_button-1] = 'cut-off';
		}
		
		$positions_apres_button = array('small blind', 'big blind', 'under the gun', 'under the gun +1');
		for ($i=0; $i<4; $i++) {
			if ($i >= (4 - $nb_joueurs_apres_button)) {
				$positions[$key_button + (4 - $i)] = $positions_apres_button[4 - $i - 1];
			} else {
				$positions[$i] = $positions_apres_button[$i+$nb_joueurs_apres_button];
			}
		}
		
		foreach ($positions as $key => $position) {
			$joueurs[$key]['position'] = $position;
		}
	}
	
	private function setActionsByEnchere(&$joueurs, $actions_enchere, $enchere) {
		preg_match_all("/(.*)\n/", $actions_enchere, $actions);
		
		$actions_by_player = array();
		foreach ($actions[1] as $action) {
			$elements = explode(':', trim($action));
		
			$action_done = '';
			if (count($elements) > 1) {
				$result = explode(' ', $elements[1]);
				if (array_search('folds', $result)) {
					$action_done = 'fold';
				} elseif (array_search('calls', $result)) {
					$action_done = 'call';
				} elseif (array_search('raises', $result)) {
					$action_done = 'raise';
				} elseif (array_search('bets', $result)) {
					$action_done = 'bet';
				} elseif (array_search('checks', $result)) {
					$action_done = 'check';
				}
			}
			
			if (array_key_exists($elements[0], $actions_by_player)) {
				$action_done = $action_done == '' ? $actions_by_player[$elements[0]] : $actions_by_player[$elements[0]].', '.$action_done;
			}
			$actions_by_player[$elements[0]] = $action_done;
		}
		
		foreach ($joueurs as $key => $joueur) {
			$joueurs[$key]['action '.$enchere] = array_key_exists($joueur['player'], $actions_by_player) ? $actions_by_player[$joueur['player']] : '';
		}
	}
}