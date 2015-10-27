<?php
App::uses('AppModel', 'Model');
//App::uses('Security', 'Utility'); 
//App::import('Component','Auth'); 
/**
 * User Model
 *
 * @property Item $Item
 */
 
class Notification extends AppModel {	
	
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
		'Manager',
		'Fight'
	);
	
	public function fightOffer($oldData = null, $boxer = null, $new_boxer = null, $session = null, $venues = null, $fight_id = null, $note = null){
		$this->create();
		$data = array();
		$oldData['fee'] = number_format($oldData['fee'], 0, '.', ',');
		if($session == $new_boxer['Boxer']['manager_id']){
			$data['Notification']['recipient_id'] = $boxer['Boxer']['manager_id'];
		}else{
			$data['Notification']['recipient_id'] = $new_boxer['Boxer']['manager_id'];
		}

        if($data['Notification']['recipient_id'] != null){
            $data['Notification']['sender_id'] = $session;
            $data['Notification']['fight_id'] = $fight_id;
            //$data['Notification']['game_date'] = $game_time;
            $data['Notification']['type'] = '1';
            $data['Notification']['title'] = 'Fight offer between <a class = "gold" href = "/boxers/view/'.$boxer['Boxer']['id'].'">'.$boxer['Forname']['name'].' '.$boxer['Surname']['name'].'</a> and <a class = "gold" href = "/boxers/view/'.$new_boxer['Boxer']['id'].'">'.$new_boxer['Forname']['name'].' '.$new_boxer['Surname']['name'].'</a>';
            $data['Notification']['text'] = '<p>You have been offered a fight between <a class = "gold" href = "/boxers/view/'.$boxer['Boxer']['id'].'">'.$boxer['Forname']['name'].' '.$boxer['Surname']['name'].'</a> and <a class = "gold" href = "/boxers/view/'.$new_boxer['Boxer']['id'].'">'.$new_boxer['Forname']['name'].' '.$new_boxer['Surname']['name'].'</a></p>
                <p>Your fighter is being offered $'.$oldData['fee'].' as his manager you will receive a percentage of this.</p>
                <p>The Venue for this fight is a '.$venues[$oldData['venue_id']].'</p>
                <p>Note from manager: '.$note.'</p>'
            ;
            return $this->save($data);
        }
	}
	
	public function removeNotifications($fight_id = null){
		$notifications = $this->find('all', array('recursive' => -1, 'fields' => array('id', 'fight_id'), 'conditions' => array('fight_id' => $fight_id)));	
		foreach($notifications as $notification){
			$this->id = $notification['Notification']['id'];
			$this->delete($notification['Notification']['id']);
		}
	}
	
	public function alertFightCancel($recipient_id = null, $sender_id = null, $date = null){
		$data['0']['Notification']['sender_id'] = $sender_id;
		$data['0']['Notification']['recipient_id'] = $recipient_id;
		$data['0']['Notification']['game_date'] = $date;
		$data['0']['Notification']['type'] = '2';
		$data['0']['Notification']['title'] = 'A fight has been cancelled due to injury';
		$data['0']['Notification']['text'] = '<p>A fight between one of your fighters has been cancelled.</p>
			<p>The reason for this is one of the two fighters has become injured and unable to fight</p>'
		;
		
		$data['1']['Notification']['sender_id'] = $recipient_id;
		$data['1']['Notification']['recipient_id'] = $sender_id;
		$data['1']['Notification']['game_date'] = $date;
		$data['1']['Notification']['type'] = '2';
		$data['1']['Notification']['title'] = 'A fight has been cancelled due to injury';
		$data['1']['Notification']['text'] = '<p>A fight between one of your fighters has been cancelled.</p>
			<p>The reason for this is one of the two fighters has become injured and unable to fight</p>'
		;
		
		$this->create();
		$this->saveAll($data);
	}
	
	public function alertArrangeFightCancel($recipient_id, $date){
		$data['Notification']['recipient_id'] = $recipient_id;
		$data['Notification']['game_date'] = $date;
		$data['Notification']['type'] = '2';
		$data['Notification']['title'] = 'A fight you\'ve tried to arrange has been cancelled due to injury';
		$data['Notification']['text'] = '<p>A fight you\'ve offered cannot take place because one of the fighters is now injured</p>'
		;
	}
	
	public function alertFightCancelOverbooked($recipient_id){
		$data['Notification']['recipient_id'] = $recipient_id;
		//$data['Notification']['game_date'] = $date;
		$data['Notification']['type'] = '2';
		$data['Notification']['title'] = 'A fight you\'ve tried to arrange has been cancelled due to over booking';
		$data['Notification']['text'] = '<p>It looks like you\'ve arranged many fights for one of your boxers</p>
		<p>however since one was accepted this one has been cancelled.</p>'
		;
	}
	
	
	public function alertBoxerInjured($boxer = null, $manager_id = null, $type = null){
		//$durationTime = strtotime($type['duration']);
		//$durationTime = date('M jS Y', $durationTime);
		$data['Notification']['recipient_id'] = $manager_id;
		$data['Notification']['type'] = '2';
		$data['Notification']['title'] = 'Your boxer <a class = "gold" href = "/boxers/view/'.$boxer['Boxer']['id'].'">'.$boxer['Forname']['name'].' '.$boxer['Surname']['name'].'</a> was injured!';
		$data['Notification']['text'] = '
			<p>'.$type['text'].'</p>
			<p>Depending on the seriousness of the injury this could affect his performance</p>'
		;
		$this->create();
		$this->save($data);
	}
	
	public function alertBoxerRecovered($boxer = null, $manager_id = null, $type = null, $date = null){
		$data['Notification']['recipient_id'] = $manager_id;
		$data['Notification']['game_date'] = $date;
		$data['Notification']['type'] = '2';
		$data['Notification']['title'] = 'Your boxer <a class = "gold" href = "/boxers/view/'.$boxer['Boxer']['id'].'">'.$boxer['Forname']['name'].' '.$boxer['Surname']['name'].'</a> has recovered from his injury!';
		$data['Notification']['text'] = '
			<p>He has fully recovered from his injury and is ready for training and fights again</p>'
		;
		$this->create();
		$this->save($data);
	}
	
	public function alertRefund($manager_id = null, $refund = null){
		$refund = number_format($refund, 0, '.', ',');
		$data['Notification']['recipient_id'] = $manager_id;
		//$data['Notification']['game_date'] = $date;
		$data['Notification']['type'] = '2';
		$data['Notification']['title'] = 'You have been refunded $'.$refund;
		$data['Notification']['text'] = '<p>You have been refunded $'.$refund.'</p>
			<p>Due to a fight being cancelled you have been refunded your fee for the fight.</p>
			<p>Plus half the amount you paid for the venue.</p>'
		;
		$this->create();
		$this->save($data);
	}
	
	public function boxerRetired($boxer = null, $reason = null){
        $weightClasses = Configure::read('Weight.class');
		$data['Notification']['recipient_id'] = $boxer['Boxer']['manager_id'];
		//$data['Notification']['game_date'] = $date;
		$data['Notification']['type'] = '2';
		$data['Notification']['title'] = 'Your boxer <a class = "gold" href = "/boxers/view/'.$boxer['Boxer']['id'].'">'.$boxer['Forname']['name'].' '.$boxer['Surname']['name'].'</a> has retired!';
		$data['Notification']['text'] = '<p>Your boxer <a class = "gold" href = "/boxers/view/'.$boxer['Boxer']['id'].'">'.$boxer['Forname']['name'].' '.$boxer['Surname']['name'].'</a> has retired!</p>
			<p>When asked why he give the following reason</p>
			<p>'.$reason.'</p>
			<p>A '.$weightClasses[$boxer['Boxer']['weight_type']].' aged '.$boxer['Boxer']['age'].' he was ranked ('.$boxer['Boxer']['rank'].'), '.$boxer['Boxer']['wins'].' wins, '.$boxer['Boxer']['loses'].' loses with '.$boxer['Boxer']['knockouts'].' knockouts</p>
			<p>He will be missed!</p>'
		;
		$this->create();
		$this->save($data);
	}
	
	public function boxerRetiredAll($boxer = null, $reason = null, $manager_id = null){
        $weightClasses = Configure::read('Weight.class');
		$data['Notification']['recipient_id'] = $manager_id;
		//$data['Notification']['game_date'] = $date;
		$data['Notification']['type'] = '2';
        if($boxer['Boxer']['rank'] != 1){
            $data['Notification']['title'] = 'A boxer <a class = "gold" href = "/boxers/view/'.$boxer['Boxer']['id'].'">'.$boxer['Forname']['name'].' '.$boxer['Surname']['name'].'</a> has retired!';
            $data['Notification']['text'] = '<p>A boxer <a class = "gold" href = "/boxers/view/'.$boxer['Boxer']['id'].'">'.$boxer['Forname']['name'].' '.$boxer['Surname']['name'].'</a> has retired!</p>
                <p>When asked why he give the following reason</p>
                <p>'.$reason.'</p>
                <p>A '.$weightClasses[$boxer['Boxer']['weight_type']].' aged '.$boxer['Boxer']['age'].' he was ranked ('.$boxer['Boxer']['rank'].'), '.$boxer['Boxer']['wins'].' wins, '.$boxer['Boxer']['loses'].' loses with '.$boxer['Boxer']['knockouts'].' knockouts</p>'
            ;
        }else{
            $data['Notification']['title'] = 'The '.$weightClasses[$boxer['Boxer']['weight_type']].' champion <a class = "gold" href = "/boxers/view/'.$boxer['Boxer']['id'].'">'.$boxer['Forname']['name'].' '.$boxer['Surname']['name'].'</a> has retired!';
            $data['Notification']['text'] = '<p>The '.$weightClasses[$boxer['Boxer']['weight_type']].' champion <a class = "gold" href = "/boxers/view/'.$boxer['Boxer']['id'].'">'.$boxer['Forname']['name'].' '.$boxer['Surname']['name'].'</a> has retired!</p>
                <p>In a move that will shock many has given up his hold on the '.$weightClasses[$boxer['Boxer']['weight_type']].' BGC belt. He stated that</p>
                <p>'.$reason.'</p>
                <p>Aged '.$boxer['Boxer']['age'].' his record included '.$boxer['Boxer']['wins'].' wins, '.$boxer['Boxer']['loses'].' loses with '.$boxer['Boxer']['knockouts'].' knockouts</p>'
            ;
        }
		$this->create();
		$this->save($data);
	}
	
	public function alertTrainerRetired($trainer = null){
		$data['Notification']['recipient_id'] = $trainer['Trainer']['manager_id'];
		//$data['Notification']['game_date'] = $date;
		$data['Notification']['type'] = '2';
		$data['Notification']['title'] = 'Your trainer '.$trainer['Forname']['name'].' '.$trainer['Surname']['name'].' has retired!';
		$data['Notification']['text'] = '<p>Your trainer '.$trainer['Forname']['name'].' '.$trainer['Surname']['name'].' has retired!</p>
			<p>'.$trainer['Forname']['name'].' '.$trainer['Surname']['name'].' reached retirement age and has decided its time to move on</p>'
		;
		$this->create();
		$this->save($data);
	}
	
	public function trainerRetiredAll($trainer = null, $manager_id){
		$data['Notification']['recipient_id'] = $manager_id;
		//$data['Notification']['game_date'] = $date;
		$data['Notification']['type'] = '2';
		$data['Notification']['title'] = 'A trainer '.$trainer['Forname']['name'].' '.$trainer['Surname']['name'].' has retired!';
		$data['Notification']['text'] = '<p>A trainer '.$trainer['Forname']['name'].' '.$trainer['Surname']['name'].' has retired!</p>
			<p>'.$trainer['Forname']['name'].' '.$trainer['Surname']['name'].' reached retirement age and has decided its time to move on</p>'
		;
		$this->create();
		$this->save($data);
	}
	
	public function returnNpcFights(){
		$data = array();
		$notifications = $this->find('all', array('recursive' => -1, 'fields' => array('id', 'fight_id', 'response', 'recipient_id'), 'conditions' => array('response' => '0', 'recipient_id' => null, 'fight_id <>' => null)));
		foreach ($notifications as $notification){
			$data[$notification['Notification']['fight_id']] = $notification['Notification']['fight_id'];
		}
		return $data;
	}
	
	public function acceptedFightOffer($acceptedFight = null){
		//$notification = $this->find('first', array('recursive' => -1, 'fields' => array('sender_id', 'fight_id'), 'conditions' => array('fight_id' => $acceptedFight['Fight']['id'])));
		if($acceptedFight["Fighter1"]['manager_id'] != null){
            $data['Notification']['recipient_id'] = $acceptedFight["Fighter1"]['manager_id'];
            $data['Notification']['type'] = 1;
            $data['Notification']['response'] = 1;
            $data['Notification']['title'] = 'Offer ACCEPTED between <a class = "gold" href = "/boxers/view/'.$acceptedFight['Fighter1']['id'].'">'.$acceptedFight['Fighter1']['Forname']['name'].' '.$acceptedFight['Fighter1']['Surname']['name'].'</a> and <a class = "gold" href = "/boxers/view/'.$acceptedFight['Fighter2']['id'].'">'.$acceptedFight['Fighter2']['Forname']['name'].' '.$acceptedFight['Fighter2']['Surname']['name'].'</a>';
            $data['Notification']['text'] = '<p>Offer ACCEPTED between '.$acceptedFight['Fighter1']['Forname']['name'].' '.$acceptedFight['Fighter1']['Surname']['name'].' and '.$acceptedFight['Fighter2']['Forname']['name'].' '.$acceptedFight['Fighter2']['Surname']['name'].'</p>
                <p>Well done the offer you made has been accepted.</p>
                <p>LETS GET IT ON!</p>';
            $this->create();
            $this->save($data);
        }

        if($acceptedFight["Fighter2"]['manager_id'] != null){
            $data['Notification']['recipient_id'] = $acceptedFight["Fighter2"]['manager_id'];
            $data['Notification']['type'] = 1;
            $data['Notification']['response'] = 1;
            $data['Notification']['title'] = 'Offer ACCEPTED between <a class = "gold" href = "/boxers/view/'.$acceptedFight['Fighter1']['id'].'">'.$acceptedFight['Fighter1']['Forname']['name'].' '.$acceptedFight['Fighter1']['Surname']['name'].'</a> and <a class = "gold" href = "/boxers/view/'.$acceptedFight['Fighter2']['id'].'">'.$acceptedFight['Fighter2']['Forname']['name'].' '.$acceptedFight['Fighter2']['Surname']['name'].'</a>';
            $data['Notification']['text'] = '<p>Offer ACCEPTED between '.$acceptedFight['Fighter1']['Forname']['name'].' '.$acceptedFight['Fighter1']['Surname']['name'].' and '.$acceptedFight['Fighter2']['Forname']['name'].' '.$acceptedFight['Fighter2']['Surname']['name'].'</p>
                            <p>Well done the offer you made has been accepted.</p>
                            <p>LETS GET IT ON!</p>';
            $this->create();
            $this->save($data);
        }
	}
	
	public function updateReponseByFightId($fight_id){
		$notification = $this->find('first', array('recursive' => -1, 'fields' => array('id', 'fight_id'), 'conditions' => array('fight_id' => $fight_id)));
		$this->id = $notification['Notification']['id'];
		$this->saveField('response', '1');
	}
	
	public function getSenderId($fight_id){
		$notification = $this->find('first', array('recursive' => -1, 'fields' => array('id', 'fight_id', 'sender_id'), 'conditions' => array('fight_id' => $fight_id)));
		return $notification['Notification']['sender_id'];
	}
	
	public function rejectedFightOffer($rejectedFight = null){
		//$notification = $this->find('first', array('recursive' => -1, 'fields' => array('sender_id', 'fight_id'), 'conditions' => array('fight_id' => $rejectedFight['Fight']['id'])));
		$data['Notification']['recipient_id'] = $rejectedFight['Fight']['manager_id'];
		$data['Notification']['type'] = 1;
		$data['Notification']['response'] = 1;
		//$data['Notification']['game_date'] = $game_time;
		$data['Notification']['title'] = 'Offer REJECTED between <a class = "gold" href = "/boxers/view/'.$rejectedFight['Fighter1']['id'].'">'.$rejectedFight['Fighter1']['Forname']['name'].' '.$rejectedFight['Fighter1']['Surname']['name'].'</a> and <a class = "gold" href = "/boxers/view/'.$rejectedFight['Fighter2']['id'].'">'.$rejectedFight['Fighter2']['Forname']['name'].' '.$rejectedFight['Fighter2']['Surname']['name'].'</a>';
		$data['Notification']['text'] = '<p>Offer REJECTED between '.$rejectedFight['Fighter1']['Forname']['name'].' '.$rejectedFight['Fighter1']['Surname']['name'].' and '.$rejectedFight['Fighter2']['Forname']['name'].' '.$rejectedFight['Fighter2']['Surname']['name'].'</p>
			<p>Could be a number reasons for this. Chances are you didnt offer enough money and a better offer was accepted.</p>';
		$this->create();
		$this->save($data);
	}
	
	public function deleteRejectedOfferByFightId($fight_id = null){
		$notification = $this->find('first', array('recursive' => -1, 'fields' => array('sender_id', 'fight_id', 'id'), 'conditions' => array('fight_id' => $fight_id)));
		$this->id = $notification['Notification']['id'];
		$this->delete($notification['Notification']['id']);
	}
	
	public function rejectedTrainerContract($rejectedContract){
		$rejectedContract['Contract']['bonus'] = number_format($rejectedContract['Contract']['bonus'], 0, '.', ',');
		$data['Notification']['recipient_id'] = $rejectedContract['Contract']['manager_id'];
		$data['Notification']['type'] = 1;
		$data['Notification']['response'] = 1;
		//$data['Notification']['game_date'] = $game_time;
		$data['Notification']['title'] = 'Contract rejected by trainer <a class = "gold" href = "/trainers/view/'.$rejectedContract['Trainer']['id'].'">'.$rejectedContract['Trainer']['Forname']['name']. ' ' .$rejectedContract['Trainer']['Surname']['name'].'</a>';
		$data['Notification']['text'] = '<p>Contract rejected by trainer '.$rejectedContract['Trainer']['Forname']['name']. ' ' .$rejectedContract['Trainer']['Surname']['name'].'</p>
			<p>You offered a fee of $'.$rejectedContract['Contract']['bonus'].'</p>
			<p>The offer was rejected because he recieved a better offer elsewhere or he felt he was worth a better contract</p>
			<p>Your bonus of $'. $rejectedContract['Contract']['bonus'].' has been returned</p>';
		$this->create();
		$this->save($data);
	}
	
	public function acceptTrainerContract($acceptedContract){
		$acceptedContract['Contract']['bonus'] = number_format($acceptedContract['Contract']['bonus'], 0, '.', ',');
		$data['Notification']['recipient_id'] = $acceptedContract['Contract']['manager_id'];
		$data['Notification']['type'] = 1;
		$data['Notification']['response'] = 1;
		//$data['Notification']['game_date'] = $game_time;
		$data['Notification']['title'] = 'Contract accepted by trainer <a class = "gold" href = "/trainers/view/'.$acceptedContract['Trainer']['id'].'">'.$acceptedContract['Trainer']['Forname']['name']. ' ' .$acceptedContract['Trainer']['Surname']['name'].'</a>';
		$data['Notification']['text'] = '<p>Contract accepted by trainer '.$acceptedContract['Trainer']['Forname']['name']. ' ' .$acceptedContract['Trainer']['Surname']['name'].'</p>
			<p>You offered him a fee of $'.$acceptedContract['Contract']['bonus'].'.</p>
			<p>The offer was accepted and you can now start using this trainer for your boxers. Well done!</p>';
		$this->create();
		$this->save($data);
	}
	
	public function acceptBoxerContract($acceptedContract){
		$percentage = 100 - $acceptedContract['Contract']['percentage'];
		$acceptedContract['Contract']['bonus'] = number_format($acceptedContract['Contract']['bonus'], 0, '.', ',');
		$data['Notification']['recipient_id'] = $acceptedContract['Contract']['manager_id'];
		$data['Notification']['type'] = 1;
		$data['Notification']['response'] = 1;
		//$data['Notification']['game_date'] = $game_time;
		$data['Notification']['title'] = 'Contract accepted by boxer <a class = "gold" href = "/boxers/view/'.$acceptedContract['Boxer']['id'].'">'.$acceptedContract['Boxer']['Forname']['name']. ' ' .$acceptedContract['Boxer']['Surname']['name'].'</a>';
		$data['Notification']['text'] = '<p>Contract accepted by boxer '.$acceptedContract['Boxer']['Forname']['name']. ' ' .$acceptedContract['Boxer']['Surname']['name'].'</p>
			<p>You offered him a percentage of '.$percentage.'% and a bonus of $'.$acceptedContract['Contract']['bonus'].'</p>
			<p>The offer was accepted and you can now start arranging fights for your boxers. Well done!</p>';
		$this->create();
		$this->save($data);
	}
	
	public function rejectedBoxerContract($rejectedContract){
		$percentage = 100 - $rejectedContract['Contract']['percentage'];
		$rejectedContract['Contract']['bonus'] = number_format($rejectedContract['Contract']['bonus'], 0, '.', ',');
		$data['Notification']['recipient_id'] = $rejectedContract['Contract']['manager_id'];
		$data['Notification']['type'] = 1;
		$data['Notification']['response'] = 1;
		//$data['Notification']['game_date'] = $game_time;
		$data['Notification']['title'] = 'Contract rejected by boxer <a class = "gold" href = "/boxers/view/'.$rejectedContract['Boxer']['id'].'">'.$rejectedContract['Boxer']['Forname']['name']. ' ' .$rejectedContract['Boxer']['Surname']['name'].'</a>';
		$data['Notification']['text'] = '<p>Contract rejected by boxer '.$rejectedContract['Boxer']['Forname']['name']. ' ' .$rejectedContract['Boxer']['Surname']['name'].'</p>
			<p>You offered him a percentage of '.$percentage.'% and a bonus of $'.$rejectedContract['Contract']['bonus'].'</p>
			<p>The offer was rejected because he recieved a better offer elsewhere or he\'s holding out for a better contract</p>
			<p>Your bonus of $'. $rejectedContract['Contract']['bonus'].' has been returned</p>';
		$this->create();
		$this->save($data);
	}
	
	public function trainingImprovement($boxer){
		$data['Notification']['recipient_id'] = $boxer['Boxer']['manager_id'];
		$data['Notification']['type'] = 2;
		$data['Notification']['response'] = 1;
		//$data['Notification']['game_date'] = $game_time;
		$data['Notification']['title'] = 'Training is going well for boxer <a class = "gold" href = "/boxers/view/'.$boxer['Boxer']['id'].'">'.$boxer['Forname']['name']. ' ' .$boxer['Surname']['name'].'</a>';
		$data['Notification']['text'] = '<p>Training is going well for boxer '.$boxer['Forname']['name']. ' ' .$boxer['Surname']['name'].'</p>
			<p>Your trainer ' .$boxer['Trainer']['Forname']['name'] . ' '.$boxer['Trainer']['Surname']['name'].' believes '.$boxer['Forname']['name']. ' ' .$boxer['Surname']['name'].' </p>
			<p>has made improvements and is a better boxer as a result!</p>';
		$this->create();
		$this->save($data);
	}
	
	public function fightArrangerMoney($fight, $totalMoney, $tvMoney = NULL, $percentage, $attendence, $ticketPrice){
		$attendence = floor($attendence);
		$receipts = ($totalMoney - $tvMoney); 
		$percentage = floor($percentage);
		$percentage = number_format($percentage, 0, '.', ',');
		$receipts = number_format($receipts, 0, '.', ',');
		$tvMoney = number_format($tvMoney, 0, '.', ',');
		$totalMoney = number_format($totalMoney, 0, '.', ',');
		$data['Notification']['recipient_id'] = $fight['Fight']['manager_id'];
		$data['Notification']['type'] = 2;
		$data['Notification']['response'] = 1;
		$data['Notification']['title'] = 'You have gained $'.$percentage.' for a fight you arranged';
		if($tvMoney != NULL){
			$data['Notification']['text'] = '<p>You have gained $'.$percentage.' for a fight you arranged</p>
			<p>The fight was between ' .$fight['Fighter1']['Forname']['name'] . ' '.$fight['Fighter1']['Surname']['name'].' and '.$fight['Fighter2']['Forname']['name']. ' ' .$fight['Fighter2']['Surname']['name'].' </p>
			<p>The venue was a '. $fight['Venue']['title'].'</p>
			<p>The ticket price was $'. $ticketPrice.'</p>
			<p>The attendence was '. $attendence.'</p>
			<p>Ticket receipts where $' .$receipts. ' and the TV rights where worth $' .$tvMoney. '</p>
			<p>After your fighters cut you earned a total of $'.$percentage.'</p>';
		} else {
			$data['Notification']['text'] = '<p>You have gained $'.$percentage.' for a fight you arranged</p>
			<p>The fight was between ' .$fight['Fighter1']['Forname']['name'] . ' '.$fight['Fighter1']['Surname']['name'].' and '.$fight['Fighter2']['Forname']['name']. ' ' .$fight['Fighter2']['Surname']['name'].' </p>
			<p>The venue was a '. $fight['Venue']['title'].'</p>
			<p>The ticket price was $'. $ticketPrice.'</p>
			<p>The attendence was '. $attendence.'</p>
			<p>Ticket receipts where $' .$totalMoney. ' The fight did not recieve any TV coverage</p>
			<p>After your fighters cut you earned a total of $'.$percentage.'</p>';
		}
		$this->create();
		$this->save($data);
	}
	
	public function fightFee($fight, $percentage, $manager_id, $fee){
		$percentage = floor($percentage);
		$percentage = number_format($percentage, 0, '.', ',');
		$fee = number_format($fee, 0, '.', ',');
		$data['Notification']['recipient_id'] = $manager_id;
		$data['Notification']['type'] = 2;
		$data['Notification']['response'] = 1;
		$data['Notification']['title'] = 'You have gained $'.$percentage.' for a fight with one of your boxers';
		$data['Notification']['text'] = '<p>You have gained $'.$percentage.' for a fight with one of your boxers</p>
		<p>The fight was between ' .$fight['Fighter1']['Forname']['name'] . ' '.$fight['Fighter1']['Surname']['name'].' and '.$fight['Fighter2']['Forname']['name']. ' ' .$fight['Fighter2']['Surname']['name'].' </p>
		<p>The venue was a '. $fight['Venue']['title'].'</p>
		<p>The fee for the fight was $' .$fee. '</p>
		<p>After your fighters cut you earned a total of $'.$percentage.'</p>';
		$this->create();
		$this->save($data);
	}
	
	public function fightResult($winnerName, $loserName, $manager_id, $overview, $winner_id, $loser_id, $result){
		//$week = 60*60*24*7;
		//$temp = strtotime($game_time);
		//$temp2 = $temp + $week;
		//$game_time = date('Y-m-d', $temp2);
		$data['Notification']['recipient_id'] = $manager_id;
		$data['Notification']['type'] = 3;
		$data['Notification']['response'] = 1;
		//$data['Notification']['game_date'] = $game_time;
        if($result == 'draw'){
            $data['Notification']['title'] = '<a class = "gold" href = "/boxers/view/'.$winner_id.'">'.$winnerName.'</a> drew with <a class = "gold" href = "/boxers/view/'.$loser_id.'">'.$loserName.'</a>';
            $data['Notification']['text'] = '<p>'.$winnerName.' drew with '.$loserName.'</p>
            <p>'.$overview.'</p>';
		}else{
            $data['Notification']['title'] = '<a class = "gold" href = "/boxers/view/'.$winner_id.'">'.$winnerName.'</a> defeats <a class = "gold" href = "/boxers/view/'.$loser_id.'">'.$loserName.'</a>';
            $data['Notification']['text'] = '<p>'.$winnerName.' defeated '.$loserName.'</p>
            <p>'.$overview.'</p>';
		}
		$this->create();
		$this->save($data);
	}
	
	public function whiners($boxer, $status){
		$data['Notification']['recipient_id'] = $boxer['Boxer']['manager_id'];
		$data['Notification']['type'] = 2;
		$data['Notification']['response'] = 1;
		//$data['Notification']['game_date'] = $game_time;
		if($status == 'leave'){
			$data['Notification']['title'] = '<a class = "gold" href = "/boxers/view/'.$boxer['Boxer']['id'].'">'.$boxer['Forname']['name'].' '.$boxer['Surname']['name'].'</a> has cancelled your contract';
			$data['Notification']['text'] = '<p>He felt despite repeated warnings and poor fights results</p>
			<p>its time for him to move on and try a manager that can help invigorate his career again</p>
			<p>From this point on, he will be a free agent.</p>';
		}elseif($status == 'awful'){
			$data['Notification']['title'] = '<a class = "gold" href = "/boxers/view/'.$boxer['Boxer']['id'].'">'.$boxer['Forname']['name'].' '.$boxer['Surname']['name'].'</a> is considering cancelling your contract';
			$data['Notification']['text'] = '<p>He feels you are mismanaging him</p>
			<p>He went on to say that if he doesn\'t get some fights he can win to get his career on track again</p>
			<p>he will look for a manager that can</p>';
		}elseif($status == 'bad'){
			$data['Notification']['title'] = '<a class = "gold" href = "/boxers/view/'.$boxer['Boxer']['id'].'">'.$boxer['Forname']['name'].' '.$boxer['Surname']['name'].'</a> is unhappy';
			$data['Notification']['text'] = '<p>He feels unhappy with where his career is currently heading</p>
			<p>it\'s likely he just needs a few easy fights he can win to give him some confidence back</p>
			<p>you may need to monitor the sitution or risk losing him</p>';
		}
		$this->create();
		$this->save($data);	
	}
	
	public function inactivityWarning($boxer){
		$data['Notification']['recipient_id'] = $boxer['Boxer']['manager_id'];
		$data['Notification']['type'] = 2;
		$data['Notification']['response'] = 1;
		//$data['Notification']['game_date'] = $game_time;
		$data['Notification']['title'] = '<a class = "gold" href = "/boxers/view/'.$boxer['Boxer']['id'].'">'.$boxer['Forname']['name']. ' ' .$boxer['Surname']['name'] . '</a> wants a fight';
		$data['Notification']['text'] = '<p>He feels he is ready for a fight and would like one arranged for him</p>
		<p>as soon as possible.</p>';
		$this->create();
		$this->save($data);
	}
	
	public function cantAffordTrainer($fullname, $manager_id, $game_time){
		$data['Notification']['recipient_id'] = $manager_id;
		$data['Notification']['type'] = 2;
		$data['Notification']['response'] = 1;
		$data['Notification']['game_date'] = $game_time;
		$data['Notification']['title'] = $fullname . ' your trainer has left your employ';
		$data['Notification']['text'] = '<p>He has decided since you are behind in his salary payments</p>
		<p>that it was time for him to move on and get a more stable employer</p>';
		$this->create();
		$this->save($data);
	}
	
	//cleanup notifications
	public function cleanUp(){
		//removing notifications if recipient id is null
		$notifications = $this->find('all', array('fields' => array('id', 'recipient_id'), 'recursive' => -1, 'conditions' => array('recipient_id' => NULL)));
		foreach($notifications as $notification){
			$this->id = $notification['Notification']['id'];
			$this->delete($notification['Notification']['id']);
		}
		$week = 60*60*24*7;
		$dateSeconds = time();
		$old = $dateSeconds - $week;
		$oldDate = date('Y-m-d', $old);
		//Removing notifications that are a week old in real time.
		$notifications = $this->find('all', array('fields' => array('id', 'created'), 'recursive' => -1, 'conditions' => array('created <=' => $oldDate)));
		foreach($notifications as $notification){
			$this->id = $notification['Notification']['id'];
			$this->delete($notification['Notification']['id']);
		}
	}
	
	public function renegotiationWarning($boxer){
		$data['Notification']['recipient_id'] = $boxer['Boxer']['manager_id'];
		$data['Notification']['type'] = 2;
		$data['Notification']['response'] = 1;
		//$data['Notification']['game_date'] = $game_time;
		$data['Notification']['title'] = '<a class = "gold" href = "/boxers/view/'.$boxer['Boxer']['id'].'">'.$boxer['Forname']['name']. ' ' .$boxer['Surname']['name'] . '</a> wants an improved contract';
		$data['Notification']['text'] = '<p>He is unhappy and feels his current contract does not reflect his current status and wants an improved offer</p>
		<p><a class = "gold" href = "/contracts/renegotiation/'.$boxer['Boxer']['id'].'">Click here to begin renegotiation</a></p>';
		$this->create();
		$this->save($data);
	}
	
	public function renegotiationSuccess($contract, $game_time){
		$data['Notification']['recipient_id'] = $contract['Contract']['manager_id'];
		$data['Notification']['type'] = 2;
		$data['Notification']['response'] = 1;
		$data['Notification']['game_date'] = $game_time;
		$data['Notification']['title'] = '<a class = "gold" href = "/boxers/view/'.$contract['Contract']['boxer_id'].'">'.$contract['Boxer']['Forname']['name']. ' ' .$contract['Boxer']['Surname']['name']. '</a> has accepted the renegotiated contract';
		$data['Notification']['text'] = '<p>He is happy with the improved contract and has signed</p>';
		$this->create();
		$this->save($data);
	}
	
	public function renegotiationReject($contract, $game_time){
		$data['Notification']['recipient_id'] = $contract['Contract']['manager_id'];
		$data['Notification']['type'] = 2;
		$data['Notification']['response'] = 1;
		$data['Notification']['game_date'] = $game_time;
		$data['Notification']['title'] = '<a class = "gold" href = "/boxers/view/'.$contract['Contract']['boxer_id'].'">'.$contract['Boxer']['Forname']['name']. ' ' .$contract['Boxer']['Surname']['name']. '</a> has rejected the renegotiated contract';
		$data['Notification']['text'] = '<p>He believes the current and new contract does not reflect his true worth</p>
		<p>and is still waiting for a worthy offer</p>';
		$this->create();
		$this->save($data);
	}
	
	public function renegotiationSuccessTrainer($contract, $game_time){
		$data['Notification']['recipient_id'] = $contract['Contract']['manager_id'];
		$data['Notification']['type'] = 2;
		$data['Notification']['response'] = 1;
		$data['Notification']['game_date'] = $game_time;
		$data['Notification']['title'] = '<a class = "gold" href = "/trainers/view/'.$contract['Contract']['trainer_id'].'">'.$contract['Trainer']['Forname']['name']. ' ' .$contract['Trainer']['Surname']['name']. '</a> has accepted the renegotiated contract';
		$data['Notification']['text'] = '<p>He is happy with the improved contract and has signed</p>';
		$this->create();
		$this->save($data);
	}
	
	public function renegotiationRejectTrainer($contract, $game_time){
		$data['Notification']['recipient_id'] = $contract['Contract']['manager_id'];
		$data['Notification']['type'] = 2;
		$data['Notification']['response'] = 1;
		$data['Notification']['game_date'] = $game_time;
		$data['Notification']['title'] = '<a class = "gold" href = "/trainers/view/'.$contract['Contract']['trainer_id'].'">'.$contract['Trainer']['Forname']['name']. ' ' .$contract['Trainer']['Surname']['name']. '</a> has rejected the renegotiated contract';
		$data['Notification']['text'] = '<p>He believes the current and new contract does not reflect his true worth</p>
		<p>and is still waiting for a worthy offer</p>';
		$this->create();
		$this->save($data);
	}
	
	public function newChampion($fight, $overview, $winnerName, $loserName, $weight_type, $manager_id, $winner_id, $loser_id){
		$data['Notification']['recipient_id'] = $manager_id;
		$data['Notification']['type'] = 3;
		$data['Notification']['response'] = 1;
		//$data['Notification']['game_date'] = $game_time;
		$data['Notification']['title'] = '<a class = "gold" href = "/boxers/view/'.$winner_id.'">'.$winnerName.'</a> defeats <a class = "gold" href = "/boxers/view/'.$loser_id.'">'.$loserName.'</a> to become the new '.$weight_type.' champion!</a>';
		$data['Notification']['text'] = '<p>Last week '.$winnerName.' defeated '.$loserName.' to become the new world '.$weight_type.' champion!</p>
		<p>'.$overview.'</p>';
		$this->create();
		$this->save($data);	
	}
	
	public function trainerStealAttempt($contract){
		$data['Notification']['recipient_id'] = $contract['Contract']['manager_id'];
		$data['Notification']['type'] = 2;
		$data['Notification']['response'] = 1;
		//$data['Notification']['game_date'] = $game_time;
		$data['Notification']['title'] = 'Another manager is trying steal your trainer <a class = "gold" href = "/trainers/view/'.$contract['Contract']['trainer_id'].'">'.$contract['Trainer']['Forname']['name'].' '.$contract['Trainer']['Surname']['name'].'</a>';
		$data['Notification']['text'] = '<p>Another manager is trying steal your trainer <a class = "gold" href = "/trainers/view/'.$contract['Contract']['trainer_id'].'">'.$contract['Trainer']['Forname']['name'].' '.$contract['Trainer']['Surname']['name'].'</a></p>
		<p>We do not know if the offer is better than what he is currently paid by yourself</p>
		<p>However if you would like to improve his current contract click <a class = "gold" href = "/contracts/renegotiationTrainer/'.$contract['Contract']['trainer_id'].'">here</a></p>';
		$this->create();
		$this->save($data);	
	}
	
	public function lostTrainer($contract, $manager_id){
		$data['Notification']['recipient_id'] = $manager_id;
		$data['Notification']['type'] = 2;
		$data['Notification']['response'] = 1;
		//$data['Notification']['game_date'] = $game_time;
		$data['Notification']['title'] = 'Your trainer <a class = "gold" href = "/trainers/view/'.$contract['Contract']['trainer_id'].'">'.$contract['Trainer']['Forname']['name'].' '.$contract['Trainer']['Surname']['name'].'</a> has left your employ';
		$data['Notification']['text'] = '<p>Your trainer <a class = "gold" href = "/trainers/view/'.$contract['Contract']['trainer_id'].'">'.$contract['Trainer']['Forname']['name'].' '.$contract['Trainer']['Surname']['name'].'</a> has left your employ</p>
		<p>Another manager offered you trainer a better contract and he decided to leave.</p>
		<p>All is not lost! You could always try to steal him back <a class = "gold" href = "/contracts/trainerSteal/'.$contract['Contract']['trainer_id'].'">'.$contract['Trainer']['Forname']['name'].' '.$contract['Trainer']['Surname']['name'].'</a></p>';
		$this->create();
		$this->save($data);	
	}
	
	public function keepTrainer($contract, $manager_id){
		$data['Notification']['recipient_id'] = $manager_id;
		$data['Notification']['type'] = 2;
		$data['Notification']['response'] = 1;
		//$data['Notification']['game_date'] = $game_time;
		$data['Notification']['title'] = 'Your trainer <a class = "gold" href = "/trainers/view/'.$contract['Contract']['trainer_id'].'">'.$contract['Trainer']['Forname']['name'].' '.$contract['Trainer']['Surname']['name'].'</a> has decided to stay';
		$data['Notification']['text'] = '<p>Your trainer <a class = "gold" href = "/trainers/view/'.$contract['Contract']['trainer_id'].'">'.$contract['Trainer']['Forname']['name'].' '.$contract['Trainer']['Surname']['name'].'</a> has decided to stay</p>
		<p>Another manager offered your trainer a contract offer, however the contract was rejected!</p>';
		$this->create();
		$this->save($data);	
	}
	
	//send notification to maanager about forced mandatory fight for champion
	public function champMandatory($champ, $challenger){
		$data['Notification']['recipient_id'] = $champ['Boxer']['manager_id'];
		$data['Notification']['type'] = 1;
		$data['Notification']['response'] = 1;
		//$data['Notification']['game_date'] = $game_time;
		$data['Notification']['title'] = 'The Boxing Game Championship has selected a mandatory challenger for <a class = "gold" href = "/boxers/view/'.$champ['Boxer']['id'].'">'.$champ['Forname']['name'].' '.$champ['Surname']['name'] .'</a>';
		$data['Notification']['text'] = '<p>The Commission feels to keep the integrity of the championship you must defeat legitimate challengers</p>
		<p>The Commission has selected <a class = "gold" href = "/boxers/view/'.$challenger['Boxer']['id'].'">'.$challenger['Forname']['name'].' '.$challenger['Surname']['name'] .'</a> as the Champs opponent</p>
		<p>May the best man win!</p>';
		$this->create();
		$this->save($data);	
	}
	
	//send notification to manager about forced mandatory fight for challenger
	public function challenegerMandatory($champ, $challenger){
		$data['Notification']['recipient_id'] = $challenger['Boxer']['manager_id'];
		$data['Notification']['type'] = 1;
		$data['Notification']['response'] = 1;
		//$data['Notification']['game_date'] = $game_time;
		$data['Notification']['title'] = 'The Boxing Game Championship has selected your boxer <a class = "gold" href = "/boxers/view/'.$challenger['Boxer']['id'].'">'.$challenger['Forname']['name'].' '.$challenger['Surname']['name'] .'</a> as a title challenger';
		$data['Notification']['text'] = '<p>The Commission feels your fighter is the legitimate contender for the championship held by <a class = "gold" href = "/boxers/view/'.$champ['Boxer']['id'].'">'.$champ['Forname']['name'].' '.$champ['Surname']['name'] .'</a></p>
		<p>The commission will pay you a fee of $150,000</p>
		<p>May the best man win!</p>';
		$this->create();
		$this->save($data);	
	}
	
	//when pvp offer does not respond
	public function noResponsePvp($fight, $refund) {
		$refund = number_format($refund, 0, '.', ',');
		$data['Notification']['recipient_id'] = $fight['Fight']['manager_id'];
		$data['Notification']['type'] = 1;
		$data['Notification']['response'] = 1;
		//$data['Notification']['game_date'] = $game_time;
		$data['Notification']['title'] = 'No response from the other manager for the fight between <a class = "gold" href = "/boxers/view/'.$fight['Fighter1']['id'].'">'.$fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].'</a> and <a class = "gold" href = "/boxers/view/'.$fight['Fighter2']['id'].'">'.$fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].'</a>';
		$data['Notification']['text'] = '<p>No response from the other manager for the fight ACCEPTED between '.$fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].' and '.$fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].'</p>
			<p>This means the fight cannot take place</p>
			<p>You have been refunded $'.$refund.' as a result</p>';
		$this->create();
		$this->save($data);
	}
}
