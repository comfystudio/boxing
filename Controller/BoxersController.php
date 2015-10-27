<?php
App::uses('AppController', 'Controller');
App::uses('PaginatorHelper', 'View/Helper');
App::uses('Sanitize', 'Utility');

class BoxersController extends AppController {
	
	public function view($id = null){
		$this->Boxer->id = $id;
        if (!$this->Boxer->exists()) {
           $this->Session->setFlash('This boxer does not appear to exist sorry');
		   $this->redirect($this->referer());
        }else{
			$weightClasses = Configure::read('Weight.class');
			$fields = array(
				'Boxer.id',
				'Boxer.rank',
				'Boxer.age',
				'Boxer.wins',
				'Boxer.loses',
				'Boxer.draws',
				'Boxer.knockouts',
				'Boxer.floored',
				'Boxer.injured_duration',
				'Boxer.weight_type',
				'Boxer.injured',
				'Boxer.manager_id',
				'Boxer.trainer_id',
				'Boxer.tech',
				'Boxer.power',
				'Boxer.hand_speed',
				'Boxer.foot_speed',
				'Boxer.block',
				'Boxer.defence',
				'Boxer.chin',
				'Boxer.heart',
				'Boxer.cut',
				'Boxer.endurance',
				'Boxer.region',
				'Boxer.retired',
				'Boxer.confidence',
				'Boxer.happiness',
				'Boxer.ambition',
				'Boxer.greed',
				'Boxer.aggression',
				'Boxer.discipline',
				'Boxer.dirty',
				'Boxer.lifestyle',
				'Boxer.injury_prone',
				'Boxer.injured_text'
			);
			$contain = array(
				'Forname' => array('fields' => 'name'),
				'Surname' => array('fields' => 'name'),
				'Manager' => array('fields' => array('Manager.id'),
									'User' => array('fields' => 'username'),
									'Trainer' => array('fields' => array('Trainer.scout'),
														'Forname' => array('fields' => 'name'),
														'Surname' => array('fields' => 'name')
												)
								),
				'Trainer' => array('fields' => array('Trainer.id', 'Trainer.scout'),
								'Forname' => array('fields' => 'name'),
								'Surname' => array('fields' => 'name')
								),
				'Contract' => array(
								'fields' => array(
									'Contract.id',
									'Contract.boxer_id'
									)
								),
				'Fight' => array('conditions' => array(
									'winner_id <>' => null, 
									'accepted' => '1',
									'or' => array(
												array('fighter1_id' => $id),
												array('fighter2_id' => $id)
											)
								),
								'order' => 'Fight.created DESC',
								'fields' => array('Fight.id', 'Fight.overview', 'Fight.winner_id', 'fighter1_id', 'fighter2_id', 'Fight.created'),
								'Fighter1' => array('fields' => array('Fighter1.id'),
											'Forname' => array('fields' => 'name'),
											'Surname' => array('fields' => 'name')
											),
								'Fighter2' => array('fields' => array('Fighter2.id'),
											'Forname' => array('fields' => 'name'),
											'Surname' => array('fields' => 'name')
											)
								)
			);
			$boxer = $this->Boxer->find('first', array('contain' => $contain, 'fields' => $fields, 'conditions' =>  array('Boxer.id' => $id)));
			
			$contain = array(
				'Fight' => array('conditions' => array('winner_id' => null, 'accepted' => '1', 'or' => array('Fight.fighter1_id' => '{$__cakeID__$}', 'Fight.fighter2_id' => '{$__cakeID__$}')),
								'fields' => array('Fight.id', 'Fight.created'),
								)
			);
			$fight = $this->Boxer->find('first', array('contain' => $contain, 'fields' => array('id'), 'conditions' => array('Boxer.id' => $id)));
			
			$scouting = array('scout' => null, 'name' => 'No trainer');
			$highest = 0;
			if($this->Session->read('User')){
				$this->loadModel('Trainer');
				$contain = array(
					'Forname' => array('fields' => 'name'),
					'Surname' => array('fields' => 'name')
				);
				$trainers = $this->Trainer->find('all', array('contain' => $contain, 'fields' => array('Trainer.id', 'Trainer.scout',), 'conditions' => array('Trainer.manager_id' => $this->Session->read('User.manager_id'))));
				if(!empty($trainers) && isset($trainers)){
					foreach($trainers as $trainer){
						if($trainer['Trainer']['scout'] > $highest){
							//$scouting = array('scout' => $trainer['Trainer']['scout'], 'name' => $trainer['Forname']['name'].' '.$trainer['Surname']['name']);
                            $scouting = $trainer;
							$highest = $trainer['Trainer']['scout'];	
						}
					}
				}
			}
			$regions = Configure::read('Region');
			$this->set(compact('boxer', 'weightClasses', 'scouting', 'regions', 'fight'));
		}
	}
	
	
	public function index($weight = null){
		//$game_time = strtotime($this->requestAction('/params/getGameTime'));
		//$game_time = date('Y-m-d', $game_time);
		$fields = array(
			'Boxer.id',
			'Boxer.region',
			'Boxer.age',
			'Boxer.rank',
			//'Boxer.injured',
			//'Boxer.injured_duration',
			'Boxer.manager_id',
			'Boxer.weight_type',
			'Boxer.retired'
		);
		
		$contain = array(
				'Forname' => array('fields' => 'name'),
				'Surname' => array('fields' => 'name'),
				'Manager' => array('fields' => array('Manager.id'),
									'User' => array('fields' => 'username'),
									'Trainer' => array('fields' => array('Trainer.scout'),
														'Forname' => array('fields' => 'name'),
														'Surname' => array('fields' => 'name')
												)
								),
				'Trainer' => array('fields' => array('Trainer.id', 'Trainer.scout'),
								'Forname' => array('fields' => 'name'),
								'Surname' => array('fields' => 'name')
								),
				'Fight' => array('conditions' => array('Fight.winner_id' => null, 'accepted' => '1', 'or' => array('Fight.fighter1_id' => '{$__cakeID__$}', 'Fight.fighter2_id' => '{$__cakeID__$}')),
								'fields' => array('Fight.id', 'Fight.game_time'),
								)
			);
			
		if($weight != null){
			$this->paginate = array('fields' => $fields, 'contain' => $contain, 'conditions' => array('Boxer.weight_type' => $weight, 'Boxer.retired' => '0', 'Boxer.rank <>' => NULL), 'order' => array('Boxer.rank' => 'ASC'));
		}else{
			$this->paginate = array('fields' => $fields, 'contain' => $contain,  'order' => array('Boxer.rank' => 'ASC'), 'conditions' => array('Boxer.retired' => '0', 'Boxer.rank <>' => NULL));
		}
		
		$weightClasses = Configure::read('Weight.class');
		$regions = Configure::read('Region');
		
		$this->set('boxers', $this->paginate());
		$this->set(compact('regions', 'weightClasses', 'weight')); 
		
	}
	
	
	public function yours($id = null){
		if($this->Session->read('User.manager_id') != $id){
			$this->Session->setFlash('Managers do not match, try logging in');
			$this->redirect(array('controller' => 'users', 'action' => 'login'));		
		}
		
		if($id == NULL){
			$this->Session->setFlash('You need to create a manager before you can own boxers');
			$this->redirect(array('controller' => 'managers', 'action' => 'home'));		
		}
		
		
		
		$game_time = strtotime($this->requestAction('/params/getGameTime'));
		$game_time = date('Y-m-d', $game_time);
		$fields = array(
			'Boxer.id',
			'Boxer.region',
			'Boxer.age',
			'Boxer.rank',
			'Boxer.injured',
			'Boxer.injured_duration',
			'Boxer.manager_id',
			'Boxer.trainer_id',
			'Boxer.weight_type'
		);
		
		$contain = array(
				'Forname' => array('fields' => 'name'),
				'Surname' => array('fields' => 'name'),
				'Manager' => array('fields' => array('Manager.id'),
									'User' => array('fields' => 'username'),
									'Trainer' => array('fields' => array('Trainer.scout'),
														'Forname' => array('fields' => 'name'),
														'Surname' => array('fields' => 'name')
												),
								),
				'Trainer' => array('fields' => array('Trainer.id', 'Trainer.scout'),
								'Forname' => array('fields' => 'name'),
								'Surname' => array('fields' => 'name')
								),
				'Fight' => array('conditions' => array('Fight.game_time >' => $game_time, 'accepted' => '1', 'or' => array('Fight.fighter1_id' => '{$__cakeID__$}', 'Fight.fighter2_id' => '{$__cakeID__$}')),
								'fields' => array('Fight.id', 'Fight.game_time'),
								),
				'Contract' => array(
								'fields' => array(
									'Contract.id',
									'Contract.boxer_id'
									)
								)
			);
			
		$this->paginate = array('fields' => $fields, 'contain' => $contain, 'conditions' => array('Boxer.manager_id' => $id), 'order' => array('Boxer.rank' => 'ASC'));
		
		$weightClasses = Configure::read('Weight.class');
		$regions = Configure::read('Region');
		
		$this->set('boxers', $this->paginate());
		$this->set(compact('regions', 'weightClasses')); 
		
		
	}
	
