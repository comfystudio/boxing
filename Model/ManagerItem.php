<?php
App::uses('AppModel', 'Model');
//App::uses('Security', 'Utility'); 
//App::import('Component','Auth'); 
/**
 * User Model
 *
 * @property Item $Item
 */
 
class ManagerItem extends AppModel {	
	
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
		'Manager' => array(
			'className' => 'Manager',
			'foreignKey' => 'manager_id',
			'dependant' => false
			),
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'item_id',
			'dependant' => false
			)
	);
	
	public function deleteItems($manager_id = null){
		$items = $this->find('all', array('recursive' => -1, 'fields' => array('id', 'manager_id'), 'conditions' => array('ManagerItem.manager_id' => $manager_id)));
		foreach($items as $item){
			$this->id = $item['ManagerItem']['id'];
			$this->delete($item['ManagerItem']['id']);	
		}
	}

}
