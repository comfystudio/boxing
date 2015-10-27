<?php
App::uses('AppController', 'Controller');
App::uses('PaginatorHelper', 'View/Helper');
App::uses('Sanitize', 'Utility');

class FightsController extends AppController {
	
	public function index(){
		$this->paginate = array(
			//'conditions' => array('not' => array('Fight.winner_id' => null)),
            'conditions' => array('Fight.winner_id <>' => NULL),
			'order' => 'Fight.created DESC', 'fields' => array(
				'fighter1_id', 'fighter2_id', 'winner_id', 'overview', 'Fight.id', 'Fight.created'),
					'contain' => array(
						'Fighter1' => array(
							'fields' => array('forname_id', 'surname_id', 'rank'),
							'Forname' => array(
								'fields' => array('name')
								),
							'Surname' => array(
								'fields' => array('name')
								)
							),
						'Fighter2' => array(
							'fields' => array('forname_id', 'surname_id', 'rank'),
							'Forname' => array(
								'fields' => array('name')
								),
							'Surname' => array(
								'fields' => array('name')
								)
							),
						'Winner' => array(
							'fields' => array('forname_id', 'surname_id'),
							'Forname' => array(
								'fields' => array('name')
								),
							'Surname' => array(
								'fields' => array('name')
								),
							)
						)
					);
		$this->set('fights', $this->paginate());	
	}
	
	
	public function view($id = null){
		//$game_time = $this->requestAction('params/getGameTime');
		$fight = $this->Fight->find('first', array(
		'conditions' => array('Fight.id' => $id),
				'contain' => array(
					'Fighter1' => array(
						'fields' => array('forname_id', 'surname_id', 'id', 'rank'),
						'Forname' => array(
							'fields' => array('name')
							),
						'Surname' => array(
							'fields' => array('name')
							)
						),
					'Fighter2' => array(
						'fields' => array('forname_id', 'surname_id', 'id', 'rank'),
						'Forname' => array(
							'fields' => array('name')
							),
						'Surname' => array(
							'fields' => array('name')
							)
						),
					'Winner' => array(
						'fields' => array('forname_id', 'surname_id'),
						'Forname' => array(
							'fields' => array('name')
							),
						'Surname' => array(
							'fields' => array('name')
							),
						)
					)
				)
			);
		if(!isset($fight) || empty($fight)){
			$this->Session->setFlash('This fight does not exist');
			$this->redirect(array('controller' => 'fights', 'action' => 'index'));	
		}
		$this->set(compact('fight'));
	}
	
	public function arrange_fight($boxer_id = null){
		$venues = $this->Fight->Venue->find('list');
		$venuesList = $this->Fight->Venue->find('all', array('recursive' => -1, 'fields' => array('id', 'cost', 'capacity', 'title', 'charge')));
		$balance = $this->requestAction('/managers/getBalance/'.$this->Session->read('User.manager_id'));
		//$game_time = strtotime($this->requestAction('/params/getGameTime'));
		//$game_time = date('Y-m-d', $game_time);
		
		if(!$this->Session->read('User.manager_id') || $this->Session->read('User.manager_id') == null){
			$this->Session->setFlash('Appears manager does not exist, please try relogin in and create a manager');
			$this->redirect(array('controller' => 'users', 'action' => 'login'));	
		}
		
		$this->loadModel('Boxer');
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
			'Fight' => array('conditions' => array('accepted' => '1', 'or' => array('Fight.fighter1_id' => '{$__cakeID__$}', 'Fight.fighter2_id' => '{$__cakeID__$}')),
								'fields' => array('Fight.id'),
								)
		);

        $fields = array(
            'id', 'weight_type', 'manager_id', 'injured', 'retired', 'rank', 'fame', 'tech', 'power', 'hand_speed', 'age',
            'foot_speed', 'block', 'defence', 'chin', 'heart', 'cut', 'endurance', 'wins', 'loses', 'draws', 'knockouts'
        );
		$boxer = $this->Boxer->find('first', array('contain' => $contain, 'fields' => $fields, 'conditions' => array('Boxer.id' => $boxer_id)));
		
		if(empty($boxer) || !isset($boxer)){
			$this->Session->setFlash('Boxer does not exist');
			$this->redirect($this->referer());	
		}
		
