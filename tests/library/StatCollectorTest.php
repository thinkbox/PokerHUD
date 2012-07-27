<?php
class StatCollectorTest extends PokerHUDMasterTest {
    private $collector;
    private $mappers;

    protected function setUp() {
        $this->collector = new Hud_StatCollector();
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        $this->mappers = $this->getMappersMocks();
    }
    
    public function testCollectorDoitAvoirUnAttributAction() {
    	$this->assertClassHasAttribute('action', 'Hud_StatCollector');
    }
    
    public function testActionDoitEtreNullParDefaut() {
    	$this->assertNull($this->collector->getAction());
    }
    
    public function testSetActionDoitEnregistrerLActionPasseEnParametreALAttributAction() {
    	$action = new Application_Model_Action(array(
			'id' => 19,
    		'name_player' => 'john'
    	));
    	$this->collector->setAction($action);
    	$this->assertEquals($action, $this->collector->getAction());
    }
    
    public function testCollectorDoitAvoirUnAttributIdJoueur() {
    	$this->assertClassHasAttribute('id_joueur', 'Hud_StatCollector');
    }
    
    public function testIdJoueurDoitEtreNullParDefaut() {
    	$this->assertNull($this->collector->getIdJoueur());
    }
    
    public function testSetIdJoueurDoitEnregistrerLIdJoueurPasseEnParametreALAttributIdJoueur() {
    	$this->collector->setIdJoueur(22);
    	$this->assertEquals(22, $this->collector->getIdJoueur());
    }
    
    public function testCollectDoitRetournerFalseSiAucuneMainAAnalyser() {
    	$this->mappers['action_mapper']->expects($this->once())
    		   					 	   ->method('getNextToTreat')
    		   					 	   ->will($this->returnValue(null));
    	
    	$this->mappers['action_mapper']->expects($this->never())
						    	 	   ->method('markAsTreated');
    	
    	$this->mappers['player_mapper']->expects($this->never())
    							 	   ->method('save');
    	
    	$this->assertFalse($this->collector->collect($this->mappers['action_mapper'], $this->mappers['player_mapper']));
    }
	
	public function testCollectDoitAppelerApplicationModelPlayerMapperSaveToutEnSetantLAttributActionEtIdJoueur() {
    	$this->mappers['action_mapper']->expects($this->once())
								 	   ->method('getNextToTreat')
								 	   ->will($this->returnValue(new Application_Model_Action(array(
											'id' => 19,
											'name_player' => 'john'
								 	   ))));
		 
		$this->mappers['action_mapper']->expects($this->once())
								 	   ->method('markAsTreated');
		 
		$this->mappers['player_mapper']->expects($this->once())
								 	   ->method('save')
								 	   ->with($this->equalTo(new Application_Model_Player(array(
							   			   'name' => 'john'
							   	 	   ))))
									   ->will($this->returnValue(23));
		
		$collector = $this->getMock('Hud_StatCollector', array('setAction', 'setIdJoueur', 'getStats'));
		$collector->expects($this->once())
				  ->method('setAction')
				  ->with($this->equalTo(new Application_Model_Action(array(
						'id' => 19,
						'name_player' => 'john'
			 	  ))));
		$collector->expects($this->once())
				  ->method('setIdJoueur')
				  ->with($this->equalTo(23));
		
		$action_traite_id = $collector->collect($this->mappers['action_mapper'], $this->mappers['player_mapper']);
		$this->assertEquals(19, $action_traite_id);
	}
	
	public function testCollectDoitAppelerApplicationModelActionMapperMarkAsTreated() {
    	$this->mappers['action_mapper']->expects($this->once())
								 	   ->method('getNextToTreat')
								 	   ->will($this->returnValue(new Application_Model_Action(array(
										   'id' => 19,
										   'name_player' => 'john'
								 	   ))));
		 
		$this->mappers['action_mapper']->expects($this->once())
								 	   ->method('markAsTreated')
			   					 	   ->with($this->equalTo(19));
		 
		$this->mappers['player_mapper']->expects($this->once())
								 	   ->method('save');
		 
		$action_traite_id = $this->collector->collect($this->mappers['action_mapper'], $this->mappers['player_mapper']);
		$this->assertEquals(19, $action_traite_id);
	}
	
	public function testCollectDoitAppelerGetStats() {
		$this->mappers['action_mapper']->expects($this->once())
									   ->method('getNextToTreat')
								 	   ->will($this->returnValue(new Application_Model_Action(array(
										   'id' => 19,
										   'name_player' => 'john'
								 	   ))));
			
		$this->mappers['action_mapper']->expects($this->once())
									   ->method('markAsTreated');
			
		$this->mappers['player_mapper']->expects($this->once())
									   ->method('save');
		
		$collector = $this->getMock('Hud_StatCollector', array('getStats'));
		$collector->expects($this->once())
				  ->method('getStats');
		
		$collector->collect($this->mappers['action_mapper'], $this->mappers['player_mapper']);
	}
	
