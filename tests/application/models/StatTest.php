<?php
class StatTest extends Zend_Test_PHPUnit_ControllerTestCase {
	
	protected $stat;
	
	public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
        $this->stat = new Application_Model_Stat();
    }
	
	public function testApplicationModelStatDoitAvoirUnAttributIdPlayer() {
		$this->assertClassHasAttribute('_id_player', 'Application_Model_Stat');
	}
	
	public function testApplicationModelStatDoitAvoirUnSetterEtUnGetterPourIdPlayer() {
		$this->stat->setIdPlayer(1);
		$this->assertEquals(1, $this->stat->getIdPlayer());
	}
	
	public function testApplicationModelStatDoitAvoirUnAttributType() {
		$this->assertClassHasAttribute('_type', 'Application_Model_Stat');
	}
	
	public function testApplicationModelStatDoitAvoirUnSetterEtUnGetterPourType() {
		$this->stat->setType('type');
		$this->assertEquals('type', $this->stat->getType());
	}
	
	public function testApplicationModelStatDoitAvoirUnAttributValeur() {
		$this->assertClassHasAttribute('_valeur', 'Application_Model_Stat');
	}
	
	public function testApplicationModelStatDoitAvoirUnSetterEtUnGetterPourValeur() {
		$this->stat->setValeur('valeur');
		$this->assertEquals('valeur', $this->stat->getValeur());
	}
	
	public function testApplicationModelStatDoitAvoirUnAttributId() {
		$this->assertClassHasAttribute('_id', 'Application_Model_Stat');
	}
	
	public function testApplicationModelStatDoitAvoirUnSetterEtUnGetterPourId() {
		$this->stat->setId(12);
		$this->assertEquals(12, $this->stat->getId());
	}
	
	public function testApplicationModelStatDoitAvoirIdIdPlayerEntiers() {
		$attributs = array('Id', 'IdPlayer');
		foreach ($attributs as $attribut) {
			$setmethod = 'set'.$attribut;
			$getmethod = 'get'.$attribut;
			$this->stat->$setmethod('125');
			$this->assertEquals(125, $this->stat->$getmethod());
			$this->stat->$setmethod('sfsdfsdf');
			$this->assertEquals(0, $this->stat->$getmethod());
			$this->stat->$setmethod(-14526);
			$this->assertEquals(0, $this->stat->$getmethod());
		} 
	}
	
	public function testApplicationModelStatDoitAvoirTypeValeurStrings() {
		$attributs = array('Type', 'Valeur');
		foreach ($attributs as $attribut) {
			$setmethod = 'set'.$attribut;
			$getmethod = 'get'.$attribut;
			$this->stat->$setmethod(125);
			$this->assertEquals('125', $this->stat->$getmethod());
			$this->stat->$setmethod(-14526);
			$this->assertEquals('-14526', $this->stat->$getmethod());
		} 
	}
	
	public function testApplicationModelStatDoitLeverUneExceptionSiOnTenteDObtenirUnAttributInconnuViaGet() {
        try {
			$this->stat->__get('inconnu');
		} catch (Exception $expected) {
			$this->assertEquals('Invalid stat property', $expected->getMessage());
			return;
		}
		$this->fail("Une exception n'a pas ete levee alors qu'on tente d'obtenir un attribut inconnu via get.");
    }
	
	public function testApplicationModelStatDoitLeverUneExceptionSiOnTenteDeSetterUnAttributInconnuViaSet() {
        try {
			$this->stat->__set('inconnu', 'valeur');
		} catch (Exception $expected) {
			$this->assertEquals('Invalid stat property', $expected->getMessage());
			return;
		}
		$this->fail("Une exception n'a pas ete levee alors qu'on tente de setter un attribut inconnu via set.");
    }
    
    public function testApplicationModelPlayerDoitRetournerEtSetterLAttributViaGetEtSet() {
    	$this->stat->__set('valeur', '152');
    	$this->assertEquals('152', $this->stat->__get('valeur'));
    }
    
    public function testSetOptionsDoitPrendreUneListeDeParametresEtAppelerLesSettersAssocies() {
    	$stat = $this->getMock('Application_Model_Stat', array('setValeur', 'setType'));
    	$stat->expects($this->once())
    		   ->method('setValeur')
			   ->with($this->equalTo('12 %'));
    	
		$stat->expects($this->once())
    		   ->method('setType')
    		   ->with($this->equalTo('nombre de flops vu au bouton'));
    	
		$stat->expects($this->never())
    		   ->method('setInconnu');
    	
    	$stat->setOptions(array('valeur' => '12 %', 'type' => 'nombre de flops vu au bouton', 'inconnu' => 'bad attribut'));
    }
    
	public function testApplicationModelStatDoitPouvoirEtreInitialiserSansArgument() {
    	$new_stat_without_array = $this->getMock('Application_Model_Stat', array('setOptions'));
    	$new_stat_without_array->expects($this->never())
    		 				   ->method('setOptions');
    }
    
    public function testApplicationModelStatDoitPouvoirEtreInitialiserAvecUnArrayDAttribut() {
    	$new_stat_with_array = $this->getMockBuilder('Application_Model_Stat')
    								->disableOriginalConstructor()
    								->getMock();
    	
    	$new_stat_with_array->expects($this->once())
    		 				->method('setOptions')
    		 				->with($this->equalTo(array('valeur' => '12 %', 'type' => 'nombre de flops vu au bouton', 'inconnu' => 'bad attribut')));
    	
    	$new_stat_with_array->__construct(array('valeur' => '12 %', 'type' => 'nombre de flops vu au bouton', 'inconnu' => 'bad attribut'));
    }
}