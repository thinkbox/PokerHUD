<?php
class ActionMapperTest extends Zend_Test_PHPUnit_DatabaseTestCase {
	
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
			APPLICATION_PATH . '/../tests/data/fixtures/actionsSeed.xml'
		);
	}
	
	public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
        $this->mapper = new Application_Model_ActionMapper();
    }
	
	public function testGetDbTableDoitAppelerSetDbTableSiDbTableNAJamaisEteInitialise() {
		$mapper = $this->getMock('Application_Model_ActionMapper', array('setDbTable'));
		 
		$mapper->expects($this->once())
			   ->method('setDbTable')
			   ->with($this->equalTo('Application_Model_DbTable_Action'));
		
		$mapper->getDbTable();
	}
	
	public function testGetDbTableDoitRetournerUnObjetApplicationModelDbTableActionParDefaut() {
		$this->assertEquals(new Application_Model_DbTable_Action(), $this->mapper->getDbTable());
	}
	
	public function testGetDbTableDoitRetournerUnObjetPrecisSiPreciseAuparavant() {
		$this->mapper->setDbTable('Application_Model_DbTable_Hand');
		$this->assertEquals(new Application_Model_DbTable_Hand(), $this->mapper->getDbTable());
	}
	
	public function testSaveDoitInsererUneEntreeDansLaBdd() {
		$result = $this->mapper->save(new Application_Model_Action(array(
			'id_hand'			=> 124,
			'name_player'		=> "hellmuth",
			'position'			=> "under the gun", 
    		'action_preflop'	=> "call, fold",
    		'action_flop'		=> "",
    		'action_turn'		=> "",
    		'action_river'		=> "", 
    		'resultat'			=> "fold"
		)));
		
		$ds = new Zend_Test_PHPUnit_Db_DataSet_QueryDataSet(
			$this->getConnection()
		);
		$ds->addTable('action', 'SELECT id_hand, name_player, position, action_preflop, action_flop, action_turn, action_river, resultat, treated FROM action');
		
		$this->assertDataSetsEqual($this->createFlatXmlDataSet(APPLICATION_PATH . "/../tests/data/fixtures/actionAdded.xml"), $ds);
		$this->assertNotEmpty($result);
	}
	
	public function testSaveDoitEchouerSiUnChampEstNull() {
		$result = $this->mapper->save(new Application_Model_Action(array(
			'name_player'		=> "hellmuth",
    		'action_preflop'	=> "call, fold",
    		'action_flop'		=> "",
    		'action_turn'		=> "",
    		'resultat'			=> "fold"
		)));
		
		$ds = new Zend_Test_PHPUnit_Db_DataSet_QueryDataSet(
			$this->getConnection()
		);
		$ds->addTable('action', 'SELECT * FROM action');
		
		$this->assertDataSetsEqual($this->createFlatXmlDataSet(APPLICATION_PATH . "/../tests/data/fixtures/actionsSeed.xml"), $ds);
		$this->assertFalse($result);
	}
	
	public function testMarkAsTreatedDoitMettreA1LeChampTreatedDeLaMain() {
		$this->mapper->markAsTreated(2);
		
		$ds = new Zend_Test_PHPUnit_Db_DataSet_QueryDataSet(
			$this->getConnection()
		);
		$ds->addTable('action', 'SELECT id, id_hand, name_player, position, action_preflop, action_flop, action_turn, action_river, resultat, treated, created FROM action');
		
		$this->assertDataSetsEqual($this->createFlatXmlDataSet(APPLICATION_PATH . "/../tests/data/fixtures/actionTreated.xml"), $ds);
	}
	
	public function testMarkAsTreatedDoitRetourner0SiIdActionNEstPasBon() {
		$result = $this->mapper->markAsTreated('pas bon id');
		
		$this->assertEquals(0, $result);
	}
	
	public function testGetNextToTreatDoitRetournerNullSiIlNYAPasDActionsAvecTreatedA0() {
		$this->mapper->markAsTreated(1);
		$this->mapper->markAsTreated(2);
		
		$this->assertNull($this->mapper->getNextToTreat());
	}
	
	public function testGetNextToTreatDoitRetournerLActionLaPlusAncienneNonTraitee() {
		$action_a_traiter = $this->mapper->getNextToTreat();
		
		$this->assertInstanceOf('Application_Model_Action', $action_a_traiter);
		$this->assertEquals("1", $action_a_traiter->getId());
		$this->assertEquals("125", $action_a_traiter->getIdHand());
		$this->assertEquals("hansen", $action_a_traiter->getNamePlayer());
		$this->assertEquals("bouton", $action_a_traiter->getPosition());
		$this->assertEquals("raise", $action_a_traiter->getActionPreflop());
		$this->assertEquals("check, raise", $action_a_traiter->getActionFlop());
		$this->assertEquals("bet", $action_a_traiter->getActionTurn());
		$this->assertEquals("", $action_a_traiter->getActionRiver());
		$this->assertEquals("won", $action_a_traiter->getResultat());
		$this->assertEquals("0", $action_a_traiter->getTreated());
		$this->assertEquals("2012-07-10 10:24:36", $action_a_traiter->getUpdated());
		$this->assertEquals("2012-07-10 10:24:36", $action_a_traiter->getCreated());
	}
	
	public function testGetNextToTreatAgainDoitRetournerNullSiIlNYAPasDActionsAvecTreatedA1EtUpdateInferieurALaDateRenseignee() {
		$this->mapper->markAsTreated(1);
		$this->mapper->markAsTreated(2);
		
		$this->assertNull($this->mapper->getNextToTreatAgain("2012-07-01 00:00:00"));
	}
	
	public function testGetNextToTreatAgainDoitRetournerLActionLaPlusAncienneDejaTraiteeMaisDontLeChampUpdatedEstInferieurOuEgalALaDateDemandee() {
		$this->mapper->markAsTreated(1);
		
		$action_a_traiter = $this->mapper->getNextToTreatAgain(date('Y-m-d H:i:s'));
		
		$this->assertInstanceOf('Application_Model_Action', $action_a_traiter);
		$this->assertEquals("1", $action_a_traiter->getId());
		$this->assertEquals("125", $action_a_traiter->getIdHand());
		$this->assertEquals("hansen", $action_a_traiter->getNamePlayer());
		$this->assertEquals("bouton", $action_a_traiter->getPosition());
		$this->assertEquals("raise", $action_a_traiter->getActionPreflop());
		$this->assertEquals("check, raise", $action_a_traiter->getActionFlop());
		$this->assertEquals("bet", $action_a_traiter->getActionTurn());
		$this->assertEquals("", $action_a_traiter->getActionRiver());
		$this->assertEquals("won", $action_a_traiter->getResultat());
		$this->assertEquals("1", $action_a_traiter->getTreated());
		$this->assertEquals("2012-07-10 10:24:36", $action_a_traiter->getCreated());
	}
}