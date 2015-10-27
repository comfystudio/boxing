<?php
App::uses('AppModel', 'Model');
App::uses('Security', 'Utility'); 
App::import('Component','Auth'); 
/**
 * User Model
 *
 * @property Item $Item
 */
 
class User extends AppModel {	
	
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
		'username' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Username cannot be empty',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'Username already taken'
			),
			'between' => array(
				'rule' => array('between', 3, 20),
				'message' => 'Username must be between 3 and 20 characters'
			),
			'alphanumeric' => array(
				'rule' => 'alphanumeric',
				'message' => 'Username must be letters and numbers only'
			)
		),
		
		'password' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Password cannot be empty',
			),
			'between' => array(
				'rule' => array('between', 5, 100),
				'message' => 'Password must be between 5 and 100 characters'
			)
		),
		
		'email' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Email cannot be empty'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'Email already in use'
			),
			'email' => array(
				'rule' => 'email',
				'message' => 'Must supply a valid email'
			)
		)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasOne = array(
		'Manager' => array(
			'className' => 'Manager',
			'foreignKey' => 'user_id',
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
	
	
	function validateLogin($data) 
    { 
		$user = $this->find('first', array('conditions' => array('username' => $data['username'], 'password' => AuthComponent::password($data['password'])))); 
        if(!empty($user)){
            return $user['User'];
		}else{
        	return false;
		}
    } 
	
	function createUser($data)
	{
		$username = $data['User']['username'];
		$condition = array ('username'=>$username);
		$temp = $this->find('first', array('conditions'=>$condition));
		
		if(empty($temp)){
			return $this->save($data);
		}
		else{
			return false;		
		}
	}
	
	function getActivationHash()
	{
		if (!isset($this->id)) {
			return false;
		}
		return substr(Security::hash(Configure::read('Security.salt') . $this->field('created') . date('Ymd')), 0, 8);
	}

}
