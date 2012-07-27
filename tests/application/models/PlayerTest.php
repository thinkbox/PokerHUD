<?php
class PlayerTest extends Zend_Test_PHPUnit_ControllerTestCase {
	
	protected $player;
	
	public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
        $this->player = new Application_Model_Player();
    }
	
	public function testApplicationModelPlayerDoitAvoirUnAttributCreated() {
		$this->assertClassHasAttribute('_created', 'Application_Model_Player');
	}
	
	public function testApplicationModelPlayerDoitAvoirUnSetterEtUnGetterPourCreated() {
		$date = '2012-07-18 15:13:54';
		$this->player->setCreated($date);
		$this->assertEquals($date, $this->player->getCreated());
	}
	
	public function testApplicationModelPlayerDoitAvoirUnAttributUpdated() {
		$this->assertClassHasAttribute('_updated', 'Application_Model_Player');
	}
	
	public function testApplicationModelPlayerDoitAvoirUnSetterEtUnGetterPourUpdated() {
		$date = '2012-07-18 15:13:54';
		$this->player->setUpdated($date);
		$this->assertEquals($date, $this->player->getUpdated());
	}
	
	public function testApplicationModelPlayerDoitAvoirUnAttributNbHands() {
		$this->assertClassHasAttribute('_nb_hands', 'Application_Model_Player');
	}
	
	public function testApplicationModelPlayerDoitAvoirUnSetterEtUnGetterPourNbHands() {
		$this->player->setNbHands(1);
		$this->assertEquals(1, $this->player->getNbHands());
	}
	
	public function testApplicationModelPlayerDoitAvoirUnAttributName() {
		$this->assertClassHasAttribute('_name', 'Application_Model_Player');
	}
	
	public function testApplicationModelPlayerDoitAvoirUnSetterEtUnGetterPourName() {
		$this->player->setName('name');
		$this->assertEquals('name', $this->player->getName());
	}
	
	public function testApplicationModelPlayerDoitAvoirUnAttributId() {
		$this->assertClassHasAttribute('_id', 'Application_Model_Player');
	}
	
	public function testApplicationModelPlayerDoitAvoirUnSetterEtUnGetterPourId() {
		$this->player->setId(12);
		$this->assertEquals(12, $this->player->getId());
	}
	
	public function testApplicationModelPlayerDoitAvoirIdNbHandsEntiers() {
		$attributs = array('Id', 'NbHands');
		foreach ($attributs as $attribut) {
			$setmethod = 'set'.$attribut;
			$getmethod = 'get'.$attribut;
			$this->player->$setmethod('125');
			$this->assertEquals(125, $this->player->$getmethod());
			$this->player->$setmethod('sfsdfsdf');
			$this->assertEquals(0, $this->player->$getmethod());
			$this->player->$setmethod(-14526);
			$this->assertEquals(0, $this->player->$getmethod());
		} 
	}
	
	public function testApplicationModelPlayerDoitAvoirNameStrings() {
		$attributs = array('Name');
		foreach ($attributs as $attribut) {
			$setmethod = 'set'.$attribut;
			$getmethod = 'get'.$attribut;
			$this->player->$setmethod(125);
			$this->assertEquals('125', $this->player->$getmethod());
			$this->player->$setmethod(-14526);
			$this->assertEquals('-14526', $this->player->$getmethod());
		} 
	}
	
	public function testApplicationModelPlayerDoitAvoirCreatedEtUpdatedRepresentantUneDateFormatBdd() {
		$this->player->setCreated('bad timestamp');
		$this->assertRegExp('/[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/', $this->player->getCreated());
		$this->player->setUpdated('bad timestamp');
		$this->assertRegExp('/[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/', $this->player->getUpdated());
	}
	
	public function testApplicationModelPlayerDoitLeverUneExceptionSiOnTenteDObtenirUnAttributInconnuViaGet() {
        try {
			$this->player->__get('inconnu');
		} catch (Exception $expected) {
			$this->assertEquals('Invalid player property', $expected->getMessage());
			return;
		}
		$this->fail("Une exception n'a pas ete levee alors qu'on tente d'obtenir un attribut inconnu via get.");
    }
	
	public function testApplicationModelPlayerDoitLeverUneExceptionSiOnTenteDeSetterUnAttributInconnuViaSet() {
        try {
			$this->player->__set('inconnu', 'valeur');
		} catch (Exception $expected) {
			$this->assertEquals('Invalid player property', $expected->getMessage());
			return;
		}
		$this->fail("Une exception n'a pas ete levee alors qu'on tente de setter un attribut inconnu via set.");
    }
    
    public function testApplicationModelPlayerDoitRetournerEtSetterLAttributViaGetEtSet() {
    	$this->player->__set('name', 'Binchou1981');
    	$this->assertEquals('Binchou1981', $this->player->__get('name'));
    }
    
    public function testSetOptionsDoitPrendreUneListeDeParametresEtAppelerLesSettersAssocies() {
    	$player = $this->getMock('Application_Model_Player', array('setNbHands', 'setName'));
    	$player->expects($this->once())
    		   ->method('setNbHands')
			   ->with($this->equalTo(12));
    	
		$player->expects($this->once())
    		   ->method('setName')
    		   ->with($this->equalTo('Binchou1981'));
    	
		$player->expects($this->never())
    		   ->method('setInconnu');
    	
    	$player->setOptions(array('name' => 'Binchou1981', 'nb_hands' => '12', 'inconnu' => 'bad attribut'));
    }
    
	public function testApplicationModelPlayerDoitPouvoirEtreInitialiserSansArgument() {
    	$new_player_without_array = $this->getMock('Application_Model_Player', array('setOptions'));
    	$new_player_without_array->expects($this->never())
    		 				     ->method('setOptions');
    }
    
    public function testApplicationModelPlayerDoitPouvoirEtreInitialiserAvecUnArrayDAttribut() {
    	$new_player_with_array = $this->getMockBuilder('Application_Model_Player')
    								  ->disableOriginalConstructor()
    								  ->getMock();
    	
    	$new_player_with_array->expects($this->once())
    		 				  ->method('setOptions')
    		 				  ->with($this->equalTo(array('name' => 'Binchou1981', 'nb_hands' => '12', 'inconnu' => 'bad attribut')));
    	
    	$new_player_with_array->__construct(array('name' => 'Binchou1981', 'nb_hands' => '12', 'inconnu' => 'bad attribut'));
    }
}