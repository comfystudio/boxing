<?php
App::uses('AppController', 'Controller');
App::import('Component','Auth');
App::uses('CakeEmail', 'Network/Email');

class UsersController extends AppController {
	
	public function help(){
			
	}
	
	public function terms(){
		
	}
	
	public function privacy(){
		
	}
	
	public function contact(){
		
	}
	
	public function about(){
		
	}
	
	public function login(){
		if(!$this->Session->read('User.id')){
			if($this->request->is('Post')){
				if(($user = $this->User->validateLogin($this->data['User'])) == true){
					if($user['active'] == 1){
						$this->Session->write('User', $user);
						$this->User->id = $user['id'];
						$this->User->saveField('last_login', date('Y-m-d H:i:s'));
						$this->Session->setFlash('You\'ve successfully logged in');
						$this->redirect(array('controller' => 'managers', 'action' => 'home'));	
					}else{
						$this->Session->setFlash('Sorry your account has not been activated yet, check your emails for activation email');
						$this->redirect(array('controller' => 'users', 'action' => 'register'));
					}
				}else{
					$this->Session->setFlash('Sorry, the information you\'ve entered is incorrect.'); 
					$this->redirect(array('controller' => 'users', 'action' => 'login')) ;	
				}
			}
		}else{
			$this->Session->setFlash('You are already logged in man!');
			$this->redirect(array('controller' => 'managers', 'action' => 'index'));	
		}	
	}
	
	public function register()
	{	
		if ($this->request->is('post')) {
			$this->request->data['User']['password'] = AuthComponent::password($this->request->data['User']['password']);
			$copy = $this->User->find('first', array('recursive' => -1, 'conditions' => array('username' => $this->data['User']['username'])));
            if ($this->User->createUser($this->data)) {
				$this->__sendActivationEmail($this->User->getLastInsertID());
				$this->redirect(array('controller' => 'users', 'action' => 'thanks'));
			}elseif(!empty($copy)){
				$this->Session->setFlash('This username already exists');
				$this->data['User']['password'] = null;
			}else{
				$this->Session->setFlash('The User could not be saved. Please, try again.');
				$this->data['User']['password'] = null;
            }
        }
	}
	
	public function __sendActivationEmail($user_id) {
		$user = $this->User->find('first', array('recursive' => -1, 'conditions' => array('User.id' => $user_id), 'fields' => array('User.email', 'User.username')));
		$activate_url =  'http://' . $_SERVER['HTTP_HOST'] . '/users/activate/' . $user_id . '/' . $this->User->getActivationHash();
		$username = $this->request->data['User']['username'];
		
		$email = new CakeEmail('gmail');
		$email->from('noreply@BoxingGame.eu');
		$email->to($user['User']['email']);
		$email->subject('BoxingGame.eu - Please confirm your email address');
		$email->template('user_confirm');
		$email->viewVars(compact('username', 'activate_url'));
		$email->send();
	}
	
	public function activate($user_id = null, $in_hash = null) {
		$this->autoRender = false;
		$this->User->id = $user_id;
		if ($this->User->exists() && ($in_hash == $this->User->getActivationHash()))
		{
			// Update the active flag in the database
			$this->User->saveField('active', 1);
	 
			// Let the user know they can now log in!
			$this->Session->setFlash('Your account has been activated, please log in below');
			$this->redirect('login');
		}
	}
	
	public function thanks(){
		
		
	}
	
	public function logout() {
        $this->Session->destroy('User');
		$this->Session->delete('User');
        $this->Session->setFlash('You\'ve successfully logged out.'); 
        $this->redirect(array('controller' => 'users', 'action' => 'login')) ; 
    } 
	
