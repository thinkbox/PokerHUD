<?php

abstract class Application_Model_Mapper
{
    protected $_dbTable;
 
    public function setDbTable($dbTable)
    {
    	if( is_string($dbTable)) {
    		$dbTable = new $dbTable();
    	}
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    public abstract function getDbTable();
    
    protected function getTable($table) {
    	if (null === $this->_dbTable) {
    		$this->setDbTable('Application_Model_DbTable_'.$table);
    	}
    	return $this->_dbTable;
    }
    
    protected function save($data, $to_update = false, $to_create = true) {
    	$date = date('Y-m-d H:i:s');
    	if ($to_update)
    		$data['updated'] = $date;
    	if ($to_create)
    		$data['created'] = $date;
    	if (!array_search(null, $data, true)) {
    		unset($data['id']);
    		return $this->getDbTable()->insert($data);
    	} else {
    		return false;
    	}
    }
}