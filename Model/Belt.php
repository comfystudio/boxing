<?php
App::uses('AppModel', 'Model');
//App::uses('Security', 'Utility'); 
//App::import('Component','Auth'); 
/**
 * User Model
 *
 * @property Item $Item
 */
 
class Belt extends AppModel {	
	
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
	public $validate = array(
		
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Boxer' => array(
			'className' => 'Boxer',
			'foreignKey' => 'boxer_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
	public function removeBoxer($boxer_id = null){
		$belts = $this->find('all', array('recursive' => -1, 'fields' => array('id', 'boxer_id'), 'conditions' => array('Belt.boxer_id' => $boxer_id)));
		foreach ($belts as $belt){
			$this->id = $belt['Belt']['id'];
			$this->delete($belt['Belt']['id']);	
		}
	}
	
	public function updateBelt($weight_type = null){
		$belts = $this->find('all', array('recursive' => -1, 'fields' => array('id', 'weight_type', 'position'), 'conditions' => array('weight_type' => $weight_type), 'order' => array('position ASC')));
		$count = 1;
		foreach($belts as $belt){
			$this->id = $belt['Belt']['id'];
			$this->saveField('position', $count);
			$count++;	
		}
	}
	
	//after a fight, pass in winner and loser boxer ids, and the best rank[0] and worst rank[1]
	public function fightUpdateBelt($winnerId, $loserId, $rank){
		$winnerBelt = $this->find('first', array('recursive' => -1, 'conditions' => array('boxer_id' => $winnerId)));
		$loserBelt = $this->find('first', array('recursive' => -1, 'conditions' => array('boxer_id' => $loserId)));
		$this->id = $winnerBelt['Belt']['id'];
		$this->saveField('position', $rank[0]);
		
		$this->id = $loserBelt['Belt']['id'];
		$this->saveField('position', $rank[1]);		
	}
	
	//takes in a boxer id, new rank and weight type checks if a belt field exists for that boxer if so update their rank or create a belt field for them.
	public function updateBeltsBasedOnRank($id,  $rank, $weight_type){
		$belt = $this->find('first', array('conditions' => array('boxer_id' => $id), 'fields' => array('boxer_id', 'position', 'id'), 'recursive' => -1));
		if(isset($belt) && !empty($belt)){
			$this->id = $belt['Belt']['id'];
			$this->saveField('position', $rank);	
		}else{
			$data = array();
			$data['Belt']['boxer_id'] = $id;
			$data['Belt']['position'] = $rank;
			$data['Belt']['weight_type'] = $weight_type;
			$this->create();
			$this->save($data);
		}
	}
}
