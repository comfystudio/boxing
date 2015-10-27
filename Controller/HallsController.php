<?php
App::uses('AppController', 'Controller');

class HallsController extends AppController {
	
	public function index(){
		$fame = $this->Hall->find('first', array('recursive' => -1, 'conditions' => array('type' => '20')));
		
		$wins = $this->Hall->find('first', array('recursive' => -1, 'conditions' => array('type' => '21')));
		
		$loses = $this->Hall->find('first', array('recursive' => -1, 'conditions' => array('type' => '22')));
		
		$draws = $this->Hall->find('first', array('recursive' => -1, 'conditions' => array('type' => '23')));
		
		$knockouts = $this->Hall->find('first', array('recursive' => -1, 'conditions' => array('type' => '24')));
		
		$floored = $this->Hall->find('first', array('recursive' => -1, 'conditions' => array('type' => '25')));
		
		$Flyweight = $this->Hall->find('all', array('recursive' => -1, 'conditions' => array('type' => '2'), 'order' => 'Hall.game_date_start DESC', 'limit' => 10));
		
		$Middleweight = $this->Hall->find('all', array('recursive' => -1, 'conditions' => array('type' => '12'), 'order' => 'Hall.game_date_start DESC', 'limit' => 10));
		
		$Heavyweight = $this->Hall->find('all', array('recursive' => -1, 'conditions' => array('type' => '16'), 'order' => 'Hall.game_date_start DESC', 'limit' => 10));
		
		$weightClasses = Configure::read('Weight.class');
		
		//$game_time = $this->requestAction('/params/getGameTime');
		
		$this->set(compact('fame', 'wins', 'loses', 'draws', 'knockouts', 'floored', 'Flyweight', 'Middleweight', 'Heavyweight', 'weightClasses'));
	}

    public function listed($weight = null){
        if(!isset($weight) || $weight == null){
            $this->Session->setFlash('This weight class does not exist in this universe >_<');
            $this->redirect($this->referer());
        }
        $boxers = $this->Hall->find('all', array('recursive' => -1, 'conditions' => array('type' => $weight), 'order' => 'Hall.game_date_start DESC'));
        if(empty($boxers) || $boxers == null){
            $this->Session->setFlash('No boxers seem to have ever been champions at this weight class');
            $this->redirect($this->referer());
        }
        $weightClasses = Configure::read('Weight.class');

        $this->set(compact('boxers', 'weightClasses', 'weight'));

    }
	
	public function view($id = null) {
		$this->Hall->id = $id;
		if (!$this->Hall->exists()) {
           $this->Session->setFlash('This boxer does not appear to exist sorry');
		   $this->redirect($this->referer());
        } else {
			$this->loadModel('Boxer');
			$weightClasses = Configure::read('Weight.class');
			$regions = Configure::read('Region');
			$game_time = $this->requestAction('/params/getGameTime');
			$boxer = $this->Hall->find('first', array('recursive' => -1, 'conditions' => array('Hall.id' => $id)));
			$boxerRetired = $this->Boxer->find('first', array('conditions' => array('Boxer.id' => $boxer['Hall']['boxer_id']), 'recursive' => -1, 'fields' => array('Boxer.id', 'Boxer.retired')));
			$this->set(compact('boxer', 'regions', 'weightClasses', 'game_time', 'boxerRetired'));
		}
	}
	
	
}