<?php
	if(empty($manager)){
?>
<div class = 'login'>
	<h1 class = 'gold'>No Manager!?</h1>
    <div class = 'login-form centre'>
    	<p>It appears you havent created a manager!</p><br/>
        <p>How can you manage without a manager!?</p><br/>
        <p>Create one now and soon you'll be the richest most successful manager ever!</p><br/>
    
		<?php
            echo $this->Form->create('Manager', array('action' => 'create'));
                echo $this->Form->hidden('user_id', array('value' => $this->Session->read('User.id')));
                echo $this->Form->submit('create.png', array('class' => 'centre'));
            echo $this->Form->end();
         ?>
     </div>
</div>


<?php }else{ ?>

<h1 class = 'manager-h1 gold light'>Inbox: <?php echo count($manager['Notification']);?> messages</h1>
<div class = 'managers-index'>
	<div class = 'managers-header'>
    	<div class="feed-items-wide left">Headline</div>
        <div class="feed-items left">Date</div>
        <div class="feed-items left">Type</div>
	</div>
    
    <?php $count = 1;?>
    <div class = "vertical-scroll height-350 width-987">
    <?php foreach ($manager['Notification'] as $notification){?>
    	<?php if ($count == 1){?>
    			<div class = 'notification-header belts-each-item color3' id = 'notification-header-<?php echo $notification['id']?>'>
    	<?php }else if (($count % 2) == 1){?>
        		<div class = 'notification-header belts-each-item color1' id = 'notification-header-<?php echo $notification['id']?>'>
        <?php }else {?>
        		<div class = 'notification-header belts-each-item color2' id = 'notification-header-<?php echo $notification['id']?>'>
        <?php } ?>
        			<?php $gameDateFormatted = strtotime($notification['game_date'])?>
        			<?php if($notification['viewed'] == 0){?>
          				<div class="feed-items-wide red left"><?php echo $notification['title']?></div>
                        <div class="feed-items red left"><?php echo date('M jS Y', strtotime($notification['created']))?></div>
                        <div class="feed-items red left"><?php echo $types[$notification['type']]?></div>
                    <?php }else{?>
                   		<div class="feed-items-wide left"><?php echo $notification['title']?></div>
                        <div class="feed-items left"><?php echo date('M jS Y', strtotime($notification['created']))?></div>
                        <div class="feed-items left"><?php echo $types[$notification['type']]?></div>
                    <?php }?>
                    
                    <?php $count++;?>      
    			</div>
    <?php } ?>
    </div>
        <div class = 'managers-footer'></div>
    </div>
    
    <div class = 'managers-info left'>
    	 <?php $count = 0;?>
		 <?php foreach ($manager['Notification'] as $notification){?>
            <?php if($count == 0){?>
                <div class = 'manager-notifcation' style="display:block" id = 'notification-info-<?php echo $notification['id']?>'>
            <?php } else { ?>
                <div class = 'manager-notifcation' style="display:none" id = 'notification-info-<?php echo $notification['id']?>'>
            <?php } ?>
            <h1 class = 'manager-h1 gold light'><?php echo $notification['title']?></h1>
            <h2 class = 'light'>
                <?php echo $notification['text'];?>
            </h2>
            <?php if( $notification['type'] == 1 &&  $notification['response'] == 0) {?>
                <?php
                
                    echo $this->Form->input('message', array('label' => false, 'type' => 'textarea'));
                    
                    echo $this->Form->create('Fight', array('action' => 'accept_fight', 'class' => 'left'));
                        echo $this->Form->hidden('fight_id', array('value' => $notification['fight_id']));
                        echo $this->Form->hidden('notification_id', array('value' => $notification['id']));
                        echo $this->Form->hidden('manager_id', array('value' => $this->Session->read('User.manager_id')));
                        echo $this->Form->hidden('message', array('label' => false, 'type' => 'textarea'));
                        echo $this->Form->submit('accept.png', array('class' => 'left'));
                    echo $this->Form->end();
                 ?>
                 <?php
                    echo $this->Form->create('Fight', array('action' => 'reject_fight', 'class' => 'left'));
                        echo $this->Form->hidden('fight_id', array('value' => $notification['fight_id']));
                        echo $this->Form->hidden('notification_id', array('value' => $notification['id']));
                        echo $this->Form->hidden('manager_id', array('value' => $this->Session->read('User.manager_id')));
                        echo $this->Form->hidden('message', array('label' => false, 'type' => 'textarea'));
                        echo $this->Form->submit('reject.png', array('class' => 'left'));
                    echo $this->Form->end();
                 ?>
            <?php  } ?>
		</div>
        	 <?php $count++;?>
        <?php } ?>
    </div>
    
    <div class = 'managers-quick right'>
        <p class = 'left'>Balance:</p> <p class = 'right'>
			<?php 
			$balance = number_format($manager['Manager']['balance'], 0, '.', ',');
			if($manager['Manager']['balance'] >= 0){
				echo '$'.$balance;
			}else{
				echo '<span class = "red" >$'.$balance.'</span>';
			}
			?>
        </p>
        <p class = 'left'>Champions:</p> <p class = 'right'><?php echo count($manager['Boxer'])?></p>
        
        <div class = 'next-fights'>
            
            <?php /*$count = 1;?>
			<?php foreach ($fights['Boxer'] as $boxer) {?>
                <?php foreach ($boxer['Fight'] as $fight ) {?>
                    <?php if ($fight['game_time'] == $game_time) {?>
                    	<?php if($count == 1){?>
                        	<h3 class = 'gold light'>Live Fights!</h3>
                        <?php }?>
                    	<?php $mod = ($count % 2) + 1;?>
                    	<div class = 'next-fights-p-wrapper-bg1 color<?php echo $mod?>'>
                        	<a href = '/fights/live_fight/<?php echo $fight['id']?>'><p class = 'small left'><?php echo $fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name']?></p><span class = 'three-panel-50 centre left'>vs</span><p class = 'right'><?php echo $fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name']?></p></a>
                    	</div>
                    <?php $count++;?>
					<?php } ?>
                <?php } ?>
            <?php } ?>
        </div>
        
        <?php 
			for($i = 1; $i <= 8; $i++){
				$week = (60 * 60 * 24 * 7);
				$game_time = strtotime($game_time);
				$game_time = $game_time + $week;
				$game_time = date('Y-m-d', $game_time);	
				
				$count = 1;
				if($i == 1){
					$weekString = 'week';	
				}else{
					$weekString = 'weeks';
				}
        		foreach ($fights['Boxer'] as $boxer) {
            		foreach ($boxer['Fight'] as $fight ) {
                		if ($fight['game_time'] == $game_time) {
                    		if($count == 1){?>
                        		<div class = 'next-fights <?php echo $i?>'>
                            		<h3 class = 'gold light'>Upcoming fights in <?php echo $i.' '.$weekString?></h3>
                   		<?php }
                    				$mod = ($count % 2) + 1;?>
                        			<div class = 'next-fights-p-wrapper-bg1 color<?php echo $mod?>'>
                           				<a href = '/fights/live_fight/<?php echo $fight['id']?>'><p class = 'small left'><?php echo $fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name']?></p><span class = 'three-panel-50 centre left'>vs</span><p class = 'right'><?php echo $fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name']?></p></a> 
                        			</div>
                		<?php if($count == 1){
                            echo '</div>';
            			} ?>
                        <?php $count++;?>	
                      
   				  <?php } 
							
                	}
       			 } 
			}*/
       		echo '</div>';
	}?>