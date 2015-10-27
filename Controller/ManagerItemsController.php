<?php
App::uses('AppController', 'Controller');
//App::import('Component','Auth');
//App::uses('CakeEmail', 'Network/Email');
/**
 * Activities Controller
 *
 * @property Activity $Activity
 */
class ManagerItemsController extends AppController {
	public function buy($item_id = null, $manager_id = null){
		$this->autoRender = false;
		if($this->Session->read('User.manager_id') != $manager_id){
			$this->Session->setFlash('Current manager does not match requested manager');
			$this->redirect($this->referer());	
		}
		$this->loadModel('Item');
		$this->loadModel('Manager');
		
		$item = $this->Item->find('first', array('recursive' => -1, 'fields' => array('Item.id', 'Item.price'), 'conditions' => array('Item.id' => $item_id)));
		if(empty($item) || $item == null){
			$this->Session->setFlash('Item does not exist sorry');
			$this->redirect($this->referer());	
		}
		
		$manager = $this->Manager->find('first', array('recursive' => -1, 'fields' => array('Manager.id', 'Manager.balance'), 'conditions' => array('Manager.id' => $manager_id)));

		if(empty($manager) || $manager == null){
			$this->Session->setFlash('Manager does not exist! Cheeky sud!');
			$this->redirect($this->referer());	
		}
		
		$managerItem = $this->ManagerItem->find('first', array('recursive' => -1, 'fields' => array('ManagerItem.id', 'ManagerItem.item_id', 'ManagerItem.manager_id'), 'conditions' => array('ManagerItem.manager_id' => $manager_id, 'ManagerItem.item_id' => $item_id)));
		if(!empty($managerItem) || $managerItem != null){
			$this->Session->setFlash('You already own this item! How did you even get here!?');
			$this->redirect($this->referer());
		}
		
		if($manager['Manager']['balance'] < $item['Item']['price']){
			$this->Session->setFlash('You need to count those pennies! Sadly you cant afford the item');
			$this->redirect($this->referer());	
		}else{
			$this->ManagerItem->create();
			$this->ManagerItem->saveField('manager_id', $manager_id);
			$this->ManagerItem->saveField('item_id', $item_id);
			
			$this->Manager->id = $manager['Manager']['id'];
			$newBalance = $manager['Manager']['balance'] - $item['Item']['price'];
			$this->Manager->saveField('balance', $newBalance);
			
			$this->Session->setFlash('Congratulations you are now the proud owner of the item. Now your fighters will pwn that little bit better');
			$this->redirect($this->referer());
		
		}
	}

}