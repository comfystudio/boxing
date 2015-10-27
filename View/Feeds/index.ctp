<h1 class = 'manager-h1 gold light'>Witness the madness of the manager feeds!</h1>

<?php
	if(isset($user) && $user['User']['manager_id'] != null){
		echo '<div class = "belts-filter">';
		echo $this->Form->create('Feed', array('action' => 'index'));
			echo $this->Form->hidden('manager_id', array('value' => $this->Session->read('User.manager_id')));
			echo $this->Form->input('content', array('type' => 'textarea', 'label' => 'Content'));
			echo $this->Form->submit('post.png', array('class' => 'right'));
	  	echo $this->Form->end();
		echo '</div>';
	}
?>



<div class = 'managers-index'>
	<div class = 'managers-header'>
    	<div class = 'feed-items-wide-650 left'>Content</div>
        <div class = 'feed-items left'>Manager</div>
        <div class = 'feed-items-200 left'>Created</div>
	</div>
    
    <?php $count = 0;?>
    <?php foreach($feeds as $feed){?>
    	<?php if (($count % 2) == 1){?>
    			<div class = 'belts-each-item feed-index color1'>
    	<?php }else{?>
        		<div class = 'belts-each-item feed-index color2'>
        <?php }?>
        
					<?php $username = $this->requestAction('users/getUsername/'.$feed['Manager']['user_id'])?>
                    <?php $feed['Feed']['content'] = str_replace('\\', "", $feed['Feed']['content']);?>
                    <div class = 'feed-items-wide-650 left'><?php echo $feed['Feed']['content']?></div>
                    <div class = 'feed-items left'><a class = 'gold' href = '/managers/view/<?php echo $feed['Manager']['id']?>'><?php echo $username?></a></div>
                    <div class = 'feed-items-200 left'><?php echo date('M jS Y h:i A', strtotime($feed['Feed']['created']))?></div>
                    <?php $count++;?>
        	 </div>
    <?php }?>
   <div class = 'managers-footer'></div>
   <?php echo '<div class = "gold">'.$this->element('pagination').'</div>'; ?>
</div>