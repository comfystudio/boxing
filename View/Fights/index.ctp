<h1 class = 'manager-h1 gold light'>The most recent and epic fights!</h1>

<div class = 'managers-index'>
	<div class = 'managers-header'>
    	<div class = 'three-panel-150 left'>Fighter 1</div>
        <div class = 'three-panel-75 left'>VS</div>
        <div class = 'three-panel-150 left'>Fighter 2</div>
        <div class = 'three-panel-150 left'>Winner</div>
        <div class = 'feed-items-wider left'>Overview</div>
        <div class = 'three-panel-75 left'>Breakdown</div>
        <div class = 'feed-items left'>Date</div>
	</div>
    
    <?php $count = 0;?>
    <?php foreach($fights as $fight){?>
    	<?php if (($count % 2) == 1){?>
    			<div class = 'belts-each-item color1'>
    	<?php }else{?>
        		<div class = 'belts-each-item color2'>
        <?php }?>
        
					<?php //$username = $this->requestAction('users/getUsername/'.$feed['Manager']['user_id'])?>
                    <div class = 'three-panel-150 left'><a class = 'blue' href = '/boxers/view/<?php echo $fight['Fight']['fighter1_id']?>'><?php echo $fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name']?></a></div>
                    <div class = 'three-panel-75 left'>vs</div>
                    <div class = 'three-panel-150 left'><a class = 'red' href = '/boxers/view/<?php echo $fight['Fight']['fighter2_id']?>'><?php echo $fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name']?></a></div>
                    <?php if($fight['Fight']['winner_id'] == 0) { ?>
                    	<div class = 'three-panel-150 left'><a class = 'green'>Draw</a></div>
                    <?php  } else {?>
                    	<div class = 'three-panel-150 left'><a class = 'gold' href = '/boxers/view/<?php echo $fight['Fight']['winner_id']?>'><?php echo $fight['Winner']['Forname']['name'].' '.$fight['Winner']['Surname']['name']?></a></div>
                    <?php } ?>
                    <div class = 'feed-items-wider left'><?php echo $this->Text->truncate($fight['Fight']['overview'],38)?></div>
                    <div class = 'three-panel-75 left'><a class = 'gold' href = '/fights/view/<?php echo $fight['Fight']['id']?>'>View</a></div>
                    <div class = 'feed-items left'><?php echo date('M jS Y', strtotime($fight['Fight']['created']))?></div>
                    <?php $count++;?>
        	 </div>
    <?php }?>
   <div class = 'managers-footer'></div>
   <?php echo '<div class = "gold">'.$this->element('pagination').'</div>'; ?>
</div>