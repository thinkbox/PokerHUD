<?php

class Application_Model_HandMapper extends Application_Model_Mapper
{
    public function getDbTable()
    {
    	return $this->getTable('Hand');
    }
 
    public function save(Application_Model_Hand $hand)
    {
        $data = array(
            'id_fichier'	=> $hand->getIdFichier(),
        	'level'			=> $hand->getLevel(),
        	'sb'			=> $hand->getSb(),
        	'bb'			=> $hand->getBb(),
        	'ante'			=> $hand->getAnte(),
        	'winner'		=> $hand->getWinner(),
        	'content'		=> $hand->getContent()
        );
        
        return parent::save($data);
    }
}