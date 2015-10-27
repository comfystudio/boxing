<?php
App::uses('AppModel', 'Model');
//App::uses('Security', 'Utility'); 
//App::import('Component','Auth'); 
/**
 * User Model
 *
 * @property Item $Item
 */
 
class Trainer extends AppModel {	
	

	public $belongsTo = array(
		'Manager' => array(
			'className' => 'Manager',
			'foreignKey' => 'manager_id',
			'fields' => array('Manager.id', 'Manager.user_id')
		),
		'Forname' => array(
			'className' => 'Name',
			'foreignKey' => 'forname_id',
			'fields' => array('Forname.id', 'Forname.name')
		),
		'Surname' => array(
			'className' => 'Name',
			'foreignKey' => 'surname_id',
			'fields' => array('Surname.id', 'Surname.name')
		),
	);
	
	public $hasMany = array(
		'Boxer',
        'Name'
	);
	
	
	public $hasOne = array(
		'Contract' => array(
			'className' => 'Contract',
			'foreignKey' => 'trainer_id',
			'conditions' => array('not' => array('Contract.trainer_id' => null))
		)
	);
	
	public function removeManager($id = null){
		$this->id = $id;
		$this->saveField('manager_id', null);
	}
	
	public function removeManagers($id = null){
		$trainers = $this->find('all', array('recursive' => -1, 'fields' => array('Trainer.id', 'Trainer.manager_id'), 'conditions' => array('Trainer.manager_id' => $id)));
		foreach($trainers as $trainer){
			$this->id = $trainer['Trainer']['id'];
			$this->saveField('manager_id', null);	
		}
	}
	
	public function checkRetired($managers){
		$contain = array(
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
		);
		$trainers = $this->find('all', array('contain' => $contain, 'fields' => array('id', 'dob', 'manager_id')));
		
		$count = 0;
		foreach($trainers as $trainer){
			//$age = $this->requestAction('params/getAge/'.$trainer['Trainer']['dob']);
			//$age =  ($age - 65);
            $rand = rand(1, 600);
			if($rand > 599 ){
				$count++;
				
				//setting trainers boxers trainer_id to null
				$this->Boxer->removeTrainers($trainer['Trainer']['id']);
				
				//Deleting contracts with this trainer
				$this->Contract->removeTrainer($trainer['Trainer']['id']);
				//$contracts = $this->Contract->find('all', array('recursive' => -1, 'fields' => array('id', 'trainer_id'), 'conditions' => array('trainer_id' => $trainer['Trainer']['id'])));
				//foreach($contracts as $contract){
				//	$this->Contract->id = $contract['Contract']['id'];
				//	$this->Contract->delete($contract['Contract']['id']);	
				//}
				
				//Send Notification to maanager about retirement
				if($trainer['Trainer']['manager_id'] != null){
					$this->Boxer->Fight->Notification->alertTrainerRetired($trainer);
				}
				
				//NEW Notifiy all Managers of retirement
				foreach($managers as $manager){
					if($manager['Manager']['id'] != $trainer['Trainer']['manager_id']){
						$this->Boxer->Fight->Notification->trainerRetiredAll($trainer, $manager['Manager']['id']);
					}
				}
				
				$this->id = $trainer['Trainer']['id'];
				$this->delete($trainer['Trainer']['id']);

			}
		}
        return($count);
	}
	
