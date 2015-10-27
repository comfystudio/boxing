<?php
App::uses('AppController', 'Controller');

class ContractsController extends AppController {
	
	public function release($id = null){
		$this->autoRender = false;
		$fields = array(
			'Contract.id',
			'Contract.manager_id',
			'Contract.boxer_id',
			'Contract..trainer_id'
		);
		$contract = $this->Contract->find('first', array('recursive' => -1, 'fields' => $fields, 'conditions' => array('Contract.id' => $id)));
		if($this->Session->read('User.manager_id') != $contract['Contract']['manager_id']){
			$this->Session->setFlash('The Manager of this contract does not appear to be you. Either you\'re a cheeky sud or you need to login');
			$this->redirect($this->referer());	
		}
		
		$this->Contract->id = $id;
		$this->Contract->delete($id);
		
		if(!empty($contract['Contract']['boxer_id'])){
			$this->Contract->Boxer->removeManager($contract['Contract']['boxer_id']);
			$this->Contract->Boxer->removeTrainer($contract['Contract']['boxer_id']);
			$this->Contract->Boxer->updateHappiness($contract['Contract']['boxer_id']);
		}elseif(!empty($contract['Contract']['trainer_id'])){
			$this->Contract->Trainer->removeManager($contract['Contract']['trainer_id']);
			$this->Contract->Boxer->removeTrainers($contract['Contract']['trainer_id']);
		}
		
		$this->Session->setFlash('The contract has been terminated');
        if(!empty($contract['Contract']['boxer_id'])){
            $this->redirect(array('controller' => 'boxers', 'action' => 'yours', $this->Session->read('User.manager_id')));
        }elseif(!empty($contract['Contract']['trainer_id'])){
            $this->redirect(array('controller' => 'trainers', 'action' => 'yours', $this->Session->read('User.manager_id')));
        }
	}
	
	public function view($id = null){
		$this->Contract->id = $id;
		if (!$this->Contract->exists()) {
			$this->Session->setFlash('This contract does not exist');
			$this->redirect($this->referer());
		}
	   
        $fields = array(
            'Contract.id',
            'Contract.manager_id',
            'Contract.boxer_id',
            'Contract.trainer_id',
            'Contract.percentage',
            'Contract.bonus',
            'Contract.start_date',
            'Contract.created'
        );
	   
	   $contain = array(
	   	'Manager' => array(
			'fields' => array(
				'Manager.id',
				),
			'User' => array(
				'fields' => array(
					'User.username'
					)
				)
			),
		'Boxer' => array(
			'fields' => array(
				'Boxer.id',
			),
			'conditions' => array(
				'not' => array(
					'Contract.boxer_id' => null
				)
			),
			'Forname' => array(
				'fields' => array(
					'Forname.name'
				)
			),
			'Surname' => array(
				'fields' => array(
					'Surname.name'
					)
				)
			),
		'Trainer' => array(
			'fields' => array(
				'Trainer.id'
			),
			'conditions' => array(
				'not' => array(
					'Contract.trainer_id' => null
				)
			),
			'Forname' => array(
				'fields' => array(
					'Forname.name'
				)
			),
			'Surname' => array(
				'fields' => array(
					'Surname.name'
					)
				)
			),
		
	   
	   );
	   
	   $contract = $this->Contract->find('first', array('contain' => $contain, 'fields' => $fields, 'conditions' => array('Contract.id' => $id)));
	   
	   
	   if($this->Session->read('User.manager_id') != $contract['Contract']['manager_id']){
			$this->Session->setFlash('The Manager of this contract does not appear to be you. Either you\'re a cheeky sud or you need to login');
			$this->redirect(array('controller' => 'users', 'action' => 'login'));	 
	   }
	   
	   $this->set(compact('contract'));
		
	}
	
	
	public function boxers($boxer_id = null){
		$this->Contract->Boxer->id = $boxer_id;
		if (!$this->Contract->Boxer->exists() || !is_numeric($boxer_id)) {
		   $this->Session->setFlash('This boxers does not appear to exist sorry');
		   $this->redirect($this->referer());
		}
		
		$manager = $this->Contract->Manager->find('first', array('recursive' => -1, 'fields' => array('id', 'balance'), 'conditions' => array('Manager.id' => $this->Session->read('User.manager_id'))));
		//$start_date = $this->requestAction('params/getGameTime');
		$balance = $manager['Manager']['balance'];
		$contain = array(
			'Forname' => array(
				'fields' => array(
					'Forname.name'
				)
			),
			'Surname' => array(
				'fields' => array(
					'Surname.name'
				)
			)
		);
		
		$boxer = $this->Contract->Boxer->find('first', array('contain' => $contain, 'fields' => array('id', 'retired', 'rank', 'fame'), 'conditions' => array('Boxer.id' => $boxer_id)));
		//working out boxers value so we can recommend bonus/fee
		if($boxer['Boxer']['fame'] > 50){
			$boxer['Boxer']['fame'] = (25 + ($boxer['Boxer']['fame'] - ($boxer['Boxer']['fame'] * 0.5)));
		}
		$boxersValue = $boxer['Boxer']['fame'] - ($boxer['Boxer']['rank'] * 2);
		$boxersValue = ($boxersValue - 50) * 11000;
		if($boxersValue < 100) {
			$boxersValue = 100;	
		}
		
		if($boxer['Boxer']['retired'] == 1){
			$this->Session->setFlash('This boxer is retired and cannot be offered a contract');
			$this->redirect(array('controller' => 'managers', 'action' => 'home'));
		}
		
		if(empty($manager) || !isset($manager))	{
			$this->Session->setFlash('Manager does not appear to exist, please try logging in and creating a manager');
			$this->redirect(array('controller' => 'managers', 'action' => 'home'));
		}
		
		if($this->request->is('post') || $this->request->is('put')){
            $this->request->data['Contract']['bonus'] = str_replace(",", "", $this->request->data['Contract']['bonus']);
			if($this->request->data['Contract']['bonus'] <= $manager['Manager']['balance']){
				$this->Contract->create();
				$this->request->data['Contract']['value'] = ((100 - $this->request->data['Contract']['percentage']) + ($this->request->data['Contract']['bonus'] / 10000));
				if($this->Contract->save($this->request->data)){
					$this->Contract->Manager->id = $manager['Manager']['id'];
					$newbalance = $manager['Manager']['balance'] - $this->request->data['Contract']['bonus'];
					$this->Contract->Manager->saveField('balance', $newbalance);
					$this->Session->setFlash('The contract has been offered, check your notifications for feedback');
                    $this->Contract->npcResponse($this->request->data['Contract']['boxer_id'], null); //MOVED FROM UPDATE FUNCTION, we want immediate feedback to users now
					$this->redirect($this->referer());
				}else{
					$this->Session->setFlash('Error');
				}
			}else{
				$this->Session->setFlash('You cannot afford the bonus man!');
				$this->redirect($this->referer());
			}
		}
		$this->set(compact('boxer_id', 'balance', 'boxer', 'boxersValue'));
	}
	