	public function forgot(){
		if(!empty($this->request->data)){
			$usernameCheck = $this->User->find('first', array('conditions' => array('username' =>$this->request->data['User']['username'])));
			if(empty($usernameCheck)){
				$this->Session->setFlash('That Username does not exsist');
				$this->redirect('forgot');	
				
			}elseif ($usernameCheck['User']['email'] != $this->request->data['User']['email']){
				$this->Session->setFlash('That email does not match the username');
				$this->redirect('forgot');	
			}else{
				$password  = rand(100000000, 1000000000);
				$dbpassword = AuthComponent::password($password);
				$this->User->id = $usernameCheck['User']['id'];
				$this->User->saveField('password', $dbpassword);
				$username = $usernameCheck['User']['username'];
				
				$email = new CakeEmail('gmail');
				$email->from('noreply@BoxingGame.eu');
				$email->to($this->request->data['User']['email']);
				$email->subject('BoxingGame.eu - Password reset');
				$email->template('user_reset');
				$email->viewVars(compact('username', 'password'));
				$email->send();
				
				$this->redirect('forgotThanks');
			}
		}
	}
	
	public function forgotThanks(){
		
	}
	
	public function getUsername($user_id){
		if($this->params['requested']){
			$user = $this->User->find('first', array('fields' => array('User.username'), 'conditions' => array('User.id' => $user_id)));
			$username = $user['User']['username'];
			return $username;	
		}
	}
	
	public function options($id = null){
		if($id != $this->Session->read('User.manager_id')){
			$this->Session->setFlash('Manager of this option does not match current manager, Either you\'re a cheeky sud or you need to login');
			$this->redirect(array('action' => 'login'));
		}
		
		if($this->request->is('post') || $this->request->is('put')){
			if(!empty($this->request->data['User']['username']) && $this->request->data['User']['username'] != null){
				$dbpassword = AuthComponent::password($this->request->data['User']['password']);
				$user = $this->User->find('first', array('fields' => array('User.id', 'User.username', 'User.password'), 'recursive' => -1, 'conditions' =>  array('username' => $this->request->data['User']['username'], 'password' => $dbpassword, 'manager_id' => $id)));
				if(!empty($user) && isset($user)){
					$this->User->id = $user['User']['id'];
					$this->User->saveField('manager_id', null);
					$this->User->Manager->Boxer->removeManagers($id);
					$this->User->Manager->Trainer->removeManagers($id);
					$this->User->Manager->ManagerItem->deleteItems($id);
					$this->User->Manager->Contract->deleteContracts($id);
					$this->User->Manager->Feed->deleteFeeds($id);
					$this->User->Manager->deleteManager($id);
					$this->Session->destroy('User');
					$this->Session->setFlash('Your manager has been deleted! You will need to login and create a manager again to play');
					$this->redirect(array('action' => 'login'));	
				}else{
					$this->Session->setFlash('Account details incorrect sorry.');
					$this->redirect($this->referer());
				}
			}else{
				$this->Session->setFlash('No username entered');
				$this->redirect($this->referer());
			}
		}
	}
	
	public function newPassword() {
		$this->autoRender = false;
		
		if($this->request->is('post') || $this->request->is('put')){
			if($this->request->data['User']['id'] != $this->Session->read('User.id')){
				$this->Session->setFlash('Manager of this options does not match current manager, Either you\'re a cheeky sud or you need to login');
				$this->redirect(array('action' => 'login'));
			}
			if(!empty($this->request->data['User']['old password']) && !empty($this->request->data['User']['new password'])) {
				$password = AuthComponent::password($this->request->data['User']['old password']);
				$user = $this->User->find('first', array('recursive' => -1, 'fields' => array('User.id', 'User.password'), 'conditions' => array('User.id' => $this->request->data['User']['id'], 'User.password' => $password)));
				if(isset($user) && !empty($user)){
					$new_password = AuthComponent::password($this->request->data['User']['new password']);
					$this->User->id = $user['User']['id'];
					$this->User->saveField('password', $new_password);
					$this->Session->setFlash('Password updated!');
					$this->redirect($this->referer());
				} else {
					$this->Session->setFlash('Old password entered does not match account');
					$this->redirect($this->referer());	
				}
				
			} else {
				$this->Session->setFlash('No data entered');
				$this->redirect($this->referer());
			}
			
			
		} else {
			$this->Session->setFlash('No data entered');
			$this->redirect($this->referer());
		}
		
	}
	
	
}
