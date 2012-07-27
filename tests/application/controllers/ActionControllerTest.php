<?php

class ActionControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{
    public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
    }

    public function testIndexContientLeNomDeLApplicationEtUnSousTitre()
    {
        $this->dispatch('/action');
        $this->assertModule('default');
        $this->assertController('action');
        $this->assertAction('index');
        $this->assertQueryContentContains("div#main h2", "Gestion des actions");
    }
}
