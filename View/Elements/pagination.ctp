<?php
	$params = $this->Paginator->params();
	if ($params['pageCount'] > 1){
		if ($params['page'] > 1){
			echo $this->Paginator->prev('« Previous', null, null, array('class' => 'disabled'));
		}
		echo '<span class = "pagination gold">'.$this->Paginator->counter(array('format' => 'Page %page% of %pages%')).'</span>';
		if ($params['page'] != $params['pageCount']){
			echo $this->Paginator->next('Next »', null, null, array('class' => 'disabled'));
		}
	};
?>