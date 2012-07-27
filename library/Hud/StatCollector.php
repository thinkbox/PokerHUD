<?php
class Hud_StatCollector extends Hud_PokerHUD {
	
	protected $action;
	protected $id_joueur;
	
	public function collect($action_mapper = null, $player_mapper = null) {
		if ($action_mapper === null)
			$action_mapper = new Application_Model_ActionMapper();
		
		if ($player_mapper === null)
			$player_mapper = new Application_Model_PlayerMapper();
		
		$action_a_traite = $action_mapper->getNextToTreat();
		if ($action_a_traite === null)
			return false;
		
		$this->setAction($action_a_traite);
		
		$id_joueur = $player_mapper->save(new Application_Model_Player(array(
			'name' => $action_a_traite->getNamePlayer()
		)));
		
		$this->setIdJoueur($id_joueur);
		
		$action_mapper->markAsTreated($action_a_traite->getId());
		
		$this->getStats();
		
		return $action_a_traite->getId();
	}
	
	public function collectAgain($type_stat, $date_traitement, $action_mapper = null, $player_mapper = null, $stat_mapper = null) {
		if ($action_mapper === null)
			$action_mapper = new Application_Model_ActionMapper();
		
		if ($player_mapper === null)
			$player_mapper = new Application_Model_PlayerMapper();
		
		if ($stat_mapper === null)
			$stat_mapper = new Application_Model_StatMapper();
		
		$action_a_traite = $action_mapper->getNextToTreatAgain($date_traitement);
		if ($action_a_traite === null)
			return false;
		
		$action_mapper->markAsTreated($action_a_traite->getId());
		
		$player = $player_mapper->playerExists($action_a_traite->getNamePlayer());
		
		if ($player === null)
			return false;
		
		$this->setAction($action_a_traite);
		$this->setIdJoueur($player->getId());
		
		$this->getStats($stat_mapper, $type_stat);
		
		return $action_a_traite->getId();
	}
	
	public function getStats($stat_mapper = null, $type_stat = null) {
		if ($stat_mapper === null)
			$stat_mapper = new Application_Model_StatMapper();
		
		if (strpos($this->action->getActionPreflop(), 'fold') === false) {
			$this->updateStatOrNot($stat_mapper, 'nombre de flops vus', $type_stat);
			
			if ($this->action->getPosition() == 'button') {
				$this->updateStatOrNot($stat_mapper, 'nombre de flops vus au bouton', $type_stat);
			} elseif ($this->action->getPosition() == 'small blind') {
				$this->updateStatOrNot($stat_mapper, 'nombre de flops vus en small blind', $type_stat);
			} elseif ($this->action->getPosition() == 'big blind') {
				$this->updateStatOrNot($stat_mapper, 'nombre de flops vus en big blind', $type_stat);
			}
		} else {
			if ($this->action->getActionPreflop() != 'fold') {
				$this->updateStatOrNot($stat_mapper, 'nombre de mains jouees et foldees preflop', $type_stat);
			}
		}
		
		if ($this->action->getResultat() == 'won at showdown') {
			$this->updateStatOrNot($stat_mapper, 'nombre de mains gagnees au showdown', $type_stat);
		} elseif ($this->action->getResultat() == 'won before showdown') {
			$this->updateStatOrNot($stat_mapper, 'nombre de mains gagnees avant le showdown', $type_stat);
		} elseif ($this->action->getResultat() == 'lost') {
			$this->updateStatOrNot($stat_mapper, 'nombre de mains perdues au showdown', $type_stat);
		}
	}
	
	public function getAction() {
		return $this->action;
	}
	
	public function setAction(Application_Model_Action $action) {
		$this->action = $action;
	}
	
	public function getIdJoueur() {
		return $this->id_joueur;
	}
	
	public function setIdJoueur($id_joueur) {
		$this->id_joueur = $id_joueur;
	}
	
	private function updateStatOrNot($stat_mapper, $type, $type_to_check) {		
		if ($type_to_check === null || ($type_to_check == $type)) {
			$stat_mapper->updateStat($this->id_joueur, $type);
		}
	}
}