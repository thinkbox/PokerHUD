<?php
class MapperTest extends Zend_Test_PHPUnit_ControllerTestCase {
	
	protected $mapper;
	
	public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
        $this->mapper = $this->getMockForAbstractClass('Application_Model_Mapper');
    }
	
	public function testApplicationModelMapperDoitAvoirUnAttributDbTable() {
		$this->assertClassHasAttribute('_dbTable', 'Application_Model_Mapper');
	}
	
	public function testSetDbTableDoitLeverUneExceptionSiPasInitialiseAvecUneInstanceDeZendDbTableAbstract() {
		try {
			$this->mapper->setDbTable(15);
		} catch (Exception $expected) {
			$this->assertEquals('Invalid table data gateway provided', $expected->getMessage());
			return;
		}
		$this->fail("Une exception n'a pas ete levee alors qu'on tente d'initialiser setDbTable avec un mauvais argument.");
	}
	
	public function testSetDbTableDoitRetournerLInstanceApplicationModelMapper() {
		$mapper = $this->mapper->setDbTable('Application_Model_DbTable_Hand');
		$this->assertEquals($this->mapper, $mapper);
	}
}