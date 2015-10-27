<?php
App::uses('AppModel', 'Model');
//App::uses('Security', 'Utility'); 
//App::import('Component','Auth'); 
/**
 * User Model
 *
 * @property Item $Item
 */
 
class Contract extends AppModel {	
	
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
		'percentage' => array(
			'between' => array(
				'rule' => array('between', 1, 2),
				'message' => 'Percentage must be between 0-99',
			),
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Percentage must be numeric',
			),
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Must offer a percentage'
			)
		),
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
		),
		
		'Boxer' => array(
			'className' => 'Boxer',
			'foreignKey' => 'boxer_id',
			'dependent' => false,
			'conditions' => array('not' =>  array('Contract.boxer_id' => null))
		),
		
		'Trainer' => array(
			'className' => 'Trainer',
			'foreignKey' => 'trainer_id',
			'dependent' => false,
			'conditions' => array('not' =>  array('Contract.trainer_id' => null))
		)
	);
	
	public function deleteContracts($manager_id = null){
		$contracts = $this->find('all', array('recursive' => -1, 'fields' => array('Contract.id' , 'Contract.manager_id'), 'conditions' => array('manager_id' => $manager_id)));
		foreach($contracts as $contract){
			$this->id = $contract['Contract']['id'];
			$this->delete($contract['Contract']['id']);	
		}
	}
	
	public function deleteTrainerContracts($manager_id = null){
		$contracts = $this->find('all', array('recursive' => -1, 'fields' => array('Contract.id' , 'Contract.manager_id'), 'conditions' => array('manager_id' => $manager_id, 'trainer_id <>' => NULL)));
		foreach($contracts as $contract){
			$this->id = $contract['Contract']['id'];
			$this->delete($contract['Contract']['id']);	
		}
	}
	
	public function removeBoxer($boxer_id = null){
		$contracts = $this->find('all', array('recursive' => -1, 'fields' => array('id', 'boxer_id'), 'conditions' => array('boxer_id' => $boxer_id)));	
		foreach ($contracts as $contract){
			$this->id  = $contract['Contract']['id'];
			$this->delete($contract['Contract']['id']);	
		}
	}
	
	public function removeTrainer($trainer_id = null){
		$contracts = $this->find('all', array('recursive' => -1, 'fields' => array('id', 'boxer_id'), 'conditions' => array('trainer_id' => $trainer_id)));	
		foreach ($contracts as $contract){
			$this->id  = $contract['Contract']['id'];
			$this->delete($contract['Contract']['id']);	
		}
	}
	
	public function removeAcceptedBoxerContract($boxer_id = null){
		$contract = $this->find('first', array('conditions' => array('active' => '1', 'boxer_id' => $boxer_id), 'fields' => array('id', 'boxer_id', 'active'), 'recursive' => -1));
		$this->id = $contract['Contract']['id'];
		$this->delete($contract['Contract']['id']);
	}
	
	public function npcResponse($boxer_id = null, $trainer_id = null){
        if(isset($boxer_id) && $boxer_id != null){
            $conditions = array('active' => '0', 'Contract.boxer_id' => $boxer_id);
        }elseif(isset($trainer_id) && $trainer_id != null){
            $conditions = array('active' => '0', 'Contract.trainer_id' => $trainer_id);
        }else{
            $conditions = array('active' => '0');
        }
		$contracts = $this->find('all', array('recursive' => -1, 'fields' => array('id', 'boxer_id', 'trainer_id', 'value', 'active'), 'conditions' => $conditions));
		//$data = array();
		foreach ($contracts as $contract){
			//if the contract is for a trainer
			if($contract['Contract']['trainer_id'] != null){
				//check if the contract is a steal attempt
				$currentContract = $this->find('first', array('recursive' => -1, 'fields' => array('id', 'boxer_id', 'trainer_id', 'value', 'active', 'manager_id'), 'conditions' => array('active' => '1', 'trainer_id' => $contract['Contract']['trainer_id'])));
			
				$trainer = $this->Trainer->find('first', array('conditions' => array('Trainer.id' => $contract['Contract']['trainer_id']), 'fields' => array('id', 'overall'), 'recursive' => -1));
				//$trainersValue = $trainer['Trainer']['overall'] * 2;
				//find the contracts that arent accepted and has the highest value for a set trainer_id
				$trainerContract = $this->find('first', array('contain' => array('Trainer' => array('fields' => array('id', 'forname_id', 'surname_id', 'manager_id'), 'Forname' => array('fields' => array('name')), 'Surname' => array('fields' => array('name')))), 'fields' => array('id', 'trainer_id', 'active', 'manager_id', 'value', 'bonus'), 'order' => array('Contract.value DESC'), 'conditions' => array('active' => '0', 'trainer_id' => $contract['Contract']['trainer_id'])));
				
				if(!empty($trainerContract) && $trainerContract['Contract']['id'] != null){
					
					//if there is a steal attempt compare highest steal vs current contract.
					if(isset($currentContract) && !empty($currentContract)){
						if($currentContract['Contract']['value'] < $trainerContract['Contract']['value']){
							//Send Notification to Manager his trainer is leaving him
							$this->Manager->Notification->lostTrainer($trainerContract, $currentContract['Contract']['manager_id']);
							
							//Remove the old Contract
							$this->id = $currentContract['Contract']['id'];
							$this->delete($currentContract['Contract']['id']);
							
							//setting boxers trainer_id to null
							$boxers = $this->Boxer->find('all', array('recursive' => -1, 'fields' => array('id', 'trainer_id'), 'conditions' => array('trainer_id' => $currentContract['Contract']['trainer_id'])));
							foreach($boxers as $boxer){
								$this->Boxer->id = $boxer['Boxer']['id'];
								$this->Boxer->removeTrainer($boxer['Boxer']['id']);	
							}
							
							//set the contract to successful
							$this->id = $trainerContract['Contract']['id'];
							$this->saveField('active', '1');
							//send notification to successful contract sender
							$this->Manager->Notification->acceptTrainerContract($trainerContract);
							//set trainers to the new manager
							$this->Trainer->newManager($trainerContract['Contract']['trainer_id'], $trainerContract['Contract']['manager_id']);
						}else{
							//Let manager know that his boxer will stay with him
							$this->Manager->Notification->keepTrainer($trainerContract, $currentContract['Contract']['manager_id']);
						}
					}else{
						if($trainerContract['Contract']['value'] >= 1){
							//set the contract to successful
							$this->id = $trainerContract['Contract']['id'];
							$this->saveField('active', '1');
							//send notification to successful contract sender
							$this->Manager->Notification->acceptTrainerContract($trainerContract);
							//set trainers to the new manager
							$this->Trainer->newManager($trainerContract['Contract']['trainer_id'], $trainerContract['Contract']['manager_id']);
						}
					}
					
					//find the other contract offers that werent the highest and remove
					$rejectContracts = $this->find('all', array('contain' => array('Trainer' => array('fields' => array('id', 'forname_id', 'surname_id'), 'Forname' => array('fields' => array('name')), 'Surname' => array('fields' => array('name')))), 'fields' => array('id', 'trainer_id', 'manager_id', 'bonus'), 'conditions' => array('active'  => 0, 'trainer_id' => $contract['Contract']['trainer_id'])));
					foreach($rejectContracts as $rejectContract){
						//notifify the manager the contract wasnt successful
						$this->Manager->Notification->rejectedTrainerContract($rejectContract);
						
						//Return managers bonuses.
						$this->Manager->updateBalance($rejectContract['Contract']['manager_id'], $rejectContract['Contract']['bonus']);
						
						//remove the rejected contracts
						$this->id = $rejectContract['Contract']['id'];
						$this->delete($rejectContract['Contract']['id']);	
					}
				}
				
			//if the contract is for a boxer
			}else if ($contract['Contract']['boxer_id'] != null){
				//adding the value of the boxer so they dont auto accept best offer but best offer that also matches their fame - rank
				$boxer = $this->Boxer->find('first', array('conditions' => array('Boxer.id' => $contract['Contract']['boxer_id']), 'fields' => array('id', 'fame', 'rank'), 'recursive' => -1));
				if($boxer['Boxer']['fame'] > 50){
					$boxer['Boxer']['fame'] = (25 + ($boxer['Boxer']['fame'] - ($boxer['Boxer']['fame'] * 0.5)));
				}
				$boxersValue = $boxer['Boxer']['fame'] - ($boxer['Boxer']['rank'] * 2);
				if($boxersValue < 10){
					$boxersValue = 10;	
				}
				//find the contracts that arent accepted and has the highest value for a set boxer_id
				$boxerContract = $this->find('first', array('contain' => array('Boxer' => array('fields' => array('id', 'forname_id', 'surname_id'), 'Forname' => array('fields' => array('name')), 'Surname' => array('fields' => array('name')))), 'fields' => array('id', 'boxer_id', 'active', 'percentage', 'manager_id', 'value', 'bonus'), 'order' => array('Contract.value DESC'), 'conditions' => array('active' => '0', 'boxer_id' => $contract['Contract']['boxer_id'])));
				
				if(!empty($boxerContract) && $boxerContract['Contract']['id'] != null){
					
					//If the contracts valus is greater or equal to the boxers value
					if($boxerContract['Contract']['value'] >= $boxersValue){
						//set the contract to successful
						$this->id = $boxerContract['Contract']['id'];
						$this->saveField('active', '1');
						//send notification to successful contract sender
						$this->Manager->Notification->acceptBoxerContract($boxerContract);
						//set boxers to the new manager
						$this->Boxer->newManager($boxerContract['Contract']['boxer_id'], $boxerContract['Contract']['manager_id']);
					}
					
					//find the other contract offers that werent the highest and remove
					$rejectContracts = $this->find('all', array('contain' => array('Boxer' => array('fields' => array('id', 'forname_id', 'surname_id'), 'Forname' => array('fields' => array('name')), 'Surname' => array('fields' => array('name')))), 'fields' => array('id', 'boxer_id', 'manager_id', 'percentage', 'bonus'), 'conditions' => array('active'  => 0, 'boxer_id' => $contract['Contract']['boxer_id'])));
					foreach($rejectContracts as $rejectContract){
						//notifify the manager the contract wasnt successful
						$this->Manager->Notification->rejectedBoxerContract($rejectContract);
						
						//Return managers bonuses.
						$this->Manager->updateBalance($rejectContract['Contract']['manager_id'], $rejectContract['Contract']['bonus']);
						
						//remove the rejected contracts
						$this->id = $rejectContract['Contract']['id'];
						$this->delete($rejectContract['Contract']['id']);	
					}
				}
			}
		}
	}
	
	public function createBoxerContract($manager_id, $boxer_id) {
		$data['Contract']['manager_id'] = $manager_id;
		$data['Contract']['boxer_id'] = $boxer_id;
		$data['Contract']['active'] = 1;
		$data['Contract']['percentage'] = 90;
		$data['Contract']['bonus'] = 65000;
		$data['Contract']['value'] = 75;
		
		$this->create();
		$this->save($data);
	}
	
	public function createTrainerContract($manager_id, $trainer_id) {
		$data['Contract']['manager_id'] = $manager_id;
		$data['Contract']['trainer_id'] = $trainer_id;
		$data['Contract']['active'] = 1;
		$data['Contract']['bonus'] = 50000;
		$data['Contract']['value'] = 50;
		
		$this->create();
		$this->save($data);
	}
	

}
