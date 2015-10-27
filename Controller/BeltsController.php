<?php
App::uses('AppController', 'Controller');
App::uses('PaginatorHelper', 'View/Helper');
/**
 * Activities Controller
 *
 * @property Activity $Activity
 */
class BeltsController extends AppController {
	
	public function index($weight = null){
		$contain = array(
			'Boxer' => array(
				'fields' => array(
					'id',
					'age',
					'wins',
					'draws',
					'loses',
					'knockouts',
					'floored',
					'Forname_id',
					'Surname_id',
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
		$options = Configure::read('Weight.class');
		if($weight != null){
			$this->paginate = array('contain' => $contain, 'order' => 'position ASC', 'conditions' => array('Belt.weight_type' => $weight));
		}else{
			$this->paginate = array('contain' => $contain, 'order' => 'position ASC', 'conditions' => array('Belt.weight_type' => 16));
		}
		
		$this->set('belts', $this->paginate());
		$this->set(compact('belts', 'options'));
	}
}