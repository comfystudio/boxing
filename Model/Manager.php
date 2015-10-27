<?php
App::uses('AppModel', 'Model');
//App::uses('Security', 'Utility'); 
//App::import('Component','Auth'); 
/**
 * User Model
 *
 * @property Item $Item
 */
 
class Manager extends AppModel {	
	
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
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'dependant' => true
		),
	
	);
	
	public $hasMany = array(
		'Boxer',
		'ManagerItem',
		'Trainer',
		'Contract',
		'Feed',
		'Notification' => array(
			'className' => 'Notification',
			'foreignKey' => 'recipient_id',
		),
		'Param',
		'Fight',
		
	);
	
	public function deleteManager($id = null){
		$this->id = $id;
		$this->delete($id);	
	}
	
	public function updateBalance($id = null, $value = null){
		$manager = $this->find('first', array('recursive' => -1, 'fields' => array('id', 'balance'), 'conditions' => array('Manager.id' => $id)));
		$new_balance = $manager['Manager']['balance'] + $value;
		$this->id = $id;
		$this->saveField('balance', $new_balance);
	}
	
	public function getItemData($manager_id){
		$items  = $this->find('first', array(
			'conditions' => array(
				'id' => $manager_id
				),
			'fields' => array(
				'id'
				),
			'contain' => array(
				'ManagerItem' => array(
					'fields' => array(
						'id',
						'manager_id',
						'item_id'
						),
					'Item' => array(
						'fields' => array(
							'id',
							'buff_stat',
							'buff_value'
							)
						)
					)
				)
			)
		);
		return $items['ManagerItem'];
	}
	
	
	//update salaries based on contracts, also if manager cant afford salaries these contracts will expire
	public function updateSalaries($game_time){
		$fields = array('id', 'balance');
		$conditions = array();
		$contain = array(
			'Contract' => array(
				'fields' => array(
					'manager_id',
					'active',
					'salary'
				),
				'conditions' => array(
					'active' => '1'
				)
			),
			'Trainer' => array(
				'fields' => array(
					'id',
					'manager_id'
				),
				'Forname' => array(
					'fields' => array(
						'name'
					)
				),
				'Surname' => array(
					'fields' => array(
						'name'
					)
				)
			)
		);
		
		$managers = $this->find('all', array('fields' => $fields, 'contain' => $contain, 'conditions' => $conditions));
		foreach($managers as $manager){
			$cost = 0;
			foreach($manager['Contract'] as $contract){
				$cost = $cost + $contract['salary'];
			}
			if($manager['Manager']['balance'] <= 0){
				$this->Contract->deleteTrainerContracts($manager['Manager']['id']);
				$this->Trainer->removeManagers($manager['Manager']['id']);
				foreach($manager['Trainer'] as $trainer){
					$fullname = $trainer['Forname']['name']. ' ' . $trainer['Surname']['name'];
					$this->Notification->cantAffordTrainer($fullname, $manager['Manager']['id'] ,$game_time);
					$this->Boxer->removeTrainers($trainer['id']);	
				}
			}
			$newBalance = $manager['Manager']['balance'] - $cost;
			$this->id = $manager['Manager']['id'];
			$this->saveField('balance', $newBalance);
		}
	}
	
	//update managers current belts_held and careers_belts_total
	public function updateBeltsHeld($boxer_id = null){
		$fields = array('id', 'belts_held', 'career_belts_total');
		$contain = array(
			'Boxer' => array(
				'fields' => array(
                    'id',
					'rank',
					'retired'
				),
				'conditions' => array(
					'rank' => '1',
					'retired' => '0'
				)
			)
		);
		$managers = $this->find('all', array('fields' => $fields, 'contain' => $contain));
		foreach($managers as $manager){
			$champs = 0;
            $count = 0;
			foreach($manager['Boxer'] as $champion){
				$champs = $champs + 1;
                if(isset($boxer_id) && $boxer_id != null){ //if there is a boxer id param
                    if($boxer_id == $champion['id']){ //if the boxer id which should be the fights winner is the managers champion then add to the count.
                        $count++;
                    }
                }
			}
			if($champs >= 1){
				$careerTotal = $manager['Manager']['career_belts_total'];
                $careerTotal = $careerTotal + $count;
				$this->id = $manager['Manager']['id'];
				$this->saveField('belts_held', $champs);
				$this->saveField('career_belts_total', $careerTotal);	
			}else{
				$this->id = $manager['Manager']['id'];
				$this->saveField('belts_held', $champs);
			}
		}
	}
	
	//This function counts all the managers, boxers and trainers and scales the number of boxers/trainers based on managers
	public function mangersToBoxersAndTrainers($name_data){
		$month = 60 * 60 * 24 * 30;
		$timeInSeconds = strtotime(date('Y-m-d H:i:s'));
		$timeMinusMonth = $timeInSeconds - $month;
		$timeMinusMonth = date('Y-m-d H:i:s', $timeMinusMonth);
		$contain = array(
			'User' => array(
				'fields' => array(
					'User.id',
					'User.last_login'
				)
			)
		);
		$countManagers = $this->find('count', array('contain' => $contain, 'conditions' => array('User.last_login >=' => $timeMinusMonth)));
		$countBoxers = $this->Boxer->find('count', array('conditions' => array('retired' => '0')));
		$difference = ($countBoxers / $countManagers); 
		
		
		if($difference < 3 || $countBoxers < 90){
			$count = 0;
			if($countBoxers < 90) {
				$count = abs($countBoxers - 90);	
			} else {
				$total = ($countManagers - 30) * 3;
				$countBoxers2 = ($countBoxers - 90);
				if($countBoxers2 < $total){
					$count = ($total - $countBoxers2);
				}
			}
			
			if($count > 0) {
				$weights = Configure::read('Weight.class');
				$data = array();
				foreach($weights as $key => $weight){
					$data[$key] = 0;
					$data2[$key] = $this->Boxer->find('count', array('conditions' => array('Boxer.weight_type' => $key, 'Boxer.retired' => '0')));
				}
			}
				
            $out = 0;
            $lowest = 100000;
            for($i = 1; $i <= $count; $i++){
                foreach($data2 as $key => $dat){
                    if($dat < $lowest){
                        $lowest = $dat;
                        $out = $key;
                    }
                }
                $lowest = 100000;
                $data2[$out]++;
                $data[$out]++;
            }
            $this->Boxer->replaceRetired($data, $name_data);
		}
		
		//work out trainers now
		$countTrainers = $this->Trainer->find('count');
		$difference = ($countTrainers / $countManagers); 
			
		if($countTrainers < 60 || $difference < 2){
			if($countTrainers < 60) {
				$count = abs($countTrainers - 60);		
			} else {
				$countTrainers2 = $countTrainers - 30;
				$total = $countManagers2 * 2;
				
				$total = ($countManagers - 30) * 2;
				$countTrainers2 = ($countTrainers - 60);
				if($countTrainers2 < $total){
					$count  = ($total - $countTrainers2);
				}
			}
			
			if($count > 0){
				$data = 0;
				for($i = 1; $i <= $count; $i++){
					$data++;	
				}
			}
			$this->Trainer->replaceRetired($data, $name_data);
		}
	}
	
	public function newChampion($fight, $overview, $fullnameWinner, $fullnameLoser, $fullnameWinnerId, $fullnameLoserId){
		$managers = $this->find('all', array('fields' => array('id'), 'recursive' => -1));
		$weights = Configure::read('Weight.class');
		$weight_type = $weights[$fight['Fighter1']['weight_type']];
		foreach ($managers as $manager){
			$this->Notification->newChampion($fight, $overview, $fullnameWinner, $fullnameLoser, $weight_type, $manager['Manager']['id'], $fullnameWinnerId, $fullnameLoserId);
		}
	}
}
