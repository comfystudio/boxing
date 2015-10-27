<?php
App::uses('AppController', 'Controller');

class ManagersController extends AppController {
	
	public function home($filter = null){
		if(!$this->Session->read('User.id')){
			$this->Session->setFlash('You must be logged in to view this section');
			$this->redirect(array('controller' => 'users', 'action' => 'login'));
		}else{
			//$game_time = $this->requestAction('/params/getGameTime');
			if($filter == null){
				$new_filter	 = array('0', '1', '2', '3', '4');
			} else if($filter == 'offers') {
				$new_filter	= '1';
			} else if ($filter == 'alerts') {
				$new_filter = '2';	
			} else if ($filter == 'results')  {
				$new_filter = '3'; 	
			}
			
			$contain = array(
				'User' => array(
					'fields' => array(
						'id'
					)
				),
				'Notification' => array(
					'fields' => array(
						'id',
						'recipient_id',
						'fight_id',
						'title',
						'text',
						'type',
						'response',
						'game_date',
						'created',
                        'viewed'
					),
					'order' => array(
						'Notification.created DESC'
					),
					'conditions' => array(
						'type' => $new_filter,
						//'Notification.game_date <=' => $game_time
					),
					'limit' => '100'
				),
				'Boxer' => array(
					'fields' => array(
						'id',
						'rank'
					),
					'conditions' => array(
						'or' => array(
							array('Boxer.rank' => 1),
							array('Boxer.rank' => 0)
						)
					)
				)
			);
			$manager = $this->Manager->find('first', array('contain' => $contain, 'fields' => array('Manager.id', 'Manager.user_id', 'Manager.balance'), 'conditions' => array('Manager.id' => $this->Session->read('User.manager_id'))));
			$types = array('0' => 'All', '1' => 'Offer', '2' => 'Alert', '3' => 'Results');
			/*$contain = array(
				'Boxer' => array(
					'fields' => array(
						'id',
						'rank'
					),
					'Forname' => array(
						'name'
					),
					'Surname' => array(
						'name'
					),
					'Fight' => array(
						'fields' => array(
							'id',
							'fighter1_id',
							'fighter2_id',
							'game_time'
						),
						'conditions' => array(
							'accepted' => 1,
							'game_time >=' => $game_time,
							'or' => array(
								array('fighter1_id' => '{$__cakeID__$}'),
								array('fighter2_id' => '{$__cakeID__$}')
							)
						),
						'Fighter1' => array(
							'fields' => array(
								'id'
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
						),
						'Fighter2' => array(
							'fields' => array(
								'id'
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
					)
				)
			);*/
			
			//$fights = $this->Manager->find('first', array('contain' => $contain, 'fields' => array('Manager.id'), 'conditions' => array('Manager.id' => $this->Session->read('User.manager_id'))));
			$this->set(compact('manager', 'types', 'fights'));

            //resetting the notifications viewed to 1
            if(isset($manager['Notification']) && $manager['Notification'] != null){
                foreach($manager['Notification'] as $notifications){
                    if($notifications['viewed'] == 0){
                        $this->Manager->Notification->id = $notifications['id'];
                        $this->Manager->Notification->saveField('viewed', 1);
                    }
                }
            }
		}
	}
	
	public function index(){
		$contain = array(
			'User' => array(
				'fields' => array(
					'username'
				)
			)
		);

        $fields = array(
            'User.username', 'Manager.balance', 'Manager.career_belts_total', 'Manager.belts_held',
            'Manager.created', 'Manager.id'
        );
		
		$this->paginate = array('contain' => $contain, 'order' => 'Manager.career_belts_total DESC', 'limit' => 30, 'fields' => $fields);
	
		$this->set('managers', $this->paginate());
	}
	