	public function trainer($id = null){
		$this->Boxer->id = $id;
		if (!$this->Boxer->exists()) {
			$this->Session->setFlash('This boxer does not exist');
			$this->redirect($this->referer());
		}
		
		$fields = array(
            'Boxer.age',
            'Boxer.rank',
            'Boxer.wins',
            'Boxer.loses',
            'Boxer.draws',
            'Boxer.knockouts',
			'Boxer.id',
			'Boxer.trainer_id',
			'Boxer.manager_id',
			'Boxer.happiness',
            'Boxer.tech',
            'Boxer.power',
            'Boxer.hand_speed',
            'Boxer.foot_speed',
            'Boxer.block',
            'Boxer.defence',
            'Boxer.chin',
            'Boxer.heart',
            'Boxer.cut',
            'Boxer.endurance'
		);
		
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
					'Manager.id'
				),
				'User' => array(
					'fields' => array(
						'User.username'
					)
				),
				'Trainer' => array(
					'fields' => array(
						'Trainer.id',
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
                        'Trainer.endurance',
                        'Trainer.corner'
					),
					'order' => 'Trainer.scout DESC',
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
			)
		);
		
		$boxer = $this->Boxer->find('first', array('fields' => $fields, 'contain' => $contain, 'conditions' => array('Boxer.id' => $id)));
        $trainerData['Trainer'] = $boxer['Manager']['Trainer'][0];
        $trainerData['Forname'] = $boxer['Manager']['Trainer'][0]['Forname'];
        $trainerData['Surname'] = $boxer['Manager']['Trainer'][0]['Surname'];
		
		if($this->Session->read('User.manager_id') != $boxer['Boxer']['manager_id']){
			$this->Session->setFlash('The Manager of this contract does not appear to be you. Either you\'re a cheeky sud or you need to login');
			$this->redirect($this->referer());
		}
		
		$options = array();
		foreach($boxer['Manager']['Trainer'] as $trainer){
			$options[$trainer['id']] = $trainer['Forname']['name'].' '.$trainer['Surname']['name'];
		}
		
		if($this->request->is('post') || $this->request->is('put')){
			$happy = $boxer['Boxer']['happiness'] -5;
			$this->Boxer->id = $id;			
			$this->Boxer->saveField('trainer_id', $this->request->data['Boxer']['filter']);
			$this->Boxer->saveField('happiness', $happy);
			$this->Session->setFlash('The trainer has been changed');
			$this->redirect(array('controller' => 'boxers', 'action' => 'yours', $this->Session->read('User.manager_id')));
		}
		$this->set(compact('boxer', 'options', 'trainerData'));
		
	}

    public function training_camp($boxer_id){
        $this->Boxer->id = $boxer_id;
        if (!$this->Boxer->exists()) {
            $this->Session->setFlash('This boxer does not exist');
            $this->redirect($this->referer());
        }

        $fields = array(
            'Boxer.age', 'Boxer.rank','Boxer.wins','Boxer.loses','Boxer.draws','Boxer.knockouts',
            'Boxer.id', 'Boxer.trainer_id','Boxer.manager_id','Boxer.happiness','Boxer.tech','Boxer.power',
            'Boxer.hand_speed','Boxer.foot_speed','Boxer.block','Boxer.defence','Boxer.chin',
            'Boxer.heart','Boxer.cut','Boxer.endurance'
        );

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
                    'Manager.id'
                ),
                'User' => array(
                    'fields' => array(
                        'User.username'
                    )
                ),
                'Trainer' => array(
                    'fields' => array(
                        'Trainer.id', 'Trainer.overall', 'Trainer.scout', 'Trainer.tech', 'Trainer.power',
                        'Trainer.hand_speed', 'Trainer.foot_speed', 'Trainer.block','Trainer.defence',
                        'Trainer.chin', 'Trainer.heart','Trainer.cut','Trainer.endurance','Trainer.corner'
                    ),
                    'order' => 'Trainer.scout DESC',
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
            )
        );

        $boxer = $this->Boxer->find('first', array('fields' => $fields, 'contain' => $contain, 'conditions' => array('Boxer.id' => $boxer_id)));
        $trainerData['Trainer'] = $boxer['Manager']['Trainer'][0];
        $trainerData['Forname'] = $boxer['Manager']['Trainer'][0]['Forname'];
        $trainerData['Surname'] = $boxer['Manager']['Trainer'][0]['Surname'];

        if($this->Session->read('User.manager_id') != $boxer['Boxer']['manager_id']){
            $this->Session->setFlash('The Manager of this boxer does not appear to be you. Either you\'re a cheeky sud or you need to login');
            $this->redirect($this->referer());
        }

        $options = array();
        foreach($boxer['Manager']['Trainer'] as $trainer){
            $options[$trainer['id']] = $trainer['Forname']['name'].' '.$trainer['Surname']['name'];
        }

        $campOptions = array('Local Club ($50K)', 'Sports Institute ($75K)', 'Foreign Retreat ($100K)', 'Mount Olympus ($125K)');

        if($this->request->is('post') || $this->request->is('put')){
            //cleaning post data
            $this->request->data = Sanitize::clean($this->request->data, array('encode' => false));
            //getting manager balance
            $balance = $this->requestAction('/managers/getBalance/'.$this->Session->read('User.manager_id'));
            //working out cost of selected training camp
            if($this->request->data['Boxer']['venue'] == 0){
                $cost = 50000;
            }elseif($this->request->data['Boxer']['venue'] == 1){
                $cost = 75000;
            }elseif($this->request->data['Boxer']['venue'] == 2){
                $cost = 100000;
            }elseif($this->request->data['Boxer']['venue'] == 3){
                $cost = 125000;
            }
            //if manager can't afford cost
            if($balance < $cost){
                $this->Session->setFlash('You cannot afford the camp price');
                $this->redirect($this->referer());
            }

            //removing cost from managers balance
            $this->requestAction('managers/updateBalance/'.$this->Session->read('User.manager_id').'/-'.$cost);

            //Setting up previous and new temp trainer_ids
            $previousTrainer = $boxer['Boxer']['trainer_id'];
            $newTrainer = $this->request->data['Boxer']['filter'];

            //assigning the boxer a temp trainer then run the training function based on the camp selected
            $this->Boxer->saveField('trainer_id', $newTrainer);
            for($i = 0; $i <= ($this->request->data['Boxer']['venue'] + 1); $i++){
                $this->Boxer->training($boxer_id);
            }
            //assigning previous trainer
            $this->Boxer->saveField('trainer_id', $previousTrainer);
            $this->Session->setFlash('Your boxer has went on their training camp. Hope it was productive');
            $this->redirect(array('controller' => 'managers', 'action' => 'home'));
        }

        $this->set(compact('boxer', 'options', 'trainerData', 'campOptions'));
    }

	
	
}