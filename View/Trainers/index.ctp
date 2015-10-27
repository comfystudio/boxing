<h1 class = 'manager-h1 gold light'>All the trainers!</h1>

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
        <div class = 'feed-items left underline'><?php echo $this->Paginator->sort('manager_id', 'Employment');?></div>
	</div>
    
     <?php $count = 1;?>
    <?php foreach($trainers as $trainer){?>
    	<?php if (($count % 2) == 1){?>
    			<div class = 'belts-each-item color1'>
    	<?php }else{?>
        		<div class = 'belts-each-item color2'>
        <?php }?>
        			<div class = 'three-panel-150 left'><a class = 'gold' href = '/trainers/view/<?php echo $trainer['Trainer']['id']?>'><?php echo $trainer['Forname']['name'].' '.$trainer['Surname']['name']?></a></div>
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
                    <?php if($trainer['Trainer']['manager_id'] == null || empty($trainer['Trainer']['manager_id'])){?>
                    	<?php if($this->Session->read('User')){?>
                            	<div class = 'feed-items left'><a class = 'gold' href = '/contracts/trainers/<?php echo $trainer['Trainer']['id']?>'><?php echo 'Offer contract'?></a></div>
                    		<?php }else{ ?>
                            	<div class = 'feed-items left'>Unemployed</div>	
                          <?php } ?>
					<?php }else{?>
                    	<?php if($this->Session->read('User') && $this->Session->read('User.manager_id') != $trainer['Trainer']['manager_id']){?>
                            <div class = 'feed-items left'><a class = 'gold' href = '/contracts/trainerSteal/<?php echo $trainer['Trainer']['id']?>'><?php echo 'Steal from '.$trainer['Manager']['User']['username']?></a></div>
                    	<?php } else {?>
                         	<div class = 'feed-items left'><a class = 'gold' href = '/managers/view/<?php echo $trainer['Trainer']['manager_id']?>'><?php echo 'Employed by '.$trainer['Manager']['User']['username']?></a></div>
                        <?php }?>
					<?php }?>
                       
                </div>
        <?php $count++;?>        
    <?php }?>
    <div class = 'managers-footer'></div>
    <?php echo '<div class = "gold">'.$this->element('pagination').'</div>'; ?>
</div>