	public function testGetStatsDoitAppelerUpdateStatNombreDeMainsJoueesEtFoldeesPreflopSiLeJoueurAAgiPreflopAvantDeFolderSansVoirLeFlop() {
		$this->collector->setAction(new Application_Model_Action(array(
			'id' => 20,
			'action_preflop' => 'raise, fold',
			'resultat' => 'fold'
		)));
		
		$this->collector->setIdJoueur(19);
		
		$mapper = $this->getMock('Application_Model_StatMapper', array('updateStat'));
		$mapper->expects($this->at(0))
			   ->method('updateStat')
		       ->with($this->equalTo(19), $this->equalTo('nombre de mains jouees et foldees preflop'));
		
		$this->collector->getStats($mapper);
	}
	
	public function testGetStatsDoitAppelerApplicationModelStatMapperUpdateStatNombreDeFlopsVusSiActionPreflopNeContientPasFold() {
		$this->collector->setAction(new Application_Model_Action(array(
			'id' => 20,
			'action_preflop' => 'call'
		)));
		
		$this->collector->setIdJoueur(24);
		
		$mapper = $this->getMock('Application_Model_StatMapper', array('updateStat'));
		$mapper->expects($this->at(0))
			   ->method('updateStat')
			   ->with($this->equalTo(24), $this->equalTo('nombre de flops vus'));
		
		$this->collector->getStats($mapper);
	}
	
	public function testGetStatsDoitAppelerUpdateStatNombreDeFlopsVusAuBoutonSiPositionBoutonEtActionPreflopNeContientPasFold() {
		$this->collector->setAction(new Application_Model_Action(array(
			'id' => 20,
			'action_preflop' => 'call',
			'position' => 'button'
		)));
		
		$this->collector->setIdJoueur(14);
		
		$mapper = $this->getMock('Application_Model_StatMapper', array('updateStat'));
		$mapper->expects($this->at(1))
			   ->method('updateStat')
			   ->with($this->equalTo(14), $this->equalTo('nombre de flops vus au bouton'));
		
		$this->collector->getStats($mapper);
	}
	
	public function testGetStatsDoitAppelerUpdateStatNombreDeFlopsVusEnSmallBlindSiPositionSmallBlindEtActionPreflopNeContientPasFold() {
		$this->collector->setAction(new Application_Model_Action(array(
			'id' => 20,
			'action_preflop' => 'call',
			'position' => 'small blind'
		)));
		
		$this->collector->setIdJoueur(14);
		
		$mapper = $this->getMock('Application_Model_StatMapper', array('updateStat'));
		$mapper->expects($this->at(1))
			   ->method('updateStat')
			   ->with($this->equalTo(14), $this->equalTo('nombre de flops vus en small blind'));
		
		$this->collector->getStats($mapper);
	}
	
	public function testGetStatsDoitAppelerUpdateStatNombreDeFlopsVusEnBigBlindSiPositionBigBlindEtActionPreflopNeContientPasFold() {
		$this->collector->setAction(new Application_Model_Action(array(
			'id' => 20,
			'action_preflop' => 'call',
			'position' => 'big blind'
		)));
		
		$this->collector->setIdJoueur(14);
		
		$mapper = $this->getMock('Application_Model_StatMapper', array('updateStat'));
		$mapper->expects($this->at(1))
			   ->method('updateStat')
			   ->with($this->equalTo(14), $this->equalTo('nombre de flops vus en big blind'));
		
		$this->collector->getStats($mapper);
	}
	
	public function testGetStatsDoitAppelerUpdateStatNombreDeMainsGagneesAuShowdownSiResultatEstWonAtShowdown() {
		$this->collector->setAction(new Application_Model_Action(array(
			'id' => 20,
			'action_preflop' => 'call',
			'resultat' => 'won at showdown'
		)));
		
		$this->collector->setIdJoueur(14);
		
		$mapper = $this->getMock('Application_Model_StatMapper', array('updateStat'));
		$mapper->expects($this->at(1))
			   ->method('updateStat')
			   ->with($this->equalTo(14), $this->equalTo('nombre de mains gagnees au showdown'));
		
		$this->collector->getStats($mapper);
	}
	
	public function testGetStatsDoitAppelerUpdateStatNombreDeMainsGagneesAvantLeShowdownSiResultatEstWonBeforeShowdown() {
		$this->collector->setAction(new Application_Model_Action(array(
			'id' => 20,
			'action_preflop' => 'call',
			'resultat' => 'won before showdown'
		)));
		
		$this->collector->setIdJoueur(14);
		
		$mapper = $this->getMock('Application_Model_StatMapper', array('updateStat'));
		$mapper->expects($this->at(1))
			   ->method('updateStat')
			   ->with($this->equalTo(14), $this->equalTo('nombre de mains gagnees avant le showdown'));
		
		$this->collector->getStats($mapper);
	}
	
	public function testGetStatsDoitAppelerUpdateStatNombreDeMainsPerduesAuShowdownSiResultatEstLost() {
		$this->collector->setAction(new Application_Model_Action(array(
			'id' => 20,
			'action_preflop' => 'call',
			'resultat' => 'lost'
		)));
		
		$this->collector->setIdJoueur(14);
		
		$mapper = $this->getMock('Application_Model_StatMapper', array('updateStat'));
		$mapper->expects($this->at(1))
			   ->method('updateStat')
			   ->with($this->equalTo(14), $this->equalTo('nombre de mains perdues au showdown'));
		
		$this->collector->getStats($mapper);
	}
	
