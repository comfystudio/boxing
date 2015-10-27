<h1 class = 'manager-h1 gold light'>Your trainers!</h1>

<div class = 'managers-index'>
	<div class = 'managers-header'>
    	<div class = 'three-panel-150 left underline'><?php echo $this->Paginator->sort('Forname.name', 'Name');?></div>
        <div class = 'three-panel-50 left underline'><?php echo $this->Paginator->sort('scout', 'Scout');?></div>
        <div class = 'three-panel-50 left underline'><?php echo $this->Paginator->sort('tech', 'Tech');?></div>
        <div class = 'three-panel-50 left underline'><?php echo $this->Paginator->sort('power', 'Pwr');?></div>
        <div class = 'three-panel-50 left underline'><?php echo $this->Paginator->sort('hand_speed', 'HSpd');?></div>
        <div class = 'three-panel-50 left underline'><?php echo $this->Paginator->sort('foot_speed', 'FSpd');?></div>
        <div class = 'three-panel-50 left underline'><?php echo $this->Paginator->sort('block', 'Bck');?></div>
        <div class = 'three-panel-50 left underline'><?php echo $this->Paginator->sort('defence', 'Def');?></div>
        <div class = 'three-panel-50 left underline'><?php echo $this->Paginator->sort('chin', 'chin');?></div>
        <div class = 'three-panel-50 left underline'><?php echo $this->Paginator->sort('heart', 'hrt');?></div>
        <div class = 'three-panel-50 left underline'><?php echo $this->Paginator->sort('cut', 'cut');?></div>
        <div class = 'three-panel-50 left underline'><?php echo $this->Paginator->sort('endurance', 'end');?></div>
        <div class = 'feed-items left underline'><?php echo $this->Paginator->sort('overall', 'Overall');?></div>
        <div class = 'feed-items left'>Actions</div>
    </div>
    <?php $count = 1;?>
    <?php foreach($trainers as $trainer){?>
    	<?php if (($count % 2) == 1){?>
    			<div class = 'belts-each-item color1'>
    	<?php }else{?>
        		<div class = 'belts-each-item color2'>
        <?php }?>
                <div class = 'three-panel-150 left'><a class = 'gold' href = '/trainers/view/<?php echo $trainer['Trainer']['id'] ?>'><?php echo $trainer['Forname']['name'].' '.$trainer['Surname']['name']?></a></div>
                <div class = 'three-panel-50 left'>
                    <?php $offset = round($trainer['Trainer']['scout'] / 10);?>
                    <div class = "stars3-small-<?php echo $offset?>"></div>
                </div>
                <div class = 'three-panel-50 left'>
                    <?php $offset = round($trainer['Trainer']['tech'] / 10);?>
                    <div class = "stars3-small-<?php echo $offset?>"></div>
                </div>
                <div class = 'three-panel-50 left'>
                    <?php $offset = round($trainer['Trainer']['power'] / 10);?>
                    <div class = "stars3-small-<?php echo $offset?>"></div>
                </div>
                <div class = 'three-panel-50 left'>
                    <?php $offset = round($trainer['Trainer']['hand_speed'] / 10);?>
                    <div class = "stars3-small-<?php echo $offset?>"></div>
                </div>
                <div class = 'three-panel-50 left'>
                    <?php $offset = round($trainer['Trainer']['foot_speed'] / 10);?>
                    <div class = "stars3-small-<?php echo $offset?>"></div>
                </div>
                <div class = 'three-panel-50 left'>
                    <?php $offset = round($trainer['Trainer']['block'] / 10);?>
                    <div class = "stars3-small-<?php echo $offset?>"></div>
                </div>
                <div class = 'three-panel-50 left'>
                    <?php $offset = round($trainer['Trainer']['defence'] / 10);?>
                    <div class = "stars3-small-<?php echo $offset?>"></div>
                </div>
                <div class = 'three-panel-50 left'>
                    <?php $offset = round($trainer['Trainer']['chin'] / 10);?>
                    <div class = "stars3-small-<?php echo $offset?>"></div>
                </div>
                <div class = 'three-panel-50 left'>
                    <?php $offset = round($trainer['Trainer']['heart'] / 10);?>
                    <div class = "stars3-small-<?php echo $offset?>"></div>
                </div>
                <div class = 'three-panel-50 left'>
                    <?php $offset = round($trainer['Trainer']['cut'] / 10);?>
                    <div class = "stars3-small-<?php echo $offset?>"></div>
                </div>
                <div class = 'three-panel-50 left'>
                    <?php $offset = round($trainer['Trainer']['endurance'] / 10);?>
                    <div class = "stars3-small-<?php echo $offset?>"></div>
                </div>
                <div class = 'feed-items left'>
                    <div class = "stars-<?php echo $trainer['Trainer']['overall']?>"></div>
                </div>
                <div class = 'feed-items left'>
<!--                	--><?php //echo '<a onclick="return confirm(\'are you sure?\');" class = "gold" href = "/contracts/release/'.$trainer['Contract']['id'].'">Cancel Contract</a>';?>
						
					<?php echo '<p><a class = "gold" href = "/contracts/view/'.$trainer['Contract']['id'].'">View Contract</a></p>'; ?>

                    <?php /*echo '<p><a class = "gold" href = "/trainers/retrain/'.$trainer['Trainer']['id'].'">Retrain</a></p>'; */?>
                    
<!--                    --><?php //echo '<p><a class = "gold" href = "/contracts/renegotiationTrainer/'.$trainer['Trainer']['id'].'">Renegot-Contract</a></p>'; ?>
                </div>
    </div>
     <?php $count++;?>        
    <?php }?>
    
    <div class = 'three-panel-footer'></div>
</div>