	public function view($id = null){
		$this->Manager->id = $id;
        if (!$this->Manager->exists()) {
		   $this->Session->setFlash('This manager does not appear to exist sorry');
		   $this->redirect($this->referer());
        }else{
			$contain = array(
				'User' => array('fields' => 'User.username'),
				'Boxer' => array('fields' => array('Boxer.id', 'Boxer.rank', 'Boxer.weight_type'), 
								 'order' => 'Boxer.rank ASC',
									'Forname' => array('fields' => 'name'), 
									'Surname' => array('fields' => 'name')
								),
				'Trainer' => array('fields' => array('Trainer.id', 'Trainer.overall, Trainer.scout'), 
									'order' => 'Trainer.overall DESC',
									'Forname' => array('fields' => 'name'), 
									'Surname' => array('fields' => 'name')
								),
				'ManagerItem' => array('fields' => array('ManagerItem.id', 'ManagerItem.item_id'),
										'Item' => array('fields' => array('Item.title', 'Item.id', 'Item.price')
									)
								)
			);
			$this->Manager->Behaviors->attach('Containable');
			$manager = $this->Manager->find('first', array('contain' => $contain, 'recursive' => 2, 'conditions' => array('Manager.id' => $id)));
			$weights = Configure::read('Weight.class');
			$this->set(compact('manager', 'weights'));
		}
		
	}
	
	public function create(){
		
		if(!$this->Session->read('User')){
			$this->Sesssion->setFlash('You must be logged in to access this page');	
		}else{
			$managerCheck = $this->Manager->find('first', array('recursive' =>  -1, 'conditions' => array('Manager.user_id' => $this->Session->read('User.id'))));
			if(!empty($managerCheck)){
				$this->Session->setFlash('You already have a manager...Cheeky sod!');
				$this->redirect($this->referer());
			}else{
				if($this->request->is('Post')){
					$this->Manager->create();
					$this->Manager->save($this->request->data);
					$id = $this->Manager->getlastinsertid();
					$this->loadModel('User');
					$this->User->id = $this->Session->read('User.id');
					$this->User->saveField('manager_id', $id);
					$this->Session->write('User.manager_id', $id);
					
					//Creating Boxer and trainer for new manager and creating contract
					$nameData = $this->Manager->Boxer->Name->getNames();
					//$game_time = $this->requestAction('/params/getGameTime');
					$weights = Configure::read('Weight.class');
					$weight = array_rand($weights, 1);
					$weights[$weight] = 1;
					$this->Manager->Boxer->replaceRetired($weights, $nameData, $id);
					$this->Manager->Trainer->replaceRetired(1, $nameData, $id);
					
					$contain = array(
						'Boxer' => array(
							'fields' => array(
								'id'
							)
						),
						'Trainer' => array(
							'fields' => array(
								'id'
							)
						)
					);
					
					$manager = $this->Manager->find('first', array('fields' => array('Manager.id', 'Manager.user_id'), 'contain' => $contain, 'conditions' => array('Manager.id' => $id)));
					
					$this->Manager->Boxer->Contract->createBoxerContract($id, $manager['Boxer'][0]['id']);
					
					$this->Manager->Trainer->Contract->createTrainerContract($id, $manager['Trainer'][0]['id']);
			
					$this->Session->setFlash('Your manager has been created! Welcome to the big leagues!');
					$this->redirect(array('action' => 'home'));
				}else{
					$this->Session->setFlash('Something has went terribly wrong, it wasn\'t me!');
					$this->redirect($this->referer());
				}
			}
		}	
	}
	
	public function getBalance($id = null){
		if($this->params['requested']){
			$manager = $this->Manager->find('first', array('recursive' => -1,  'fields' => array('Manager.balance'), 'conditions' => array('Manager.id' => $id)));
			return $manager['Manager']['balance'];
		}	
	}
	
	public function updateBalance($id = null, $value = null){
		if($this->params['requested']){
			$manager = $this->Manager->find('first', array('recursive' => -1, 'fields' => array('id', 'balance'), 'conditions' => array('Manager.id' => $id)));
			$this->Manager->id = $id;
			$new_balance = $manager['Manager']['balance'] + $value;
			$this->Manager->saveField('balance', $new_balance);
		}	
	}
	
