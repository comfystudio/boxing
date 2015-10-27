<?php
App::uses('AppModel', 'Model');
//App::uses('Security', 'Utility'); 
//App::import('Component','Auth'); 
/**
 * User Model
 *
 * @property Item $Item
 */
 
class Name extends AppModel {	

	/*public $hasMany = array(
		'Boxer',
		'Trainer'	
	);*/
	
	public $belongsTo = array(
		'Boxer',
		'Trainer'
	);
    public function getNames() {
        $data = Cache::read('names', 'default');
        if(!$data){
            $euFirst = $this->find('all', array('recursive' => -1, 'fields' => array('id'), 'conditions' => array('type' => 0, 'region' => 'Europe')));
            $euSecond = $this->find('all', array('recursive' => -1, 'fields' => array('id'), 'conditions' => array('type' => 1, 'region' => 'Europe')));

            $saFirst = $this->find('all', array('recursive' => -1, 'fields' => array('id'), 'conditions' => array('type' => 0, 'region' => 'South American')));
            $saSecond = $this->find('all', array('recursive' => -1, 'fields' => array('id'), 'conditions' => array('type' => 1, 'region' => 'South American')));

            $meFirst = $this->find('all', array('recursive' => -1, 'fields' => array('id'), 'conditions' => array('type' => 0, 'region' => 'Middle Eastern')));
            $meSecond = $this->find('all', array('recursive' => -1, 'fields' => array('id'), 'conditions' => array('type' => 1, 'region' => 'Middle Eastern')));

            $afFirst = $this->find('all', array('recursive' => -1, 'fields' => array('id'), 'conditions' => array('type' => 0, 'region' => 'African')));
            $afSecond = $this->find('all', array('recursive' => -1, 'fields' => array('id'), 'conditions' => array('type' => 1, 'region' => 'African')));

            $asFirst = $this->find('all', array('recursive' => -1, 'fields' => array('id'), 'conditions' => array('type' => 0, 'region' => 'Asian')));
            $asSecond = $this->find('all', array('recursive' => -1, 'fields' => array('id'), 'conditions' => array('type' => 1, 'region' => 'Asian')));

            $data['euFirst'] = $euFirst;
            $data['euSecond'] = $euSecond;
            $data['saFirst'] = $saFirst;
            $data['saSecond'] = $saSecond;
            $data['meFirst'] = $meFirst;
            $data['meSecond'] = $meSecond;
            $data['afFirst'] = $afFirst;
            $data['afSecond'] = $afSecond;
            $data['asFirst'] = $asFirst;
            $data['asSecond'] = $asSecond;

            Cache::write('names', $data,'default');
        }
        return $data;
    }
}
