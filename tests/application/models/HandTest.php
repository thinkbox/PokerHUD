<?php
class HandTest extends Zend_Test_PHPUnit_ControllerTestCase {
	
	protected $hand;
	
	public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
        $this->hand = new Application_Model_Hand();
    }
	
	public function testApplicationModelHandDoitAvoirUnAttributCreated() {
		$this->assertClassHasAttribute('_created', 'Application_Model_Hand');
	}
	
	public function testApplicationModelHandDoitAvoirUnSetterEtUnGetterPourCreated() {
		$date = '2012-07-18 15:13:54';
		$this->hand->setCreated($date);
		$this->assertEquals($date, $this->hand->getCreated());
	}
	
	public function testApplicationModelHandDoitAvoirUnAttributContent() {
		$this->assertClassHasAttribute('_content', 'Application_Model_Hand');
	}
	
	public function testApplicationModelHandDoitAvoirUnSetterEtUnGetterPourContent() {
		$this->hand->setContent('content');
		$this->assertEquals('content', $this->hand->getContent());
	}
	
	public function testApplicationModelHandDoitAvoirUnAttributWinner() {
		$this->assertClassHasAttribute('_winner', 'Application_Model_Hand');
	}
	
	public function testApplicationModelHandDoitAvoirUnSetterEtUnGetterPourWinner() {
		$this->hand->setWinner('winner');
		$this->assertEquals('winner', $this->hand->getWinner());
	}
	
	public function testApplicationModelHandDoitAvoirUnAttributAnte() {
		$this->assertClassHasAttribute('_ante', 'Application_Model_Hand');
	}
	
	public function testApplicationModelHandDoitAvoirUnSetterEtUnGetterPourAnte() {
		$this->hand->setAnte(15);
		$this->assertEquals(15, $this->hand->getAnte());
	}
	
	public function testApplicationModelHandDoitAvoirUnAttributBb() {
		$this->assertClassHasAttribute('_bb', 'Application_Model_Hand');
	}
	
	public function testApplicationModelHandDoitAvoirUnSetterEtUnGetterPourBb() {
		$this->hand->setBb(200);
		$this->assertEquals(200, $this->hand->getBb());
	}
	
	public function testApplicationModelHandDoitAvoirUnAttributSb() {
		$this->assertClassHasAttribute('_sb', 'Application_Model_Hand');
	}
	
	public function testApplicationModelHandDoitAvoirUnSetterEtUnGetterPourSb() {
		$this->hand->setSb(100);
		$this->assertEquals(100, $this->hand->getSb());
	}
	
	public function testApplicationModelHandDoitAvoirUnAttributLevel() {
		$this->assertClassHasAttribute('_level', 'Application_Model_Hand');
	}
	
	public function testApplicationModelHandDoitAvoirUnSetterEtUnGetterPourLevel() {
		$this->hand->setLevel('X');
		$this->assertEquals('X', $this->hand->getLevel());
	}
	
	public function testApplicationModelHandDoitAvoirUnAttributIdFichier() {
		$this->assertClassHasAttribute('_id_fichier', 'Application_Model_Hand');
	}
	
	public function testApplicationModelHandDoitAvoirUnSetterEtUnGetterPourIdFichier() {
		$this->hand->setIdFichier('832615965');
		$this->assertEquals('832615965', $this->hand->getIdFichier());
	}
	
	public function testApplicationModelHandDoitAvoirUnAttributId() {
		$this->assertClassHasAttribute('_id', 'Application_Model_Hand');
	}
	
	public function testApplicationModelHandDoitAvoirUnSetterEtUnGetterPourId() {
		$this->hand->setId(12);
		$this->assertEquals(12, $this->hand->getId());
	}
	
	public function testApplicationModelHandDoitAvoirIdSbBbAnteEntiers() {
		$attributs = array('Id', 'Sb', 'Bb', 'Ante');
		foreach ($attributs as $attribut) {
			$setmethod = 'set'.$attribut;
			$getmethod = 'get'.$attribut;
			$this->hand->$setmethod('125');
			$this->assertEquals(125, $this->hand->$getmethod());
			$this->hand->$setmethod('sfsdfsdf');
			$this->assertEquals(0, $this->hand->$getmethod());
			$this->hand->$setmethod(-14526);
			$this->assertEquals(0, $this->hand->$getmethod());
		} 
	}
	
	public function testApplicationModelHandDoitAvoirIdFichierContentWinnerLevelStrings() {
		$attributs = array('IdFichier', 'Content', 'Winner', 'Level');
		foreach ($attributs as $attribut) {
			$setmethod = 'set'.$attribut;
			$getmethod = 'get'.$attribut;
			$this->hand->$setmethod(125);
			$this->assertEquals('125', $this->hand->$getmethod());
			$this->hand->$setmethod(-14526);
			$this->assertEquals('-14526', $this->hand->$getmethod());
		} 
	}
	
	public function testApplicationModelHandDoitAvoirCreatedRepresentantUneDateFormatBdd() {
		$this->hand->setCreated('bad timestamp');
		$this->assertRegExp('/[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/', $this->hand->getCreated());
	}
	
	public function testApplicationModelHandDoitLeverUneExceptionSiOnTenteDObtenirUnAttributInconnuViaGet() {
        try {
			$this->hand->__get('inconnu');
		} catch (Exception $expected) {
			$this->assertEquals('Invalid hand property', $expected->getMessage());
			return;
		}
		$this->fail("Une exception n'a pas ete levee alors qu'on tente d'obtenir un attribut inconnu via get.");
    }
	
	public function testApplicationModelHandDoitLeverUneExceptionSiOnTenteDeSetterUnAttributInconnuViaSet() {
        try {
			$this->hand->__set('inconnu', 'valeur');
		} catch (Exception $expected) {
			$this->assertEquals('Invalid hand property', $expected->getMessage());
			return;
		}
		$this->fail("Une exception n'a pas ete levee alors qu'on tente de setter un attribut inconnu via set.");
    }
    
    public function testApplicationModelHandDoitRetournerEtSetterLAttributViaGetEtSet() {
    	$this->hand->__set('bb', 300);
    	$this->assertEquals(300, $this->hand->__get('bb'));
    }
    
    public function testSetOptionsDoitPrendreUneListeDeParametresEtAppelerLesSettersAssocies() {
    	$hand = $this->getMock('Application_Model_Hand', array('setSb', 'setBb', 'setAnte', 'setIdFichier'));
    	$hand->expects($this->once())
    		 ->method('setSb')
    		 ->with($this->equalTo(10));
    	
		$hand->expects($this->once())
    		 ->method('setBb')
    		 ->with($this->equalTo(20));
    	
		$hand->expects($this->once())
    		 ->method('setAnte')
    		 ->with($this->equalTo(0));
    	
		$hand->expects($this->once())
    		 ->method('setIdFichier')
    		 ->with($this->equalTo('1452456'));
    	
		$hand->expects($this->never())
    		 ->method('setInconnu');
    	
    	$hand->setOptions(array('sb' => 10, 'bb' => 20, 'ante' => 0, 'id_fichier' => '1452456', 'inconnu' => 'bad attribut'));
    }
    
	public function testApplicationModelHandDoitPouvoirEtreInitialiserSansArgument() {
    	$new_hand_without_array = $this->getMock('Application_Model_Hand', array('setOptions'));
    	$new_hand_without_array->expects($this->never())
    		 				   ->method('setOptions');
    }
    
    public function testApplicationModelHandDoitPouvoirEtreInitialiserAvecUnArrayDAttribut() {
    	$new_hand_with_array = $this->getMockBuilder('Application_Model_Hand')
    								->disableOriginalConstructor()
    								->getMock();
    	
    	$new_hand_with_array->expects($this->once())
    		 				->method('setOptions')
    		 				->with($this->equalTo(array('sb' => 10, 'bb' => 20, 'ante' => 0, 'inconnu' => 'bad attribut')));
    	
    	$new_hand_with_array->__construct(array('sb' => 10, 'bb' => 20, 'ante' => 0, 'inconnu' => 'bad attribut'));
    }
}