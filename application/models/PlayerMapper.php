<?php

class Application_Model_PlayerMapper extends Application_Model_Mapper
{
    public function getDbTable()
    {
        return $this->getTable('Player');
    }
    
    public function playerExists($name) {
    	$select = $this->getDbTable()->select()->from('player')->where('name = ?', $name)->limit(1);
    	$result = $this->getDbTable()->fetchRow($select);
    	
    	return $result === null ? null : new Application_Model_Player(array(
    		'id' => $result->id,
    		'name' => $result->name,
    		'nb_hands' => $result->nb_hands,
    		'updated' => $result->updated,
    		'created' => $result->created
    	));
    }
 
    public function save(Application_Model_Player $player)
    {
        $data = array(
        	'name'		=> $player->getName(),
        	'nb_hands'	=> 1
        );
        
        if (($player = $this->playerExists($player->getName())) !== null) {
        	unset($data['name']);
        	$data['nb_hands'] = $player->getNbHands() + 1;
        	$data['updated'] = date('Y-m-d H:i:s');
        	$this->getDbTable()->update($data, $this->getDbTable()->getAdapter()->quoteInto('id = ?', $player->getId()));
        	return $player->getId();
        } else {
        	return parent::save($data, true);
        }
    }
}