		if($boxer['Boxer']['retired'] == '1'){
			$this->Session->setFlash('Boxer is retired');
			$this->redirect($this->referer());	
		}

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
						'username'
					)
				)
			),
			'Fight' => array(
				'conditions' => array(
					'accepted' => '1',
					'or' => array(
						'Fight.fighter1_id' => '{$__cakeID__$}', 'Fight.fighter2_id' => '{$__cakeID__$}'
					)
				),
				'fields' => array('Fight.id', 'Fight.game_time'),
				)
		);

        $fields = array(
            'id', 'manager_id', 'weight_type', 'injured', 'retired', 'fame', 'rank', 'wins', 'loses', 'tech', 'power',
            'hand_speed', 'foot_speed', 'block', 'defence', 'chin', 'heart', 'cut', 'endurance', 'draws', 'knockouts', 'age'
        );
		
		if($boxer['Boxer']['manager_id'] != $this->Session->read('User.manager_id')){
			$temp = $this->Boxer->find('all', array('contain' => $contain, 'fields' => $fields, 'conditions' =>  array('retired' => '0', 'Boxer.manager_id' => $this->Session->read('User.manager_id'), 'Boxer.weight_type' => $boxer['Boxer']['weight_type']), 'order' => 'Boxer.rank ASC'));
			$opponents = array();
			$fameValue = array();
			foreach($temp as $tem){
				if($tem['Boxer']['injured'] != 1){
					$opponents[$tem['Boxer']['id']] = $tem['Forname']['name'].' '.$tem['Surname']['name'].' (R '.$tem['Boxer']['rank'].')';
					$fameValue[0] = (($boxer['Boxer']['fame'] - $boxer['Boxer']['rank']) * 100) * 1.1;
					if($fameValue[0] < 100) {
						$fameValue[0] = 100;
					}
				}
			}
		}else{
			$temp = $this->Boxer->find('all', array(
					'contain' => $contain,
					'fields' => $fields,
					'conditions' => array(
						'Boxer.weight_type' => $boxer['Boxer']['weight_type'],
						'Boxer.id <>' => $boxer['Boxer']['id'],
						'Boxer.retired' => '0',
                        'OR' => array(
                            'Boxer.manager_id <>' => $boxer['Boxer']['manager_id'],
                            'Boxer.manager_id' => NULL
                        )
					),
					'order' => 'Boxer.rank ASC'
				)
			);
			$opponents = array();
			$fameValue = array();
			foreach($temp as $tem){
				if($tem['Boxer']['manager_id'] != $this->Session->read('User.manager_id') && $tem['Boxer']['injured'] != 1){
					if(!empty($tem['Manager']['id'])) {
						$opponents[$tem['Boxer']['id']] = $tem['Forname']['name'].' '.$tem['Surname']['name'].' (R '.$tem['Boxer']['rank'].')'. ' ('.$tem['Manager']['User']['username'].')';
					} else {
						$opponents[$tem['Boxer']['id']] = $tem['Forname']['name'].' '.$tem['Surname']['name'].' (R '.$tem['Boxer']['rank'].')';
					}
					$fameValue[$tem['Boxer']['id']] = (($tem['Boxer']['fame'] - $tem['Boxer']['rank']) * 100) * 1.1;
                    $totalFights = $tem['Boxer']['wins'] + $tem['Boxer']['loses'] + $tem['Boxer']['draws'];
					if($fameValue[$tem['Boxer']['id']] < 100 && $totalFights > 30) {
						$fameValue[$tem['Boxer']['id']] = 3300;
					}elseif($fameValue[$tem['Boxer']['id']] < 100 && $totalFights > 20){
                        $fameValue[$tem['Boxer']['id']] = 2200;
                    }elseif($fameValue[$tem['Boxer']['id']] < 100 && $totalFights > 10){
                        $fameValue[$tem['Boxer']['id']] = 1100;
                    }elseif($fameValue[$tem['Boxer']['id']] < 100){
                        $fameValue[$tem['Boxer']['id']] = 100;
                    }
				}
			}
		}

        //Need to get the highest scout rated trainer that belongs to the manager
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

        $fields = array(
            'id', 'manager_id', 'forname_id', 'surname_id', 'scout'
        );
        $bestTrainer = $this->Fight->Manager->Trainer->find('first', array('conditions' => array('Trainer.manager_id' => $this->Session->read('User.manager_id')), 'limit' => 1, 'order' => array('Trainer.scout DESC'), 'contain' => $contain, 'fields' => $fields));
		
		if($this->request->is('post') || $this->request->is('put')){
            $this->request->data['Fight']['fee'] = str_replace(",", "", $this->request->data['Fight']['fee']);
            $this->request->data['Fight']['ticket_price'] = str_replace(",", "", $this->request->data['Fight']['ticket_price']);
			$this->request->data = Sanitize::clean($this->request->data, array('encode' => false));
            if(!isset($this->request->data['Fight']['fighter1_id']) || !isset($this->request->data['Fight']['fighter2_id'])){
                $this->Session->setFlash('Needs to be two fighters');
                $this->redirect($this->referer());
            }
			//$game_time = $this->requestAction('params/getGameTime');
			//$game_time2 = $game_time;
			//$game_time = strtotime($game_time);
			//$week = 60*60*24*7;
			//$fight_date = ($game_time + ($week * $this->request->data['Fight']['weeks']));
			//$fight_date = date('Y-m-d', $fight_date);
			//$this->request->data['Fight']['game_time'] = $fight_date;
			$this->request->data['Fight']['manager_id'] = $this->Session->read('User.manager_id');
			$this->Fight->create();
			if($this->Fight->save($this->request->data)){
				if($balance < ($venuesList[$this->request->data['Fight']['venue_id']-1]['Venue']['cost'] + $this->request->data['Fight']['fee'])){
					$this->Session->setFlash('You cannot afford to hire this venue and pay the fee');
					$this->redirect($this->referer());
				}
					//if fighter 1 is the original selected boxer
					$fight_id = $this->Fight->getLastInsertID();
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
					);
					if($this->request->data['Fight']['fighter1_id'] == $boxer['Boxer']['id']){
						$new_boxer = $this->Boxer->find('first', array('contain' => $contain, 'fields' => array('id', 'manager_id'), 'conditions' => array('Boxer.id' => $this->request->data['Fight']['fighter2_id'])));
					}else{
						$new_boxer = $this->Boxer->find('first', array('contain' => $contain, 'fields' => array('id', 'manager_id'), 'conditions' => array('Boxer.id' => $this->request->data['Fight']['fighter1_id'])));
					}

                if($boxer['Boxer']['manager_id'] == null || $new_boxer['Boxer']['manager_id'] == null){ //If we are dealing with a player arranging a fight with an NPC
                    //$this->Manager->Boxer->Fight->npcAccept($boxer_ids, $game_time);//NEEDS MOVED NPCS WILL respond right away#
                    if($boxer['Boxer']['manager_id'] == null){ // working out which boxer is the npc fighter
                        $data[$boxer['Boxer']['id']] = $boxer['Boxer']['id'];
                    }elseif($new_boxer['Boxer']['manager_id'] == null){
                        $data[$new_boxer['Boxer']['id']] = $new_boxer['Boxer']['id'];
                    }
                    $response = $this->Fight->npcAccept($data);
                }

                //if other boxer has a manager, create and send notification
				$sessionManager = $this->Session->read('User.manager_id');
				$this->Fight->Notification->fightOffer($this->request->data['Fight'], $boxer, $new_boxer, $sessionManager, $venues, $fight_id, $this->request->data['Fight']['note']);
					
				$this->requestAction('managers/updateBalance/'.$this->Session->read('User.manager_id').'/-'.$venuesList[$this->request->data['Fight']['venue_id']-1]['Venue']['cost']);
				$this->requestAction('managers/updateBalance/'.$this->Session->read('User.manager_id').'/-'.$this->request->data['Fight']['fee']);

                if(isset($response) && $response != null){ //if the fight was accepted then redirect to live fight
                    $this->redirect(array('controller' => 'fights', 'action' => 'live_fight', $response));
                }else{//else give generic response and redirect
                    $this->Session->setFlash('The fight offer has been made check back at your home later to see if it was accepted');
                    $this->redirect(array('controller' => 'boxers', 'action' => 'yours', $this->Session->read('User.manager_id')));
                }
			}else{
				$this->Session->setFlash('Error while saving');
			}
			
		}
		
		//$weeks = array(2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8);
        $otherBoxers = $temp;
            $this->set(compact('boxer_id', 'opponents', 'venues', 'venuesList', 'balance', 'boxer', 'fameValue', 'bestTrainer', 'otherBoxers'));
	}
	
	public function accept_fight(){
		if(!$this->Session->read('User.manager_id') || $this->Session->read('User.manager_id') == null){
			$this->Session->setFlash('Appears manager does not exist, please try reloggin in and create a manager');
			$this->redirect(array('controller' => 'users', 'action' => 'login'));	
		}
		
		if(!$this->request->is('Post')){
			$this->Session->setFlash('This page can only be accessed through accepting a fight offer');
			$this->redirect($this->referer());	
		}
		
		if($this->request->data['Fight']['fight_id'] == null || !isset($this->request->data['Fight']['fight_id'])){
			$this->Session->setFlash('Error with data');
			$this->redirect($this->referer());	
		}else{
			$this->request->data = Sanitize::clean($this->request->data, array('encode' => false));
			//$game_time = $this->requestAction('params/getGameTime');
			$fight = $this->Fight->find('first', array('recursive' => -1, 'fields' => array('id', 'fighter1_id', 'fighter2_id'), 'conditions' => array('Fight.id' => $this->request->data['Fight']['fight_id'])));
			$venues = $this->Fight->Venue->find('all', array('recursive' => -1, 'fields' => array('id', 'cost')));
			$this->Fight->id = $this->request->data['Fight']['fight_id'];
			$this->Fight->saveField('accepted', '1');
			$contain = array(
				'Fighter1' => array(
					'fields' => array(
						'id',
						'forname_id',
						'surname_id'
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
			
			);
			
			$fights = $this->Fight->find('all',
				 array(
					'fields' => array(
						'id',
						'fee',
						'venue_id',
						'fighter1_id',
						'fighter2_id'
					),
					'contain' => $contain,
					'conditions' => array(
						'accepted' => 0,
						'or' => array(
							array('fighter1_id' => $fight['Fight']['fighter1_id']),
							array('fighter1_id' => $fight['Fight']['fighter2_id']),
							array('fighter2_id' => $fight['Fight']['fighter1_id']),
							array('fighter2_id' => $fight['Fight']['fighter2_id']),
						)
						
					)
				)
			);
			foreach($fights as $fight){
				$notification = $this->Fight->Notification->find('first', array('recursive' => -1, 'fields' => array('id', 'fight_id', 'sender_id', 'recipient_id'), 'conditions' => array('fight_id' => $fight['Fight']['id'])));
				
				//refunding fee and venue cost to unsuccessful arrange_fights
				$refund = ($fight['Fight']['fee'] + $venues[$fight['Fight']['venue_id'] - 1]['Venue']['cost']);
				$this->requestAction('managers/updateBalance/'.$notification['Notification']['sender_id'].'/'.$refund);
				$refund = number_format($refund, 0, '.', ',');

				
				//Sending notification to senders that the offer has been refused.
				$data['Notification']['recipient_id'] = $notification['Notification']['sender_id'];
				$data['Notification']['sender_id'] = $notification['Notification']['recipient_id'];
				$data['Notification']['type'] = 1;
				$data['Notification']['response'] = 1;
				//$data['Notification']['game_date'] = $game_time;
				$data['Notification']['title'] = 'Offer REJECTED between <a class = "gold" href = "/boxers/view/'.$fight['Fighter1']['id'].'">'.$fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].'</a> and <a class = "gold" href = "/boxers/view/'.$fight['Fighter2']['id'].'">'.$fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].'</a>';
				$data['Notification']['text'] = '<p>Offer REJECTED between <a class = "gold" href = "/boxers/view/'.$fight['Fighter1']['id'].'">'.$fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].'</a> and <a class = "gold" href = "/boxers/view/'.$fight['Fighter2']['id'].'">'.$fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].'</a></p>
					<p>Could be a number reasons for this. Chances are you didnt offer enough money and a better offer was accepted.</p>
					<p>You have been refunded the cost of the venue and fee which come to a total of $'.$refund.'</p>';
				$this->Fight->Notification->create();
				$this->Fight->Notification->save($data);
				
				//deleting unaccepted notifications
				$this->Fight->Notification->id = $notification['Notification']['id'];
				$this->Fight->Notification->delete($notification['Notification']['id']);
				
				//deleting unaccepted fights
				$this->Fight->id = $fight['Fight']['id'];
				$this->Fight->delete($fight['Fight']['id']);
			}
			
			//Send Notification to successful offer
			$notification = $this->Fight->Notification->find('first', array('conditions' => array('Notification.id' => $this->request->data['Fight']['notification_id']), 'fields' => array('id', 'sender_id', 'recipient_id'), 'recursive' => -1));
			$fight = $this->Fight->find('first', array('conditions' => array('Fight.id' => $this->request->data['Fight']['fight_id']), 'contain' => $contain, 'fields' => array('id', 'fee', 'fighter1_id', 'fighter2_id', 'venue_id')));
			$data['Notification']['recipient_id'] = $notification['Notification']['sender_id'];
			$data['Notification']['sender_id'] = $notification['Notification']['recipient_id'];
			$data['Notification']['type'] = 1;
			$data['Notification']['response'] = 1;
			//$data['Notification']['game_date'] = $game_time;
			$data['Notification']['title'] = 'Offer ACCEPTED between <a class = "gold" href = "/boxers/view/'.$fight['Fighter1']['id'].'">'.$fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].'</a> and <a class = "gold" href = "/boxers/view/'.$fight['Fighter2']['id'].'">'.$fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].'</a>';
			$data['Notification']['text'] = '<p>Offer ACCEPTED between <a class = "gold" href = "/boxers/view/'.$fight['Fighter1']['id'].'">'.$fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].'</a> and <a class = "gold" href = "/boxers/view/'.$fight['Fighter2']['id'].'">'.$fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].'</a></p>
				<p>Well done the offer you made has been accepted.</p>
				<p>Managers note: '.$this->request->data['Fight']['message'].'</p>
				<p>LETS GET IT ON!</p>';
			$this->Fight->Notification->create();
			$this->Fight->Notification->save($data);
			
			//Updating Notification of successful offer to reponse 1
			$this->Fight->Notification->id = $this->request->data['Fight']['notification_id'];
			$this->Fight->Notification->saveField('response', 1);
			
			//Success Message + redirect
			$this->Session->setFlash('The offer has been accepted! LETS GET IT ON!');
			//$this->redirect($this->referer());
            $this->Fight->gameFights($this->request->data['Fight']['fight_id']);
            $this->redirect(array('controller' => 'fights', 'action' => 'live_fight', $this->request->data['Fight']['fight_id']));
				
		}
	}
	
	public function reject_fight(){
		if(!$this->Session->read('User.manager_id') || $this->Session->read('User.manager_id') == null){
			$this->Session->setFlash('Appears manager does not exist, please try reloggin in and create a manager');
			$this->redirect(array('controller' => 'users', 'action' => 'login'));	
		}
		
		if(!$this->request->is('Post')){
			$this->Session->setFlash('This page can only be accessed through accepting a fight offer');
			$this->redirect($this->referer());	
		}
		
		if($this->request->data['Fight']['fight_id'] == null || !isset($this->request->data['Fight']['fight_id'])){
			$this->Session->setFlash('Error with data');
			$this->redirect($this->referer());	
		}else{
			$this->request->data = Sanitize::clean($this->request->data, array('encode' => false));
			$game_time = $this->requestAction('params/getGameTime');
			$contain = array(
				'Fighter1' => array(
					'fields' => array(
						'forname_id',
						'surname_id',
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
						'forname_id',
						'surname_id',
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
			);
			$fight = $this->Fight->find('first', array('contain' => $contain, 'fields' => array('id', 'fighter1_id', 'fighter2_id', 'fee', 'venue_id'), 'conditions' => array('Fight.id' => $this->request->data['Fight']['fight_id'])));
			$venues = $this->Fight->Venue->find('all', array('recursive' => -1, 'fields' => array('id', 'cost')));
			$notification = $this->Fight->Notification->find('first', array('recursive' => -1, 'fields' => array('id', 'fight_id', 'sender_id', 'recipient_id'), 'conditions' => array('Notification.id' => $this->request->data['Fight']['notification_id'])));
			
			//refunding fee and venue cost to unsuccessful arrange_fights
			$refund = ($fight['Fight']['fee'] + $venues[$fight['Fight']['venue_id'] - 1]['Venue']['cost']);
			$this->requestAction('managers/updateBalance/'.$notification['Notification']['sender_id'].'/'.$refund);
			$refund = number_format($refund, 0, '.', ',');
			
			//Sending notification to senders that the offer has been refused.
			$data['Notification']['recipient_id'] = $notification['Notification']['sender_id'];
			$data['Notification']['sender_id'] = $notification['Notification']['recipient_id'];
			$data['Notification']['type'] = 1;
			$data['Notification']['response'] = 1;
			$data['Notification']['game_date'] = $game_time;
			$data['Notification']['title'] = 'Offer REJECTED between <a class = "gold" href = "/boxers/view/'.$fight['Fighter1']['id'].'">'.$fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].'</a> and <a class = "gold" href = "/boxers/view/'.$fight['Fighter2']['id'].'">'.$fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].'</a>';
			$data['Notification']['text'] = '<p>Offer REJECTED between <a class = "gold" href = "/boxers/view/'.$fight['Fighter1']['id'].'">'.$fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].'</a> and <a class = "gold" href = "/boxers/view/'.$fight['Fighter2']['id'].'">'.$fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].'</a></p>
				<p>Could be a number reasons for this. Chances are you didnt offer enough money and a better offer was accepted.</p>
				<p>You have been refunded the cost of the venue and fee which come to a total of $'.$refund.'</p>
				<p>Manager note: '.$this->request->data['Fight']['message'].'</p>';
			$this->Fight->Notification->create();
			$this->Fight->Notification->save($data);
			
			//deleting unaccepted notification
			$this->Fight->Notification->id = $notification['Notification']['id'];
			$this->Fight->Notification->delete($notification['Notification']['id']);
			
			//deleting unaccepted fights
			$this->Fight->id = $fight['Fight']['id'];
			$this->Fight->delete($fight['Fight']['id']);
			
			//Success message + redirect
			$this->Session->setFlash('The offer has been rejected');
			$this->redirect($this->referer());
			
		}
		
	}
	
	public function live_fight($fight_id = null){
		$fight = $this->Fight->find('first', array(
						'conditions' => array('Fight.id' => $fight_id), 
								'contain' => array(
									'Fighter1' => array(
										'fields' => array('forname_id', 'surname_id', 'id', 'rank'),
										'Forname' => array(
											'fields' => array('name')
											),
										'Surname' => array(
											'fields' => array('name')
											)
										),
									'Fighter2' => array(
										'fields' => array('forname_id', 'surname_id', 'id', 'rank'),
										'Forname' => array(
											'fields' => array('name')
											),
										'Surname' => array(
											'fields' => array('name')
											)
										),
									'Winner' => array(
										'fields' => array('forname_id', 'surname_id'),
										'Forname' => array(
											'fields' => array('name')
											),
										'Surname' => array(
											'fields' => array('name')
											),
										)
									)
								)
							);
		if(!isset($fight) || empty($fight)){
			$this->Session->setFlash('This fight does not exist');
			$this->redirect(array('controller' => 'fights', 'action' => 'index'));	
		}
		
		if($fight['Fight']['winner_id'] == NULL){
			$this->Session->setFlash('This fight is not due to start yet');
			$this->redirect(array('controller' => 'fights', 'action' => 'index'));
		}
		
		$this->set('fight', $fight);
	}
	
	public function cancelFight($id = null){
		$this->autoRender = false;
		$fight = $this->Fight->find('first', array('conditions' => array('Fight.id' => $id), 'fields' => array('id', 'fee', 'venue_id', 'manager_id'), 'recursive' => -1));
		if(empty($fight) || !isset($fight)){
			$this->Session->setFlash('This fight offer was not found');	
			$this->redirect($this->referer());
		}
		
		if(!$this->Session->read('User.manager_id') || $this->Session->read('User.manager_id') != $fight['Fight']['manager_id']){
			$this->Session->setFlash('Please login as the correct manager to preform this action');	
			$this->redirect($this->referer());
		}
		
		$venuesList = $this->Fight->Venue->find('all', array('recursive' => -1, 'fields' => array('id', 'cost')));
		$venueCost = $venuesList[$fight['Fight']['venue_id'] -1]['Venue']['cost'];
		$refund = $venueCost + $fight['Fight']['fee'];
		
		$this->requestAction('managers/updateBalance/'.$fight['Fight']['manager_id'].'/'.$refund);
		$this->Fight->id = $fight['Fight']['id'];
		$this->Fight->delete($fight['Fight']['id']);
		
		//Need to also delete notification in the case the offer was made to another player.
		$notification = $this->Fight->Notification->find('first', array('fields' => array('id', 'fight_id'), 'recursive' => -1, 'conditions' => array('fight_id' => $id)));
		$this->Fight->Notification->id = $notification['Notification']['id'];
		$this->Fight->Notification->delete($notification['Notification']['id']);
		
		$this->Session->setFlash('The fight offer has been cancelled and you have been refunded the cost of the venue + fee which was $ '.$refund);	
		$this->redirect($this->referer());die;
	}
}