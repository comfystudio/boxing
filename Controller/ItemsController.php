<?php
App::uses('AppController', 'Controller');

class ItemsController extends AppController {
	
	public function index(){
		
		if(!$this->Session->read('User.manager_id')){
			$this->Session->setFlash('You must be logged in to view this section');
			$this->redirect(array('controller' => 'users', 'action' => 'login'));
		}
		
		$fields = array(
			'Item.id',
			'Item.price',
			'Item.title',
			'Item.description'
		);
		
		$contain = array(
			'ManagerItem' => array(
				'conditions' => array(
					'ManagerItem.manager_id' => $this->Session->read('User.manager_id')
				),
				'Manager' => array(
					'fields' => array(
						'Manager.id',
						'Manager.balance'
					)
				),
			),
		
		);
		
		$items = $this->Item->find('all', array('recursive' => 2, 'fields' => $fields, 'contain' => $contain));
		$this->set(compact('items', 'manager'));
		
	}

}