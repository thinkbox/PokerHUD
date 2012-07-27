<?php
class HandMapperTest extends Zend_Test_PHPUnit_DatabaseTestCase {
	
	protected $mapper;
	private $_connectionMock;
	
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
			APPLICATION_PATH . '/../tests/data/fixtures/handsSeed.xml'
		);
	}
	
	public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
        $this->mapper = new Application_Model_HandMapper();
    }
	
	public function testGetDbTableDoitAppelerSetDbTableSiDbTableNAJamaisEteInitialise() {
		$mapper = $this->getMock('Application_Model_HandMapper', array('setDbTable'));
		 
		$mapper->expects($this->once())
			   ->method('setDbTable')
			   ->with($this->equalTo('Application_Model_DbTable_Hand'));
		
		$mapper->getDbTable();
	}
	
	public function testGetDbTableDoitRetournerUnObjetApplicationModelDbTableHandParDefaut() {
		$this->assertEquals(new Application_Model_DbTable_Hand(), $this->mapper->getDbTable());
	}
	
	public function testGetDbTableDoitRetournerUnObjetPrecisSiPreciseAuparavant() {
		$this->mapper->setDbTable('Application_Model_DbTable_Action');
		$this->assertEquals(new Application_Model_DbTable_Action(), $this->mapper->getDbTable());
	}
	
	public function testSaveDoitInsererUneEntreeDansLaBdd() {
		$result = $this->mapper->save(new Application_Model_Hand(array(
            'id_fichier'	=> '832156458745',
            'level'			=> 'IV',
            'sb'			=> 30,
            'bb'      		=> 60,
            'ante'			=> 5,
            'winner'		=> 'Binchou1981',
            'content'		=> 'content nouvelle main'
		)));
		
		$ds = new Zend_Test_PHPUnit_Db_DataSet_QueryDataSet(
			$this->getConnection()
		);
		$ds->addTable('hand', 'SELECT id_fichier, level, sb, bb, ante, winner, content FROM hand');
		
		$this->assertDataSetsEqual($this->createFlatXmlDataSet(APPLICATION_PATH . "/../tests/data/fixtures/handAdded.xml"), $ds);
		$this->assertNotEmpty($result);
	}
	
	public function testSaveDoitEchouerSiUnChampEstNull() {
		$result = $this->mapper->save(new Application_Model_Hand(array(
		            'id_fichier'	=> '832156458745',
		            'level'			=> 'IV',
		            'content'		=> 'content nouvelle main'
		)));
		
		$ds = new Zend_Test_PHPUnit_Db_DataSet_QueryDataSet(
		$this->getConnection()
		);
		$ds->addTable('hand', 'SELECT * FROM hand');
		
		$this->assertDataSetsEqual($this->createFlatXmlDataSet(APPLICATION_PATH . "/../tests/data/fixtures/handsSeed.xml"), $ds);
		$this->assertFalse($result);
	}
}