	public function trainers($trainer_id = null){
		$this->Contract->Trainer->id = $trainer_id;
		$manager = $this->Contract->Manager->find('first', array('recursive' => -1, 'fields' => array('id', 'balance'), 'conditions' => array('Manager.id' => $this->Session->read('User.manager_id'))));
		//$start_date = $this->requestAction('params/getGameTime');
		$balance = $manager['Manager']['balance'];
		
		if (!$this->Contract->Trainer->exists() || !is_numeric($trainer_id)) {
		   $this->Session->setFlash('This trainer does not appear to exist sorry');
		   $this->redirect($this->referer());
		}
		
		if(empty($manager) || !isset($manager))	{
			$this->Session->setFlash('Manager does not appear to exist, please try logging in and creating a manager');
			$this->redirect(array('controller' => 'managers', 'action' => 'home'));
		}
		
		$contain = array(
			'Forname' => array(
				'fields' => array(
					'Forname.name'
				)
			),
			'Surname' => array(
				'fields' => array(
					'Surname.name'
				)
			)
		);
		
		$trainer = $this->Contract->Trainer->find('first', array('contain' => $contain, 'fields' => array('Trainer.id'), 'conditions' => array('Trainer.id' => $trainer_id)));

		if($this->request->is('post') || $this->request->is('put')){
            $this->request->data['Contract']['bonus'] = str_replace(",", "", $this->request->data['Contract']['bonus']);
			if($this->request->data['Contract']['bonus'] <= $manager['Manager']['balance']){
				$this->Contract->create();
				$this->request->data['Contract']['value'] = ($this->request->data['Contract']['bonus'] / 1000);
				if($this->Contract->save($this->request->data)){
					$this->Contract->Manager->id = $manager['Manager']['id'];
					$newbalance = $manager['Manager']['balance'] - $this->request->data['Contract']['bonus'];
					$this->Contract->Manager->saveField('balance', $newbalance);
					$this->Session->setFlash('The Contract has been offered, you should hear back next week if you were successful, your bonus will be returned if not');
                    $this->Contract->npcResponse(null, $this->request->data['Contract']['trainer_id']); //MOVED FROM UPDATE FUNCTION, we want immediate feedback to users now
					$this->redirect($this->referer());
				}else{
					$this->Session->setFlash('Error');
				}
			}else{
				$this->Session->setFlash('You cannot afford the bonus man!');
				$this->redirect($this->referer());
			}
		}
		$this->set(compact('trainer_id', 'balance', 'trainer'));
	}
	
