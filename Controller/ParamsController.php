<?php
App::uses('AppController', 'Controller');
//App::import('Component','Auth');
//App::uses('CakeEmail', 'Network/Email');
/**
 * Activities Controller
 *
 * @property Activity $Activity
 */
class ParamsController extends AppController {
	
	public function getGameTime(){
		if($this->params['requested']){
			$gameTime = $this->Param->find('first', array('conditions' => array('Param.id' => 1)));	
			return $gameTime['Param']['game_time'];
		}
	}
	
	public function getAge($date= null){
		if($this->params['requested']){
			$gameTime = $this->Param->find('first', array('conditions' => array('Param.id' => 1)));
			$today = strtotime($gameTime['Param']['game_time']);
			$year = (60*60*24*365.35);
			$difference = ($today - strtotime($date));
			$age = $difference / $year ;
			$age = floor($age);
			
			return $age;	
		}
	}
	
	public function getInjured($date = null){
		if($this->params['requested']){
			$gameTime = $this->Param->find('first', array('conditions' => array('Param.id' => 1)));
			$date =  strtotime($date);
			$today = strtotime($gameTime['Param']['game_time']);
			$difference =  $date - $today;
			$weeks = floor($difference / (60*60*24*7));
			return $weeks;	
		}	
	}

}