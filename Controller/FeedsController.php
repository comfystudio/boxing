<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
App::uses('PaginatorHelper', 'View/Helper');
//App::import('Component','Auth');
//App::uses('CakeEmail', 'Network/Email');
/**
 * Activities Controller
 *
 * @property Activity $Activity
 */
class FeedsController extends AppController {
	
	public function index(){
		
		if($this->Session->read('User.id')){
			$this->loadModel('User');
			$user = $this->User->find('first', array('recursive' => -1, 'fields' => array('User.id', 'User.manager_id'), 'conditions' => array('User.id' => $this->Session->read('User.id'))));
			$this->set(compact('user'));
		}
		
		//if($this->Session->read('User.manager_id') != null && $this->request->is('Post')){
			if($this->request->is('Post')){
				$this->request->data = Sanitize::clean($this->request->data, array('encode' => false));
				$this->Feed->create();
				if($this->Feed->save($this->request->data)){
					$this->Session->setFlash('Your post has been added to the feed');
					$this->redirect(array('action' => 'index'));
				}else{
					$this->Session->setFlash('Error, post was not saved');
				}
			}
		//}else{
		//	$this->Session->setFlash('You must be a user with a manager to post');
		//	$this->redirect(array('action' => 'index'));
		//}
		
		$this->paginate = array('order' => 'Feed.created DESC', 'fields' => array('Feed.content', 'Feed.created', 'Manager.user_id', 'Manager.id'));
		$this->set('feeds', $this->paginate());
		
	}
}