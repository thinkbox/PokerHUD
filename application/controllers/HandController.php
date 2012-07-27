<?php
class HandController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	
    	$hand = new Application_Model_Hand();
    	$hand->setSb(10);
    	echo var_export($hand->getSb(), true);
        /*$mapper = new Application_Model_HandMapper();
    	
    	$hand = new Hud_HandAnalyser('83426525740');
    	$data = $hand->getInfos();
    	$hand_model = new Application_Model_Hand($data);
    	$hand_model->setSb($data['small blind']);
    	$hand_model->setBb($data['big blind']);
    	$hand_model->setIdFichier($hand->getIdHand());
    	$hand_model->setContent($hand->getHand());
    	
    	echo var_export($hand_model->getIdFichier(), true);*/
    }
}