<?php
App::uses('AppController', 'Controller');
//App::import('Component','Auth');
//App::uses('CakeEmail', 'Network/Email');
/**
 * Activities Controller
 *
 * @property Activity $Activity
 */
class NamesController extends AppController {
	
	/*public function getGameTime(){
		if($this->params['requested']){
			$gameTime = $this->Param->find('first', array('conditions' => array('Param.id' => 1)));
			return strtotime($gameTime['Param']['game_time']);	
		}
	}
	
	public function getAge($date){
		if($this->params['requested']){
			$gameTime = $this->Param->find('first', array('conditions' => array('Param.id' => 1)));
			$today = strtotime($gameTime['Param']['game_time']);
			$year = (60*60*24*365.35);
			$difference = ($today - strtotime($date));
			$age = $difference / $year ;
			$age = floor($age);
			
			return $age;	
		}
	}*/
	
	public function getName($forname, $surname){
		if($this->params['requested']){
			$firstName = $this->Name->find('first', array('recursive' => -1, 'conditions' => array('Name.id' => $forname)));
			$secondName = $this->Name->find('first', array('recursive' => -1, 'conditions' => array('Name.id' => $surname)));
			$name = $firstName['Name']['name'].' '.$secondName['Name']['name'];
			return $name;	
		}
		
	}

}