<?php

class Application_Model_ActionMapper extends Application_Model_Mapper
{
    public function getDbTable()
    {
        return $this->getTable('Action');
    }
 
    public function save(Application_Model_Action $action)
    {
        $data = array(
            'id_hand'			=> $action->getIdHand(),
			'name_player'		=> $action->getNamePlayer(),
        	'position'			=> $action->getPosition(), 
            'action_preflop'	=> $action->getActionPreflop(),
            'action_flop'		=> $action->getActionFlop(),
            'action_turn'		=> $action->getActionTurn(),
            'action_river'		=> $action->getActionRiver(),
            'resultat'			=> $action->getResultat(),
            'treated'			=> '0'
        );
        
        return parent::save($data, true);
    }
    
    public function markAsTreated($id_action) {
    	return $this->getDbTable()->update(array('treated' => 1, 'updated' => date('Y-m-d H:i:s')), $this->getDbTable()->getAdapter()->quoteInto('id = ?', $id_action));
    }
    
    public function getNextToTreat() {
    	$next = $this->getDbTable()->fetchRow($this->getDbTable()->select()->where('treated = ?', 0)->order('created')->limit(1));
    	return $next === null ? null : new Application_Model_Action(array(
	    	'id'				=> $next->id,
	    	'id_hand'			=> $next->id_hand,
	    	'name_player'		=> $next->name_player,
	    	'position'			=> $next->position,
	    	'action_preflop'	=> $next->action_preflop,
	    	'action_flop'		=> $next->action_flop,
	    	'action_turn'		=> $next->action_turn,
	    	'action_river'		=> $next->action_river,
	    	'resultat'			=> $next->resultat,
	    	'treated'			=> $next->treated,
	    	'updated'			=> $next->updated,
	    	'created'			=> $next->created
    	));
    }
    
    public function getNextToTreatAgain($date) {
    	$next = $this->getDbTable()->fetchRow($this->getDbTable()->select()->where('treated = ?', 1)->where('updated <= ?', $date)->order('updated')->limit(1));
    	return $next === null ? null : new Application_Model_Action(array(
	    	'id'				=> $next->id,
	    	'id_hand'			=> $next->id_hand,
	    	'name_player'		=> $next->name_player,
	    	'position'			=> $next->position,
	    	'action_preflop'	=> $next->action_preflop,
	    	'action_flop'		=> $next->action_flop,
	    	'action_turn'		=> $next->action_turn,
	    	'action_river'		=> $next->action_river,
	    	'resultat'			=> $next->resultat,
	    	'treated'			=> $next->treated,
	    	'updated'			=> $next->updated,
	    	'created'			=> $next->created
    	));
    	return null;
    }
}