	public function testCollectAgainDoitRetournerFalseSiAucuneMainNEstAAnalyserUneNouvelleFois() {
    	$this->mappers['action_mapper']->expects($this->once())
    		   					 	   ->method('getNextToTreatAgain')
    		   					 	   ->will($this->returnValue(null));
    	
    	$this->mappers['action_mapper']->expects($this->never())
						    	 	   ->method('markAsTreated');
    	
    	$this->mappers['player_mapper']->expects($this->never())
    							 	   ->method('playerExists');
    	
    	$this->assertFalse($this->collector->collectAgain('nombre de flops vus', date('Y-m-d H:i:s'), $this->mappers['action_mapper'], $this->mappers['player_mapper']));
    }
	
	public function testCollectAgainDoitRetournerFalseEnAyantUpdateLActionAssocieeSiLeJoueurAssocieALaMainATraiteANouveauNExistePas() {
    	$this->mappers['action_mapper']->expects($this->once())
								 	   ->method('getNextToTreatAgain')
								 	   ->will($this->returnValue(new Application_Model_Action(array(
											'id' => 19,
											'name_player' => 'john'
								 	   ))));
    	
    	$this->mappers['action_mapper']->expects($this->once())
						    	 	   ->method('markAsTreated')
			   					 	   ->with($this->equalTo(19));
    	
    	$this->mappers['player_mapper']->expects($this->once())
    							 	   ->method('playerExists')
			   					 	   ->with($this->equalTo('john'))
			   					 	   ->will($this->returnValue(null));
    	
    	$this->assertFalse($this->collector->collectAgain('nombre de flops vus', date('Y-m-d H:i:s'), $this->mappers['action_mapper'], $this->mappers['player_mapper']));
    }
	
	public function testCollectAgainDoitAppelerGetStatsAvecLeTypePasseEnParametre() {
		$this->mappers['action_mapper']->expects($this->once())
								 	   ->method('getNextToTreatAgain')
								 	   ->will($this->returnValue(new Application_Model_Action(array(
											'id' => 19,
											'name_player' => 'john',
											'action_preflop' => 'raise'
								 	   ))));
    	
    	$this->mappers['action_mapper']->expects($this->once())
						    	 	   ->method('markAsTreated')
			   					 	   ->with($this->equalTo(19));
    	
    	$this->mappers['player_mapper']->expects($this->once())
    							 	   ->method('playerExists')
			   					 	   ->with($this->equalTo('john'))
			   					 	   ->will($this->returnValue(new Application_Model_Player(array(
											'id' => 63,
											'name' => 'john'
								 	   ))));
    	
    	$collector = $this->getMock('Hud_StatCollector', array('getStats'));
    	$collector->expects($this->once())
    		      ->method('getStats')
    			  ->with($this->equalTo(new Application_Model_StatMapper()), $this->equalTo('nombre de flops vus'));
		
		$collector->collectAgain('nombre de flops vus', date('Y-m-d H:i:s'), $this->mappers['action_mapper'], $this->mappers['player_mapper']);
	}
	
	public function testGetStatsDoitAppelerUneSeuleFoisUpdateStatAvecLeTypeDonneEnParametre() {
		$this->mappers['action_mapper']->expects($this->once())
								 	   ->method('getNextToTreatAgain')
								 	   ->will($this->returnValue(new Application_Model_Action(array(
											'id' => 19,
											'name_player' => 'john',
											'action_preflop' => 'raise',
											'position' => 'button',
											'resultat' => 'won before showdown'
								 	   ))));
    	
    	$this->mappers['action_mapper']->expects($this->once())
						    	 	   ->method('markAsTreated')
			   					 	   ->with($this->equalTo(19));
    	
    	$this->mappers['player_mapper']->expects($this->once())
    							 	   ->method('playerExists')
			   					 	   ->with($this->equalTo('john'))
			   					 	   ->will($this->returnValue(new Application_Model_Player(array(
											'id' => 63,
											'name' => 'john'
								 	   ))));
    	
    	$mapper = $this->getMock('Application_Model_StatMapper', array('updateStat'));
    	$mapper->expects($this->once())
    		   ->method('updateStat')
    		   ->with($this->equalTo(63), $this->equalTo('nombre de mains gagnees avant le showdown'));
		
		$this->collector->collectAgain('nombre de mains gagnees avant le showdown', date('Y-m-d H:i:s'), $this->mappers['action_mapper'], $this->mappers['player_mapper'], $mapper);
	}
    
    private function getMappersMocks() {
    	$action_mapper = $this->getMock('Application_Model_ActionMapper', array('getNextToTreat', 'markAsTreated', 'getNextToTreatAgain'));
    	$player_mapper = $this->getMock('Application_Model_PlayerMapper', array('save', 'playerExists'));
    	
    	return array('action_mapper' => $action_mapper, 'player_mapper' => $player_mapper);
    }
}