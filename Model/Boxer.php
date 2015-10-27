<?php
App::uses('AppModel', 'Model');
//App::uses('Security', 'Utility'); 
//App::import('Component','Auth'); 
/**
 * User Model
 *
 * @property Item $Item
 */
 
class Boxer extends AppModel {	
	

	public $belongsTo = array(
		'Manager' => array(
			'className' => 'Manager',
			'foreignKey' => 'manager_id',
		),
		'Trainer' => array(
			'className' => 'Trainer',
			'foreignKey' => 'trainer_id',
			'fields' => array('Trainer.id', 'Trainer.forname_id', 'Trainer.surname_id')
			
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
		'Fight' => array(
			'className' => 'Fight',
			'foreignKey' => false,
			'conditions' => array('or' => array('Fight.fighter1_id' => '{$__cakeID__$}', 'Fight.fighter2_id' => '{$__cakeID__$}')),
		),
		'Hall',
        'Name'
	);
	
	public $hasOne = array(
		'Contract' => array(
			'className' => 'Contract',
			'foreignKey' => 'boxer_id',
			'conditions' => array('not' => array('Contract.boxer_id' => null))
		),
		'Belt' => array(
			'className' => 'Belt',
			'foreignKey' => 'boxer_id'
		),
	);
	
	public function removeManager($id = null){
		$this->id = $id;
		$this->saveField('manager_id', null);
	}
	
	public function removeManagers($id = null){
		$boxers = $this->find('all', array('recursive' => -1, 'fields' => array('Boxer.id', 'Boxer.manager_id'), 'conditions' => array('Boxer.manager_id' => $id)));
		foreach($boxers as $boxer){
			$this->id = $boxer['Boxer']['id'];
			$this->saveField('manager_id', null);
			$this->saveField('trainer_id', null);	
		}
	}
	
	public function removeTrainers($id = null){
		$boxers = $this->find('all', array('recursive' => -1, 'fields' => array('Boxer.id', 'Boxer.trainer_id'), 'conditions' => array('Boxer.trainer_id' => $id)));
		foreach($boxers as $boxer){
			$this->id = $boxer['Boxer']['id'];
			$this->saveField('trainer_id', null);
		}
	}
	
	public function removeTrainer($id = null){
		$this->id = $id;
		$this->saveField('trainer_id', null);
	}
	
	public function updateInjuries($boxer1_id = null, $boxer2_id = null){
		//$date = $game_time;
		//$week = (60 * 60 * 24 * 7);
		//$game_time = strtotime($game_time);
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
            'id', 'injury_prone', 'injured', 'injured_text', 'injured_duration', 'manager_id', 'tech', 'power',
            'hand_speed', 'foot_speed', 'block', 'defence', 'chin', 'heart', 'cut'
        );
		//$boxers = $this->find('all', array('contain' => $contain, 'conditions' => array('retired' => '0'), 'fields' => array('id', 'injury_prone', 'injured', 'injured_text', 'injured_duration', 'manager_id', 'tech', 'power', 'hand_speed', 'foot_speed', 'block', 'defence', 'chin', 'heart', 'cut')));
		$boxers = $this->find('all', array('fields' => $fields, 'contain' => $contain, 'conditions' => array('manager_id <>' => null, 'retired' => '0', 'or' => array(array('Boxer.id' => $boxer1_id), array('Boxer.id' => $boxer2_id)))));
		$injuryList = array(
			'0' => array(
				'text' => 'While training the boxer hurt his hand which has caused bruising and swelling',
				//'duration' => date('Y-m-d', ($game_time + $week))
			),
			'1' => array(
				'text' => 'The boxer has the flu',
				//'duration' => date('Y-m-d', ($game_time + ($week * 2)))
			),
			'2' => array(
				'text' => 'While sparring the boxer received a cut above his eye',
				//'duration' => date('Y-m-d', ($game_time + ($week * 3)))
			),
			'3' => array(
				'text' => 'The Boxer has caught a really nasty virus which has him bed ridden',
				//'duration' => date('Y-m-d', ($game_time + ($week * 4)))
			),
			'4' => array(
				'text' => 'The boxer has developed an eye injury during trainer',
				//'duration' => date('Y-m-d', ($game_time + ($week * 5)))
			),
			'5' => array(
				'text' => 'While weight training the boxer lost control and dropped the bar on his sternum',
				//'duration' => date('Y-m-d', ($game_time + ($week * 6)))
			),
			'6' => array(
				'text' => 'While sparring the boxer took a nasty body blow which has cracked a rib',
				//'duration' => date('Y-m-d', ($game_time + ($week * 7)))
			),
			'7' => array(
				'text' => 'The boxer has broken a bone in his hand while sparring',
				//'duration' => date('Y-m-d', ($game_time + ($week * 8)))
			)
		);
		foreach($boxers as $boxer){
            $chance = ($boxer['Boxer']['injury_prone'] / 15);
            $rand = rand(1, 100);
            if($chance >= $rand){
                $injured = true;
            }else{
                $injured = false;
            }
            $newRand = rand(0, 7);
            $type = $injuryList[$newRand];
            $lowerStat = '';
            $newRand2 = rand(1,9);
            if($newRand2 == 1){
                $lowerStat = 'tech';
            }elseif($newRand2 == 2){
                $lowerStat = 'power';
            }elseif($newRand2 == 3){
                $lowerStat = 'hand_speed';
            }elseif($newRand2 == 4){
                $lowerStat = 'foot_speed';
            }elseif($newRand2 == 5){
                $lowerStat = 'block';
            }elseif($newRand2 == 6){
                $lowerStat = 'defence';
            }elseif($newRand2 == 7){
                $lowerStat = 'chin';
            }elseif($newRand2 == 8){
                $lowerStat = 'heart';
            }elseif($newRand2 == 9){
                $lowerStat = 'cut';
            }
            if($injured == true){
                $boxerStat = $boxer['Boxer'][$lowerStat] - $newRand;
                if($boxerStat < 0){
                    $boxerStat = 0;
                }
                $this->id = $boxer['Boxer']['id'];
                $this->saveField($lowerStat, $boxerStat);
                //$this->saveField('injured', 1);
                //$this->saveField('injured_text', $type['text']);
                //$this->saveField('injured_duration', $type['duration']);
                /*if($boxer['Boxer']['manager_id'] != NULL){
                    $this->saveField($lowerStat, $boxerStat);
                }*/

                //$this->Fight->cancelFights($boxer['Boxer']['id'], $date);
               // if($boxer['Boxer']['manager_id'] != null && !empty($boxer['Boxer']['manager_id'])){
                $this->Fight->Notification->alertBoxerInjured($boxer, $boxer['Boxer']['manager_id'], $type);
                //}
            }

		}
	}
	
