<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Pages';

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();

/**
 * Displays a view
 *
 * @param mixed What page to display
 * @return void
 */
	public function display() {
		$this->loadModel('Fight');
		$this->loadModel('Manager');
		$path = func_get_args();

		$count = count($path);
		if (!$count) {
			$this->redirect('/');
		}
		$page = $subpage = $title_for_layout = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		
		$conditions = array(
			'Fight.winner_id <>' => 'null'
		);
		$order = 'Fight.game_time DESC';
		$fields = array(
			'fighter1_id', 
			'fighter2_id', 
			'winner_id', 
			'overview', 
			'game_time', 
			'Fight.id'
		);
		$contain = array(
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
		);
		$fights = $this->Fight->find('all', array('contain' => $contain, 'conditions' => $conditions, 'limit' => 20, 'order' => $order, 'fields' => $fields));
		
		$contain = array(
			'User' => array(
				'fields' => array(
					'username'
				)
			)
		);
		
		$managers = $this->Manager->find('all', array('contain' => $contain, 'order' => 'Manager.balance DESC', 'limit' => 20, 'fields' => array('User.username', 'Manager.balance', 'Manager.career_belts_total', 'Manager.belts_held', 'Manager.created', 'Manager.id')));
		
		$this->set(compact('page', 'subpage', 'title_for_layout', 'fights', 'managers'));
		$this->render(implode('/', $path));
	}
	
}
