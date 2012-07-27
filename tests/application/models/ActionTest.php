<?php
class ActionTest extends Zend_Test_PHPUnit_ControllerTestCase {
	
	protected $action;
	
	public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
        $this->action = new Application_Model_Action();
    }
	
	public function testApplicationModelActionDoitAvoirUnAttributCreated() {
		$this->assertClassHasAttribute('_created', 'Application_Model_Action');
	}
	
	public function testApplicationModelActionDoitAvoirUnSetterEtUnGetterPourCreated() {
		$date = '2012-07-18 15:13:54';
		$this->action->setCreated($date);
		$this->assertEquals($date, $this->action->getCreated());
	}
	
	public function testApplicationModelActionDoitAvoirUnAttributUpdated() {
		$this->assertClassHasAttribute('_updated', 'Application_Model_Action');
	}
	
	public function testApplicationModelActionDoitAvoirUnSetterEtUnGetterPourUpdated() {
		$date = '2012-07-18 15:13:54';
		$this->action->setUpdated($date);
		$this->assertEquals($date, $this->action->getUpdated());
	}
	
	public function testApplicationModelActionDoitAvoirUnAttributTreated() {
		$this->assertClassHasAttribute('_treated', 'Application_Model_Action');
	}
	
	public function testApplicationModelActionDoitAvoirUnSetterEtUnGetterPourTreated() {
		$this->action->setTreated(1);
		$this->assertEquals(1, $this->action->getTreated());
	}
	
	public function testApplicationModelActionDoitAvoirUnAttributResultat() {
		$this->assertClassHasAttribute('_resultat', 'Application_Model_Action');
	}
	
	public function testApplicationModelActionDoitAvoirUnSetterEtUnGetterPourResultat() {
		$this->action->setResultat('resultat');
		$this->assertEquals('resultat', $this->action->getResultat());
	}
	
	public function testApplicationModelActionDoitAvoirUnAttributActionRiver() {
		$this->assertClassHasAttribute('_action_river', 'Application_Model_Action');
	}
	
	public function testApplicationModelActionDoitAvoirUnSetterEtUnGetterPourActionRiver() {
		$this->action->setActionRiver('action');
		$this->assertEquals('action', $this->action->getActionRiver());
	}
	
	public function testApplicationModelActionDoitAvoirUnAttributActionTurn() {
		$this->assertClassHasAttribute('_action_turn', 'Application_Model_Action');
	}
	
	public function testApplicationModelActionDoitAvoirUnSetterEtUnGetterPourActionTurn() {
		$this->action->setActionTurn('action');
		$this->assertEquals('action', $this->action->getActionTurn());
	}
	
	public function testApplicationModelActionDoitAvoirUnAttributActionFlop() {
		$this->assertClassHasAttribute('_action_flop', 'Application_Model_Action');
	}
	
	public function testApplicationModelActionDoitAvoirUnSetterEtUnGetterPourActionFlop() {
		$this->action->setActionFlop('action');
		$this->assertEquals('action', $this->action->getActionFlop());
	}
	
	public function testApplicationModelActionDoitAvoirUnAttributActionPreflop() {
		$this->assertClassHasAttribute('_action_preflop', 'Application_Model_Action');
	}
	
	public function testApplicationModelActionDoitAvoirUnSetterEtUnGetterPourActionPreflop() {
		$this->action->setActionPreflop('action');
		$this->assertEquals('action', $this->action->getActionPreflop());
	}
	
	public function testApplicationModelActionDoitAvoirUnAttributPosition() {
		$this->assertClassHasAttribute('_position', 'Application_Model_Action');
	}
	
	public function testApplicationModelActionDoitAvoirUnSetterEtUnGetterPourPosition() {
		$this->action->setPosition('position');
		$this->assertEquals('position', $this->action->getPosition());
	}
	
	public function testApplicationModelActionDoitAvoirUnAttributNamePlayer() {
		$this->assertClassHasAttribute('_name_player', 'Application_Model_Action');
	}
	
	public function testApplicationModelActionDoitAvoirUnSetterEtUnGetterPourNamePlayer() {
		$this->action->setNamePlayer('name');
		$this->assertEquals('name', $this->action->getNamePlayer());
	}
	
	public function testApplicationModelActionDoitAvoirUnAttributIdHand() {
		$this->assertClassHasAttribute('_id_hand', 'Application_Model_Action');
	}
	
	public function testApplicationModelActionDoitAvoirUnSetterEtUnGetterPourIdHand() {
		$this->action->setIdHand(1245);
		$this->assertEquals(1245, $this->action->getIdHand());
	}
	
	public function testApplicationModelActionDoitAvoirUnAttributId() {
		$this->assertClassHasAttribute('_id', 'Application_Model_Action');
	}
	
	public function testApplicationModelActionDoitAvoirUnSetterEtUnGetterPourId() {
		$this->action->setId(12);
		$this->assertEquals(12, $this->action->getId());
	}
	
	public function testApplicationModelActionDoitAvoirIdIdHandTreatedEntiers() {
		$attributs = array('Id', 'IdHand', 'Treated');
		foreach ($attributs as $attribut) {
			$setmethod = 'set'.$attribut;
			$getmethod = 'get'.$attribut;
			$this->action->$setmethod('125');
			$this->assertEquals(125, $this->action->$getmethod());
			$this->action->$setmethod('sfsdfsdf');
			$this->assertEquals(0, $this->action->$getmethod());
			$this->action->$setmethod(-14526);
			$this->assertEquals(0, $this->action->$getmethod());
		} 
	}
	
	public function testApplicationModelActionDoitAvoirNamePlayerPositionActionsResultatStrings() {
		$attributs = array('NamePlayer', 'Position', 'ActionPreflop', 'ActionFlop', 'ActionTurn', 'ActionRiver', 'Resultat');
		foreach ($attributs as $attribut) {
			$setmethod = 'set'.$attribut;
			$getmethod = 'get'.$attribut;
			$this->action->$setmethod(125);
			$this->assertEquals('125', $this->action->$getmethod());
			$this->action->$setmethod(-14526);
			$this->assertEquals('-14526', $this->action->$getmethod());
		} 
	}
	
	public function testApplicationModelActionDoitAvoirCreatedEtUpdatedRepresentantUneDateFormatBdd() {
		$this->action->setCreated('bad timestamp');
		$this->assertRegExp('/[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/', $this->action->getCreated());
		$this->action->setUpdated('bad timestamp');
		$this->assertRegExp('/[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/', $this->action->getUpdated());
	}
	
	public function testApplicationModelActionDoitLeverUneExceptionSiOnTenteDObtenirUnAttributInconnuViaGet() {
        try {
			$this->action->__get('inconnu');
		} catch (Exception $expected) {
			$this->assertEquals('Invalid action property', $expected->getMessage());
			return;
		}
		$this->fail("Une exception n'a pas ete levee alors qu'on tente d'obtenir un attribut inconnu via get.");
    }
	
	public function testApplicationModelActionDoitLeverUneExceptionSiOnTenteDeSetterUnAttributInconnuViaSet() {
        try {
			$this->action->__set('inconnu', 'valeur');
		} catch (Exception $expected) {
			$this->assertEquals('Invalid action property', $expected->getMessage());
			return;
		}
		$this->fail("Une exception n'a pas ete levee alors qu'on tente de setter un attribut inconnu via set.");
    }
    
    public function testApplicationModelActionDoitRetournerEtSetterLAttributViaGetEtSet() {
    	$this->action->__set('resultat', 'won');
    	$this->assertEquals('won', $this->action->__get('resultat'));
    }
    
    public function testSetOptionsDoitPrendreUneListeDeParametresEtAppelerLesSettersAssocies() {
    	$action = $this->getMock('Application_Model_Action', array('setPosition', 'setActionFlop', 'setIdHand'));
    	$action->expects($this->once())
    		   ->method('setPosition')
			   ->with($this->equalTo('small blind'));
    	
		$action->expects($this->once())
    		   ->method('setActionFlop')
    		   ->with($this->equalTo('fold'));
    	
		$action->expects($this->once())
    		   ->method('setIdHand')
    		   ->with($this->equalTo(12));
    	
		$action->expects($this->never())
    		   ->method('setInconnu');
    	
    	$action->setOptions(array('position' => 'small blind', 'action_flop' => 'fold', 'id_hand' => 12, 'inconnu' => 'bad attribut'));
    }
    
	public function testApplicationModelActionDoitPouvoirEtreInitialiserSansArgument() {
    	$new_action_without_array = $this->getMock('Application_Model_Action', array('setOptions'));
    	$new_action_without_array->expects($this->never())
    		 				     ->method('setOptions');
    }
    
    public function testApplicationModelActionDoitPouvoirEtreInitialiserAvecUnArrayDAttribut() {
    	$new_action_with_array = $this->getMockBuilder('Application_Model_Action')
    								  ->disableOriginalConstructor()
    								  ->getMock();
    	
    	$new_action_with_array->expects($this->once())
    		 				  ->method('setOptions')
    		 				  ->with($this->equalTo(array('position' => 'small blind', 'action_flop' => 'fold', 'id_hand' => 12, 'inconnu' => 'bad attribut')));
    	
    	$new_action_with_array->__construct(array('position' => 'small blind', 'action_flop' => 'fold', 'id_hand' => 12, 'inconnu' => 'bad attribut'));
    }
}