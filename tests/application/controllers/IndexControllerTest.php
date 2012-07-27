<?php

class IndexControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{

    public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
    }

    public function testIndexContientLeNomDeLApplication()
    {
        $this->dispatch('/');
        $this->assertModule('default');
        $this->assertController('index');
        $this->assertAction('index');
        $this->assertQueryContentContains("div#main h1", "Poker Hud Tracker");
    }
    
    public function testIndexContientUnFormulaireDeRechercheDeJoueur() {
    	$this->dispatch('/');
    	$this->assertQueryContentContains("div#main h2", "Rechercher un joueur");
    	$this->assertQuery('div#main form#search');
    	$this->assertQueryContentContains('div#main form#search label', 'Nom :');
    	$this->assertQuery('div#main form#search input#player');
    	$this->assertQuery('div#main form#search input#chercher');
    }
}
