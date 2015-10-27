<?php
App::uses('Helper', 'View');

/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class AppHelper extends Helper {
	/*var $helpers = array('Html');
	
	public function highlight($path, $normal = '', $selected = 'active') {
		$class = $normal;
		$currentPath = substr($this->Html->here, strlen($this->Html->base));
		pr($currentPath);die;
		if (preg_match($path, $currentPath)){
			$class .= " ".$selected;
		}
		return $class;
    }*/
	
	public function highlighter($path, $normal = '', $selected = 'active') {
		$class = $normal;
		$currentPath = substr($this->request->here, strlen($this->request->base));
		if (preg_match($path, $currentPath)) {
			$class .= ' ' . $selected;
		}
		return $class;
	}
}