	public function renegotiation($boxer_id){
		$fields = array(
			'id',
			'manager_id',
			'boxer_id',
			'value',
			'active',
			'percentage',
			'bonus',
			'start_date'
		);
		$contain = array(
			'Boxer' => array(
				'fields' => array(
					'id',
					'forname_id',
					'surname_id',
					'rank',
					'fame',
					'manager_id',
					'happiness',
					'greed'
				),
				'Forname' => array(
					'name'
				),
				'Surname' => array(
					'name'
				)
			),
			'Manager' => array(
				'fields' => array(
					'balance'
				)
			)
		);
		$contract = $this->Contract->find('first', array('contain' => $contain, 'fields' => $fields, 'conditions' => array('Contract.boxer_id' => $boxer_id, 'active' => '1')));
		if(!isset($contract) || empty($contract)){
			$this->Session->setFlash('Boxer does not have a contract');
			$this->redirect($this->referer());
		}
		
		if($this->Session->read('User.manager_id') != $contract['Contract']['manager_id']){
			$this->Session->setFlash('You are not the manager of this boxer thus cannot into renegotiation');
			$this->redirect($this->referer());	
		}
		
		if($this->request->is('post') || $this->request->is('put')){
            $this->request->data['Contract']['bonus'] = str_replace(",", "", $this->request->data['Contract']['bonus']);
			if($this->request->data['Contract']['bonus'] <= $contract['Manager']['balance']){
				$game_time = $this->requestAction('params/getGameTime');
				$value = ((100 - $this->request->data['Contract']['percentage']) + ($this->request->data['Contract']['bonus'] / 10000));
				$boxersGreed = abs($contract['Boxer']['greed'] - 100);
				$buffedValue = $value + ($boxersGreed / 2) + ($contract['Boxer']['happiness'] / 2);
				if($contract['Boxer']['fame'] > 50){
					$contract['Boxer']['fame'] = (25 + ($contract['Boxer']['fame'] - ($contract['Boxer']['fame'] * 0.6)));
				}
				$boxerValue = ($contract['Boxer']['fame'] - ($contract['Boxer']['rank'] * 2));
				if($buffedValue >= $boxerValue){
					$this->Contract->id = $contract['Contract']['id'];
					$this->Contract->delete($contract['Contract']['id']);
					
					$this->request->data['Contract']['value'] = $value;
					$this->request->data['Contract']['active'] = '1';
					$this->request->data['Contract']['start_date'] = $game_time;
					$this->request->data['Contract']['boxer_id'] = $contract['Contract']['boxer_id'];
					$this->request->data['Contract']['manager_id'] = $contract['Contract']['manager_id'];
					$this->Contract->create();
					if($this->Contract->save($this->request->data)){
						$this->Contract->Manager->id = $contract['Contract']['manager_id'];
						$newbalance = $contract['Manager']['balance'] - $this->request->data['Contract']['bonus'];
						$this->Contract->Manager->saveField('balance', $newbalance);
						$this->Contract->Manager->Notification->renegotiationSuccess($contract, $game_time);
						$this->Session->setFlash('The renegotiation has been made hopefully the boxer will accept the new contract');
						$this->redirect(array('controller' => 'managers', 'action' => 'home'));
					}
					
				}else{
					$this->Contract->Manager->Notification->renegotiationReject($contract, $game_time);
					$this->Session->setFlash('The renegotiation has been made hopefully the boxer will accept the new contract');
					$this->redirect(array('controller' => 'managers', 'action' => 'home'));
				}
			}else{
				$this->Session->setFlash('You cannot afford the bonus man!');
				$this->redirect($this->referer());		
			}
		}
		$this->set(compact('contract'));
	}
	
