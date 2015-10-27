<?php
App::uses('AppModel', 'Model');
//App::uses('Security', 'Utility'); 
//App::import('Component','Auth'); 
/**
 * User Model
 *
 * @property Item $Item
 */
 
class Param extends AppModel {	
	
/**
 * Display field
 *
 * @var string
 */
	//public $displayField = 'title';

/**
 * Validation rules
 *
 * @var array
 */


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
 
 	//public $belongsTo = array(
	//	'Manager'
	//);
	
	
	public function advanceWeek(){
		$time = $this->find('first', array('recursive' =>  -1,  'conditions' => array('Param.id' =>  1)));
		$week = (60*60*24*7);
		$time = strtotime($time['Param']['game_time']);
		$time = $time + $week;
		$game_time = date('Y-m-d', $time);
		$this->id = 1;
		$this->saveField('game_time', $game_time);
		return $game_time;
	}


}