	public function replaceRetired($retired = null, $names = null, $manager_id = null){
		//creating age. 
		//$eighteen = 60 * 60 * 24 * 7 * 52 * 35;
		//$secondsTime = strtotime($game_time);
		//$secondsTime = $secondsTime - $eighteen;
		//$age = date('Y-m-d', $secondsTime);
		
		for($i = 0; $i < $retired; $i++){
			$region = rand(0, 5);
			$data['Trainer']['region'] = $region;
			//$data['Trainer']['dob'] = $age;
			if($region == 0 || $region == 2){
				$numberFirst = (count($names['euFirst']) -1);
				$numberSecond = (count($names['euSecond']) -1);
				$rand = rand(0, $numberFirst);
				$data['Trainer']['forname_id'] = $names['euFirst'][$rand]['Name']['id'];
				$rand = rand(0, $numberSecond);
				$data['Trainer']['surname_id'] = $names['euSecond'][$rand]['Name']['id'];
			} else if ($region == 1){
				$numberFirst = (count($names['saFirst']) -1);
				$numberSecond = (count($names['saSecond']) -1);
				$rand = rand(0, $numberFirst);
				$data['Trainer']['forname_id'] = $names['saFirst'][$rand]['Name']['id'];
				$rand = rand(0, $numberSecond);
				$data['Trainer']['surname_id'] = $names['saSecond'][$rand]['Name']['id'];
			} else if ($region == 3){
				$numberFirst = (count($names['meFirst']) -1);
				$numberSecond = (count($names['meSecond']) -1);
				$rand = rand(0, $numberFirst);
				$data['Trainer']['forname_id'] = $names['meFirst'][$rand]['Name']['id'];
				$rand = rand(0, $numberSecond);
				$data['Trainer']['surname_id'] = $names['meSecond'][$rand]['Name']['id'];
			} else if ($region == 4){
				$numberFirst = (count($names['afFirst']) -1);
				$numberSecond = (count($names['afSecond']) -1);
				$rand = rand(0, $numberFirst);
				$data['Trainer']['forname_id'] = $names['afFirst'][$rand]['Name']['id'];
				$rand = rand(0, $numberSecond);
				$data['Trainer']['surname_id'] = $names['afSecond'][$rand]['Name']['id'];	
			} else if ($region == 5){
				$numberFirst = (count($names['asFirst']) -1);
				$numberSecond = (count($names['asSecond']) -1);
				$rand = rand(0, $numberFirst);
				$data['Trainer']['forname_id'] = $names['asFirst'][$rand]['Name']['id'];
				$rand = rand(0, $numberSecond);
				$data['Trainer']['surname_id'] = $names['asSecond'][$rand]['Name']['id'];	
			}
			
			$rand = rand(1, 100);
			$total = $rand;
			$data['Trainer']['scout'] = $rand;
			
			$rand = rand(1, 100);
			$total = $total + $rand;
			$data['Trainer']['tech'] = $rand;
			
			$rand = rand(1, 100);
			$total = $total + $rand;
			$data['Trainer']['power'] = $rand;
			
			$rand = rand(1, 100);
			$total = $total + $rand;
			$data['Trainer']['hand_speed'] = $rand;
			
			$rand = rand(1, 100);
			$total = $total + $rand;
			$data['Trainer']['foot_speed'] = $rand;
			
			$rand = rand(1, 100);
			$total = $total + $rand;
			$data['Trainer']['block'] = $rand;
			
			$rand = rand(1, 100);
			$total = $total + $rand;
			$data['Trainer']['defence'] = $rand;
			
			$rand = rand(1, 100);
			$total = $total + $rand;
			$data['Trainer']['chin'] = $rand;
			
			$rand = rand(1, 100);
			$total = $total + $rand;
			$data['Trainer']['heart'] = $rand;
			
			$rand = rand(1, 100);
			$total = $total + $rand;
			$data['Trainer']['cut'] = $rand;
			
			$rand = rand(1, 100);
			$total = $total + $rand;
			$data['Trainer']['endurance'] = $rand;
			
			$rand = rand(1, 100);
			$total = $total + $rand;
			$data['Trainer']['corner'] = $rand;
			
			$total = $total / 10;
			$overall = round(($total / 12), 0);
			$data['Trainer']['overall'] = $overall;
			$data['Trainer']['salary'] = ($overall * 100);
			
			if($manager_id != null){
				$data['Trainer']['manager_id'] = $manager_id;	
			}
			
			$this->create();
			$this->save($data);
		}
	}
	
	//updates a trainer to have a new manager
	public function newManager($trainer_id, $manager_id){
		$this->id = $trainer_id;
		$this->saveField('manager_id', $manager_id);	
	}

    //updates Overall for trainer
    public function updateOverall($trainer_id){
        $fields = array(
            'Trainer.id', 'Trainer.scout', 'Trainer.tech', 'Trainer.power', 'Trainer.hand_speed', 'Trainer.foot_speed',
            'Trainer.block', 'Trainer.defence', 'Trainer.chin', 'Trainer.heart', 'Trainer.cut', 'Trainer.endurance',
            'Trainer.corner'
        );
        $trainer = $this->find('first', array('conditions' => array('Trainer.id' => $trainer_id), 'fields' => $fields, 'recursive' => -1));
        $total = $trainer['Trainer']['scout'] + $trainer['Trainer']['tech'] + $trainer['Trainer']['power'] + $trainer['Trainer']['hand_speed']
            + $trainer['Trainer']['foot_speed'] + $trainer['Trainer']['block'] + $trainer['Trainer']['defence'] + $trainer['Trainer']['chin']
            + $trainer['Trainer']['heart'] + $trainer['Trainer']['cut'] + $trainer['Trainer']['endurance'] + $trainer['Trainer']['corner'];

        $total = $total / 10;
        $overall = round(($total / 12), 0);
        $this->id = $trainer_id;
        $this->saveField('overall', $overall);
    }
}
