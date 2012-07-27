<?php

class Application_Model_StatMapper extends Application_Model_Mapper
{
    public function getDbTable()
    {
        return $this->getTable('Stat');
    }
    
    public function statExists($id_player, $type) {
    	$select = $this->getDbTable()->select()->from('stat')->where('id_player = ?', $id_player)->where('type = ?', $type)->limit(1);
    	$result = $this->getDbTable()->fetchRow($select);
    	
    	return $result === null ? null : new Application_Model_Stat(array(
    		'id' 		=> $result->id,
    		'id_player' => $result->id_player,
    		'type' 		=> $result->type,
    		'valeur' 	=> $result->valeur
    	));
    }
 
    public function save(Application_Model_Stat $stat)
    {
        $data = array(
        	'id_player'	=> $stat->getIdPlayer(),
        	'type'		=> $stat->getType(),
        	'valeur'	=> $stat->getValeur()
        );
        
        if (($stat = $this->statExists($stat->getIdPlayer(), $stat->getType())) !== null) {
        	unset($data['id_player']);
        	return $this->getDbTable()->update($data, $this->getDbTable()->getAdapter()->quoteInto('id = ?', $stat->getId()));
        } else {
        	return parent::save($data, false, false);
        }
    }
    
    public function updateStat($id_player, $type) {
    	if (($stat = $this->statExists($id_player, $type)) !== null) {
        	return $this->getDbTable()->update(array('valeur' => $stat->getValeur()+1), $this->getDbTable()->getAdapter()->quoteInto('id = ?', $stat->getId()));
        } else {
        	$this->save(new Application_Model_Stat(array(
        		'id_player'	=> $id_player,
        		'type'		=> $type,
        		'valeur'	=> 1
        	)));
        }
    }
}