<?php
App::uses('AppController', 'Controller');
App::uses('PaginatorHelper', 'View/Helper');
App::uses('Sanitize', 'Utility');

class TrainersController extends AppController {
	
	public function view($id = null){
		$this->Trainer->id = $id;
        if (!$this->Trainer->exists()) {
           $this->Session->setFlash('This trainer does not appear to exist sorry');
		   $this->redirect($this->referer());
        }else{
			$contain = array(
				'Forname' => array('fields' => 'name'),
				'Surname' => array('fields' => 'name'),
				'Manager' => array('fields' => array('Manager.id'),
									'User' => array('fields' => 'username'),
								),
				'Boxer' => array('fields' => array('Boxer.id', 'Boxer.rank', 'Boxer.weight_type'),
								'Forname' => array('fields' => 'name'),
								'Surname' => array('fields' => 'name')
								),
				'Contract' => array(
					'fields' => array(
						'id'
					)
				)
			);
			$trainer = $this->Trainer->find('first', array('contain' => $contain, 'recursive' => 2, 'conditions' =>  array('Trainer.id' => $id), 'fields' => array('Trainer.id', 'Trainer.overall', 'Trainer.dob', 'Trainer.manager_id', 'Trainer.scout', 'Trainer.tech', 'Trainer.power', 'Trainer.hand_speed', 'Trainer.foot_speed', 'Trainer.block', 'Trainer.defence', 'Trainer.chin', 'Trainer.heart', 'Trainer.cut', 'Trainer.endurance', 'Trainer.corner', 'Trainer.region')));
			$weights = Configure::read('Weight.class');
			$regions = Configure::read('Region');
			$this->set(compact('trainer', 'weights', 'regions'));
		}
	}
	
