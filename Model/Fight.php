<?php
App::uses('AppModel', 'Model');
App::uses('Security', 'Utility'); 
App::import('Component','Auth'); 
/**
 * User Model
 *
 * @property Item $Item
 */
 
class Fight extends AppModel {	
	
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
		'fee' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Fee must be numeric',
			),
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Must offer a fee'
			)
		),
		'ticket_price' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Fee must be numeric',
			),
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Must offer a fee'
			)
		)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Venue' => array(
			'className' => 'Venue',
			'foreignKey' => 'venue_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Fighter1' => array(
			'className' => 'Boxer',
			'foreignKey' => 'fighter1_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Fighter2' => array(
			'className' => 'Boxer',
			'foreignKey' => 'fighter2_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Winner' => array(
			'className' => 'Boxer',
			'foreignKey' => 'winner_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Manager' => array(
			'className' => 'Manager',
			'foreignKey' => 'manager_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
	public $hasMany = array(
		'Notification'
	);
	
	public function cancelFights($boxer_id = null, $date = null){
		$contain = array(
			'Fighter1' => array(
				'fields' => array(
					'id',
					'manager_id'
				)
			),
			'Fighter2' => array(
				'fields' => array(
					'id',
					'manager_id'
				)
			)
		);
		$fights = $this->find('all', array('contain' => $contain, 'fields' => array('id', 'accepted', 'winner_id', 'fee', 'venue_id', 'manager_id'), 'conditions' => array('winner_id' => null, 'or' => array(array('fighter1_id' => $boxer_id), array('fighter2_id' => $boxer_id)))));
		$venues = $this->Venue->find('all', array('recursive' => -1));
		foreach($fights as $fight){
			$this->Notification->removeNotifications($fight['Fight']['id']);
			if($fight['Fight']['accepted'] == 1){
				$this->Notification->alertFightCancel($fight['Fighter1']['manager_id'], $fight['Fighter2']['manager_id'], $date);
				$refund = ($fight['Fight']['fee'] + ($venues[$fight['Fight']['venue_id'] - 1]['Venue']['cost'] / 2));
			}else{
				$this->Notification->alertArrangeFightCancel($fight['Fighter1']['manager_id'], $fight['Fighter2']['manager_id'], $date);
				$refund = ($fight['Fight']['fee'] + ($venues[$fight['Fight']['venue_id'] - 1]['Venue']['cost']));
			}
			//refund fee and part of venue cost
			if($fight['Fight']['manager_id'] != null && !empty($fight['Fight']['manager_id'])){
				$this->requestAction('managers/updateBalance/'.$fight['Fight']['manager_id'].'/'.$refund);
				$this->Notification->alertRefund($fight['Fight']['manager_id'], $refund, $date);
			}
			$this->id = $fight['Fight']['id'];
			$this->delete($fight['Fight']['id']);	
		}
	}
	
	public function cancelFightbyBoxer($boxer_id){
		$fights = $this->find('all', array('fields' => array('id', 'accepted', 'winner_id', 'fighter1_id', 'fighter2_id', 'fee', 'venue_id', 'manager_id'), 'recursive' => -1, 'conditions' => array('winner_id' => null, 'accepted' => '0', 'or' => array(array('fighter1_id' => $boxer_id), array('fighter2_id' => $boxer_id)))));
		$venues = $this->Venue->find('all', array('recursive' => -1));
		foreach($fights as $fight){
			$this->Notification->alertFightCancelOverbooked($fight['Fighter1']['manager_id']);
			$refund = ($fight['Fight']['fee'] + ($venues[$fight['Fight']['venue_id'] - 1]['Venue']['cost']));
			//refund fee and part of venue cost
			if($fight['Fight']['manager_id'] != null && !empty($fight['Fight']['manager_id'])){
				$this->requestAction('managers/updateBalance/'.$fight['Fight']['manager_id'].'/'.$refund);
				$this->Notification->alertRefund($fight['Fight']['manager_id'], $refund);
			}
			$this->id = $fight['Fight']['id'];
			$this->delete($fight['Fight']['id']);	
		}
	}
	
	public function npcAccept($boxer_ids = null){
		$venues = $this->Venue->find('all', array('recursive' => -1, 'fields' => array('id', 'cost')));
		$contain = array(
			'Fighter1' => array(
				'fields' => array(
					'id',
					'forname_id',
					'surname_id',
					'manager_id',
					'rank',
					'fame',
                    'wins',
                    'loses',
                    'draws'
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
					'id',
					'forname_id',
					'surname_id',
					'manager_id',
					'rank',
					'fame',
                    'wins',
                    'loses',
                    'draws'
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
		foreach($boxer_ids as $boxer_id){
			$fights = $this->find('all', array('contain' => $contain, 'fields' => array('id', 'fighter1_id', 'fighter2_id', 'accepted', 'winner_id', 'fee', 'game_time', 'venue_id', 'manager_id'), 'conditions' => array('accepted' => '0', 'winner_id' => null, 'or' => array(array('fighter1_id' => $boxer_id), array('fighter2_id' => $boxer_id)))));
			$playersBoxer = 0;
			$accepted = array();
			$rejected = array();
			$highest = 0;
			foreach($fights as $fight){
				//setting up value of npcfighter
				$value = 0;
				if($fight['Fighter1']['manager_id'] == NULL){
					$value = ($fight['Fighter1']['fame'] - $fight['Fighter1']['rank']) * 100;
					$playersBoxer = $fight['Fighter2']['id'];
                    $opponent = $fight['Fighter1'];
				}else{
					$value = ($fight['Fighter2']['fame'] - $fight['Fighter2']['rank']) * 100;
					$playersBoxer = $fight['Fighter1']['id'];
                    $opponent = $fight['Fighter2'];
				}
				//value will always be atleast 100 and adjusted for number of fights if too low.
                $totalFights = $opponent['wins'] + $opponent['loses'] + $opponent['draws'];
				if($value < 100 && $totalFights > 30){
					$value = 3000;
				}elseif($value < 100 && $totalFights > 20){
                    $value = 2000;
                }elseif($value < 100 && $totalFights > 10){
                    $value = 1000;
                }elseif($value < 100){
                    $value = 100;
                }
				
				if($fight['Fight']['fee'] >= $highest && $fight['Fight']['fee'] >= $value){
					$accepted = $fight;
					$highest = $fight['Fight']['fee']; 	
				}
			}
			$count = 0;
			foreach($fights as $fight){
				if(isset($accepted['Fight']['id']) && $accepted['Fight']['id'] != NULL){
					if($fight['Fight']['id'] != $accepted['Fight']['id']){
						$rejected[$count] = $fight;
						$count++;
					}
				}else{
					$rejected[$count] = $fight;
					$count++;
				}
			}
			
			if(isset($accepted['Fight']['id']) && $accepted['Fight']['id'] != NULL){
				//accepting fight
				$this->id = $accepted['Fight']['id'];
				$this->saveField('accepted', '1');
				
				//Sending notification to successful offer
				//$this->Notification->acceptedFightOffer($accepted);
                $this->gameFights($accepted['Fight']['id']);
                return $accepted['Fight']['id'];
				
				//Updating Notification of successful offer to repsonse 1
				//$this->Notification->updateReponseByFightId($accepted['Fight']['id']);
			}
			
			//loop through each reject and do stuff
			foreach($rejected as $reject){
				//refunding fee and venue cost to unsuccessful offers
				//$sender_id = $this->Notification->getSenderId($reject['Fight']['id']);
				$refund = ($reject['Fight']['fee'] + $venues[$reject['Fight']['venue_id'] - 1]['Venue']['cost']);
				$this->requestAction('managers/updateBalance/'.$reject['Fight']['manager_id'].'/'.$refund);
				
				//Sending notification to senders that the offer has been refused.
				$this->Notification->rejectedFightOffer($reject);
				
				//deleting unaccepted notifications
				//$this->Notification->deleteRejectedOfferByFightId($reject['Fight']['id']);
				
				//deleting unaccepted fights
				$this->id = $reject['Fight']['id'];
				$this->delete($reject['Fight']['id']);
			}
			
			if(isset($accepted['Fight']['id']) && $accepted['Fight']['id'] != NULL){
				//Need to remove other fight offers from successful boxer
				$this->cancelFightbyBoxer($playersBoxer);
			}
		}
	}

	public function createFight($fighter1_id, $fighter2_id, $totalFame, $fee = NULL, $manager_id = NULL){
		if ($fighter2_id != NULL){
			$data['Fight']['fighter1_id'] = $fighter1_id;
			$data['Fight']['fighter2_id'] = $fighter2_id;
			if($totalFame >= 4000){
				$data['Fight']['venue_id'] = 8;
			}elseif($totalFame >= 1000){
				$data['Fight']['venue_id'] = 7;
			}elseif($totalFame >= 500){
				$data['Fight']['venue_id'] = 6;
			}elseif($totalFame >= 200){
				$data['Fight']['venue_id'] = 5;
			}elseif($totalFame >= 100){
				$data['Fight']['venue_id'] = 4;
			}elseif($totalFame >= 50){
				$data['Fight']['venue_id'] = 3;
			}elseif($totalFame >= 10){
				$data['Fight']['venue_id'] = 2;	
			}else{
				$data['Fight']['venue_id'] = 1;
			}
			$data['Fight']['accepted'] = 1;
			if(isset($fee) && $fee != NULL){
				$data['Fight']['fee'] = $fee;	
			}
			
			if(isset($manager_id) && $manager_id != NULL) {
				$data['Fight']['manager_id'] = $manager_id;
				$data['Fight']['ticket_price'] = 25;	
			}
			
			//$twoWeeks = (60*60*24*7*2);
			//$time = strtotime($game_time);
			//$time = $time + $twoWeeks;
			//$game_time = date('Y-m-d', $time);
			//$data['Fight']['game_time'] = $game_time;
			$this->create();
			$this->save($data);
		}
	}
	
	public function gameFights($fight_id = null){
        if($fight_id != null ){
            $conditions = array('accepted' => '1', 'winner_id' => null, 'Fight.id' => $fight_id);
        }else{
            $conditions = array('accepted' => '1', 'winner_id' => null);
        }
		$fights = $this->find('all', array(
			'conditions' => $conditions,
			'contain' => array(
				'Venue' => array(
					'fields' => array(
						'id',
						'capacity',
						'title'
					)
				),
				'Fighter1' => array(
					'fields' => array(
						'id',
						'rank',
						'fame',
						'aggression',
						'dirty',
						'wins',
						'loses',
						'draws',
						'knockouts',
						'floored',
						'manager_id',
						'forname_id',
						'surname_id',
						'trainer_id',
						'discipline',
						'lifestyle',
						'confidence',
						'tech',
						'power',
						'hand_speed',
						'foot_speed',
						'block',
						'defence',
						'chin',
						'heart',
						'cut',
						'endurance',
						'weight_type'
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
						),
					'Trainer' => array(
						'fields' => array(
							'corner'
							)
						),
					'Contract' => array(
						'fields' => array(
							'percentage'
							),
						'conditions' => array(
							'active' => '1'
							)
						)
					),
				'Fighter2' => array(
					'fields' => array(
						'id',
						'rank',
						'fame',
						'aggression',
						'dirty',
						'wins',
						'loses',
						'draws',
						'knockouts',
						'floored',
						'manager_id',
						'forname_id',
						'surname_id',
						'trainer_id',
						'discipline',
						'lifestyle',
						'confidence',
						'tech',
						'power',
						'hand_speed',
						'foot_speed',
						'block',
						'defence',
						'chin',
						'heart',
						'cut',
						'endurance'
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
						),
					'Trainer' => array(
						'fields' => array(
							'corner'
							)
						),
					'Contract' => array(
						'fields' => array(
							'percentage'
							),
						'conditions' => array(
							'active' => '1'
							)
						)
					)
				),
			)
		);
		
		$flavourPunchSingle = array(
			' throws a sharp left jab',
			' throws a cheeky straight',
			' throws an aggressive uppercut',
			' throws a thunderous left hook',
			' throws a random leading right hook'
		);
		
		$flavourPunchBodySingle = array(
			' throws a stiff jab to the body',
			' throws a long straight to the body',
			' throws a powerful uppercut aimed at the body',
			' throws a murderous left hook to the ribs',
			' throws a dangerous looking right hook too the ribs'
		);
		
		$flavourPunchTwo = array(
			' throws a stiff left right combo',
			' throws a jab left hook combo',
			' throws a jab and tries a thunderous uppercut',
			' throws a murderous left and right hook',
			' throws a jab right hook combo'
		);
		
		$flavourPunchBodyTwo = array(
			' throws a stiff left right combo to the body',
			' throws a jab left hook combo to the body',
			' throws a jab and tries an uppercut to the mid section',
			' throws a murderous left and right hook to the body',
			' throws a jab right hook combo to the body'
		);
		
		$flavourPunchThree = array(
			' throws a murderous left right uppercut combo',
			' throws a sharp left right left hook combo',
			' throws an aggressive left right right hook combo',
			' throws a series of hooks',
			' throws a murderous left right uppercut combo'
		);
		
		$flavourPunchBodyThree = array(
			' throws a murderous left right uppercut combo to the midsection',
			' throws a sharp left right left hook combo to the body',
			' throws an aggressive left right right hook combo to the body',
			' throws a series of hooks aimed at the ribcage',
			' throws a murderous left right uppercut combo to the midsection'
		);
		
		$flavourDirtyBlow = array(
			' rushes in with his head',
			' goes for a dirty low blow!',
			' throws a hook with more elbow than glove!',
			' throws a punch that was aimed at his opponents kidney',
			' goes for a dirty low blow!'
		);

		foreach ($fights as $fight){
			//setting up judges
			$judge1 = array();
			$judge1[0] = 0; $judge1[1] = 0;
			$judge2 = array();
			$judge2[0] = 0; $judge2[1] = 0;
			$judge3 = array();
			$judge3[0] = 0; $judge3[1] = 0;
			$lastRound = 0;
			$fightOverReason = '';
			$winnerId = 0;
			$tvMoney = NULL;
			$data = array();
			$overview = '';
			//check if fighter 1 has a manager and if they have add buff items
			if($fight['Fighter1']['manager_id'] != NULL){
				$items = $this->Manager->getItemData($fight['Fighter1']['manager_id']);
				//foreach item take the current fighter stat and add the buff value
				foreach($items as $item){
					$fight['Fighter1'][$item['Item']['buff_stat']] = $fight['Fighter1'][$item['Item']['buff_stat']] + $item['Item']['buff_value'];
				}
			}
			
			//check if fighter 2 has a manager and if they have add buff items
			if($fight['Fighter2']['manager_id'] != NULL){
				$items = $this->Manager->getItemData($fight['Fighter2']['manager_id']);
				//foreach item take the current fighter stat and add the buff value
				foreach($items as $item){
					$fight['Fighter2'][$item['Item']['buff_stat']] = $fight['Fighter2'][$item['Item']['buff_stat']] + $item['Item']['buff_value'];
				}
			}
			
			//Adjust fighter1 stats based on current Confidence
			$adjustment = ((($fight['Fighter1']['confidence'] - 50) / 5) + 100) / 100;
			$fight['Fighter1']['tech'] = $fight['Fighter1']['tech'] * $adjustment;
			$fight['Fighter1']['power'] = $fight['Fighter1']['power'] * $adjustment;
			$fight['Fighter1']['hand_speed'] = $fight['Fighter1']['hand_speed'] * $adjustment;
			$fight['Fighter1']['foot_speed'] = $fight['Fighter1']['foot_speed'] * $adjustment;
			$fight['Fighter1']['block'] = $fight['Fighter1']['block'] * $adjustment;
			$fight['Fighter1']['defence'] = $fight['Fighter1']['defence'] * $adjustment;
			$fight['Fighter1']['chin'] = $fight['Fighter1']['chin'] * $adjustment;
			$fight['Fighter1']['heart'] = $fight['Fighter1']['heart'] * $adjustment;
			$fight['Fighter1']['cut'] = $fight['Fighter1']['cut'] * $adjustment;
			$fight['Fighter1']['endurance'] = $fight['Fighter1']['endurance'] * $adjustment;
			
			//Adjust fighter2 stats based on current Confidence
			$adjustment = ((($fight['Fighter2']['confidence'] - 50) / 5) + 100) / 100;
			$fight['Fighter2']['tech'] = $fight['Fighter2']['tech'] * $adjustment;
			$fight['Fighter2']['power'] = $fight['Fighter2']['power'] * $adjustment;
			$fight['Fighter2']['hand_speed'] = $fight['Fighter2']['hand_speed'] * $adjustment;
			$fight['Fighter2']['foot_speed'] = $fight['Fighter2']['foot_speed'] * $adjustment;
			$fight['Fighter2']['block'] = $fight['Fighter2']['block'] * $adjustment;
			$fight['Fighter2']['defence'] = $fight['Fighter2']['defence'] * $adjustment;
			$fight['Fighter2']['chin'] = $fight['Fighter2']['chin'] * $adjustment;
			$fight['Fighter2']['heart'] = $fight['Fighter2']['heart'] * $adjustment;
			$fight['Fighter2']['cut'] = $fight['Fighter2']['cut'] * $adjustment;
			$fight['Fighter2']['endurance'] = $fight['Fighter2']['endurance'] * $adjustment;
			
			/*--------------------------------------FIGHT-----------------------------------------------------*/
			//Work out how many rounds their are!
			
			$highestRank = 0;
			if($fight['Fighter1']['rank'] == NULL && $fight['Fighter2']['rank'] == NULL){
				$highestRank = 30;	
			}else{
				$highestRank = min($fight['Fighter1']['rank'], $fight['Fighter2']['rank']);	
			}
			
			$totalRounds = 4;
			if($highestRank <= 5){
				$totalRounds = 12;	
			}elseif($highestRank <= 10){
				$totalRounds = 10;
			}elseif($highestRank <= 15){
				$totalRounds = 8;	
			}elseif($highestRank <= 25){
				$totalRounds = 6;	
			}
			
			$fightOver = 0;
			$knockout = 0;
			$cutStoppage = 0;
			$disqualified = 0;
			
			$fighter1Warnings = 0;
			$fighter2Warnings = 0;
			
			//fighter1 total stats
			$fighter1_total_stats = '';
			$fighter1ThrownTotal = 0;
			$fighter1LandedTotal = 0;
			$fighter1KnockdownsTotal = 0;
			$fighter1FlooredTotal = 0;
			$fighter1MissedTotal = 0;
			$fighter1BlockedTotal = 0;
			
			//fighter2 total stats
			$fighter2_total_stats = '';
			$fighter2ThrownTotal = 0;
			$fighter2LandedTotal = 0;
			$fighter2KnockdownsTotal = 0;
			$fighter2FlooredTotal = 0;
			$fighter2MissedTotal = 0;
			$fighter2BlockedTotal = 0;
			
			//figher defensive check
			$fighter1defensive = 0;
			$fighter2defensive = 0;
			
		 /********************** ALL THE ROUNDS ***********************/
		 for($i = 1; $i <= $totalRounds; $i++){
			 //if the fight is not over
			if($fightOver == 0){
				${'round'.$i.'_description'} = '';
				$rand = rand(25, 100);
				$punches1 = ($fight['Fighter1']['aggression'] + $rand) / 2;
				if($punches1 < 10){
					$punches1 = 10;	
				}
				$tempPunches1 = $punches1;
				
				$rand = rand(25, 100);
				$punches2 = ($fight['Fighter2']['aggression'] + $rand) / 2;
				if($punches2 < 10){
					$punches2 = 10;	
				}
				$tempPunches2 = $punches2;
				$totalPunches = $punches1 + $punches2;
				$fighter1Notify = 0;
				$fighter2Notify = 0;
				$fighter1Deduct = 0;
				$fighter2Deduct = 0;
				
				//fighter1 round stats
				${'fighter1_r'.$i.'_stats'} = '';
				$fighter1Thrown = 0;
				$fighter1Landed = 0;
				$fighter1Knockdowns = 0;
				$fighter1Floored = 0;
				$fighter1Missed = 0;
				$fighter1Blocked = 0;
				
				//fighter2 round stats
				${'fighter2_r'.$i.'_stats'} = '';
				$fighter2Thrown = 0;
				$fighter2Landed = 0;
				$fighter2Knockdowns = 0;
				$fighter2Floored = 0;
				$fighter2Missed = 0;
				$fighter2Blocked = 0;
				
				//if its the first round add some first round fluff
				if($i == 1){
					$round1_description .= '<p class = "gold">The referees instructions are over the fights about to begin! Schuduled for '.$totalRounds.' rounds</p>';
					if($fight['Fighter1']['confidence'] > 75){
						$round1_description .= '<p class = "gold">' . $fight['Fighter1']['Forname']['name']. ' ' .$fight['Fighter1']['Surname']['name']. ' looks very confident</p>';	
					}elseif($fight['Fighter1']['confidence'] >= 50){
						$round1_description .= '<p class = "gold">' . $fight['Fighter1']['Forname']['name']. ' ' .$fight['Fighter1']['Surname']['name']. ' looks confident</p>';	
					}elseif($fight['Fighter1']['confidence'] >= 25){
						$round1_description .= '<p class = "gold">' . $fight['Fighter1']['Forname']['name']. ' ' .$fight['Fighter1']['Surname']['name']. ' looks a little timid</p>';			
					}else{
						$round1_description .= '<p class = "gold">' . $fight['Fighter1']['Forname']['name']. ' ' .$fight['Fighter1']['Surname']['name']. ' looks afraid!</p>';	
				
					}
					
					if($fight['Fighter2']['confidence'] >= 75){
						$round1_description .= '<p class = "gold">' . $fight['Fighter2']['Forname']['name']. ' ' .$fight['Fighter2']['Surname']['name']. ' looks very confident</p>';	
					}elseif($fight['Fighter2']['confidence'] >= 50){
						$round1_description .= '<p class = "gold">' . $fight['Fighter2']['Forname']['name']. ' ' .$fight['Fighter2']['Surname']['name']. ' looks confident</p>';	
					}elseif($fight['Fighter2']['confidence'] >= 25){
						$round1_description .= '<p class = "gold">' . $fight['Fighter2']['Forname']['name']. ' ' .$fight['Fighter2']['Surname']['name']. ' looks a little timid</p>';			
					}else{
						$round1_description .= '<p class = "gold">' . $fight['Fighter2']['Forname']['name']. ' ' .$fight['Fighter2']['Surname']['name']. ' looks afraid!</p>';		
					}	
				}else{
					//if its not the first round add stats refund based on trainers corner ability
					$rand = rand(1, 100);
					if(isset($fight['Fighter1']['Trainer']['corner'])){
						$corner = ($rand + $fight['Fighter1']['Trainer']['corner']) / 2;
						$fight['Fighter1']['cut'] = $fight['Fighter1']['cut'] + ($corner / 10);
						$fight['Fighter1']['heart'] = $fight['Fighter1']['heart'] + ($corner / 20); 
						$fight['Fighter1']['endurance'] = $fight['Fighter1']['endurance'] + $corner  + 15; 
						if($corner >= 75){
							${'round'.$i.'_description'} .= '<p class = "gold">' . $fight['Fighter1']['Surname']['name']. '\'s corner has done an amazing job, he comes out looking much fresher</p>';	
						}elseif($corner >= 50){
							${'round'.$i.'_description'} .= '<p class = "gold">' . $fight['Fighter1']['Surname']['name']. '\'s corner has done a good job, he comes out looking eager</p>';		
						}elseif($corner >= 25){
							${'round'.$i.'_description'} .= '<p class = "gold">' . $fight['Fighter1']['Surname']['name']. '\'s corner has done an ok job, he comes out looking a bit better</p>';				
						}else{
							${'round'.$i.'_description'} .= '<p class = "gold">' . $fight['Fighter1']['Surname']['name']. '\'s corner advice has fallen on deaf ears, he comes out looking much the same</p>';			
						}	
					}else{
						$corner = ($rand / 2);
						$fight['Fighter1']['cut'] = $fight['Fighter1']['cut'] + ($corner / 10);
						$fight['Fighter1']['heart'] = $fight['Fighter1']['heart'] + ($corner / 20); 
						$fight['Fighter1']['endurance'] = $fight['Fighter1']['endurance'] + $corner + 15; 
						if($corner >= 75){
							${'round'.$i.'_description'} .= '<p class = "gold">' . $fight['Fighter1']['Surname']['name']. '\'s corner has done an amazing job, he comes out looking much fresher</p>';	
						}elseif($corner >= 50){
							${'round'.$i.'_description'} .= '<p class = "gold">' . $fight['Fighter1']['Surname']['name']. '\'s corner has done a good job, he comes out looking eager</p>';		
						}elseif($corner >= 25){
							${'round'.$i.'_description'} .= '<p class = "gold">' . $fight['Fighter1']['Surname']['name']. '\'s corner has done an ok job, he comes out looking a bit better</p>';				
						}else{
							${'round'.$i.'_description'} .= '<p class = "gold">' . $fight['Fighter1']['Surname']['name']. '\'s corner advice has fallen on deaf ears, he comes out looking much the same</p>';			
						}	
					}
					
					$rand = rand(1, 100);
					if(isset($fight['Fighter2']['Trainer']['corner'])){
						$corner = ($rand + $fight['Fighter2']['Trainer']['corner']) / 2;
						$fight['Fighter2']['cut'] = $fight['Fighter2']['cut'] + ($corner / 10);
						$fight['Fighter2']['heart'] = $fight['Fighter2']['heart'] + ($corner / 20); 
						$fight['Fighter2']['endurance'] = $fight['Fighter2']['endurance'] + $corner + 15; 
						if($corner >= 75){
							${'round'.$i.'_description'} .= '<p class = "gold">' . $fight['Fighter2']['Surname']['name']. '\'s corner has done an amazing job, he comes out looking much fresher</p>';	
						}elseif($corner >= 50){
							${'round'.$i.'_description'} .= '<p class = "gold">' . $fight['Fighter2']['Surname']['name']. '\'s corner has done a good job, he comes out looking eager</p>';		
						}elseif($corner >= 25){
							${'round'.$i.'_description'} .= '<p class = "gold">' . $fight['Fighter2']['Surname']['name']. '\'s corner has done an ok job, he comes out looking a bit better</p>';				
						}else{
							${'round'.$i.'_description'} .= '<p class = "gold">' . $fight['Fighter2']['Surname']['name']. '\'s corner advice has fallen on deaf ears, he comes out looking much the same</p>';			
						}	
					}else{
						$corner = ($rand / 2);
						$fight['Fighter2']['cut'] = $fight['Fighter2']['cut'] + ($corner / 10);
						$fight['Fighter2']['heart'] = $fight['Fighter2']['heart'] + ($corner / 20); 
						$fight['Fighter2']['endurance'] = $fight['Fighter2']['endurance'] + $corner + 15; 
						if($corner >= 75){
							${'round'.$i.'_description'} .= '<p class = "gold">' . $fight['Fighter2']['Surname']['name']. '\'s corner has done an amazing job, he comes out looking much fresher</p>';	
						}elseif($corner >= 50){
							${'round'.$i.'_description'} .= '<p> class = "gold">' . $fight['Fighter2']['Surname']['name']. '\'s corner has done a good job, he comes out looking eager</p>';		
						}elseif($corner >= 25){
							${'round'.$i.'_description'} .= '<p class = "gold">' . $fight['Fighter2']['Surname']['name']. '\'s corner has done an ok job, he comes out looking a bit better</p>';				
						}else{
							${'round'.$i.'_description'} .= '<p class = "gold">' . $fight['Fighter2']['Surname']['name']. '\'s corner advice has fallen on deaf ears, he comes out looking much the same</p>';			
						}	
					}
				}
				
				if($fight['Fighter1']['endurance'] < 25){
					$fight['Fighter1']['endurance'] = 25;	
				}
				
				if($fight['Fighter2']['endurance'] < 25){
					$fight['Fighter2']['endurance'] = 25;
				}
				
				//removing defensive stats if fighters went defensive.
				if($fighter1defensive == 1){
					$fight['Fighter1']['block'] = $fight['Fighter1']['block'] - 25;
					$fight['Fighter1']['defence'] = $fight['Fighter1']['defence'] - 25;
					$fighter1defensive = 0;
				}
				
				if($fighter2defensive == 1){
					$fight['Fighter2']['block'] = $fight['Fighter2']['block'] - 25;
					$fight['Fighter2']['defence'] = $fight['Fighter2']['defence'] - 25;
					$fighter2defensive = 0;
				}
				
				
				//foreach total punch of both boxers based on their aggression
				for($x = 1; $x <= $totalPunches; $x++){
					//If the fight is not over
					if($fightOver == 0 && $knockout == 0){
						//if fighter 1 has punches left for the round and endurance
						if($tempPunches1 > 0 && $fight['Fighter1']['endurance'] > 0){
							$rand = rand(1,10);
							//if single punch
							if($rand <= 6){
								$rand = rand(1,10);
								//if head or body shot
								if($rand <= 9){
									$rand = rand(0,4);
									${'round'.$i.'_description'} .= '<p>'.$fight['Fighter1']['Surname']['name'].' '.$flavourPunchSingle[$rand].'</p>';
									$result = $this->calculatePunch(1,'head', $fight, 1);
									$fight['Fighter2']['cut'] = $fight['Fighter2']['cut'] - $result['cut_damage'];
									$fight['Fighter2']['endurance'] = $fight['Fighter2']['endurance'] - $result['damage'];
									$fight['Fighter2']['heart'] = $fight['Fighter2']['heart'] - $result['heart_damage'];
									$fighter1Landed = $fighter1Landed + $result['landed'];
									$fighter1Knockdowns = $fighter1Knockdowns + $result['knockdowns'];
									$fighter2Floored = $fighter2Floored + $result['knockdowns'];
									$fighter1Missed = $fighter1Missed + $result['misses'];
									$fighter2Blocked = $fighter2Blocked + $result['blocks'];
									${'round'.$i.'_description'} .= $result['text'];
									if($result['knockouts'] >= 1){
										$knockout = 1;
										$winnerId = $fight['Fighter1']['id'];
										$lastRound = $i;
										$fightOverReason = 'knockout';
										break;	
									}elseif($result['cutStoppage'] >= 1){
										$cutStoppage = 1;
										$winnerId = $fight['Fighter1']['id'];
										$lastRound = $i;
										$fightOverReason = 'cutStoppage';
										break;
									}elseif($fighter2Floored >= 3){
										$knockout = 1;
										$winnerId = $fight['Fighter1']['id'];
										$lastRound = $i;
										$fightOverReason = 'TKO';
										${'round'.$i.'_description'} .= '<p class = "red">The ref is having a good look at '.$fight['Fighter2']['Surname']['name'].'</p>';
										${'round'.$i.'_description'} .= '<p class = "red">he\'s stopped the fight! '.$fight['Fighter1']['Surname']['name'].' has won!</p>';

									}
									//work out dirty blow + consequences
									$rand = rand(1,1000);
									$chanceToDirt = (($fight['Fighter1']['dirty'] * 10) / 25);
									if($chanceToDirt >= $rand){
										$rand = rand(0,4);
										${'round'.$i.'_description'} .= '<p>'.$fight['Fighter1']['Surname']['name'].' '.$flavourDirtyBlow[$rand].'</p>';
										//does damage to opponent
										$fight['Fighter2']['endurance'] = $fight['Fighter2']['endurance'] - 25;
										//does referee notice
										$rand = rand(1,100);
										if($rand <= 7){
											$fighter1Warnings++;
											if($fighter1Warnings == 1){
												${'round'.$i.'_description'} .= '<p class = "red">The referee has seen it and gives '.$fight['Fighter1']['Surname']['name'].' his first warning</p>';
											}elseif($fighter1Warnings == 2){
												$fighter1Deduct = 1;
												${'round'.$i.'_description'} .= '<p class = "red">'.$fight['Fighter1']['Surname']['name'].' is playing with fire! The referee gives a final warning and deducts a point!</p>';												
											}elseif($fighter1Warnings == 3){
												${'round'.$i.'_description'} .= '<p class = "red">Can you believe it! '.$fight['Fighter1']['Surname']['name'].' has fouled again and the referee has disqualified him!</p>';
												$disqualified = 1;
												$winnerId = $fight['Fighter2']['id'];
												$lastRound = $i;
												$fightOverReason = 'disqualified';	
												break;																				
											}
										}else{
											${'round'.$i.'_description'} .= '<p>The referee didn\'t noticed a thing!</p>';
										}
									}
								}else{
									$rand = rand(0,4);
									${'round'.$i.'_description'} .= '<p>'.$fight['Fighter1']['Surname']['name'].' '.$flavourPunchBodySingle[$rand].'</p>';
									$result = $this->calculatePunch(1,'body', $fight, 1);
									$fight['Fighter2']['cut'] = $fight['Fighter2']['cut'] - $result['cut_damage'];
									$fight['Fighter2']['endurance'] = $fight['Fighter2']['endurance'] - $result['damage'];
									$fight['Fighter2']['heart'] = $fight['Fighter2']['heart'] - $result['heart_damage'];
									$fighter1Landed = $fighter1Landed + $result['landed'];
									$fighter1Knockdowns = $fighter1Knockdowns + $result['knockdowns'];
									$fighter2Floored = $fighter2Floored + $result['knockdowns'];
									$fighter1Missed = $fighter1Missed + $result['misses'];
									$fighter2Blocked = $fighter2Blocked + $result['blocks'];
									${'round'.$i.'_description'} .= $result['text'];
									if($result['knockouts'] >= 1){
										$knockout = 1;
										$winnerId = $fight['Fighter1']['id'];
										$lastRound = $i;
										$fightOverReason = 'knockout';
										break;	
									}elseif($fighter2Floored >= 3){
										$knockout = 1;
										$winnerId = $fight['Fighter1']['id'];
										$lastRound = $i;
										$fightOverReason = 'TKO';
										${'round'.$i.'_description'} .= '<p class = "red">The ref is having a good look at '.$fight['Fighter2']['Surname']['name'].'</p>';
										${'round'.$i.'_description'} .= '<p class = "red">he\'s stopped the fight! '.$fight['Fighter1']['Surname']['name'].' has won!</p>';

									}
									//work out dirty blow + consequences
									$rand = rand(1,1000);
									$chanceToDirt = (($fight['Fighter1']['dirty'] * 10) / 25);
									if($chanceToDirt >= $rand){
										$rand = rand(0,4);
										${'round'.$i.'_description'} .= '<p>'.$fight['Fighter1']['Surname']['name'].' '.$flavourDirtyBlow[$rand].'</p>';
										//does damage to opponent
										$fight['Fighter2']['endurance'] = $fight['Fighter2']['endurance'] - 25;
										//does referee notice
										$rand = rand(1,100);
										if($rand <= 7){
											$fighter1Warnings++;
											if($fighter1Warnings == 1){
												${'round'.$i.'_description'} .= '<p class = "red">The referee has seen it and gives '.$fight['Fighter1']['Surname']['name'].' his first warning</p>';
											}elseif($fighter1Warnings == 2){
												$fighter1Deduct = 1;
												${'round'.$i.'_description'} .= '<p class = "red">'.$fight['Fighter1']['Surname']['name'].' is playing with fire! The referee gives a final warning and deducts a point!</p>';												
											}elseif($fighter1Warnings == 3){
												${'round'.$i.'_description'} .= '<p class = "red">Can you believe it! '.$fight['Fighter1']['Surname']['name'].' has fouled again and the referee has disqualified him!</p>';
												$disqualified = 1;
												$winnerId = $fight['Fighter2']['id'];
												$lastRound = $i;
												$fightOverReason = 'disqualified';	
												break;																				
											}
										}else{
											${'round'.$i.'_description'} .= '<p>The referee didn\'t noticed a thing!</p>';
										}
									}
								}
								
								$fighter1Thrown++;
								$tempPunches1 = $tempPunches1 - 1;
								$fight['Fighter1']['endurance'] = $fight['Fighter1']['endurance'] - 1;
							//if two punch
							}elseif($rand <= 9){
								$rand = rand(1,10);
								//if head or body shot
								if($rand <= 9){
									$rand = rand(0,4);
									${'round'.$i.'_description'}.= '<p>'.$fight['Fighter1']['Surname']['name'].' '.$flavourPunchTwo[$rand].'</p>';
									$result = $this->calculatePunch(2,'head', $fight, 1);
									$fight['Fighter2']['cut'] = $fight['Fighter2']['cut'] - $result['cut_damage'];
									$fight['Fighter2']['endurance'] = $fight['Fighter2']['endurance'] - $result['damage'];
									$fight['Fighter2']['heart'] = $fight['Fighter2']['heart'] - $result['heart_damage'];
									$fighter1Landed = $fighter1Landed + $result['landed'];
									$fighter1Knockdowns = $fighter1Knockdowns + $result['knockdowns'];
									$fighter2Floored = $fighter2Floored + $result['knockdowns'];
									$fighter1Missed = $fighter1Missed + $result['misses'];
									$fighter2Blocked = $fighter2Blocked + $result['blocks'];
									${'round'.$i.'_description'} .= $result['text'];
									if($result['knockouts'] >= 1){
										$knockout = 1;
										$winnerId = $fight['Fighter1']['id'];
										$lastRound = $i;
										$fightOverReason = 'knockout';
										break;
									}elseif($result['cutStoppage'] >= 1){
										$cutStoppage = 1;
										$winnerId = $fight['Fighter1']['id'];
										$lastRound = $i;
										$fightOverReason = 'cutStoppage';
										break;
									}elseif($fighter2Floored >= 3){
										$knockout = 1;
										$winnerId = $fight['Fighter1']['id'];
										$lastRound = $i;
										$fightOverReason = 'TKO';
										${'round'.$i.'_description'} .= '<p class = "red">The ref is having a good look at '.$fight['Fighter2']['Surname']['name'].'</p>';
										${'round'.$i.'_description'} .= '<p class = "red">he\'s stopped the fight! '.$fight['Fighter1']['Surname']['name'].' has won!</p>';

									}
									//work out dirty blow + consequences
									$rand = rand(1,1000);
									$chanceToDirt = (($fight['Fighter1']['dirty'] * 10) / 25);
									if($chanceToDirt >= $rand){
										$rand = rand(0,4);
										${'round'.$i.'_description'} .= '<p>'.$fight['Fighter1']['Surname']['name'].' '.$flavourDirtyBlow[$rand].'</p>';
										//does damage to opponent
										$fight['Fighter2']['endurance'] = $fight['Fighter2']['endurance'] - 25;
										//does referee notice
										$rand = rand(1,100);
										if($rand <= 7){
											$fighter1Warnings++;
											if($fighter1Warnings == 1){
												${'round'.$i.'_description'} .= '<p class = "red">The referee has seen it and gives '.$fight['Fighter1']['Surname']['name'].' his first warning</p>';
											}elseif($fighter1Warnings == 2){
												$fighter1Deduct = 1;
												${'round'.$i.'_description'} .= '<p class = "red">'.$fight['Fighter1']['Surname']['name'].' is playing with fire! The referee gives a final warning and deducts a point!</p>';												
											}elseif($fighter1Warnings == 3){
												${'round'.$i.'_description'} .= '<p class = "red">Can you believe it! '.$fight['Fighter1']['Surname']['name'].' has fouled again and the referee has disqualified him!</p>';
												$disqualified = 1;
												$winnerId = $fight['Fighter2']['id'];
												$lastRound = $i;
												$fightOverReason = 'disqualified';	
												break;																				
											}
										}else{
											${'round'.$i.'_description'} .= '<p>The referee didn\'t noticed a thing!</p>';
										}
									}
								}else{
									$rand = rand(0,4);
									${'round'.$i.'_description'} .= '<p>'.$fight['Fighter1']['Surname']['name'].' '.$flavourPunchBodyTwo[$rand].'</p>';
									$result = $this->calculatePunch(2,'body', $fight, 1);
									$fight['Fighter2']['cut'] = $fight['Fighter2']['cut'] - $result['cut_damage'];
									$fight['Fighter2']['endurance'] = $fight['Fighter2']['endurance'] - $result['damage'];
									$fight['Fighter2']['heart'] = $fight['Fighter2']['heart'] - $result['heart_damage'];
									$fighter1Landed = $fighter1Landed + $result['landed'];
									$fighter1Knockdowns = $fighter1Knockdowns + $result['knockdowns'];
									$fighter2Floored = $fighter2Floored + $result['knockdowns'];
									$fighter1Missed = $fighter1Missed + $result['misses'];
									$fighter2Blocked = $fighter2Blocked + $result['blocks'];
									${'round'.$i.'_description'} .= $result['text'];
									if($result['knockouts'] >= 1){
										$knockout = 1;
										$winnerId = $fight['Fighter1']['id'];
										$lastRound = $i;
										$fightOverReason = 'knockout';
										break;	
									}elseif($fighter2Floored >= 3){
										$knockout = 1;
										$winnerId = $fight['Fighter1']['id'];
										$lastRound = $i;
										$fightOverReason = 'TKO';
										${'round'.$i.'_description'} .= '<p class = "red">The ref is having a good look at '.$fight['Fighter2']['Surname']['name'].'</p>';
										${'round'.$i.'_description'} .= '<p class = "red">he\'s stopped the fight! '.$fight['Fighter1']['Surname']['name'].' has won!</p>';
									}
									//work out dirty blow + consequences
									$rand = rand(1,1000);
									$chanceToDirt = (($fight['Fighter1']['dirty'] * 10) / 25);
									if($chanceToDirt >= $rand){
										$rand = rand(0,4);
										${'round'.$i.'_description'} .= '<p>'.$fight['Fighter1']['Surname']['name'].' '.$flavourDirtyBlow[$rand].'</p>';
										//does damage to opponent
										$fight['Fighter2']['endurance'] = $fight['Fighter2']['endurance'] - 25;
										//does referee notice
										$rand = rand(1,100);
										if($rand <= 7){
											$fighter1Warnings++;
											if($fighter1Warnings == 1){
												${'round'.$i.'_description'} .= '<p class = "red">The referee has seen it and gives '.$fight['Fighter1']['Surname']['name'].' his first warning</p>';
											}elseif($fighter1Warnings == 2){
												$fighter1Deduct = 1;
												${'round'.$i.'_description'} .= '<p class = "red">'.$fight['Fighter1']['Surname']['name'].' is playing with fire! The referee gives a final warning and deducts a point!</p>';												
											}elseif($fighter1Warnings == 3){
												${'round'.$i.'_description'} .= '<p class = "red">Can you believe it! '.$fight['Fighter1']['Surname']['name'].' has fouled again and the referee has disqualified him!</p>';
												$disqualified = 1;
												$winnerId = $fight['Fighter2']['id'];
												$lastRound = $i;
												$fightOverReason = 'disqualified';	
												break;																				
											}
										}else{
											${'round'.$i.'_description'} .= '<p>The referee didn\'t noticed a thing!</p>';
										}
									}
								}
								$fighter1Thrown = $fighter1Thrown + 2;
								$tempPunches1 = $tempPunches1 - 2;
								$fight['Fighter1']['endurance'] = $fight['Fighter1']['endurance'] - 2;
							//else 3 punch combition
							}else{
								$rand = rand(1,10);
								//if head or body shot
								if($rand <= 9){
									$rand = rand(0,4);
									${'round'.$i.'_description'} .= '<p>'.$fight['Fighter1']['Surname']['name'].' '.$flavourPunchThree[$rand].'</p>';
									$result = $this->calculatePunch(3,'head', $fight, 1);
									$fight['Fighter2']['cut'] = $fight['Fighter2']['cut'] - $result['cut_damage'];
									$fight['Fighter2']['endurance'] = $fight['Fighter2']['endurance'] - $result['damage'];
									$fight['Fighter2']['heart'] = $fight['Fighter2']['heart'] - $result['heart_damage'];
									$fighter1Landed = $fighter1Landed + $result['landed'];
									$fighter1Knockdowns = $fighter1Knockdowns + $result['knockdowns'];
									$fighter2Floored = $fighter2Floored + $result['knockdowns'];
									$fighter1Missed = $fighter1Missed + $result['misses'];
									$fighter2Blocked = $fighter2Blocked + $result['blocks'];
									${'round'.$i.'_description'} .= $result['text'];
									if($result['knockouts'] >= 1){
										$knockout = 1;
										$winnerId = $fight['Fighter1']['id'];
										$lastRound = $i;
										$fightOverReason = 'knockout';
										break;
									}elseif($result['cutStoppage'] >= 1){
										$cutStoppage = 1;
										$winnerId = $fight['Fighter1']['id'];
										$lastRound = $i;
										$fightOverReason = 'cutStoppage';
										break;
									}elseif($fighter2Floored >= 3){
										$knockout = 1;
										$winnerId = $fight['Fighter1']['id'];
										$lastRound = $i;
										$fightOverReason = 'TKO';
										${'round'.$i.'_description'} .= '<p class = "red">The ref is having a good look at '.$fight['Fighter2']['Surname']['name'].'</p>';
										${'round'.$i.'_description'} .= '<p class = "red">he\'s stopped the fight! '.$fight['Fighter1']['Surname']['name'].' has won!</p>';
									}
									//work out dirty blow + consequences
									$rand = rand(1,1000);
									$chanceToDirt = (($fight['Fighter1']['dirty'] * 10) / 25);
									if($chanceToDirt >= $rand){
										$rand = rand(0,4);
										${'round'.$i.'_description'} .= '<p>'.$fight['Fighter1']['Surname']['name'].' '.$flavourDirtyBlow[$rand].'</p>';
										//does damage to opponent
										$fight['Fighter2']['endurance'] = $fight['Fighter2']['endurance'] - 25;
										//does referee notice
										$rand = rand(1,100);
										if($rand <= 7){
											$fighter1Warnings++;
											if($fighter1Warnings == 1){
												${'round'.$i.'_description'} .= '<p class = "red">The referee has seen it and gives '.$fight['Fighter1']['Surname']['name'].' his first warning</p>';
											}elseif($fighter1Warnings == 2){
												$fighter1Deduct = 1;
												${'round'.$i.'_description'} .= '<p class = "red">'.$fight['Fighter1']['Surname']['name'].' is playing with fire! The referee gives a final warning and deducts a point!</p>';												
											}elseif($fighter1Warnings == 3){
												${'round'.$i.'_description'} .= '<p class = "red">Can you believe it! '.$fight['Fighter1']['Surname']['name'].' has fouled again and the referee has disqualified him!</p>';
												$disqualified = 1;
												$winnerId = $fight['Fighter2']['id'];
												$lastRound = $i;
												$fightOverReason = 'disqualified';	
												break;																				
											}
										}else{
											${'round'.$i.'_description'} .= '<p>The referee didn\'t noticed a thing!</p>';
										}
									}
								}else{
									$rand = rand(0,4);
									${'round'.$i.'_description'} .= '<p>'.$fight['Fighter1']['Surname']['name'].' '.$flavourPunchBodyThree[$rand].'</p>';
									$result = $this->calculatePunch(3,'body', $fight, 1);
									$fight['Fighter2']['cut'] = $fight['Fighter2']['cut'] - $result['cut_damage'];
									$fight['Fighter2']['endurance'] = $fight['Fighter2']['endurance'] - $result['damage'];
									$fight['Fighter2']['heart'] = $fight['Fighter2']['heart'] - $result['heart_damage'];
									$fighter1Landed = $fighter1Landed + $result['landed'];
									$fighter1Knockdowns = $fighter1Knockdowns + $result['knockdowns'];
									$fighter2Floored = $fighter2Floored + $result['knockdowns'];
									$fighter1Missed = $fighter1Missed + $result['misses'];
									$fighter2Blocked = $fighter2Blocked + $result['blocks'];
									${'round'.$i.'_description'} .= $result['text'];
									if($result['knockouts'] >= 1){
										$knockout = 1;
										$winnerId = $fight['Fighter1']['id'];
										$lastRound = $i;
										$fightOverReason = 'knockout';
										break;	
									}elseif($fighter2Floored >= 3){
										$knockout = 1;
										$winnerId = $fight['Fighter1']['id'];
										$lastRound = $i;
										$fightOverReason = 'TKO';
										${'round'.$i.'_description'} .= '<p class = "red">The ref is having a good look at '.$fight['Fighter2']['Surname']['name'].'</p>';
										${'round'.$i.'_description'} .= '<p class = "red">he\'s stopped the fight! '.$fight['Fighter1']['Surname']['name'].' has won!</p>';
									}
									//work out dirty blow + consequences
									$rand = rand(1,1000);
									$chanceToDirt = (($fight['Fighter1']['dirty'] * 10) / 25);
									if($chanceToDirt >= $rand){
										$rand = rand(0,4);
										${'round'.$i.'_description'} .= '<p>'.$fight['Fighter1']['Surname']['name'].' '.$flavourDirtyBlow[$rand].'</p>';
										//does damage to opponent
										$fight['Fighter2']['endurance'] = $fight['Fighter2']['endurance'] - 25;
										//does referee notice
										$rand = rand(1,100);
										if($rand <= 7){
											$fighter1Warnings++;
											if($fighter1Warnings == 1){
												${'round'.$i.'_description'} .= '<p class = "red">The referee has seen it and gives '.$fight['Fighter1']['Surname']['name'].' his first warning</p>';
											}elseif($fighter1Warnings == 2){
												$fighter1Deduct = 1;
												${'round'.$i.'_description'} .= '<p class = "red">'.$fight['Fighter1']['Surname']['name'].' is playing with fire! The referee gives a final warning and deducts a point!</p>';												
											}elseif($fighter1Warnings == 3){
												${'round'.$i.'_description'} .= '<p class = "red">Can you believe it! '.$fight['Fighter1']['Surname']['name'].' has fouled again and the referee has disqualified him!</p>';
												$disqualified = 1;
												$winnerId = $fight['Fighter2']['id'];
												$lastRound = $i;
												$fightOverReason = 'disqualified';	
												break;																				
											}
										}else{
											${'round'.$i.'_description'} .= '<p>The referee didn\'t noticed a thing!</p>';
										}
									}
								}
								
								$fighter1Thrown = $fighter1Thrown + 3;
								$tempPunches1 = $tempPunches1 - 3;
								$fight['Fighter1']['endurance'] = $fight['Fighter1']['endurance'] - 3;
							}
						//if the fighter 1 has no punches left for the round but has endurance left
						}elseif($fight['Fighter1']['endurance'] > 0 && $fighter1Notify == 0){
							${'round'.$i.'_description'} .= '<p>' .$fight['Fighter1']['Surname']['name']. ' goes on the defensive!</p>';
							$fight['Fighter1']['endurance'] = $fight['Fighter1']['endurance'] + 10;
							$fight['Fighter1']['block'] = $fight['Fighter1']['block'] + 25;
							$fight['Fighter1']['defence'] = $fight['Fighter1']['defence'] + 25;
							$fighter1defensive = 1;
							$fighter1Notify = 1;	
						//if the fighter 1 has no endurance left.
						}elseif($fighter1Notify == 0){
							${'round'.$i.'_description'} .= '<p>' .$fight['Fighter1']['Surname']['name']. ' looks exhausted!</p>';
							$fighter1Notify = 1;	
						}
						
						//if fighter 2 has punches left for the round and endurance
						if($tempPunches2 > 0 && $fight['Fighter2']['endurance'] > 0){
							$rand = rand(1,10);
							//if single punch
							if($rand <= 6){
								$rand = rand(1,10);
								//if head or body shot
								if($rand <= 9){
									$rand = rand(0,4);
									${'round'.$i.'_description'} .= '<p>'.$fight['Fighter2']['Surname']['name'].' '.$flavourPunchSingle[$rand].'</p>';
									$result = $this->calculatePunch(1,'head', $fight, 2);
									$fight['Fighter1']['cut'] = $fight['Fighter1']['cut'] - $result['cut_damage'];
									$fight['Fighter1']['endurance'] = $fight['Fighter1']['endurance'] - $result['damage'];
									$fight['Fighter1']['heart'] = $fight['Fighter1']['heart'] - $result['heart_damage'];
									$fighter2Landed = $fighter2Landed + $result['landed'];
									$fighter2Knockdowns = $fighter2Knockdowns + $result['knockdowns'];
									$fighter1Floored = $fighter1Floored + $result['knockdowns'];
									$fighter2Missed = $fighter2Missed + $result['misses'];
									$fighter1Blocked = $fighter1Blocked + $result['blocks'];
									${'round'.$i.'_description'} .= $result['text'];
									if($result['knockouts'] >= 1){
										$knockout = 1;
										$winnerId = $fight['Fighter2']['id'];
										$lastRound = $i;
										$fightOverReason = 'knockout';
										break;
									}elseif($result['cutStoppage'] >= 1){
										$cutStoppage = 1;
										$winnerId = $fight['Fighter2']['id'];
										$lastRound = $i;
										$fightOverReason = 'cutStoppage';
										break;
									}elseif($fighter1Floored >= 3){
										$knockout = 1;
										$winnerId = $fight['Fighter2']['id'];
										$lastRound = $i;
										$fightOverReason = 'TKO';
										${'round'.$i.'_description'} .= '<p class = "red">The ref is having a good look at '.$fight['Fighter1']['Surname']['name'].'</p>';
										${'round'.$i.'_description'} .= '<p class = "red">he\'s stopped the fight! '.$fight['Fighter2']['Surname']['name'].' has won!</p>';
									}
									//work out dirty blow + consequences
									$rand = rand(1,1000);
									$chanceToDirt = (($fight['Fighter2']['dirty'] * 10) / 25);
									if($chanceToDirt >= $rand){
										$rand = rand(0,4);
										${'round'.$i.'_description'} .= '<p>'.$fight['Fighter2']['Surname']['name'].' '.$flavourDirtyBlow[$rand].'</p>';
										//does damage to opponent
										$fight['Fighter1']['endurance'] = $fight['Fighter1']['endurance'] - 50;
										//does referee notice
										$rand = rand(1,100);
										if($rand <= 7){
											$fighter2Warnings++;
											if($fighter2Warnings == 1){
												${'round'.$i.'_description'} .= '<p class = "red">The referee has seen it and gives '.$fight['Fighter2']['Surname']['name'].' his first warning</p>';
											}elseif($fighter2Warnings == 2){
												$fighter2Deduct = 1;
												${'round'.$i.'_description'} .= '<p class = "red">'.$fight['Fighter2']['Surname']['name'].' is playing with fire! The referee gives a final warning and deducts a point!</p>';												
											}elseif($fighter2Warnings == 3){
												${'round'.$i.'_description'} .= '<p class = "red">Can you believe it! '.$fight['Fighter2']['Surname']['name'].' has fouled again and the referee has disqualified him!</p>';
												$disqualified = 1;
												$winnerId = $fight['Fighter1']['id'];
												$lastRound = $i;
												$fightOverReason = 'disqualified';	
												break;																				
											}
										}else{
											${'round'.$i.'_description'} .= '<p>The referee didn\'t noticed a thing!</p>';
										}
									}
								}else{
									$rand = rand(0,4);
									${'round'.$i.'_description'} .= '<p>'.$fight['Fighter2']['Surname']['name'].' '.$flavourPunchBodySingle[$rand].'</p>';
									$result = $this->calculatePunch(1,'body', $fight, 2);
									$fight['Fighter1']['cut'] = $fight['Fighter1']['cut'] - $result['cut_damage'];
									$fight['Fighter1']['endurance'] = $fight['Fighter1']['endurance'] - $result['damage'];
									$fight['Fighter1']['heart'] = $fight['Fighter1']['heart'] - $result['heart_damage'];
									$fighter2Landed = $fighter2Landed + $result['landed'];
									$fighter2Knockdowns = $fighter2Knockdowns + $result['knockdowns'];
									$fighter1Floored = $fighter1Floored + $result['knockdowns'];
									$fighter2Missed = $fighter2Missed + $result['misses'];
									$fighter1Blocked = $fighter1Blocked + $result['blocks'];
									${'round'.$i.'_description'} .= $result['text'];
									if($result['knockouts'] >= 1){
										$knockout = 1;
										$winnerId = $fight['Fighter2']['id'];
										$lastRound = $i;
										$fightOverReason = 'knockout';
										break;	
									}elseif($fighter1Floored >= 3){
										$knockout = 1;
										$winnerId = $fight['Fighter2']['id'];
										$lastRound = $i;
										$fightOverReason = 'TKO';
										${'round'.$i.'_description'} .= '<p class = "red">The ref is having a good look at '.$fight['Fighter1']['Surname']['name'].'</p>';
										${'round'.$i.'_description'} .= '<p class = "red">he\'s stopped the fight! '.$fight['Fighter2']['Surname']['name'].' has won!</p>';
									}
									//work out dirty blow + consequences
									$rand = rand(1,1000);
									$chanceToDirt = (($fight['Fighter2']['dirty'] * 10) / 25);
									if($chanceToDirt >= $rand){
										$rand = rand(0,4);
										${'round'.$i.'_description'} .= '<p>'.$fight['Fighter2']['Surname']['name'].' '.$flavourDirtyBlow[$rand].'</p>';
										//does damage to opponent
										$fight['Fighter1']['endurance'] = $fight['Fighter1']['endurance'] - 50;
										//does referee notice
										$rand = rand(1,100);
										if($rand <= 7){
											$fighter2Warnings++;
											if($fighter2Warnings == 1){
												${'round'.$i.'_description'} .= '<p class = "red">The referee has seen it and gives '.$fight['Fighter2']['Surname']['name'].' his first warning</p>';
											}elseif($fighter2Warnings == 2){
												$fighter2Deduct = 1;
												${'round'.$i.'_description'} .= '<p class = "red">'.$fight['Fighter2']['Surname']['name'].' is playing with fire! The referee gives a final warning and deducts a point!</p>';												
											}elseif($fighter2Warnings == 3){
												${'round'.$i.'_description'} .= '<p class = "red">Can you believe it! '.$fight['Fighter2']['Surname']['name'].' has fouled again and the referee has disqualified him!</p>';
												$disqualified = 1;
												$winnerId = $fight['Fighter1']['id'];
												$lastRound = $i;
												$fightOverReason = 'disqualified';	
												break;																				
											}
										}else{
											${'round'.$i.'_description'} .= '<p>The referee didn\'t noticed a thing!</p>';
										}
									}
								}
								
								$fighter2Thrown++;
								$tempPunches2 = $tempPunches2 - 1;
								$fight['Fighter2']['endurance'] = $fight['Fighter2']['endurance'] - 1;
								
							//if two punch
							}elseif($rand <= 9){
								$rand = rand(1,10);
								//if head or body shot
								if($rand <= 9){
									$rand = rand(0,4);
									${'round'.$i.'_description'} .= '<p>'.$fight['Fighter2']['Surname']['name'].' '.$flavourPunchTwo[$rand].'</p>';
									$result = $this->calculatePunch(2,'head', $fight, 2);
									$fight['Fighter1']['cut'] = $fight['Fighter1']['cut'] - $result['cut_damage'];
									$fight['Fighter1']['endurance'] = $fight['Fighter1']['endurance'] - $result['damage'];
									$fight['Fighter1']['heart'] = $fight['Fighter1']['heart'] - $result['heart_damage'];
									$fighter2Landed = $fighter2Landed + $result['landed'];
									$fighter2Knockdowns = $fighter2Knockdowns + $result['knockdowns'];
									$fighter1Floored = $fighter1Floored + $result['knockdowns'];
									$fighter2Missed = $fighter2Missed + $result['misses'];
									$fighter1Blocked = $fighter1Blocked + $result['blocks'];
									${'round'.$i.'_description'} .= $result['text'];
									if($result['knockouts'] >= 1){
										$knockout = 1;
										$winnerId = $fight['Fighter2']['id'];
										$lastRound = $i;
										$fightOverReason = 'knockout';
										break;	
									}elseif($result['cutStoppage'] >= 1){
										$cutStoppage = 1;
										$winnerId = $fight['Fighter2']['id'];
										$lastRound = $i;
										$fightOverReason = 'cutStoppage';
										break;
									}elseif($fighter1Floored >= 3){
										$knockout = 1;
										$winnerId = $fight['Fighter2']['id'];
										$lastRound = $i;
										$fightOverReason = 'TKO';
										${'round'.$i.'_description'} .= '<p class = "red">The ref is having a good look at '.$fight['Fighter1']['Surname']['name'].'</p>';
										${'round'.$i.'_description'} .= '<p class = "red">he\'s stopped the fight! '.$fight['Fighter2']['Surname']['name'].' has won!</p>';
									}
									//work out dirty blow + consequences
									$rand = rand(1,1000);
									$chanceToDirt = (($fight['Fighter2']['dirty'] * 10) / 25);
									if($chanceToDirt >= $rand){
										$rand = rand(0,4);
										${'round'.$i.'_description'} .= '<p>'.$fight['Fighter2']['Surname']['name'].' '.$flavourDirtyBlow[$rand].'</p>';
										//does damage to opponent
										$fight['Fighter1']['endurance'] = $fight['Fighter1']['endurance'] - 50;
										//does referee notice
										$rand = rand(1,100);
										if($rand <= 7){
											$fighter2Warnings++;
											if($fighter2Warnings == 1){
												${'round'.$i.'_description'} .= '<p class = "red">The referee has seen it and gives '.$fight['Fighter2']['Surname']['name'].' his first warning</p>';
											}elseif($fighter2Warnings == 2){
												$fighter2Deduct = 1;
												${'round'.$i.'_description'} .= '<p class = "red">'.$fight['Fighter2']['Surname']['name'].' is playing with fire! The referee gives a final warning and deducts a point!</p>';												
											}elseif($fighter2Warnings == 3){
												${'round'.$i.'_description'} .= '<p class = "red">Can you believe it! '.$fight['Fighter2']['Surname']['name'].' has fouled again and the referee has disqualified him!</p>';
												$disqualified = 1;
												$winnerId = $fight['Fighter1']['id'];
												$lastRound = $i;
												$fightOverReason = 'disqualified';	
												break;																				
											}
										}else{
											${'round'.$i.'_description'} .= '<p>The referee didn\'t noticed a thing!</p>';
										}
									}
								}else{
									$rand = rand(0,4);
									${'round'.$i.'_description'} .= '<p>'.$fight['Fighter2']['Surname']['name'].' '.$flavourPunchBodyTwo[$rand].'</p>';
									$result = $this->calculatePunch(2,'body', $fight, 2);
									$fight['Fighter1']['cut'] = $fight['Fighter1']['cut'] - $result['cut_damage'];
									$fight['Fighter1']['endurance'] = $fight['Fighter1']['endurance'] - $result['damage'];
									$fight['Fighter1']['heart'] = $fight['Fighter1']['heart'] - $result['heart_damage'];
									$fighter2Landed = $fighter2Landed + $result['landed'];
									$fighter2Knockdowns = $fighter2Knockdowns + $result['knockdowns'];
									$fighter1Floored = $fighter1Floored + $result['knockdowns'];
									$fighter2Missed = $fighter2Missed + $result['misses'];
									$fighter1Blocked = $fighter1Blocked + $result['blocks'];
									${'round'.$i.'_description'} .= $result['text'];
									if($result['knockouts'] >= 1){
										$knockout = 1;
										$winnerId = $fight['Fighter2']['id'];
										$lastRound = $i;
										$fightOverReason = 'knockout';
										break;	
									}elseif($fighter1Floored >= 3){
										$knockout = 1;
										$winnerId = $fight['Fighter2']['id'];
										$lastRound = $i;
										$fightOverReason = 'TKO';
										${'round'.$i.'_description'} .= '<p class = "red">The ref is having a good look at '.$fight['Fighter1']['Surname']['name'].'</p>';
										${'round'.$i.'_description'} .= '<p class = "red">he\'s stopped the fight! '.$fight['Fighter2']['Surname']['name'].' has won!</p>';
									}
									//work out dirty blow + consequences
									$rand = rand(1,1000);
									$chanceToDirt = (($fight['Fighter2']['dirty'] * 10) / 25);
									if($chanceToDirt >= $rand){
										$rand = rand(0,4);
										${'round'.$i.'_description'} .= '<p>'.$fight['Fighter2']['Surname']['name'].' '.$flavourDirtyBlow[$rand].'</p>';
										//does damage to opponent
										$fight['Fighter1']['endurance'] = $fight['Fighter1']['endurance'] - 50;
										//does referee notice
										$rand = rand(1,100);
										if($rand <= 7){
											$fighter2Warnings++;
											if($fighter2Warnings == 1){
												${'round'.$i.'_description'} .= '<p class = "red">The referee has seen it and gives '.$fight['Fighter2']['Surname']['name'].' his first warning</p>';
											}elseif($fighter2Warnings == 2){
												$fighter2Deduct = 1;
												${'round'.$i.'_description'} .= '<p class = "red">'.$fight['Fighter2']['Surname']['name'].' is playing with fire! The referee gives a final warning and deducts a point!</p>';												
											}elseif($fighter2Warnings == 3){
												${'round'.$i.'_description'} .= '<p class = "red">Can you believe it! '.$fight['Fighter2']['Surname']['name'].' has fouled again and the referee has disqualified him!</p>';
												$disqualified = 1;
												$winnerId = $fight['Fighter1']['id'];
												$lastRound = $i;
												$fightOverReason = 'disqualified';	
												break;																				
											}
										}else{
											${'round'.$i.'_description'} .= '<p>The referee didn\'t noticed a thing!</p>';
										}
									}
								}
								
								$fighter2Thrown = $fighter2Thrown + 2;
								$tempPunches2 = $tempPunches2 - 2;
								$fight['Fighter2']['endurance'] = $fight['Fighter2']['endurance'] - 2;
								
							//if three punch combo
							}else{
								$rand = rand(1,10);
								//if head or body shot
								if($rand <= 9){
									$rand = rand(0,4);
									${'round'.$i.'_description'} .= '<p>'.$fight['Fighter2']['Surname']['name'].' '.$flavourPunchThree[$rand].'</p>';
									$result = $this->calculatePunch(3,'head', $fight, 2);
									$fight['Fighter1']['cut'] = $fight['Fighter1']['cut'] - $result['cut_damage'];
									$fight['Fighter1']['endurance'] = $fight['Fighter1']['endurance'] - $result['damage'];
									$fight['Fighter1']['heart'] = $fight['Fighter1']['heart'] - $result['heart_damage'];
									$fighter2Landed = $fighter2Landed + $result['landed'];
									$fighter2Knockdowns = $fighter2Knockdowns + $result['knockdowns'];
									$fighter1Floored = $fighter1Floored + $result['knockdowns'];
									$fighter2Missed = $fighter2Missed + $result['misses'];
									$fighter1Blocked = $fighter1Blocked + $result['blocks'];
									${'round'.$i.'_description'} .= $result['text'];
									if($result['knockouts'] >= 1){
										$knockout = 1;
										$winnerId = $fight['Fighter2']['id'];
										$lastRound = $i;
										$fightOverReason = 'knockout';
										break;	
									}elseif($result['cutStoppage'] >= 1){
										$cutStoppage = 1;
										$winnerId = $fight['Fighter2']['id'];
										$lastRound = $i;
										$fightOverReason = 'cutStoppage';
										break;
									}elseif($fighter1Floored >= 3){
										$knockout = 1;
										$winnerId = $fight['Fighter2']['id'];
										$lastRound = $i;
										$fightOverReason = 'TKO';
										${'round'.$i.'_description'} .= '<p class = "red">The ref is having a good look at '.$fight['Fighter1']['Surname']['name'].'</p>';
										${'round'.$i.'_description'} .= '<p class = "red">he\'s stopped the fight! '.$fight['Fighter2']['Surname']['name'].' has won!</p>';
									}
									//work out dirty blow + consequences
									$rand = rand(1,1000);
									$chanceToDirt = (($fight['Fighter2']['dirty'] * 10) / 25);
									if($chanceToDirt >= $rand){
										$rand = rand(0,4);
										${'round'.$i.'_description'} .= '<p>'.$fight['Fighter2']['Surname']['name'].' '.$flavourDirtyBlow[$rand].'</p>';
										//does damage to opponent
										$fight['Fighter1']['endurance'] = $fight['Fighter1']['endurance'] - 50;
										//does referee notice
										$rand = rand(1,100);
										if($rand <= 7){
											$fighter2Warnings++;
											if($fighter2Warnings == 1){
												${'round'.$i.'_description'} .= '<p class = "red">The referee has seen it and gives '.$fight['Fighter2']['Surname']['name'].' his first warning</p>';
											}elseif($fighter2Warnings == 2){
												$fighter2Deduct = 1;
												${'round'.$i.'_description'} .= '<p class = "red">'.$fight['Fighter2']['Surname']['name'].' is playing with fire! The referee gives a final warning and deducts a point!</p>';												
											}elseif($fighter2Warnings == 3){
												${'round'.$i.'_description'} .= '<p class = "red">Can you believe it! '.$fight['Fighter2']['Surname']['name'].' has fouled again and the referee has disqualified him!</p>';
												$disqualified = 1;
												$winnerId = $fight['Fighter1']['id'];
												$lastRound = $i;
												$fightOverReason = 'disqualified';	
												break;																				
											}
										}else{
											${'round'.$i.'_description'} .= '<p>The referee didn\'t noticed a thing!</p>';
										}
									}
								}else{
									$rand = rand(0,4);
									${'round'.$i.'_description'} .= '<p>'.$fight['Fighter2']['Surname']['name'].' '.$flavourPunchBodyThree[$rand].'</p>';
									$result = $this->calculatePunch(3,'body', $fight, 2);
									$fight['Fighter1']['cut'] = $fight['Fighter1']['cut'] - $result['cut_damage'];
									$fight['Fighter1']['endurance'] = $fight['Fighter1']['endurance'] - $result['damage'];
									$fight['Fighter1']['heart'] = $fight['Fighter1']['heart'] - $result['heart_damage'];
									$fighter2Landed = $fighter2Landed + $result['landed'];
									$fighter2Knockdowns = $fighter2Knockdowns + $result['knockdowns'];
									$fighter1Floored = $fighter1Floored + $result['knockdowns'];
									$fighter2Missed = $fighter2Missed + $result['misses'];
									$fighter1Blocked = $fighter1Blocked + $result['blocks'];
									${'round'.$i.'_description'} .= $result['text'];
									if($result['knockouts'] >= 1){
										$knockout = 1;
										$winnerId = $fight['Fighter2']['id'];
										$lastRound = $i;
										$fightOverReason = 'knockout';
										break;	
									}elseif($fighter1Floored >= 3){
										$knockout = 1;
										$winnerId = $fight['Fighter2']['id'];
										$lastRound = $i;
										$fightOverReason = 'TKO';
										${'round'.$i.'_description'} .= '<p class = "red">The ref is having a good look at '.$fight['Fighter1']['Surname']['name'].'</p>';
										${'round'.$i.'_description'} .= '<p class = "red">he\'s stopped the fight! '.$fight['Fighter2']['Surname']['name'].' has won!</p>';
									}
									//work out dirty blow + consequences
									$rand = rand(1,1000);
									$chanceToDirt = (($fight['Fighter2']['dirty'] * 10) / 25);
									if($chanceToDirt >= $rand){
										$rand = rand(0,4);
										${'round'.$i.'_description'} .= '<p>'.$fight['Fighter2']['Surname']['name'].' '.$flavourDirtyBlow[$rand].'</p>';
										//does damage to opponent
										$fight['Fighter1']['endurance'] = $fight['Fighter1']['endurance'] - 50;
										//does referee notice
										$rand = rand(1,100);
										if($rand <= 7){
											$fighter2Warnings++;
											if($fighter2Warnings == 1){
												${'round'.$i.'_description'} .= '<p class = "red">The referee has seen it and gives '.$fight['Fighter2']['Surname']['name'].' his first warning</p>';
											}elseif($fighter2Warnings == 2){
												$fighter2Deduct = 1;
												${'round'.$i.'_description'} .= '<p class = "red">'.$fight['Fighter2']['Surname']['name'].' is playing with fire! The referee gives a final warning and deducts a point!</p>';												
											}elseif($fighter2Warnings == 3){
												${'round'.$i.'_description'} .= '<p class = "red">Can you believe it! '.$fight['Fighter2']['Surname']['name'].' has fouled again and the referee has disqualified him!</p>';
												$disqualified = 1;
												$winnerId = $fight['Fighter1']['id'];
												$lastRound = $i;
												$fightOverReason = 'disqualified';	
												break;																				
											}
										}else{
											${'round'.$i.'_description'} .= '<p>The referee didn\'t noticed a thing!</p>';
										}
									}
								}
								
								$fighter2Thrown = $fighter2Thrown + 3;
								$tempPunches2 = $tempPunches2 - 3;
								$fight['Fighter2']['endurance'] = $fight['Fighter2']['endurance'] - 3;
								
							}
						
						//if the fighter 2 has no punches left for the round but has endurance left	
						}elseif($fight['Fighter2']['endurance'] > 0 && $fighter2Notify == 0){
							${'round'.$i.'_description'} .= '<p>' .$fight['Fighter2']['Surname']['name']. ' goes on the defensive!</p>';
							$fight['Fighter2']['endurance'] = $fight['Fighter2']['endurance'] + 10;
							$fight['Fighter2']['block'] = $fight['Fighter2']['block'] + 25;
							$fight['Fighter2']['defence'] = $fight['Fighter2']['defence'] + 25;
							$fighter2defensive = 1;
							$fighter2Notify = 1;	
						//if the fighter 2 has no endurance left.
						}elseif($fighter2Notify == 0){
							${'round'.$i.'_description'} .= '<p>' .$fight['Fighter2']['Surname']['name']. ' looks exhausted!</p>';	
							$fighter2Notify = 1;
						}
					}
				}
				
				//END OF ROUND
				//judge1 awards shots landed
				if($fighter1Landed > $fighter2Landed){
					$temp1 = (10 - ($fighter1Floored + $fighter1Deduct));
					$temp2 = (9 - ($fighter2Floored + $fighter2Deduct));
				}else{
					$temp1 = (9 - ($fighter1Floored + $fighter1Deduct));
					$temp2 = (10 - ($fighter2Floored + $fighter2Deduct));
				}
				$judge1[0] = $judge1[0] + $temp1;
				$judge1[1] = $judge1[1] + $temp2;
				
				//work out who won the round for the announcer
				if($temp1 >= $temp2){
					$tempRoundWinner = $fight['Fighter1']['Surname']['name'];
				}else{
					$tempRoundWinner = $fight['Fighter2']['Surname']['name'];
				}
				
				//judge2 awards on shots thrown
				if($fighter1Thrown > $fighter2Thrown){
					$temp1 = (10 - ($fighter1Floored + $fighter1Deduct));
					$temp2 = (9 - ($fighter2Floored + $fighter2Deduct));
				}else{
					$temp1 = (9 - ($fighter1Floored + $fighter1Deduct));
					$temp2 = (10 - ($fighter2Floored + $fighter2Deduct));
				}
				$judge2[0] = $judge2[0] + $temp1;
				$judge2[1] = $judge2[1] + $temp2;;

                //judge3 awards based on higher % of shots thrown / landed.
                if($fighter1Thrown > 0){
                    $fighter1Percentage = ((100 / $fighter1Thrown) * $fighter1Landed);
                }else{
                    $fighter1Percentage = 0;
                }
                if($fighter2Thrown > 0){
                    $fighter2Percentage = ((100 / $fighter2Thrown) * $fighter2Landed);
                }else{
                    $fighter2Percentage = 0;
                }

                if($fighter1Percentage > $fighter2Percentage){
                    $temp1 = (10 - ($fighter1Floored + $fighter1Deduct));
                    $temp2 = (9 - ($fighter2Floored + $fighter2Deduct));
                }else{
                    $temp1 = (9 - ($fighter1Floored + $fighter1Deduct));
                    $temp2 = (10 - ($fighter2Floored + $fighter2Deduct));
                }
                $judge3[0] = $judge3[0] + $temp1;
                $judge3[1] = $judge3[1] + $temp2;
				
				//tell if its the final round or not
				if($i == $totalRounds && $fightOver == 0 && $knockout == 0 && $cutStoppage == 0 && $disqualified == 0){
					${'round'.$i.'_description'} .= '<p class = "gold">Ladies and gentlement this fight is over and will go to the judges!</p>';
				}elseif($fightOver == 0 && $knockout == 0 &&  $cutStoppage == 0 && $disqualified == 0){
					${'round'.$i.'_description'} .= '<p class = "gold">Round '.$i.' comes to a close and both fighters head back to their corners</p>';
					${'round'.$i.'_description'} .= '<p class = "gold">In my opinion I think ' . $tempRoundWinner. ' won the round</p>';
				}
				${'round'.$i.'_description'} .= '<END OF ROUND>';
				
				if($fightOver == 0){
					//fighter1 round stats
					${'fighter1_r'.$i.'_stats'} = ' '.$fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].' ';
					${'fighter1_r'.$i.'_stats'} .= '<p>Punches Thrown: '.$fighter1Thrown.'</p>';
					${'fighter1_r'.$i.'_stats'} .= '<p>Punches Landed: '.$fighter1Landed.'</p>';
					${'fighter1_r'.$i.'_stats'} .= '<p>Punches Blocked: '.$fighter1Blocked.'</p>';
					${'fighter1_r'.$i.'_stats'} .= '<p>Punches Missed: '.$fighter1Missed.'</p>';
					${'fighter1_r'.$i.'_stats'} .= '<p>Knockdowns: '.$fighter1Knockdowns.'</p>';
					${'fighter1_r'.$i.'_stats'} .= '<p>Floored: '.$fighter1Floored.'</p>';
					//fighter1 totals update
					$fighter1ThrownTotal = $fighter1ThrownTotal + $fighter1Thrown;
					$fighter1LandedTotal = $fighter1LandedTotal + $fighter1Landed;
					$fighter1BlockedTotal = $fighter1BlockedTotal + $fighter1Blocked;
					$fighter1MissedTotal = $fighter1MissedTotal + $fighter1Missed;
					$fighter1KnockdownsTotal = $fighter1KnockdownsTotal + $fighter1Knockdowns;
					$fighter1FlooredTotal = $fighter1FlooredTotal + $fighter1Floored;
					
					//fighter2 round stats
					${'fighter2_r'.$i.'_stats'} = ' '.$fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].' ';
					${'fighter2_r'.$i.'_stats'} .= '<p>Punches Thrown: '.$fighter2Thrown.'</p>';
					${'fighter2_r'.$i.'_stats'} .= '<p>Punches Landed: '.$fighter2Landed.'</p>';
					${'fighter2_r'.$i.'_stats'} .= '<p>Punches Blocked: '.$fighter2Blocked.'</p>';
					${'fighter2_r'.$i.'_stats'} .= '<p>Punches Missed: '.$fighter2Missed.'</p>';
					${'fighter2_r'.$i.'_stats'} .= '<p>Knockdowns: '.$fighter2Knockdowns.'</p>';
					${'fighter2_r'.$i.'_stats'} .= '<p>Floored: '.$fighter2Floored.'</p>';
					//fighter2 totals update
					$fighter2ThrownTotal = $fighter2ThrownTotal + $fighter2Thrown;
					$fighter2LandedTotal = $fighter2LandedTotal + $fighter2Landed;
					$fighter2BlockedTotal = $fighter2BlockedTotal + $fighter2Blocked;
					$fighter2MissedTotal = $fighter2MissedTotal + $fighter2Missed;
					$fighter2KnockdownsTotal = $fighter2KnockdownsTotal + $fighter2Knockdowns;
					$fighter2FlooredTotal = $fighter2FlooredTotal + $fighter2Floored;
					
					$data['Fight']['fighter1_r'.$i.'_stats'] = ${'fighter1_r'.$i.'_stats'};
					$data['Fight']['fighter2_r'.$i.'_stats'] = ${'fighter2_r'.$i.'_stats'};
					$data['Fight']['round'.$i.'_description'] = ${'round'.$i.'_description'};
				}
				
				if($knockout == 1 || $cutStoppage == 1 || $disqualified == 1){
					$fightOver = 1;	
				}
				
			}
		 }
		//END OF ALL ROUNDS	
		
		//IF fight has not ended after the total number of rounds go to the scorecard
		$temp = 0;
		if($judge1[0] > $judge1[1]){
			$temp++;	
		}elseif($judge1[0] < $judge1[1]){
			$temp--;	
		}
		
		if($judge2[0] > $judge2[1]){
			$temp++;	
		}elseif($judge2[0] < $judge2[1]){
			$temp--;	
		}
		
		if($judge3[0] > $judge3[1]){
			$temp++;	
		}elseif($judge3[0] < $judge3[1]){
			$temp--;	
		}
			
			
		if($fightOver == 0){
			${'round'.$totalRounds.'_description'} = '<p>The boxers are with the referee and the results are in</p>';
			${'round'.$totalRounds.'_description'} .= '<p>The first judge scores the fight</p>';
			${'round'.$totalRounds.'_description'} .= '<p> ' .$judge1[0]. ' to '.$fight['Fighter1']['Forname']['name'].' ' .$fight['Fighter1']['Surname']['name']. ' and </p>';		
			${'round'.$totalRounds.'_description'} .= '<p> ' .$judge1[1]. ' to '.$fight['Fighter2']['Forname']['name'].' ' .$fight['Fighter2']['Surname']['name']. '</p>';
			${'round'.$totalRounds.'_description'} .= '<p>The second judge scores the fight</p>';
			${'round'.$totalRounds.'_description'} .= '<p> ' .$judge2[0]. ' to '.$fight['Fighter1']['Forname']['name'].' ' .$fight['Fighter1']['Surname']['name']. ' and </p>';		
			${'round'.$totalRounds.'_description'} .= '<p> ' .$judge2[1]. ' to '.$fight['Fighter2']['Forname']['name'].' ' .$fight['Fighter2']['Surname']['name']. '</p>';
			${'round'.$totalRounds.'_description'} .= '<p>The third judge scores the fight</p>';
			${'round'.$totalRounds.'_description'} .= '<p> ' .$judge3[0]. ' to '.$fight['Fighter1']['Forname']['name'].' ' .$fight['Fighter1']['Surname']['name']. ' and </p>';		
			${'round'.$totalRounds.'_description'} .= '<p> ' .$judge3[1]. ' to '.$fight['Fighter2']['Forname']['name'].' ' .$fight['Fighter2']['Surname']['name']. '</p>';
			if($temp == 3){
				${'round'.$totalRounds.'_description'} .= '<p class = "gold">The results are unanimous the winner is '.$fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].'!</p>';
				$winnerId = $fight['Fighter1']['id'];
				$fightOverReason = 'pointsUnanimous';
			}elseif($temp >= 1){
				${'round'.$totalRounds.'_description'} .= '<p class = "gold">We have a split decision and the winner is '.$fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].'!</p>';
				$winnerId = $fight['Fighter1']['id'];
				$fightOverReason = 'pointsSplit';
			}elseif($temp == 0){
				${'round'.$totalRounds.'_description'} .= '<p class = "gold">We have a split decision and the fight is a......draw!</p>';
				$winnerId = 'draw';
				$fightOverReason = 'draw';
			}elseif($temp == -3){
				${'round'.$totalRounds.'_description'} .= '<p class = "gold">The results are unanimous the winner is '.$fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].'!</p>';
				$winnerId = $fight['Fighter2']['id'];
				$fightOverReason = 'pointsUnanimous';
			}elseif($temp <= -1){
				${'round'.$totalRounds.'_description'} .= '<p class = "gold">We have a split decision and the winner is '.$fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].'!</p>';
				$winnerId = $fight['Fighter2']['id'];
				$fightOverReason = 'pointsSplit';
			}
			$fightOver = 1;
			$data['Fight']['round'.$totalRounds.'_description'] .= ${'round'.$totalRounds.'_description'};
		}
			
		//END OF FIGHT OVERVIEW + STATS
		
		//working out rank change.
		if($fight['Fighter1']['rank'] < $fight['Fighter2']['rank']){
			$rank[0] = $fight['Fighter1']['rank'];
			$rank[1] = $fight['Fighter2']['rank'];	
		}else{
			$rank[0] = $fight['Fighter2']['rank'];
			$rank[1] = $fight['Fighter1']['rank'];	
		}
		
		if($fightOver >= 1){
			//Working out attendence
			$totalFame =  $fight['Fighter1']['fame'] + $fight['Fighter2']['fame'];
			$rankAdjustment = ((150 - $rank[0]) / 100);
			if($rankAdjustment < 1){
				$rankAdjustment = 1;	
			}
			if($fight['Fight']['ticket_price'] == NULL || !isset($fight['Fight']['ticket_price'])){
				$fight['Fight']['ticket_price'] = 15;	
			}
			
			if($fight['Fight']['ticket_price'] > 20){
				$priceAdjustment = (15 / ($fight['Fight']['ticket_price'] + ($fight['Fight']['ticket_price'] * 0.75)));
			}else{
				$priceAdjustment = (15 / $fight['Fight']['ticket_price']);
			}
			$attendence = ((($totalFame * 15) * $rankAdjustment) * $priceAdjustment);
			
			//if attendence is greater than venue capacity then set the attendence to max capacity
			if($attendence > $fight['Venue']['capacity']){
				$attendence = $fight['Venue']['capacity'];	
			}
			//if attendence is less than 100 then generate a 1-100 rand attendence. 
			if($attendence < 100 && $fight['Fight']['ticket_price'] <= 50){
				$rand = rand(1,100);
				$attendence = $rand;	
			}
			$attendence = floor($attendence);
			$data['Fight']['attendence'] = $attendence;
			
			//Working out total fight Money
			$totalMoney = $attendence * $fight['Fight']['ticket_price'];
			if($totalFame > 100){
				$tvMoney = $totalFame * 100;
				$totalMoney = $totalMoney + $tvMoney;
			}
			
			
			//setting overview for the fight
			//getting fullname of the winner
			if($winnerId == $fight['Fighter1']['id']){
				$fullnameWinner  = $fight['Fighter1']['Forname']['name'].' '. $fight['Fighter1']['Surname']['name'];
				$fullnameLoser  = $fight['Fighter2']['Forname']['name'].' '. $fight['Fighter2']['Surname']['name'];	
				$fullnameWinnerId = $fight['Fighter1']['id'];
				$fullnameLoserId = $fight['Fighter2']['id'];
			}else{
				$fullnameWinner  = $fight['Fighter2']['Forname']['name'].' '. $fight['Fighter2']['Surname']['name'];
				$fullnameLoser  = $fight['Fighter1']['Forname']['name'].' '. $fight['Fighter1']['Surname']['name'];		
				$fullnameWinnerId = $fight['Fighter2']['id'];
				$fullnameLoserId = $fight['Fighter1']['id'];
			}
			
			//checking to what the reason for the fight ending was an outputting correct $overview as a result
			if($fightOverReason == 'knockout'){
				$overview .= '<p>'.$fullnameWinner .' destroys '.$fullnameLoser.' in round '.$lastRound.' with a knockout finish</p>';
				if(abs($temp > 2)){
					$overview .= '<p>In what was a largely one sided afair, few were suprised with the end result</p>';
				}else{
					$overview .= '<p>The fight was a tense close fought afair, until the stunning knockout came</p>';
				}
			}
			
			if($fightOverReason == 'pointsUnanimous'){
				$overview = '<p>'.$fullnameWinner .' defeats '.$fullnameLoser.' with an unanimous points decision</p>';
				$overview .= '<p>The fight went the full '.$totalRounds.' rounds which saw '.$fullnameWinner.' dominate and control the fight</p>';
			}
			
			if($fightOverReason == 'pointsSplit'){
				$overview = '<p>'.$fullnameWinner .' defeats '.$fullnameLoser.' with a split points decision</p>';
				$overview .= '<p>The fight went the full '.$totalRounds.' rounds. Some could argue about the result, but the winner is the right one</p>';
			}
			
			if($fightOverReason == 'draw'){
				$overview = '<p>'.$fullnameWinner .' and '.$fullnameLoser.' draw after going the distance</p>';
				$overview .= '<p>The fight went the full '.$totalRounds.' rounds. Disappointedly the judges couldn\'t separate the pair and scored it as a draw</p>';
			}
			
			if($fightOverReason == 'cutStoppage'){
				$overview = '<p>'.$fullnameWinner .' defeats '.$fullnameLoser.' due to a nasty cut above the eye of '.$fullnameLoser.'</p>';
				$overview .= '<p>The ref stopped the fight in round '.$lastRound.' stating '.$fullnameLoser.' couldn\'t properly defend himself with such a bad cut</p>';
			}
			
			if($fightOverReason == 'disqualified'){
				$overview = '<p>'.$fullnameWinner .' defeats '.$fullnameLoser.' due to a disqualification</p>';
				$overview .= '<p>The ref stopped the fight in round '.$lastRound.' after '.$fullnameLoser.' repeatedly foul blowed his opponent despite being warned twice by the ref</p>';
			}
			
			if($fightOverReason == 'TKO'){
				$overview = '<p>'.$fullnameWinner .' defeats '.$fullnameLoser.' due to a TKO</p>';
				$overview .= '<p>The ref stopped the fight in round '.$lastRound.' because '.$fullnameLoser.' could not defend himself properly and was forced to wave the fight over</p>';
			}
			
			$overview .= '<p>The venue was '.$fight['Venue']['title'].' and the fight attracted '.$attendence.' people</p>';
			$data['Fight']['overview'] = $overview;
			
			//fighter1 total stats
			$fighter1_total_stats = ' '.$fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].' ';
			$fighter1_total_stats .= '<p>Punches Thrown: '.$fighter1ThrownTotal.'</p>';
			$fighter1_total_stats .= '<p>Punches Landed: '.$fighter1LandedTotal.'</p>';
			$fighter1_total_stats .= '<p>Punches Blocked: '.$fighter1BlockedTotal.'</p>';
			$fighter1_total_stats .= '<p>Punches Missed: '.$fighter1MissedTotal.'</p>';
			$fighter1_total_stats .= '<p>Knockdowns: '.$fighter1KnockdownsTotal.'</p>';
			$fighter1_total_stats .= '<p>Floored: '.$fighter1FlooredTotal.'</p>';
			
			//fighter2 total stats
			$fighter2_total_stats = ' '.$fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].' ';
			$fighter2_total_stats .= '<p>Punches Thrown: '.$fighter2ThrownTotal.'</p>';
			$fighter2_total_stats .= '<p>Punches Landed: '.$fighter2LandedTotal.'</p>';
			$fighter2_total_stats .= '<p>Punches Blocked: '.$fighter2BlockedTotal.'</p>';
			$fighter2_total_stats .= '<p>Punches Missed: '.$fighter2MissedTotal.'</p>';
			$fighter2_total_stats .= '<p>Knockdowns: '.$fighter2KnockdownsTotal.'</p>';
			$fighter2_total_stats .= '<p>Floored: '.$fighter2FlooredTotal.'</p>';
			
			$data['Fight']['fighter1_total_stats'] = $fighter1_total_stats;
			$data['Fight']['fighter2_total_stats'] = $fighter2_total_stats;
			$data['Fight']['winner_id'] = $winnerId;
		}
			
			
			/*----------------------------------END OF FIGHT--------------------------------------------------*/
			
			
			//Managers cut of totalMoney and notification of said monies!
			if($fight['Fight']['manager_id'] !=  NULL){
				if($fight['Fight']['manager_id'] == $fight['Fighter1']['manager_id']){
					$percentage =  ($fight['Fighter1']['Contract']['percentage'] / 100) * $totalMoney;
					$this->Manager->updateBalance($fight['Fight']['manager_id'], $percentage);
					$this->Notification->fightArrangerMoney($fight, $totalMoney, $tvMoney, $percentage, $attendence, $fight['Fight']['ticket_price']);
				} elseif ($fight['Fight']['manager_id'] == $fight['Fighter2']['manager_id']) {
					$percentage =  ($fight['Fighter2']['Contract']['percentage'] / 100) * $totalMoney;
					$this->Manager->updateBalance($fight['Fight']['manager_id'], $percentage);
					$this->Notification->fightArrangerMoney($fight, $totalMoney, $tvMoney, $percentage, $attendence, $fight['Fight']['ticket_price']);
				}
			}
			
			//giving fee percentage to the other manager if he exist
			if($fight['Fight']['manager_id'] != NULL){
				if($fight['Fight']['manager_id'] != $fight['Fighter1']['manager_id'] && $fight['Fighter1']['manager_id'] != NULL){
					$percentage =  ($fight['Fighter1']['Contract']['percentage'] / 100) * $fight['Fight']['fee'];
					$this->Manager->updateBalance($fight['Fighter1']['manager_id'], $percentage);
					$this->Notification->fightFee($fight, $percentage, $fight['Fighter1']['manager_id'], $fight['Fight']['fee']);
				} elseif ($fight['Fight']['manager_id'] != $fight['Fighter2']['manager_id'] && $fight['Fighter2']['manager_id'] != NULL) {
					$percentage =  ($fight['Fighter2']['Contract']['percentage'] / 100) * $fight['Fight']['fee'];
					$this->Manager->updateBalance($fight['Fighter2']['manager_id'], $percentage);
					$this->Notification->fightFee($fight, $percentage, $fight['Fighter2']['manager_id'], $fight['Fight']['fee']);
				}
			}
			
			//updating fight with data
			$this->id = $fight['Fight']['id'];
			$this->save($data);
			
			//Updating boxers stats based on result
			if($winnerId == 'draw'){
				$this->Manager->Boxer->updateBoxerAfterFight($fight['Fighter1']['id'], 'draw', $fighter1FlooredTotal, null, null, $fight['Fighter1']['manager_id']);
				$this->Manager->Boxer->updateBoxerAfterFight($fight['Fighter2']['id'], 'draw', $fighter2FlooredTotal, null, null, $fight['Fighter2']['manager_id']);
			}elseif($winnerId == $fight['Fighter1']['id'] && $fightOverReason == 'knockout' || $winnerId == $fight['Fighter1']['id'] && $fightOverReason == 'TKO'){
				$this->Manager->Boxer->updateBoxerAfterFight($fight['Fighter1']['id'], 'winner', $fighter1FlooredTotal, 'knockout', $rank, $fight['Fighter1']['manager_id']);
				$this->Manager->Boxer->updateBoxerAfterFight($fight['Fighter2']['id'], 'loser', $fighter2FlooredTotal, 'knockout', $rank, $fight['Fighter2']['manager_id']);
			}elseif($winnerId == $fight['Fighter2']['id'] && $fightOverReason == 'knockout' || $winnerId == $fight['Fighter2']['id'] && $fightOverReason == 'TKO'){
				$this->Manager->Boxer->updateBoxerAfterFight($fight['Fighter2']['id'], 'winner', $fighter1FlooredTotal, 'knockout', $rank, $fight['Fighter2']['manager_id']);
				$this->Manager->Boxer->updateBoxerAfterFight($fight['Fighter1']['id'], 'loser', $fighter2FlooredTotal, 'knockout', $rank, $fight['Fighter1']['manager_id']);
			}elseif($winnerId == $fight['Fighter1']['id']){
				$this->Manager->Boxer->updateBoxerAfterFight($fight['Fighter1']['id'], 'winner', $fighter1FlooredTotal, null, $rank, $fight['Fighter1']['manager_id']);
				$this->Manager->Boxer->updateBoxerAfterFight($fight['Fighter2']['id'], 'loser', $fighter2FlooredTotal, null, $rank, $fight['Fighter2']['manager_id']);
			}else{
				$this->Manager->Boxer->updateBoxerAfterFight($fight['Fighter2']['id'], 'winner', $fighter1FlooredTotal, null, $rank, $fight['Fighter2']['manager_id']);
				$this->Manager->Boxer->updateBoxerAfterFight($fight['Fighter1']['id'], 'loser', $fighter2FlooredTotal, null, $rank, $fight['Fighter1']['manager_id']);
			}
			
			//swap places in BELTs
			if($winnerId == $fight['Fighter1']['id']){
				$this->Fighter1->Belt->fightUpdateBelt($winnerId, $fight['Fighter2']['id'], $rank);	
			}else{
				$this->Fighter1->Belt->fightUpdateBelt($winnerId, $fight['Fighter1']['id'], $rank);
			}
			
			//Send notification if one or more of the boxers have a manager.
			if($fight['Fighter1']['manager_id'] != NULL){
				$this->Notification->fightResult($fullnameWinner, $fullnameLoser, $fight['Fighter1']['manager_id'], $overview, $fullnameWinnerId, $fullnameLoserId, $winnerId);
			}
			
			if($fight['Fighter2']['manager_id'] != NULL){
				$this->Notification->fightResult($fullnameWinner, $fullnameLoser, $fight['Fighter2']['manager_id'], $overview, $fullnameWinnerId, $fullnameLoserId, $winnerId);
			}
			
			//Send notification to all managers if a new champion has occurred
			if($fight['Fighter1']['rank'] == 1 && $winnerId != $fight['Fighter1']['id'] && $winnerId != 'draw'){
				$this->Manager->newChampion($fight, $overview, $fullnameWinner, $fullnameLoser, $fullnameWinnerId, $fullnameLoserId);
			}else if($fight['Fighter2']['rank'] == 1 &&  $winnerId != $fight['Fighter2']['id'] && $winnerId != 'draw'){
				$this->Manager->newChampion($fight, $overview, $fullnameWinner, $fullnameLoser, $fullnameWinnerId, $fullnameLoserId);
			}

            //going to check fighters to see if they get "injured" which will now be a few stat point deduction
            $this->Manager->Boxer->updateInjuries($fight['Fighter1']['id'], $fight['Fighter2']['id']);

            //need to check for training for fighters who just fought. THIS METHOD IS STILL USED FROM MANAGER UPDATE
            if($fight['Fighter1']['manager_id'] != null && $fight['Fighter2']['manager_id'] != null){
                $this->Manager->Boxer->training($fight['Fighter1']['id'], $fight['Fighter2']['id']);
            }elseif($fight['Fighter1']['manager_id'] != null){
                $this->Manager->Boxer->training($fight['Fighter1']['id'], null);
            }elseif($fight['Fighter2']['manager_id'] != null){
                $this->Manager->Boxer->training(null, $fight['Fighter2']['id']);
            }

            //Need to check if boxer wants to retire, if the fight was arranged by a player
            if($fight['Fighter1']['manager_id'] != null || $fight['Fighter2']['manager_id'] != null){
                //The Retiring Boxers
                $managers = $this->Manager->find('all', array('recursive' => -1, 'fields' => 'Manager.id'));
                $retired = $this->Manager->Boxer->checkRetired($managers, $fight['Fighter1']['id'], $fight['Fighter2']['id']);
                //get Name data
                $nameData = $this->Manager->Boxer->Name->getNames();
                //replacing the boxers that have retired
                $this->Manager->Boxer->replaceRetired($retired, $nameData);
            }

            //need to check for contract complaints after a fight based on either fighter. THIS METHOD USED TO BE CALLED FROM MANAGER UPDATE
            $this->Manager->Boxer->reviewContract($fight['Fighter1']['id'], $fight['Fighter2']['id']);

            //Need to check for whiners after a fight to check everything moving THIS METHOD USED TO BE CALLED FROM MANAGERS UPDATE
            $this->Manager->Boxer->whiners($fight['Fighter1']['id'], $fight['Fighter2']['id']);

            //Need to update belts held and total manager points based on the winner id THIS METHOD USED TO BE CALLED FROM MANAGERS UPDATE
            $this->Manager->updateBeltsHeld($winnerId);
		}
	}
	
	public function calculatePunch($punches, $target, $fight, $fighter){
		$data = array();
		$data['text'] = '';
		$data['damage'] = 0;
		$data['cut_damage'] = 0;
		$data['heart_damage'] = 0;
		$missed = 0;
		$blocked = 0;
		$landed = 0;
		$knockdown = 0;
		$knockout = 0;
		$cutStoppage = 0;
		if($fighter == 1){
			$fighter2 = 2;	
		}else{
			$fighter2 = 1;
		}
		
		//stats needed to test chance to hit
		$chanceToHit = (($fight['Fighter'.$fighter]['tech'] + $fight['Fighter'.$fighter]['hand_speed'] + $fight['Fighter'.$fighter]['foot_speed']) / 3);
		$chanceToDodge =  (($fight['Fighter'.$fighter2]['tech'] + $fight['Fighter'.$fighter2]['defence'] + $fight['Fighter'.$fighter2]['foot_speed']) / 4);
		$toHit = ($chanceToHit - $chanceToDodge);
		if($toHit < 10){
			$toHit = 10;	
		}
		
		//stats needed to test chance to block
		$chanceBypassBlock =  ($fight['Fighter'.$fighter]['hand_speed']);
		$chanceToBlock = ($fight['Fighter'.$fighter2]['block']) * 0.75;
		$toBlock = ($chanceBypassBlock - $chanceToBlock);
		if($toBlock < 5){
			$toBlock = 5;	
		}
		
		for($i = 1; $i <= $punches; $i++){
			//chance to hit
			$rand = rand(1,100);
			if($toHit >= $rand){
				//check if hit is a block or not
				$rand = rand(1,100);
				if($toBlock >= $rand){
					//if the shot is a head shot
					if($target == 'head'){
						$landed++;
						//power - chin = damage done, atleast 10 damage done
						if($fight['Fighter'.$fighter]['power'] - ($fight['Fighter'.$fighter2]['chin'] / 1.5) > 5){
							$data['damage'] = $data['damage'] + $fight['Fighter'.$fighter]['power'] - $fight['Fighter'.$fighter2]['chin'];
						}else{
							$data['damage'] = $data['damage'] + 5;
						}
						//check cut status.
						$cutChance = ($fight['Fighter'.$fighter2]['cut'] - $data['cut_damage']) - 50;
						$rand = rand(1, 100);
						if($cutChance < $rand){
							//if the fighter has no more cut points left then subtract from heart
							if($fight['Fighter'.$fighter2]['cut'] - $data['cut_damage'] <= 0){
								$data['heart_damage'] = $data['heart_damage'] + 1;
								if(($fight['Fighter'.$fighter2]['cut'] - $data['cut_damage']) <= 0 && ($fight['Fighter'.$fighter2]['cut'] - $data['cut_damage']) >= -3){
									$data['text'] .= '<p class = "red">'.$fight['Fighter'.$fighter2]['Surname']['name'].'\'s cut looks nasty!</p>';
								}
								$cutStopFight = $fight['Fighter'.$fighter2]['heart'] - $data['heart_damage'];
								$rand = rand(1,100);
								if($cutStopFight < $rand){
										$data['text'] .= '<p class = "red">The referee is having a good look at '.$fight['Fighter'.$fighter2]['Surname']['name'].'s cut</p>';
										$data['text'] .= '<p class = "red">The ref has stopped the fight, this fight is over ladies and gentlemen!</p>';
										$cutStoppage = 1;
										break;
								}
							}else{
								$data['cut_damage'] = $data['cut_damage'] + 1;
							}
						}
						//check if knockdown has occurred.
						$dropChance = ($fight['Fighter'.$fighter]['power'] - ($fight['Fighter'.$fighter2]['chin'] + ($fight['Fighter'.$fighter2]['endurance'] - $data['damage'])));
						$rand = rand(1,100);
						//atleast 1% to drop from punch
						if($dropChance <= 0){
							$dropChance = 1;	
						}
						//if knockdown has occurred
						if($dropChance >= $rand){
							$knockdown++;
							$data['text'] .= '<p class = "red">'.$fight['Fighter'.$fighter2]['Surname']['name'].' looks hurt! and he goes down!</p>';
							$data['heart_damage'] = $data['heart_damage'] + 10;
							$data['text'] .= '<p>Can he get up?</p>';
							$getUpChance = (($fight['Fighter'.$fighter2]['heart'] - $data['heart_damage']) + (($fight['Fighter'.$fighter]['endurance'] - $data['damage']) / 10 ) / 5 );
							$data['text'] .= '<p>1!</p>';
							$data['text'] .= '<p>2!</p>';
							$data['text'] .= '<p>3!</p>';
							$data['text'] .= '<p>4!</p>';
							for($j = 5; $j <= 9; $j++){
								$rand = rand(1, 100);
								//The fighter gets up
								if($getUpChance >= $rand){
									$data['text'] .= '<p>He gets back up on his feet at the '.$j.' count!</p>';
									break(2);
								}else{
									$data['text'] .= '<p>'.$j.'!</p>';
								}
							}
							//count of 9 has been reached and fighter still hasnt got up.
							$knockout = 1;
							$data['text'] .= '<p>'.$fight['Fighter'.$fighter2]['Surname']['name'].' is still seeing stars and this fight is over!</p>';
							break;
						}
						
					//if the shot is a body shot
					}elseif($target == 'body'){
						$landed++;
						//power - (chin / 1.5) = damage done, atleast 20 damage done
						if($fight['Fighter'.$fighter]['power'] - ($fight['Fighter'.$fighter2]['chin'] / 2) > 10){
							$data['damage'] = $data['damage'] + $fight['Fighter'.$fighter]['power'] - ($fight['Fighter'.$fighter2]['chin'] / 1.5);
						}else{
							$data['damage'] = $data['damage'] + 10;
						}
						//check if knockdown has occurred.
						$dropChance = ($fight['Fighter'.$fighter]['power'] - ($fight['Fighter'.$fighter2]['chin'] + ($fight['Fighter'.$fighter2]['heart'] - $data['heart_damage'])));
						$rand = rand(1,100);
						//atleast 1% to drop from punch
						if($dropChance <= 0){
							$dropChance = 1;	
						}
						//if knockdown has occurred
						if($dropChance >= $rand){
							$knockdown++;
							$data['text'] .= '<p class = "red">'.$fight['Fighter'.$fighter2]['Surname']['name'].' looks hurt! and he goes down!</p>';
							$data['heart_damage'] = $data['heart_damage'] + 10;
							$data['text'] .= '<p>Can he get up?</p>';
							$getUpChance = (($fight['Fighter'.$fighter2]['heart'] - $data['heart_damage']) + (($fight['Fighter'.$fighter]['endurance'] - $data['damage']) / 10 ) / 5 );
							$data['text'] .= '<p>1!</p>';
							$data['text'] .= '<p>2!</p>';
							$data['text'] .= '<p>3!</p>';
							$data['text'] .= '<p>4!</p>';
							for($j = 5; $j <= 9; $j++){
								$rand = rand(1, 100);
								//The fighter gets up
								if($getUpChance >= $rand){
									$data['text'] .= '<p>He gets back up on his feet at the '.$j.' count!</p>';
									break(2);
								}else{
									$data['text'] .= '<p>'.$j.'!</p>';
								}
							}
							//count of 9 has been reached and fighter still hasnt got up.
							$knockout = 1;
							$data['text'] .= '<p>'.$fight['Fighter'.$fighter2]['Surname']['name'].' is still seeing stars and this fight is over!</p>';
							break;
						}
					}
				}else{
					//punch is blocked
					$blocked++;
					//if attackers power - defenders block + chin > 1 then add the difference to damaage done
					if(($fight['Fighter'.$fighter]['power'] - ($fight['Fighter'.$fighter2]['block'] + $fight['Fighter'.$fighter]['chin'])) > 1){
						$data['damage'] = $data['damage'] + ($fight['Fighter'.$fighter]['power'] - ($fight['Fighter'.$fighter2]['block'] + $fight['Fighter'.$fighter]['chin']));	
					}
				}
			}else{
				$missed++;
			}
		
		}
		//creating flavour text, starting with single punches
		if($knockdown < 1 && $cutStoppage == 0){
			if($punches == 1){
				if($missed >= 1){
					$data['text'] .= '<p>'.$fight['Fighter'.$fighter2]['Surname']['name'].' sidesteps out of the way</p>';	
				}elseif($blocked >= 1){
					$data['text'] .= '<p>'.$fight['Fighter'.$fighter2]['Surname']['name'].' obsorbs the blow with his guard</p>';		
				}elseif($landed >= 1){
					$data['text'] .= '<p class = "red">'.$fight['Fighter'.$fighter2]['Surname']['name'].' Has took a nasty shot!</p>';	
				}
			}
			
			if($punches == 2){
				if($missed >= 2){
					$data['text'] .= '<p>'.$fight['Fighter'.$fighter2]['Surname']['name'].' dodges both punches</p>';	
				}elseif($missed == 1 && $blocked == 1){
					$data['text'] .= '<p>'.$fight['Fighter'.$fighter2]['Surname']['name'].' sidestepped a punch and blocked the other</p>';	
				}elseif($missed == 1 && $landed == 1){
					$data['text'] .= '<p class = "red">'.$fight['Fighter'.$fighter2]['Surname']['name'].' dodged one punch but was caught flush with the other</p>';	
				}elseif($landed >= 2){
					$data['text'] .= '<p class = "red">'.$fight['Fighter'.$fighter2]['Surname']['name'].' takes the two punches flush!</p>';				
				}elseif($landed == 1 && $blocked == 1){
					$data['text'] .= '<p class = "red">'.$fight['Fighter'.$fighter2]['Surname']['name'].' caught one on his gloves but the other got though</p>';		
				}elseif($blocked >= 2){
					$data['text'] .= '<p>'.$fight['Fighter'.$fighter2]['Surname']['name'].' blocks both punches</p>';						
				}
			}
			
			if($punches == 3){
				if($landed >= 3){
					$data['text'] .= '<p class = "red">'.$fight['Fighter'.$fighter2]['Surname']['name'].' takes the entire three punch combination!</p>';	
				}elseif($landed == 2){
					$data['text'] .= '<p class = "red">'.$fight['Fighter'.$fighter2]['Surname']['name'].' takes two punches</p>';	
				}elseif($landed == 1){
					$data['text'] .= '<p class = "red">'.$fight['Fighter'.$fighter2]['Surname']['name'].' takes only one punch from the combination</p>';	
				}else{
					$data['text'] .= '<p>'.$fight['Fighter'.$fighter2]['Surname']['name'].' takes no damage from the three punch combination</p>';	
				}
			}
		}
		$data['misses'] = 0 + $missed;
		$data['landed'] = 0 + $landed;
		$data['blocks'] = 0 + $blocked;
		$data['knockdowns'] = 0 + $knockdown;
		$data['knockouts'] = 0 + $knockout;
		$data['cutStoppage'] = 0 + $cutStoppage;
		return $data;
	}
	
	public function cleanUp(){
		$contain = array(
			'Fighter1' => array(
				'fields' => array(
					'id',
					'retired'
				),
			),
			'Fighter2' => array(
				'fields' => array(
					'id',
					'retired'
				)
			)
		);
		$fields	 = array('id', 'fighter1_id', 'fighter2_id');
		$fights = $this->find('all', array('contain' => $contain, 'fields' => $fields));
		//if both the fighters have retired then delete the fight
		foreach($fights as $fight){
			if($fight['Fighter1']['retired'] == '1' && $fight['Fighter2']['retired'] == 1){
				$this->id = $fight['Fight']['id'];
				$this->delete($fight['Fight']['id']);	
			}
			
			if($fight['Fighter1']['id'] == NULL || $fight['Fighter2']['id'] == NULL){
				$this->id = $fight['Fight']['id'];
				$this->delete($fight['Fight']['id']);
			}
		}
		
		//Need a fight cleanUp that will deal with PvP fight offers that go unanswered
		$venues = $this->Venue->find('all', array('recursive' => -1, 'fields' => array('id', 'cost')));
		$fields = array(
			'Fight.id',
			'Fight.winner_id',
			'Fight.game_time',
			'Fight.venue_id',
			'Fight.accepted',
			'Fight.fee',
			'Fight.manager_id',
			'Fight.fighter1_id',
			'Fight.fighter2_id'
		);
		
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
						'id',
						'name'
					)
				)
			),
			'Fighter2' => array(
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
			)
		
		);
		
		$fights = $this->find('all', array('contain' => $contain, 'fields' => $fields, 'conditions' => array('winner_id' => NULL)));
		foreach ($fights as $fight){
			$this->id = $fight['Fight']['id'];
			$this->delete($fight['Fight']['id']);
			if($fight['Fight']['manager_id'] != NULL){
				$refund = ($fight['Fight']['fee'] + ($venues[$fight['Fight']['venue_id'] - 1]['Venue']['cost']));
				$this->requestAction('managers/updateBalance/'.$fight['Fight']['manager_id'].'/'.$refund);
				$this->Notification->removeNotifications($fight['Fight']['id']);
				$this->Notification->noResponsePvp($fight, $refund);
			}
		}
	}
}
