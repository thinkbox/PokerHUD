<?php
class PlayerMapperTest extends Zend_Test_PHPUnit_DatabaseTestCase {
	
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
			APPLICATION_PATH . '/../tests/data/fixtures/playersSeed.xml'
		);
	}
	
	public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
        $this->mapper = new Application_Model_PlayerMapper();
    }
	
	public function testGetDbTableDoitAppelerSetDbTableSiDbTableNAJamaisEteInitialise() {
		$mapper = $this->getMock('Application_Model_PlayerMapper', array('setDbTable'));
		 
		$mapper->expects($this->once())
			   ->method('setDbTable')
			   ->with($this->equalTo('Application_Model_DbTable_Player'));
		
		$mapper->getDbTable();
	}
	
	public function testGetDbTableDoitRetournerUnObjetApplicationModelDbTablePlayerParDefaut() {
		$this->assertEquals(new Application_Model_DbTable_Player(), $this->mapper->getDbTable());
	}
	
	public function testGetDbTableDoitRetournerUnObjetPrecisSiPreciseAuparavant() {
		$this->mapper->setDbTable('Application_Model_DbTable_Hand');
		$this->assertEquals(new Application_Model_DbTable_Hand(), $this->mapper->getDbTable());
	}
	
	public function testPlayerExistsDoitRetournerNullSiLePlayerDemandeNEstPasEnregistreEnBase() {
		$result = $this->mapper->playerExists('antonius');
		$this->assertNull($result);
	}
	
	public function testPlayerExistsDoitRetournerUneInstanceApplicationModelPlayerSiLeJoueurExisteAvecSesInfos() {
		$result = $this->mapper->playerExists('hansen');
		
		$this->assertInstanceOf('Application_Model_Player', $result);
		$this->assertEquals(1, $result->getId());
		$this->assertEquals('hansen', $result->getName());
		$this->assertEquals(124, $result->getNbHands());
		$this->assertEquals("2012-07-23 15:04:14", $result->getUpdated());
		$this->assertEquals("2012-07-10 10:24:36", $result->getCreated());
	}
	
	public function testSaveDoitInsererUneEntreeEnBddSiJoueurInexistant() {
		$result = $this->mapper->save(new Application_Model_Player(array(
			'name' => "antonius"
		)));
		
		$ds = new Zend_Test_PHPUnit_Db_DataSet_QueryDataSet(
			$this->getConnection()
		);
		$ds->addTable('player', 'SELECT name, nb_hands FROM player');
		
		$this->assertDataSetsEqual($this->createFlatXmlDataSet(APPLICATION_PATH . "/../tests/data/fixtures/playerAdded.xml"), $ds);
	}
	
	public function testSaveDoitIncrementerDe1LeNombreDeMainsJoueesDuJoueurSIlExisteDejaEnBase() {
		$result = $this->mapper->save(new Application_Model_Player(array(
			'name' => "hansen"
		)));
		
		$ds = new Zend_Test_PHPUnit_Db_DataSet_QueryDataSet(
			$this->getConnection()
		);
		$ds->addTable('player', 'SELECT id, name, nb_hands, created FROM player');
		
		$this->assertDataSetsEqual($this->createFlatXmlDataSet(APPLICATION_PATH . "/../tests/data/fixtures/playerUpdated.xml"), $ds);
	}
	
	public function testSaveDoitRetournerLIdDuJoueur() {
		$result = $this->mapper->save(new Application_Model_Player(array(
			'name' => "hellmuth"
		)));
		
		$this->assertEquals(3, $result);
		
		$result = $this->mapper->save(new Application_Model_Player(array(
			'name' => "Binchou1981"
		)));
		
		$where = $this->mapper->getDbTable()->getAdapter()->quoteInto('name = ?', 'Binchou1981');
		$this->assertEquals($this->mapper->getDbTable()->fetchRow($where, 'id')->__get('id'), $result);
	}
}