	public function checkRetired($managers, $boxer1_id = null, $boxer2_id = null){
        if($boxer1_id != null && $boxer2_id != null){
            $boxers = array($boxer1_id, $boxer2_id);
            $conditions = array('retired' => 0, 'Boxer.id' => $boxers);
        }else{
            $conditions = array('retired' => 0);
        }
		$contain = array(
			'Fight' => array(
				'fields' => array(
					'id',
					'accepted',
                    'created'
				),
				'order' => 'Fight.created DESC',
				'conditions' => array(
					'or' => array(
								array('fighter1_id' => '{$__cakeID__$}'),
								array('fighter2_id' => '{$__cakeID__$}')
							)
				)
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
		);

        $fields = array(
            'id', 'manager_id', 'happiness', 'ambition', 'greed', 'injured', 'age', 'rank',
            'confidence', 'weight_type', 'forname_id', 'surname_id', 'wins', 'loses', 'knockouts'
        );
		$boxers = $this->find('all', array('conditions' => $conditions, 'contain' => $contain, 'fields' => $fields));
		for ($i = 0; $i <= 16; $i++){
			$retired[$i] = 0;
		}
		foreach ($boxers as $boxer){
			$retire = 0;
            $age = $boxer['Boxer']['age'];
			if(!empty($boxer['Fight'])){ //retiring if the boxer has lost a percentage of fights after 10 fights
				if($age > 22){
					$ratio = 100 / (count($boxer['Fight']));
					$ratio = $ratio * $boxer['Boxer']['wins'];
					if($ratio < 25){
						$retire = 1;
						$reason = '"I\'ve lost too many fights! I don\'t think I\'m cut out for this"';
					}
				}
				
				if($retire == 0) {
					if($boxer['Boxer']['confidence'] > 100){
						$boxer['Boxer']['confidence'] = 100;	
					}
					$confidence  = ((50 - $boxer['Boxer']['confidence']) / 10);
					$happiness = ((50 - $boxer['Boxer']['happiness']) / 10);
                    $greed = ((50 - $boxer['Boxer']['greed']) / 10);
					
					if($boxer['Boxer']['rank'] == 1){
						$ambition = -5;	
					} else if ($boxer['Boxer']['rank'] == 2){
						$ambition = -4;	
					} else if ($boxer['Boxer']['rank'] == 3) {
						$ambition = -3;	
					} else if ($boxer['Boxer']['rank'] == 4) {
						$ambition = -2;	
					} else if ($boxer['Boxer']['rank'] == 5) {
						$ambition = -1;	
					} else {
						$ambition = ($boxer['Boxer']['ambition'] / 20);
					}

					$chance = ((($age - 25) + $confidence + $ambition + $greed) / 1);
					$rand = rand(1, 100);
					if($chance >= $rand ){
						$retire = 1;	
						if(($age - 25) >= $confidence && ($age - 25) >= $happiness && ($age - 25) >= $ambition && ($age - 25) >= $greed){
							$reason = '"I\'m not getting any younger and need to think about the next stage of my life."';
						}else if ($confidence >= ($age - 25) && $confidence >= $happiness && $confidence >= $ambition && $confidence >= $greed){
							$reason = '"I don\'t think I\'m cut out to be a top boxer, I tired my best but I\'m just not cut out for it."';
						}else if ($happiness >= ($age - 25) && $happiness >= $confidence && $happiness >= $ambition && $happiness >= $greed) {
							$reason = '"I\'m not happy being a boxer, I think I\'ve been mismanaged and never got the break I needed."';
						}else if ($ambition >= ($age - 25) && $ambition >= $confidence && $ambition >= $happiness && $ambition >= $greed) {
							$reason = '"I just don\'t want it anymore, I know I\'m never going to make it to the top."';
						}else if ($greed >= ($age - 25) && $greed >= $confidence && $greed >= $happiness &&  $greed >= $ambition){
							$reason  = '"I don\'t need the money anymore, why risk dying man!?"';
						}else{
							$reason = 'Think we have an error here please contact admin.';	
						}
					}
				}
				
				if($retire == 1 ){
					//remove boxer from belts
					$this->Belt->removeBoxer($boxer['Boxer']['id']);
					$this->Belt->updateBelt($boxer['Boxer']['weight_type']);
					
					
					//remove boxer contracts
					$this->Contract->removeBoxer($boxer['Boxer']['id']);
					
					
					//Notifiy Boxers Manager of retirement
					if($boxer['Boxer']['manager_id'] != null){
						$this->Fight->Notification->boxerRetired($boxer, $reason);
					}
					
					//NEW Notifiy all Managers of retirement
					foreach($managers as $manager){
						if($manager['Manager']['id'] != $boxer['Boxer']['manager_id']){
							$this->Fight->Notification->boxerRetiredAll($boxer, $reason, $manager['Manager']['id']);
						}
					}
					
					$this->id = $boxer['Boxer']['id'];
					$data = array('Boxer' => 
						array('retired' => '1', 
							  'manager_id' => NULL, 
							  'trainer_id' => NULL,
							  'injured' => '0',
							  'injured_text' => NULL,
							  'injured_duration' => NULL
							 )
					);
					$this->save($data);
					
					$retired[$boxer['Boxer']['weight_type']]++;
					}	
				}	
			}
			return $retired;
	}
		
	public function replaceRetired($retired = null, $names = null, $manager_id = null){
		//creating age. 
		//$eighteen = 60 * 60 * 24 * 7 * 52 * 18;
		//$secondsTime = strtotime($game_time);
		//$secondsTime = $secondsTime - $eighteen;
		//$age = date('Y-m-d', $secondsTime);


		foreach($retired as $key => $retire){
			for($i = 0; $i < $retire; $i++){
				$region = rand(0, 5);
				$data['Boxer']['weight_type'] = $key;
				$data['Boxer']['region'] = $region;
                $data['Boxer']['rank'] = ($this->find('count', array('conditions' => array('retired' => 0, 'Boxer.weight_type' => $key))) + 1);
				if($region == 0 || $region == 2){
					$numberFirst = (count($names['euFirst']) -1);
					$numberSecond = (count($names['euSecond']) -1);
					$rand = rand(0, $numberFirst);
					$data['Boxer']['forname_id'] = $names['euFirst'][$rand]['Name']['id'];
					$rand = rand(0, $numberSecond);
					$data['Boxer']['surname_id'] = $names['euSecond'][$rand]['Name']['id'];
				} else if ($region == 1){
					$numberFirst = (count($names['saFirst']) -1);
					$numberSecond = (count($names['saSecond']) -1);
					$rand = rand(0, $numberFirst);
					$data['Boxer']['forname_id'] = $names['saFirst'][$rand]['Name']['id'];
					$rand = rand(0, $numberSecond);
					$data['Boxer']['surname_id'] = $names['saSecond'][$rand]['Name']['id'];
				} else if ($region == 3){
					$numberFirst = (count($names['meFirst']) -1);
					$numberSecond = (count($names['meSecond']) -1);
					$rand = rand(0, $numberFirst);
					$data['Boxer']['forname_id'] = $names['meFirst'][$rand]['Name']['id'];
					$rand = rand(0, $numberSecond);
					$data['Boxer']['surname_id'] = $names['meSecond'][$rand]['Name']['id'];
				} else if ($region == 4){
					$numberFirst = (count($names['afFirst']) -1);
					$numberSecond = (count($names['afSecond']) -1);
					$rand = rand(0, $numberFirst);
					$data['Boxer']['forname_id'] = $names['afFirst'][$rand]['Name']['id'];
					$rand = rand(0, $numberSecond);
					$data['Boxer']['surname_id'] = $names['afSecond'][$rand]['Name']['id'];	
				} else if ($region == 5){
					$numberFirst = (count($names['asFirst']) -1);
					$numberSecond = (count($names['asSecond']) -1);
					$rand = rand(0, $numberFirst);
					$data['Boxer']['forname_id'] = $names['asFirst'][$rand]['Name']['id'];
					$rand = rand(0, $numberSecond);
					$data['Boxer']['surname_id'] = $names['asSecond'][$rand]['Name']['id'];	
				}
				
				$rand = rand(1, 100);
				$data['Boxer']['ambition'] = $rand;
				
				$rand = rand(1, 100);
				$data['Boxer']['greed'] = $rand;
				
				$rand = rand(1, 100);
				$data['Boxer']['aggression'] = $rand;
				
				$rand = rand(1, 100);
				$data['Boxer']['discipline'] = $rand;
				
				$rand = rand(1, 100);
				$data['Boxer']['dirty'] = $rand;
				
				$rand = rand(1, 100);
				$data['Boxer']['lifestyle'] = $rand;
				
				//$rand = rand(1, 100);
				$data['Boxer']['confidence'] = 200;
				
				$rand = rand(1, 100);
				$data['Boxer']['injury_prone'] = $rand;
				
				$rand = rand(1, 100);
				$data['Boxer']['tech'] = $rand;
				
				$rand = rand(1, 100);
				$data['Boxer']['power'] = $rand;
				
				$rand = rand(1, 100);
				$data['Boxer']['hand_speed'] = $rand;
				
				$rand = rand(1, 100);
				$data['Boxer']['foot_speed'] = $rand;
				
				$rand = rand(1, 100);
				$data['Boxer']['block'] = $rand;
				
				$rand = rand(1, 100);
				$data['Boxer']['defence'] = $rand;
				
				$rand = rand(1, 100);
				$data['Boxer']['chin'] = $rand;
				
				$rand = rand(1, 100);
				$data['Boxer']['heart'] = $rand;
				
				$rand = rand(1, 100);
				$data['Boxer']['cut'] = $rand;
				
				$rand = rand(400, 1000);
				$data['Boxer']['endurance'] = $rand;
				
				if($manager_id != null){
					$data['Boxer']['manager_id'] = $manager_id;	
				}

                $data['Boxer']['age'] = 18;
				
				$this->create();
				$this->save($data);
			}
		}
	}
	
	//returns boxerids of the fighters who have no manager 
	public function returnBoxerId($npcFights = null){
		$data = array();
		foreach($npcFights as $npcFight){
			$fighters = $this->Fight->find('first', array('recursive' => -1, 'fields' => array('id', 'fighter1_id', 'fighter2_id'), 'conditions' => array('id' => $npcFight)));
			$boxer = $this->find('first', array('recursive' => -1, 'fields' => array('id', 'manager_id'), 'conditions' => array('retired' => '0', 'manager_id' => null, 'or' => array(array('boxer.id' => $fighters['Fight']['fighter1_id']), array('boxer.id' => $fighters['Fight']['fighter2_id'])))));
			$data[$boxer['Boxer']['id']] = $boxer['Boxer']['id'];
		}
		return $data;	
	}
	
	//update a boxer to have a new manager_id
	public function newManager($boxer_id, $manager_id){
		$this->id = $boxer_id;
		$this->saveField('manager_id', $manager_id);	
	}
	
	//Npcs boxers will sometimes attempt to arrange their own fights 
	public function npcOwnFights(){
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
			'Fight' => array(
				'fields' => array(
					'id',
					'accepted',
					//'game_time'
				),
				'conditions' => array(
					//'game_time >=' => $game_time,
                    'winner_id' => NULL,
					'accepted' => '1',
					'or' => array(
								array('fighter1_id' => '{$__cakeID__$}'),
								array('fighter2_id' => '{$__cakeID__$}')
							)
				)
			),
		);
		
		$fields = array(
			'id',
			'weight_type',
			'manager_id',
			//'injured',
			'fame'
		);
		
 		$heavyNpc = $this->find('all', array('contain' => $contain, 'fields' => $fields, 'conditions' => array('retired' => '0', 'manager_id' => NULL, 'weight_type' => '16', /*'Fight.0.id' => NULL*/)));
		
		$middleNpc = $this->find('all', array('contain' => $contain, 'fields' => $fields, 'conditions' => array('retired' => '0', 'manager_id' => NULL, 'weight_type' => '12', /*'Fight.id' => NULL*/)));

 		$flyNpc = $this->find('all', array('contain' => $contain, 'fields' => $fields, 'conditions' => array('retired' => '0', 'manager_id' => NULL, 'weight_type' => '2', /*'Fight.id' => NULL*/)));
		
		//need to manually remove the boxers that have an upcoming fight.
		$data = array();
		$count = 0;
		foreach($heavyNpc as $heavy){
			if(!isset($heavy['Fight'][0]['id'])){
				$data[$count] = $heavy;
				$count++;
			}
		}
		$heavyNpc = $data;
		
		//need to manually remove the boxers that have an upcoming fight.
		$data = array();
		$count = 0;
		foreach($middleNpc as $middle){
			if(!isset($middle['Fight'][0]['id'])){
				$data[$count] = $middle;
				$count++;
			}
		}
		$middleNpc = $data;
		
		//need to manually remove the boxers that have an upcoming fight.
		$data = array();
		$count = 0;
		foreach($flyNpc as $fly){
			if(!isset($fly['Fight'][0]['id'])){
				$data[$count] = $fly;
				$count++;
			}
		}
		$flyNpc = $data;
		
		//Heavyweight npcs check to see if there are more than 1 eligible boxers, then goes through them and makes sure we don't reuse same boxer then random check to create npc fight
		$count = count($heavyNpc);
		$count2 = 0;
		$skipBoxer = array();
		if($count > 1){
			foreach($heavyNpc as $heavy){
				$randBoxer = rand(0, $count-1);
				if($heavy['Boxer']['id'] == $heavyNpc[$randBoxer]['Boxer']['id']){
					$randBoxer = $randBoxer + 1;	
				}
				if(in_array($randBoxer, $skipBoxer) || in_array($count2, $skipBoxer)){
					$count2++;
				} else {
					$rand = rand(1, 100);
					if($rand <= 50){
						if($randBoxer < $count){
							$totalFame = $heavy['Boxer']['fame'] + $heavyNpc[$randBoxer]['Boxer']['fame'];
							$this->Fight->createFight($heavy['Boxer']['id'], $heavyNpc[$randBoxer]['Boxer']['id'], $totalFame);
							//array_push($skipBoxer, $randBoxer);
							//array_push($skipBoxer, $count2);
                            array_push($skipBoxer, $randBoxer, $count2);
						}	
					}
					$count2++;
				}
			}
		}
		
		
		//Middlewight npcs check to see if there are more than 1 eliable boxers, then goes through them and makes sure we dont reuse same boxer then random check to create npc fight
		$count = count($middleNpc);
		$count2 = 0;
		$skipBoxer = array();
		if($count > 1){
			foreach($middleNpc as $middle){
				$randBoxer = rand(0, $count-1);
				if($middle['Boxer']['id'] == $middleNpc[$randBoxer]['Boxer']['id']){
					$randBoxer = $randBoxer + 1;	
				}
				if(in_array($randBoxer, $skipBoxer) || in_array($count2, $skipBoxer)){
					$count2++;
				} else {
					$rand = rand(1, 100);
					if($rand <= 50){
						if($randBoxer < $count){
							$totalFame = $middle['Boxer']['fame'] + $middleNpc[$randBoxer]['Boxer']['fame'];
							$this->Fight->createFight($middle['Boxer']['id'], $middleNpc[$randBoxer]['Boxer']['id'], $totalFame);
//							array_push($skipBoxer, $randBoxer);
//							array_push($skipBoxer, $count2);
                            array_push($skipBoxer, $randBoxer, $count2);
						}	
					}
					$count2++;
				}
			}
		}
		
		//Flyweight npcs check to see if there are more than 1 eliable boxers, then goes through them and makes sure we dont reuse same boxer then random check to create npc fight
		$count = count($flyNpc);
		$count2 = 0;
		$skipBoxer = array();
		if($count > 1){
			foreach($flyNpc as $fly){
				$randBoxer = rand(0, $count-1);
				if($fly['Boxer']['id'] == $flyNpc[$randBoxer]['Boxer']['id']){
					$randBoxer = $randBoxer + 1;	
				}
				if(in_array($randBoxer, $skipBoxer) || in_array($count2, $skipBoxer)){
					$count2++;
				} else {
					$rand = rand(1, 100);
					if($rand <= 50){
						if($randBoxer < $count){
							$totalFame = $fly['Boxer']['fame'] + $flyNpc[$randBoxer]['Boxer']['fame'];
							$this->Fight->createFight($fly['Boxer']['id'], $flyNpc[$randBoxer]['Boxer']['id'], $totalFame);
//							array_push($skipBoxer, $randBoxer);
//							array_push($skipBoxer, $count2);
                            array_push($skipBoxer, $randBoxer, $count2);
						}	
					}
					$count2++;
				}
			}
		}
	}
	
