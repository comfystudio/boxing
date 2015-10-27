<?php
App::uses('AppModel', 'Model');
//App::uses('Security', 'Utility'); 
//App::import('Component','Auth'); 
/**
 * User Model
 *
 * @property Item $Item
 */
 
class Feed extends AppModel {	
	
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
		'content' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Feed cannot be empty',
			),
			'maxlength' => array(
				'rule' => array('maxLength', 500),
				'message' => 'Feed cannot exceed 500 characters!',
			)
		)
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
	
	public function deleteFeeds($id = null){
		$feeds = $this->find('all', array('recursive' => -1, 'fields' => array('Feed.id', 'Feed.manager_id'), 'conditions' => array('Feed.manager_id' => $id)));
		foreach($feeds as $feed){
			$this->id = $feed['Feed']['id'];
			$this->delete($feed['Feed']['id']);	
		}
	}
	
	//cleanup feeds if we get too many
	public function cleanUp(){
		$count = $this->find('count', array('recursive' => -1, 'fields' => array('id')));
		if($count > 300){
			$feeds = $this->find('all', array('recursive' => -1, 'fields' => array('id'), 'order' => 'created DESC', 'limit' => '150'));
			foreach($feeds as $feed){
				$this->id = $feed['Feed']['id'];
				$this->delete($feed['Feed']['id']);	
			}
		}
		
	}

}
