<?php
class StatMapperTest extends Zend_Test_PHPUnit_DatabaseTestCase {
	
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
			APPLICATION_PATH . '/../tests/data/fixtures/statsSeed.xml'
		);
	}
	
	public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
        $this->mapper = new Application_Model_StatMapper();
    }
	
	public function testGetDbTableDoitAppelerSetDbTableSiDbTableNAJamaisEteInitialise() {
		$mapper = $this->getMock('Application_Model_StatMapper', array('setDbTable'));
		 
		$mapper->expects($this->once())
			   ->method('setDbTable')
			   ->with($this->equalTo('Application_Model_DbTable_Stat'));
		
		$mapper->getDbTable();
	}
	
	public function testGetDbTableDoitRetournerUnObjetApplicationModelDbTableStatParDefaut() {
		$this->assertEquals(new Application_Model_DbTable_Stat(), $this->mapper->getDbTable());
	}
	
	public function testGetDbTableDoitRetournerUnObjetPrecisSiPreciseAuparavant() {
		$this->mapper->setDbTable('Application_Model_DbTable_Hand');
		$this->assertEquals(new Application_Model_DbTable_Hand(), $this->mapper->getDbTable());
	}
	
	public function testStatExistsDoitRetournerNullSiLaStatDemandeeNEstPasEnregistreeEnBase() {
		$result = $this->mapper->statExists(1, 'nombre de flops vus au bouton');
		$this->assertNull($result);
	}
	
	public function testPlayerExistsDoitRetournerUneInstanceApplicationModelStatSiLaStatExisteAvecSesInfos() {
		$result = $this->mapper->statExists(1, 'nombre de flops vus');
		
		$this->assertInstanceOf('Application_Model_Stat', $result);
		$this->assertEquals(1, $result->getId());
		$this->assertEquals(1, $result->getIdPlayer());
		$this->assertEquals('nombre de flops vus', $result->getType());
		$this->assertEquals(15, $result->getValeur());
	}
	
	public function testSaveDoitInsererUneEntreeDansLaBdd() {
		$result = $this->mapper->save(new Application_Model_Stat(array(
			'id_player'	=> '15',
			'type'		=> 'nombre de check raise',
			'valeur'	=> '2'
		)));
		
		$ds = new Zend_Test_PHPUnit_Db_DataSet_QueryDataSet(
			$this->getConnection()
		);
		$ds->addTable('stat', 'SELECT id_player, type, valeur FROM stat');
		
		$this->assertDataSetsEqual($this->createFlatXmlDataSet(APPLICATION_PATH . "/../tests/data/fixtures/statAdded.xml"), $ds);
		$this->assertNotEmpty($result);
	}
	
	public function testSaveDoitEchouerSiUnChampEstNull() {
		$result = $this->mapper->save(new Application_Model_Stat(array(
			'id_player' => '13',
			'type'		=> 'nombre de flops vus'
		)));
		
		$ds = new Zend_Test_PHPUnit_Db_DataSet_QueryDataSet(
			$this->getConnection()
		);
		$ds->addTable('stat', 'SELECT * FROM stat');
		
		$this->assertDataSetsEqual($this->createFlatXmlDataSet(APPLICATION_PATH . "/../tests/data/fixtures/statsSeed.xml"), $ds);
		$this->assertFalse($result);
	}
	
	public function testSaveDoitMettreAJourUneStatSiOnTenteDeSauvegarderUneStatDejaEnBase() {
		$result = $this->mapper->save(new Application_Model_Stat(array(
			'id_player'	=> '15',
			'type'		=> 'nombre de flops vus',
			'valeur'	=> 61
		)));
		
		$ds = new Zend_Test_PHPUnit_Db_DataSet_QueryDataSet(
			$this->getConnection()
		);
		$ds->addTable('stat', 'SELECT * FROM stat');
		
		$this->assertDataSetsEqual($this->createFlatXmlDataSet(APPLICATION_PATH . "/../tests/data/fixtures/statUpdated.xml"), $ds);
	}
	
	public function testUpdateStatDoitIncrementeeDe1LaStatDesiree() {
		$this->mapper->updateStat(15, 'nombre de flops vus');
		
		$ds = new Zend_Test_PHPUnit_Db_DataSet_QueryDataSet(
		$this->getConnection()
		);
		$ds->addTable('stat', 'SELECT * FROM stat');
		
		$this->assertDataSetsEqual($this->createFlatXmlDataSet(APPLICATION_PATH . "/../tests/data/fixtures/statUpdated.xml"), $ds);
	}
	
	public function testUpdateStatDoitAppelerSaveSiLaStatAMettreAJourNEstPasDisponible() {
		$mapper = $this->getMock('Application_Model_StatMapper', array('save'));
		$mapper->expects($this->once())
			   ->method('save')
			   ->with(new Application_Model_Stat(array(
					'id_player'	=> 15,
					'type' 		=> 'nombre de flops vus au bouton',
					'valeur'	=> 1
			   )));
		
		$mapper->updateStat(15, 'nombre de flops vus au bouton');
	}
}