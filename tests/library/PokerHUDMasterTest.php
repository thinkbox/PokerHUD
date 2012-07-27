<?php

require_once 'Zend/Test/PHPUnit/DatabaseTestCase.php';

class PokerHUDMasterTest extends Zend_Test_PHPUnit_DatabaseTestCase {
	protected $DATA_TEST_DIR;
	protected $HISTORIQUE_PATH;
	private $_connectionMock;

	function __construct() {
		$this->DATA_TEST_DIR = APPLICATION_PATH.'/../tests/data';
		$this->HISTORIQUE_PATH = APPLICATION_PATH.'/../historique.txt';
	}
	
	protected function getConnection()
	{
		if($this->_connectionMock == null) {
			$connection = Zend_Db::factory('PDO_SQLITE', array(
				'dbname' => APPLICATION_PATH . "/../data/db/pokerhud-testing.db.sqlite"
			));
			$this->_connectionMock = $this->createZendDbConnection(
				$connection, 'pokerhudunittests'
			);
			Zend_Db_Table_Abstract::setDefaultAdapter($connection);
		}
		return $this->_connectionMock;
	}
	
	protected function getDataSet()
	{
		return $this->createFlatXmlDataSet(
			$this->DATA_TEST_DIR . '/fixtures/handsSeed.xml'
		);
	}
}