	public function index(){
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
				),
			'Manager' => array(
				'fields' => array(
					'Manager.id',
					'Manager.user_id'
				),
				'User' => array(
					'fields' => array(
						'User.username'
					)
				)
			)
		);
		$fields = array(
			'Trainer.id',
			'Trainer.dob',
			'Trainer.manager_id',
			'Trainer.overall',
			'Trainer.scout',
			'Trainer.tech',
			'Trainer.power',
			'Trainer.hand_speed',
			'Trainer.foot_speed',
			'Trainer.block',
			'Trainer.defence',
			'Trainer.chin',
			'Trainer.heart',
			'Trainer.cut',
			'Trainer.endurance'
		);
			
		$this->paginate = array('contain' => $contain, 'fields' => $fields, 'order' => array('Trainer.overall' => 'DESC'));
		$this->set('trainers', $this->paginate());
	}
	
	public function yours($id = null){
		if($this->Session->read('User.manager_id') != $id){
			$this->Session->setFlash('Managers do not match, try logging in');
			$this->redirect(array('controller' => 'users', 'action' => 'login'));		
		}
		
		if($id == NULL){
			$this->Session->setFlash('You need to create a manager before you can own trainers');
			$this->redirect(array('controller' => 'managers', 'action' => 'home'));		
		}
		
		$fields = array(
			'Trainer.id',
			'Trainer.dob',
			'Trainer.manager_id',
			'Trainer.overall',
			'Trainer.scout',
			'Trainer.tech',
			'Trainer.power',
			'Trainer.hand_speed',
			'Trainer.foot_speed',
			'Trainer.block',
			'Trainer.defence',
			'Trainer.chin',
			'Trainer.heart',
			'Trainer.cut',
			'Trainer.endurance'
		);	
		
		$contain = array(
				'Forname' => array('fields' => 'name'),
				'Surname' => array('fields' => 'name'),
				'Contract' => array(
								'fields' => array(
									'Contract.id',
									'Contract.trainer_id'
									)
								)
			);
			
		$this->paginate = array('order' => 'Trainer.overall DESC', 'fields' => $fields, 'contain' => $contain, 'conditions' => array('Trainer.manager_id' => $id, 'active' => '1'));
		
		$regions = Configure::read('Region');
		
		$this->set('trainers', $this->paginate());
		$this->set(compact('regions')); 
		
	}


    public function retrain($trainer_id){
        $this->Trainer->id = $trainer_id;
        if (!$this->Trainer->exists()) {
            $this->Session->setFlash('This trainer does not appear to exist sorry');
            $this->redirect($this->referer());
        }else{
            $contain = array(
                'Forname' => array('fields' => 'name'),
                'Surname' => array('fields' => 'name'),
                'Manager' => array('fields' => array('Manager.id'),
                    'User' => array('fields' => 'username'),
                ),
                'Boxer' => array('fields' => array('Boxer.id', 'Boxer.rank', 'Boxer.weight_type'),
                    'Forname' => array('fields' => 'name'),
                    'Surname' => array('fields' => 'name')
                ),
                'Contract' => array(
                    'fields' => array(
                        'id'
                    )
                )
            );
            $fields = array(
                'Trainer.id', 'Trainer.overall', 'Trainer.dob', 'Trainer.manager_id', 'Trainer.scout', 'Trainer.tech',
                'Trainer.power', 'Trainer.hand_speed', 'Trainer.foot_speed', 'Trainer.block', 'Trainer.defence',
                'Trainer.chin', 'Trainer.heart', 'Trainer.cut', 'Trainer.endurance', 'Trainer.corner', 'Trainer.region'
            );

            $trainer = $this->Trainer->find('first', array('contain' => $contain, 'conditions' =>  array('Trainer.id' => $trainer_id), 'fields' => $fields));
            if($trainer['Trainer']['manager_id'] != $this->Session->read('User.manager_id')){
                $this->Session->setFlash('This trainer does belong to you!');
                $this->redirect($this->referer());
            }
            $weights = Configure::read('Weight.class');
            $regions = Configure::read('Region');

            //setting options array for stats
            $statOptions = array(
                'scout' => 'Scouting', 'tech' => 'Technique', 'power' => 'Power', 'hand_speed' => 'Hand speed',
                'foot_speed' => 'Foot Speed', 'block' => 'Block', 'defence' => 'Defence', 'chin' => 'Chin',
                'heart' => 'Heart', 'cut' => 'Cuts', 'endurance' => 'Endurance', 'corner' => 'Corner'
            );

            //setting options for learn from
            $retainOptions = array(
                50000 => 'Novice ($50K)', 75000 => 'Intermediate ($75K)', 100000 => 'Adept ($100K)',
                125000 => 'Master ($125K)'
            );


            if($this->request->is('post') || $this->request->is('put')){
                //cleaning post data
                $this->request->data = Sanitize::clean($this->request->data, array('encode' => false));
                //getting manager balance
                $balance = $this->requestAction('/managers/getBalance/'.$this->Session->read('User.manager_id'));
                //if manager can't afford cost
                if($balance < $this->request->data['Trainer']['trainer']){
                    $this->Session->setFlash('You cannot afford the camp price');
                    $this->redirect($this->referer());
                }
                //removing cost from managers balance
                $this->requestAction('managers/updateBalance/'.$this->Session->read('User.manager_id').'/-'.$this->request->data['Trainer']['trainer']);

                //working out new stat value
                if($this->request->data['Trainer']['trainer'] == 50000){
                    $rand1 = rand(1, 100);
                    $rand2 = rand(1, 100);
                    $value = min($rand1, $rand2);
                }elseif($this->request->data['Trainer']['trainer'] == 75000){
                    $value = rand(1, 100);
                }elseif($this->request->data['Trainer']['trainer'] == 100000){
                    $rand1 = rand(1, 100);
                    $rand2 = rand(1, 100);
                    $value = max($rand1, $rand2);
                }elseif($this->request->data['Trainer']['trainer'] == 125000){
                    $rand1 = rand(1, 100);
                    $rand2 = rand(1, 100);
                    $rand3 = rand(1, 100);
                    $value = max($rand1, $rand2, $rand3);
                }
                $this->Trainer->saveField($this->request->data['Trainer']['stat'], $value);
                $this->Trainer->updateOverall($trainer['Trainer']['id']);

                $this->Session->setFlash('Your trainer has retrained! Hopefully he has learnt something.');
                $this->redirect(array('controller' => 'trainers', 'action' => 'view', $trainer['Trainer']['id']));
            }

            $this->set(compact('trainer', 'weights', 'regions', 'statOptions', 'retainOptions'));
        }

    }
	
	
}