	public function training($boxer1_id = null, $boxer2_id = null){
        if($boxer1_id != null || $boxer2_id != null){ //if the method has boxer ids passed in from params
            $conditions = array('retired' => '0','trainer_id <>' => NULL, 'or' => array(array('Boxer.id' => $boxer1_id), array('Boxer.id' => $boxer2_id)));
        }else{
            $conditions = array('retired' => '0','trainer_id <>' => NULL);
        }
		$boxers = $this->find('all', array(
			'conditions' => $conditions,
			'contain' => array(
				'Trainer' => array(
					'fields' => array(
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
						)
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
			'fields' => array(
				'id',
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
				)
			)
		);
	
		foreach($boxers as $boxer){
			$data = array();
			$success = 0;
			$chance = $boxer['Boxer']['discipline'] + ($boxer['Boxer']['confidence'] / 10) - $boxer['Boxer']['lifestyle'];
			if($chance <= 2){
				$chance = 2;	
			}
            //OLD METHOD
            /*if($boxer['Boxer']['tech'] < $boxer['Trainer']['tech']){
				$rand = rand(1, 100);
				if($rand <= $chance){
					$data['Boxer']['tech'] = $boxer['Boxer']['tech'] + 1;
					$success++;
				}
			}*/

			//Techinque if the boxers technique is less than the trainers
            if($boxer['Boxer']['tech'] < $boxer['Trainer']['tech']){
                $trainerOffset = $boxer['Trainer']['tech'] - $boxer['Boxer']['tech'];
                $newChance = $trainerOffset + $chance;
                $rand = rand(1, 100);
                if($rand <= $newChance){
                    $data['Boxer']['tech'] = $boxer['Boxer']['tech'] + 1;
                    $success++;
                }
            }

			
			//Power if the boxers power is less than the trainers
            if($boxer['Boxer']['power'] < $boxer['Trainer']['power']){
                $trainerOffset = $boxer['Trainer']['power'] - $boxer['Boxer']['power'];
                $newChance = $trainerOffset + $chance;
                $rand = rand(1, 100);
                if($rand <= $newChance){
                    $data['Boxer']['power'] = $boxer['Boxer']['power'] + 1;
                    $success++;
                }
            }
			
			//hand_speed if the boxers hand_speed is less than the trainers
            if($boxer['Boxer']['hand_speed'] < $boxer['Trainer']['hand_speed']){
                $trainerOffset = $boxer['Trainer']['hand_speed'] - $boxer['Boxer']['hand_speed'];
                $newChance = $trainerOffset + $chance;
                $rand = rand(1, 100);
                if($rand <= $newChance){
                    $data['Boxer']['hand_speed'] = $boxer['Boxer']['hand_speed'] + 1;
                    $success++;
                }
            }
			
			//foot_speed if the boxers foot_speed is less than the trainers
            if($boxer['Boxer']['foot_speed'] < $boxer['Trainer']['foot_speed']){
                $trainerOffset = $boxer['Trainer']['foot_speed'] - $boxer['Boxer']['foot_speed'];
                $newChance = $trainerOffset + $chance;
                $rand = rand(1, 100);
                if($rand <= $newChance){
                    $data['Boxer']['foot_speed'] = $boxer['Boxer']['foot_speed'] + 1;
                    $success++;
                }
            }
			
			//block if the boxers block is less than the trainers
            if($boxer['Boxer']['block'] < $boxer['Trainer']['block']){
                $trainerOffset = $boxer['Trainer']['block'] - $boxer['Boxer']['block'];
                $newChance = $trainerOffset + $chance;
                $rand = rand(1, 100);
                if($rand <= $newChance){
                    $data['Boxer']['block'] = $boxer['Boxer']['block'] + 1;
                    $success++;
                }
            }
			
			//defence if the boxers defence is less than the trainers
            if($boxer['Boxer']['defence'] < $boxer['Trainer']['defence']){
                $trainerOffset = $boxer['Trainer']['defence'] - $boxer['Boxer']['defence'];
                $newChance = $trainerOffset + $chance;
                $rand = rand(1, 100);
                if($rand <= $newChance){
                    $data['Boxer']['defence'] = $boxer['Boxer']['defence'] + 1;
                    $success++;
                }
            }
			
			//chin if the boxers chin is less than the trainers
            if($boxer['Boxer']['chin'] < $boxer['Trainer']['chin']){
                $trainerOffset = $boxer['Trainer']['chin'] - $boxer['Boxer']['chin'];
                $newChance = $trainerOffset + $chance;
                $rand = rand(1, 100);
                if($rand <= $newChance){
                    $data['Boxer']['chin'] = $boxer['Boxer']['chin'] + 1;
                    $success++;
                }
            }
			
			//heart if the boxers heart is less than the trainers
            if($boxer['Boxer']['heart'] < $boxer['Trainer']['heart']){
                $trainerOffset = $boxer['Trainer']['heart'] - $boxer['Boxer']['heart'];
                $newChance = $trainerOffset + $chance;
                $rand = rand(1, 100);
                if($rand <= $newChance){
                    $data['Boxer']['heart'] = $boxer['Boxer']['heart'] + 1;
                    $success++;
                }
            }
			
			//cut if the boxers cut is less than the trainers
            if($boxer['Boxer']['cut'] < $boxer['Trainer']['cut']){
                $trainerOffset = $boxer['Trainer']['cut'] - $boxer['Boxer']['cut'];
                $newChance = $trainerOffset + $chance;
                $rand = rand(1, 100);
                if($rand <= $newChance){
                    $data['Boxer']['cut'] = $boxer['Boxer']['cut'] + 1;
                    $success++;
                }
            }
			
			//endurance if the boxers endurance is less than the trainers
			/*$adjustedEndurance = $boxer['Trainer']['endurance'] * 10;
			if($boxer['Boxer']['endurance'] < $adjustedEndurance){
				$rand = rand(1, 100);
				if($rand <= $chance){
					$data['Boxer']['endurance'] = $boxer['Boxer']['endurance'] + 10;
					$success++;	
				}
			}*/
            if($boxer['Boxer']['endurance'] < ($boxer['Trainer']['endurance'] * 10)){
                $trainerOffset = $boxer['Trainer']['endurance'] - ($boxer['Boxer']['endurance'] / 10);
                $newChance = $trainerOffset + $chance;
                $rand = rand(1, 100);
                if($rand <= $newChance){
                    $data['Boxer']['endurance'] = $boxer['Boxer']['endurance'] + 10;
                    $success++;
                }
            }
			
			//if atlesst one stat has changed
			if($success != 0){
				$this->id = $boxer['Boxer']['id'];
				$this->save($data);
				//if the boxer has a manager send notification of improvement
				if($boxer['Boxer']['manager_id'] != NULL && $success >= 3){
					$this->Fight->Notification->trainingImprovement($boxer);
				}
			}
		}
	}
	
	//function for updating boxers stats based on their result at a fight
	public function updateBoxerAfterFight($fighterId, $result, $floored = 0, $reason = null, $rank = null, $manager_id){
		if($result == 'draw'){
			$fighter = $this->find('first', array('conditions' => array('Boxer.id' => $fighterId), 'recursive' => -1));
            //every other fight the boxer has add one to his age
            if((($fighter['Boxer']['wins'] + $fighter['Boxer']['draws'] + $fighter['Boxer']['loses']) % 2) == 0){
                $fighter['Boxer']['age'] =  $fighter['Boxer']['age'] + 1;
            }
            $fighter['Boxer']['draws'] = $fighter['Boxer']['draws'] + 1;
			$fighter['Boxer']['floored'] = $fighter['Boxer']['floored'] + $floored;
			$this->id = $fighterId;
			$this->save($fighter);
		}elseif($result == 'winner'){
			$fighter = $this->find('first', array('conditions' => array('Boxer.id' => $fighterId), 'recursive' => -1));
            if((($fighter['Boxer']['wins'] + $fighter['Boxer']['draws'] + $fighter['Boxer']['loses']) % 2) == 0){
                $fighter['Boxer']['age'] =  $fighter['Boxer']['age'] + 1;
            }
			$fighter['Boxer']['wins'] = $fighter['Boxer']['wins'] + 1;
			$fameOffset = (30 - $fighter['Boxer']['rank']) / 19;
				if($fameOffset < 1){
					$fameOffset = 1;	
				}
			if($reason == 'knockout'){
				$fame = 10 * $fameOffset;
				$knockouts = 1;
				$confidence = 7;
			}else{
				$fame = 5 * $fameOffset;
				$knockouts = 0;	
				$confidence = 5;
			}
			if($fighter['Boxer']['loses'] == 0){
				$fame = $fame + 5;	
			}
			
			$fighter['Boxer']['rank'] = $rank[0];
			$fighter['Boxer']['fame'] = $fighter['Boxer']['fame'] + $fame;
			$fighter['Boxer']['floored'] = $fighter['Boxer']['floored'] + $floored;
			$fighter['Boxer']['knockouts'] = $fighter['Boxer']['knockouts'] + $knockouts;
			$fighter['Boxer']['confidence'] = $fighter['Boxer']['confidence'] + $confidence;
			if($fighter['Boxer']['confidence'] > 200){
				$fighter['Boxer']['confidence'] = 200;	
			}
			if($manager_id != NULL){
				$fighter['Boxer']['happiness'] = $fighter['Boxer']['happiness'] + 10;
			}
			if($fighter['Boxer']['happiness'] > 100){
				$fighter['Boxer']['happiness'] = 100;
			}
			$this->id = $fighterId;
			$this->save($fighter);
		}elseif($result == 'loser'){
			$fighter = $this->find('first', array('conditions' => array('Boxer.id' => $fighterId), 'recursive' => -1));
            if((($fighter['Boxer']['wins'] + $fighter['Boxer']['draws'] + $fighter['Boxer']['loses']) % 2) == 0){
                $fighter['Boxer']['age'] =  $fighter['Boxer']['age'] + 1;
            }
			$fighter['Boxer']['loses'] = $fighter['Boxer']['loses'] + 1;
			if($reason == 'knockout'){
				$fame = 3;
				$confidence = 7;
				$happiness = 10;
			}else{
				$fame = 2;
				$confidence = 5;
				$happiness = 5;
			}
			if($fighter['Boxer']['confidence'] > 150){
				$confidence = 25;
			}else if($fighter['Boxer']['confidence'] > 100) {
				$confidence = 10;
			}
			
			$fighter['Boxer']['rank'] = $rank[1];
			$fighter['Boxer']['fame'] = $fighter['Boxer']['fame'] - $fame;
			
			if($fighter['Boxer']['fame'] < 0){
				$fighter['Boxer']['fame'] = 0;
			}
			$fighter['Boxer']['floored'] = $fighter['Boxer']['floored'] + $floored;
			$fighter['Boxer']['confidence'] = $fighter['Boxer']['confidence'] - $confidence;
			if($manager_id != NULL){
				$fighter['Boxer']['happiness'] = $fighter['Boxer']['happiness'] - $happiness;
			}
			if($fighter['Boxer']['confidence'] < 0){
				$fighter['Boxer']['confidence'] = 0;	
			}
			if($fighter['Boxer']['happiness'] < 0){
				$fighter['Boxer']['happiness'] = 0;	
			}
			
			$this->id = $fighterId;
			$this->save($fighter);
		}
	}
	
	//this function will update boxers ranks based on those who already have ranks and those that don't
	public function updateRanks(){
		$weights = Configure::read('Weight.class');
		$fields = array('id', 'rank', 'weight_type', 'retired');
		foreach($weights as $key => $weight){
            /*MUST HAVE BEEN ON CRACK WHEN I WROTE THIS*/
			/*$count = 1;
			$boxers = $this->find('all', array('conditions' => array('weight_type' => $key, 'retired' => '0'), 'recursive' => -1, 'fields' => $fields, 'order' => 'Boxer.rank ASC'));
			$count2 = count($boxers)-1;
			foreach($boxers as $boxer){
				if($boxer['Boxer']['rank'] == NULL){
					$this->id = $boxer['Boxer']['id'];
					$this->saveField('rank', $count2);
				}else{
					$this->id = $boxer['Boxer']['id'];
					$this->saveField('rank', $count);
					$count++;
				}
				$count2--;	
			}*/
            $count = 1;
            $boxers = $this->find('all', array('conditions' => array('weight_type' => $key, 'retired' => '0'), 'recursive' => -1, 'fields' => $fields, 'order' => 'Boxer.rank ASC'));
            foreach($boxers as $boxer){
                if($boxer['Boxer']['rank'] != $count){
                    $this->id = $boxer['Boxer']['id'];
                    $this->saveField('rank', $count);
                }
                $count++;
            }
		}
	}
	
	public function updateBelts(){
		$fields = array('id', 'rank', 'weight_type', 'retired');
		$boxers = $this->find('all', array('recursive' => -1, 'fields' => $fields, 'conditions' => array('retired' => '0')));
		foreach($boxers as $boxer){
			$this->Belt->updateBeltsBasedOnRank($boxer['Boxer']['id'], $boxer['Boxer']['rank'], $boxer['Boxer']['weight_type']);	
		}
	}
	
	//sends notifications to managers if their boxers are not happy
	public function whiners($boxer1_id = null, $boxer2_id = null){
		$fields = array('id', 'manager_id', 'happiness', 'retired');
		$conditions = array('manager_id <>' => NULL, 'retired' => '0', 'or' => array(
            array('Boxer.id' => $boxer1_id),
            array('Boxer.id' => $boxer2_id)
        ));
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
		$boxers = $this->find('all', array('conditions' => $conditions, 'fields' => $fields, 'contain' => $contain));
		foreach($boxers as $boxer){
			if($boxer['Boxer']['happiness'] <= 0){
				$this->Fight->Notification->whiners($boxer, 'leave');
				$this->removeManager($boxer['Boxer']['id']);
				$this->removeTrainer($boxer['Boxer']['id']);
				$this->Manager->Contract->removeAcceptedBoxerContract($boxer['Boxer']['id']);
				$this->id = $boxer['Boxer']['id'];
				$this->saveField('happiness', '50');
				
			}elseif($boxer['Boxer']['happiness'] <= 15){
				$this->Fight->Notification->whiners($boxer, 'awful');
				
			}elseif($boxer['Boxer']['happiness'] <= 30){
				$this->Fight->Notification->whiners($boxer, 'bad');
				
			}
		}
	}
	
	//adjust the happiness of boxers if they are managed and not getting any fights arranged for them.
	public function inactivity(){
        $month = 60 * 60 * 24 * 31;
        $fields = array(
            'id', 'manager_id', 'retired', 'happiness'
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
            'Fight' => array(
                'fields' => array(
                    'Fight.created'
                ),
                'order' => array(
                    'created DESC'
                ),
                'limit' => '1',
                'conditions' => array(
                    'accepted' => '1',
                    'or' => array(
                        array('fighter1_id' => '{$__cakeID__$}'),
                        array('fighter2_id' => '{$__cakeID__$}')
                    )
                )
            ),
            'Contract' => array(
                'fields' => array(
                    'boxer_id',
                    'created'
                )
            )
        );
        $boxers = $this->find('all', array('fields' => $fields, 'contain' => $contain, 'conditions' => array('Boxer.manager_id <>' => NULL, 'Boxer.retired' => 0)));
        foreach($boxers as $boxer){
            $now  = strtotime(date('Y-m-d H:i:s'));
            if(isset($boxer['Fight']) && !empty($boxer['Fight'])){
                    $lastFight = strtotime($boxer['Fight']['0']['created']);
                    $difference = $now - $lastFight;
                    if($difference > $month){
//                        $this->id = $boxer['Boxer']['id'];
//                        $happiness = $boxer['Boxer']['happiness'] - 2;
//                        $this->saveField('happiness', $happiness);
//                        $this->Fight->Notification->inactivityWarning($boxer);
                            $this->Fight->Notification->whiners($boxer, 'leave');
                            $this->removeManager($boxer['Boxer']['id']);
                            $this->removeTrainer($boxer['Boxer']['id']);
                            $this->Manager->Contract->removeAcceptedBoxerContract($boxer['Boxer']['id']);
                            $this->id = $boxer['Boxer']['id'];
                            $this->saveField('happiness', '50');
                    }
            }else{
                $lastFight = strtotime($boxer['Contract']['created']);
                $difference = $now - $lastFight;
                if($difference > $month){
//                    $this->id = $boxer['Boxer']['id'];
//                    $happiness = $boxer['Boxer']['happiness'] - 2;
//                    $this->saveField('happiness', $happiness);
//                    $this->Fight->Notification->inactivityWarning($boxer);
                    $this->Fight->Notification->whiners($boxer, 'leave');
                    $this->removeManager($boxer['Boxer']['id']);
                    $this->removeTrainer($boxer['Boxer']['id']);
                    $this->Manager->Contract->removeAcceptedBoxerContract($boxer['Boxer']['id']);
                    $this->id = $boxer['Boxer']['id'];
                    $this->saveField('happiness', '50');
                }
            }
        }
	}
	//setting happiness back to 50.
	public function updateHappiness($boxer_id){
		$this->id = $boxer_id;
		$this->saveField('happiness', '50');
	}
	
	public function reviewContract($boxer1_id = null, $boxer2_id = null){
		$contain = array(
			'Contract' => array(
				'fields' => array(
					'boxer_id',
					'value',
                    'percentage'
					//'start_date'
				)
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
		);
		$boxers = $this->find('all', array(
                'fields' => array(
                    'id', 'rank', 'fame', 'manager_id', 'greed', 'happiness', 'forname_id', 'surname_id'
                    ),
                'contain' => $contain,
                'conditions' => array(
                    'Boxer.manager_id <>' => NULL, 'retired' => '0', 'or' => array(
                        array('Boxer.id' => $boxer1_id),
                        array('Boxer.id' => $boxer2_id)
                    )
                )
            )
        );
		foreach($boxers as $boxer){
            if($boxer['Contract']['percentage'] >= 20){
                if($boxer['Boxer']['fame'] > 50){
                    $boxer['Boxer']['fame'] = (25 + ($boxer['Boxer']['fame'] - ($boxer['Boxer']['fame'] * 0.6)));
                }
                $boxersValue = $boxer['Boxer']['fame'] - ($boxer['Boxer']['rank'] * 2);
                    if($boxersValue < 10){
                        $boxersValue = 10;
                    }
                $boxersGreed = abs($boxer['Boxer']['greed'] - 100);
                $boxer['Contract']['value'] = $boxer['Contract']['value'] + ($boxersGreed / 2) + ($boxer['Boxer']['happiness'] / 2);
                if($boxer['Contract']['value'] < $boxersValue){
                    $this->Fight->Notification->renegotiationWarning($boxer);
                    $boxer['Boxer']['happiness'] = $boxer['Boxer']['happiness'] - 20;
                    if($boxer['Boxer']['happiness'] < 0){
                        $boxer['Boxer']['happiness'] = 0;
                    }
                    $this->id = $boxer['Boxer']['id'];
                    $this->saveField('happiness', $boxer['Boxer']['happiness']);
                }
            }
        }
	}
	
	public function cleanUp(){
		$contain = array(
			'Fight' => array(
				'fields' => array(
					'Fight.id'
				)
			)
		);
		
		$fields = array(
			'Boxer.id',
			'Boxer.retired'
		);
		
		$boxers = $this->find('all', array('fields' => $fields, 'contain' => $contain, 'conditions' => array('retired' => '1')));
		foreach($boxers as $boxer){
			if(empty($boxer['Fight']) || !isset($boxer['Fight'])){
				$this->id = $boxer['Boxer']['id'];
				$this->delete($boxer['Boxer']['id']);	
			}
		}
	}
	
	public function hallOfFame(){
		$this->getHighest('fame', '20');
		
		$this->getHighest('wins', '21');
		
		$this->getHighest('loses', '22');
		
		$this->getHighest('draws', '23');
		
		$this->getHighest('knockouts', '24');
		
		$this->getHighest('floored', '25');
		
		$this->getHallRankChamps();
		
	}
	
	public function getHighest($var, $type){
		$fields = array(
			'Boxer.id',
			'Boxer.manager_id',
			'Boxer.forname_id',
			'Boxer.surname_id',
			'Boxer.region',
			'Boxer.rank',
			'Boxer.weight_type',
			'Boxer.fame',
			'Boxer.wins',
			'Boxer.loses',
			'Boxer.draws',
			'Boxer.knockouts',
			'Boxer.floored',
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
			'Manager' => array(
				'fields' => array(
					'id',
					'user_id'
				),
				'User' => array(
					'fields' => array(
						'username'
					)
				)
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
		
		);
		
		$highest = $this->Hall->find('first', array('recursive' => -1, 'fields' => array('Hall.id', 'Hall.'.$var.''), 'conditions' => array('Hall.type' => $type)));
		$current = $this->find('first', array('contain' => $contain, 'order' => 'Boxer.'.$var.' DESC', 'fields' => $fields));
		if(empty($highest)){
			$highest = array('Hall' => array('id' => NULL, $var => 0));	
		}
		if($current['Boxer'][$var] > $highest['Hall'][$var]) {
			$temp = $current['Boxer']['id'];
			$current['Boxer']['id'] = NULL;
			$data['Hall'] = $current['Boxer'];
			$data['Hall']['boxer_id'] = $temp;
			if(!empty($current['Manager']['User'])){
				$data['Hall']['manager_name'] = $current['Manager']['User']['username'];
			}
			$data['Hall']['type'] = $type;
			$data['Hall']['name'] = $current['Forname']['name']. ' '.$current['Surname']['name'];
			$data['Hall']['game_date_start'] = date('Y-m-d H:i:s');
			
			$this->Hall->save($data);
			
			if($highest['Hall']['id'] != NULL){
				$this->Hall->id = $highest['Hall']['id'];
				$this->Hall->delete($highest['Hall']['id']);
			}
		}
	}
	
	public function getHallRankChamps(){
		$fields = array(
			'Boxer.id',
			'Boxer.manager_id',
			'Boxer.forname_id',
			'Boxer.surname_id',
			'Boxer.region',
			'Boxer.rank',
			'Boxer.weight_type',
			'Boxer.fame',
			'Boxer.wins',
			'Boxer.loses',
			'Boxer.draws',
			'Boxer.knockouts',
			'Boxer.floored',
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
			'Manager' => array(
				'fields' => array(
					'id',
					'user_id'
				),
				'User' => array(
					'fields' => array(
						'username'
					)
				)
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
		
		);
		
		$weights =  Configure::read('Weight.class');
		foreach($weights as $key => $weight) {
			$champ = $this->find('first', array('conditions' => array('weight_type' => $key, 'rank' => '1', 'retired' => '0'), 'contain' => $contain, 'fields' => $fields));
			$hallChamp = $this->Hall->find('first', array('conditions' => array('weight_type' => $key, 'rank' => '1', 'type' => $key), 'recursive' => -1, 'fields' => array('Hall.id', 'Hall.type', 'Hall.rank', 'Hall.weight_type', 'Hall.boxer_id'), 'order' => 'Hall.game_date_start DESC'));
            if(empty($hallChamp) || $champ['Boxer']['id'] != $hallChamp['Hall']['boxer_id']) {
				$temp = $champ['Boxer']['id'];
				$champ['Boxer']['id'] = NULL;
				$data['Hall'] = $champ['Boxer'];
				$data['Hall']['boxer_id'] = $temp;
				if(!empty($champ['Manager']['User'])){
					$data['Hall']['manager_name'] = $champ['Manager']['User']['username'];
				}
				$data['Hall']['type'] = $key;
				$data['Hall']['name'] = $champ['Forname']['name']. ' '.$champ['Surname']['name'];
				$data['Hall']['game_date_start'] = date('Y-m-d H:i:s');
				$this->Hall->save($data);

                //setting previous hallChamp game date end
                $this->Hall->id = $hallChamp['Hall']['id'];
                $this->Hall->saveField('game_date_end', date('Y-m-d H:i:s'));
			} else {
				$this->Hall->id = $hallChamp['Hall']['id'];
				$this->Hall->saveField('game_date_end', NULL);
			}
		}
	}
	
	public function mandatory() {
		$weights = Configure::read('Weight.class');
        $time = date('Y-m-d H:i:s');
        $week = 60 * 60 * 24 * 7;
        $time = strtotime($time);
        $offsetTime = $time - $week;
//        $time
		//foreach weight class
		foreach ($weights as $key => $weight){
			$fields = array(
				'Boxer.id',
				'Boxer.retired',
				'Boxer.rank',
				'Boxer.weight_type',
				'Boxer.manager_id',
				'Boxer.injured'
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
						'Manager.id',
						'Manager.user_id'
					),
					'User' => array(
						'fields' => array(
							'User.id',
							'User.username'
						)	
					)
				),
				'Fight' => array(
					'fields' => array(
						'Fight.id',
						'Fight.fighter1_id',
						'Fight.fighter2_id',
						'Fight.accepted',
						'Fight.winner_id',
                        'Fight.created'
					),
					'conditions' => array(
						'or' => array(
							array('fighter1_id' => '{$__cakeID__$}'),
							array('fighter2_id' => '{$__cakeID__$}')
						),
						'Fight.accepted' => '1',
						//'Fight.winner_id <>' => NULL,
						//'Fight.created <' => $game_time
					),
					'order' => 'Fight.created DESC',
					'limit' => '3',
					'Fighter1' => array(
						'fields' => array(
							'id',
							'rank'
						)
					),
					'Fighter2' => array(
						'fields' => array(
							'id',
							'rank'
						)
					)
				)
			
			);
			
			$champ = $this->find('first', array('fields' => $fields, 'contain' => $contain, 'conditions' => array('retired' => '0', 'rank' => '1', 'weight_type' => $key, 'manager_id <>' => NULL, 'injured' => '0')));
			//if there is a player controlled champ and he has had atleast one fight
			if(!empty($champ) && isset($champ) && !empty($champ['Fight']) && strtotime($champ['Fight'][0]['created']) < $offsetTime){
				$chicken = 0;
				$opponent = array();
				//foreach of champs last 3 fights did he fight a top 6 opponent
				foreach($champ['Fight'] as $fight){
					$opponent = $fight['Fighter1'];
					if($champ['Boxer']['id'] == $opponent['id']){
						$opponent = $fight['Fighter2'];	
					}
					if($opponent['rank'] <= 6){
						$chicken = 0;
						break;
					} else {
						$chicken = 1;
					}
				}
				//if the champ is a "chicken" then do something about it
				if($chicken == 1){
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
						'Fight' => array(
							'fields' => array(
								'id',
								'accepted',
								'game_time'
							),
							'conditions' => array(
								//'game_time >=' => $game_time,
                                'winner_id' => NULL,
								'accepted' => '1',
								'or' => array(
											array('fighter1_id' => '{$__cakeID__$}'),
											array('fighter2_id' => '{$__cakeID__$}')
										)
							)
						),
					);
					
					$fields = array(
						'Boxer.id',
						'Boxer.rank',
						'Boxer.manager_id',
						'Boxer.injured',
						'Boxer.retired',
						'Boxer.weight_type'
					);
					
					//try and get top 6 challengers for champ
					//$challengers = $this->find('all', array('fields' => $fields, 'contain' => $contain, 'conditions' => array('weight_type' => $key, 'retired' => 0, 'rank <>' => 1, 'rank <' => 6, 'Boxer.manager_id <>' => $champ['Boxer']['manager_id']), 'order' => 'rank ASC'));
					$challengers = $this->find('all', array('fields' => $fields, 'contain' => $contain, 'conditions' => array('weight_type' => $key, 'retired' => 0, 'rank <>' => 1), 'order' => 'rank ASC', 'limit' => 5));
					$data = array();
					$count = 0;
					foreach ($challengers as $challenger){
						if(!isset($challenger['Fight'][0]['id'])){
							$data[$count] = $challenger;
							$count++;
						}
					}
					
					//if after all the requirements we still have challengers pick a random one
					if(!empty($data)){
						$rand = array_rand($data, 1);
						$challenger = $data[$rand];
					} 
					
					if(!empty($challenger)){
						//send notification to the champs manager about mandatory fight
						$this->Fight->Notification->champMandatory($champ, $challenger);
						
						//send notification to challengers manager if they have one
						if(!empty($challenger['Boxer']['manager_id'])){
							$this->Fight->Notification->challenegerMandatory($champ, $challenger);
							//create the fight now.
							$this->Fight->createFight($champ['Boxer']['id'], $challenger['Boxer']['id'], '5000', 150000, $champ['Boxer']['manager_id']);
						} else {
							//create the fight now.
							$this->Fight->createFight($champ['Boxer']['id'], $challenger['Boxer']['id'], '5000', NULL, $champ['Boxer']['manager_id']);
						}
					}
				}
			}
		}
	}
}