	public function trainerSteal($trainer_id = null){
		$manager = $this->Contract->Manager->find('first', array('recursive' => -1, 'fields' => array('id', 'balance'), 'conditions' => array('Manager.id' => $this->Session->read('User.manager_id'))));
		//$start_date = $this->requestAction('params/getGameTime');
		$balance = $manager['Manager']['balance'];
		
		if(empty($manager) || !isset($manager))	{
			$this->Session->setFlash('Manager does not appear to exist, please try logging in and creating a manager');
			$this->redirect(array('controller' => 'managers', 'action' => 'home'));
		}
		
		if($this->request->is('post') || $this->request->is('put')){
            $this->request->data['Contract']['bonus'] = str_replace(",", "", $this->request->data['Contract']['bonus']);
			if($this->request->data['Contract']['bonus'] <= $manager['Manager']['balance']){
				$this->request->data['Contract']['value'] = ($this->request->data['Contract']['bonus'] / 1000);
				$contain = array(
					'Trainer' => array(
						'fields' => array(
							'id',
							'forname_id',
							'surname_id'
						),
						'Forname' => array(
							'fields' => array(
								'id',
								'name'
							)
						),
						'Surname' => array(
							'fields' => array(
								'id',
								'name'
							)
						)
					)
				);
				$currentContract = $this->Contract->find('first', array('contain' => $contain, 'fields' => array('id', 'active', 'manager_id', 'value', 'trainer_id'), 'conditions' => array('trainer_id' => $trainer_id, 'active' => '1')));
				$this->Contract->create();
				if($this->Contract->save($this->request->data)){
					$this->Contract->Manager->id = $manager['Manager']['id'];
					$newbalance = $manager['Manager']['balance'] - $this->request->data['Contract']['bonus'];
					$this->Contract->Manager->saveField('balance', $newbalance);

                    //calling the npcresponse to speed up the steal process to be real time.
                    $this->Contract->npcResponse(null, $trainer_id);

					//$this->Contract->Manager->Notification->trainerStealAttempt($currentContract, $start_date);
					$this->Session->setFlash('The Contract has been offered, check your notifications.');
					$this->redirect($this->referer());
				}else{
					$this->Session->setFlash('Error');
				}
			}else{
				$this->Session->setFlash('You cannot afford the bonus man!');
				$this->redirect($this->referer());
			}
		}
		$this->set(compact('trainer_id', 'balance'));
	}
	
	public function renegotiationTrainer($trainer_id){
		$fields = array(
			'id',
			'manager_id',
			'trainer_id',
			'value',
			'active',
			'bonus',
			'start_date'
		);
		$contain = array(
			'Trainer' => array(
				'fields' => array(
					'id',
					'forname_id',
					'surname_id',
					'manager_id',
				),
				'Forname' => array(
					'name'
				),
				'Surname' => array(
					'name'
				)
			),
			'Manager' => array(
				'fields' => array(
					'balance'
				)
			)
		);
		$contract = $this->Contract->find('first', array('contain' => $contain, 'fields' => $fields, 'conditions' => array('Contract.trainer_id' => $trainer_id, 'active' => '1')));
		if(!isset($contract) || empty($contract)){
			$this->Session->setFlash('Trainer does not have a contract');
			$this->redirect($this->referer());
		}
		
		if($this->Session->read('User.manager_id') != $contract['Contract']['manager_id']){
			$this->Session->setFlash('You are not the manager of this trainer thus cannot into renegotiation');
			$this->redirect($this->referer());	
		}
		
		if($this->request->is('post') || $this->request->is('put')){
            $this->request->data['Contract']['bonus'] = str_replace(",", "", $this->request->data['Contract']['bonus']);
			if($this->request->data['Contract']['bonus'] <= $contract['Manager']['balance']){
				$game_time = $this->requestAction('params/getGameTime');
				$this->request->data['Contract']['value'] = ($this->request->data['Contract']['bonus'] / 1000);
				if($this->request->data['Contract']['value'] >= $contract['Contract']['value']){
					$this->Contract->id = $contract['Contract']['id'];
					$this->request->data['Contract']['start_date'] = $game_time;
					if($this->Contract->save($this->request->data)){
						$this->Contract->Manager->id = $contract['Contract']['manager_id'];
						$newbalance = $contract['Manager']['balance'] - $this->request->data['Contract']['bonus'];
						$this->Contract->Manager->saveField('balance', $newbalance);
						$this->Contract->Manager->Notification->renegotiationSuccessTrainer($contract, $game_time);
						$this->Session->setFlash('The renegotiation has been made hopefully the trainer will accept the new contract');
						$this->redirect(array('controller' => 'managers', 'action' => 'home'));
					}
					
				}else{
					$this->Contract->Manager->Notification->renegotiationRejectTrainer($contract, $game_time);
					$this->Session->setFlash('The renegotiation has been made hopefully the trainer will accept the new contract');
					$this->redirect(array('controller' => 'managers', 'action' => 'home'));
				}
			}else{
				$this->Session->setFlash('You cannot afford the bonus man!');
				$this->redirect($this->referer());		
			}
		}
		$this->set(compact('contract'));
	}

}