	public function update2661d0a6e1d1000543b2a29e4e8510f09eea5116(){
		set_time_limit(0);
		
		$this->autoRender = false;
		//Pushing game time by one week
		//$game_time = $this->Manager->Param->advanceWeek(); // removing game time from game allowing real time progress for most things
		
		//The Injured!
		//$this->Manager->Boxer->updateInjuries($game_time); // injured mechanic sucks and is too hard to maintain in a meaningful way without game time.
		
		//The Retiring Boxers
		$managers = $this->Manager->find('all', array('recursive' => -1, 'fields' => 'Manager.id'));
		$retired = $this->Manager->Boxer->checkRetired($managers);
		
		//get Name data
		$data = $this->Manager->Boxer->Name->getNames();

        //replacing the boxers that have retired
		$this->Manager->Boxer->replaceRetired($retired, $data);
		
		//The Retiring Trainers
		$retired = $this->Manager->Trainer->checkRetired($managers);
		
		//Create boxers and trainers based on manager count and replace normal retires
		$this->Manager->mangersToBoxersAndTrainers($data);
		
		//The New Trainers
		$this->Manager->Trainer->replaceRetired($retired, $data);

		//NPC Respond to Arrange_fights
		//$npcFights =  $this->Manager->Boxer->Fight->Notification->returnNpcFights();//NEEDS MOVED NPCS WILL respond right away
		//$boxer_ids = $this->Manager->Boxer->returnBoxerId($npcFights);//NEEDS MOVED NPCS WILL respond right away
		//$this->Manager->Boxer->Fight->npcAccept($boxer_ids, $game_time);//NEEDS MOVED NPCS WILL respond right away
		
		//NEW BGC Mandatory fight for champs
		$this->Manager->Boxer->mandatory();
		
		//NPC Respond to contracts
		//$this->Manager->Contract->npcResponse($game_time); //NEEDS MOVED NPCS WILL respond right away
		
		//NPC Create own fights
		$this->Manager->Boxer->npcOwnFights();
		
		//Training
		$this->Manager->Boxer->training(); //NEEDS ADDED TO WHEN A FIGHT HAS HAPPENED
		
		//Update Boxers position
		$this->Manager->Boxer->updateRanks();
		$this->Manager->Boxer->updateBelts();
		
		//Fights
		$this->Manager->Boxer->Fight->gameFights(); //ALSO NEEDS CALLED WHEN PLAYER ARRANGES A FIGHT
		
		//fighters leave that are owned by players and have been inactive for a month(maybe longer)
		$this->Manager->Boxer->inactivity();
		
		//Lower happiness for shitty Contracts
		$this->Manager->Boxer->reviewContract(); //NEEDS MOVED, THIS CHECK SHOULD BE DONE AFTER A FIGHT HAS HAPPENED
		
		//Whiners
		//$this->Manager->Boxer->whiners(); //NEEDS MOVED, THIS CHECK SHOULD BE DONE AFTER A FIGHT HAS HAPPENED
		
		//Remove Salaries
		//$this->Manager->updateSalaries($game_time);
		
		//work out Managers current Champs
		//$this->Manager->updateBeltsHeld($boxer_id); //NEEDS MOVED, THIS CHECK SHOULD BE DONE AFTER A FIGHT HAS HAPPENED
		
		//Test area to see if it is worth doing a hall of fame
		$this->Manager->Boxer->hallOfFame();
		
		//Garbage cleanup. Looking at you Notifications
		$this->Manager->Notification->cleanUp();
		$this->Manager->Fight->cleanUp();
		$this->Manager->Boxer->cleanUp();
		$this->Manager->Feed->cleanup();
		
		$this->Session->setFlash('The game has been updated!');
		$this->redirect(array('controller' => 'managers', 'action' => 'home'));
	}
}