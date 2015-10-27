<h1 class = 'manager-h1 gold light'>Manager - <?php echo $manager['User']['username']?></h1>
<?php
	$manager['Manager']['balance'] = number_format($manager['Manager']['balance'], 0, '.', ',');
?>

<div class = 'managers-index'>
	<div class = 'managers-header'>
        <div class = 'feed-items-wider left'>Name</div>
        <div class = 'belts-items left'>Balance</div>
        <div class = 'feed-items-200 left'><abbr class = 'tag-tip' title = 'This is the number of weeks the manager has had champions.' rel = 'tooltip' data-placement = 'right' data-html = 'true'>Champion Points *</abbr></div>
        <div class = 'feed-items-200 left'>Current Belts Held</div>
        <div class = 'feed-items-200 left'>Created</div>
	</div>
   
    <div class = 'belts-each-item color1'>
        <div class = 'feed-items-wider left'><a class = 'gold' href = '/managers/view/<?php echo $manager['Manager']['id']?>'><?php echo $manager['User']['username']?></a></div>
        <div class = 'belts-items left'><?php echo '$'.$manager['Manager']['balance']?></div>
        <div class = 'feed-items-200 left'><?php echo $manager['Manager']['career_belts_total']?></div>
        <div class = 'feed-items-200 left'><?php echo $manager['Manager']['belts_held']?></div>
        <div class = 'feed-items-200 left'><?php echo date('M jS Y', strtotime($manager['Manager']['created']))?></div>
    </div>
    <div class = 'managers-footer'></div>
</div>

<h1 class = 'manager-h1 gold light left w325'><?php echo $manager['User']['username'].'\'s Boxers'?></h1>
<h1 class = 'manager-h1 gold light left w325'><?php echo $manager['User']['username'].'\'s Trainers'?></h1>
<h1 class = 'manager-h1 gold light left w325'><?php echo $manager['User']['username'].'\'s Items'?></h1>


<div class = 'three-panel'>
	<div class = 'three-panel-header'>
    	<div class = 'three-panel-150 left'>Name</div>
        <div class = 'three-panel-100 left'>Weight</div>
        <div class = 'three-panel-50 left'>Rank</div>
    </div>
    
    <?php $count = 0;?>
    <?php foreach($manager['Boxer'] as $boxer){?>
    	<?php if (($count % 2) == 1){?>
    			 <div class = 'three-panel-content color1'>
    	<?php }else{?>
        		 <div class = 'three-panel-content color2'>
        <?php }?>
        <?php if ($boxer['rank'] == 0){$boxer['rank'] = 'Undisputed Champion';}elseif($boxer['rank'] == 1){$boxer['rank'] = 'Champ';}?>
       		<div class = 'three-panel-150 left'><a class = 'gold' href = '/boxers/view/<?php echo $boxer['id']?>'><?php echo $boxer['Forname']['name'].' '.$boxer['Surname']['name']?></a></div>
            <div class = 'three-panel-100 left'><?php echo $weights[$boxer['weight_type']]?></div>
            <div class = 'three-panel-50 left'><?php echo $boxer['rank']?></div>
        
        <?php $count++;?>
        </div>
    <?php } ?>

	<div class = 'three-panel-footer'></div>
</div>


<div class = 'three-panel'>
	<div class = 'three-panel-header'>
    	<div class = 'three-panel-100 left'>Name</div>
        <div class = 'three-panel-100 left'>Scouting</div>
        <div class = 'three-panel-100 left'>Rating</div>
    </div>
    
    <?php $count = 0;?>
    <?php foreach($manager['Trainer'] as $trainer){?>
    	<?php if (($count % 2) == 1){?>
    			 <div class = 'three-panel-content color1'>
    	<?php }else{?>
        		 <div class = 'three-panel-content color2'>
        <?php }?>
       		<div class = 'three-panel-100 left'><a class = 'gold' href = '/trainers/view/<?php echo $trainer['id']?>'><?php echo $trainer['Forname']['name'].' '.$trainer['Surname']['name']?></a></div>
            <div class = 'three-panel-100 left'>
                <?php $offset = round($trainer['scout'] / 10);?>
                <div class = "star2-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
            </div>
            <div class = 'three-panel-100 left'>
                <div class = "stars-<?php echo $trainer['overall']?>"></div>
            </div>
        
        <?php $count++;?>
        </div>
    <?php } ?>

	<div class = 'three-panel-footer'></div>
</div>

<div class = 'three-panel'>
	<div class = 'three-panel-header'>
    	<div class = 'three-panel-150 left'>Name</div>
        <div class = 'three-panel-150 left'>Price</div>
    </div>
    
    <?php $count = 0;?>
    <?php foreach($manager['ManagerItem'] as $item){?>
    	<?php
			$item['Item']['price'] = number_format($item['Item']['price'], 0, '.', ',');
		?>
    	<?php if (($count % 2) == 1){?>
    			 <div class = 'three-panel-content color1'>
    	<?php }else{?>
        		 <div class = 'three-panel-content color2'>
        <?php }?>
       		<div class = 'three-panel-150 left'><?php echo $item['Item']['title']?></div>
            <div class = 'three-panel-150 left'><?php echo '$'.$item['Item']['price']?></div>
        
        <?php $count++;?>
        </div>
    <?php } ?>

	<div class = 'three-panel-footer'></div>
</div>