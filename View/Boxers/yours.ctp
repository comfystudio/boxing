<h1 class = 'manager-h1 gold light'>Your boxers!</h1>

<div class = 'managers-index'>
	<div class = 'managers-header'>
    	<div class = 'three-panel-150 left underline'><?php echo $this->Paginator->sort('Forname.name', 'Name');?></div>
        <div class = 'three-panel-100 left underline'><?php echo $this->Paginator->sort('age', 'Age');?></div>
        <div class = 'three-panel-100 left underline'><?php echo $this->Paginator->sort('region', 'Region');?></div>
        <div class = 'three-panel-100 left underline'><?php echo $this->Paginator->sort('rank', 'Rank');?></div>
        <div class = 'three-panel-150 left underline'><?php echo $this->Paginator->sort('weight_type', 'Weight Class');?></div>
        <div class = 'three-panel-150 left underline'><?php echo $this->Paginator->sort('trainer_id', 'Trainer');?></div>
        <div class = 'three-panel-150 left'>Actions</div>
	</div>
    <?php $count = 1;?>
    <?php foreach($boxers as $boxer){?>
    	<?php if (($count % 2) == 1){?>
    			<div class = 'belts-each-item color1'>
    	<?php }else{?>
        		<div class = 'belts-each-item color2'>
        <?php }?>
					<div class = 'three-panel-150 left'><a class = 'gold' href = '/boxers/view/<?php echo $boxer['Boxer']['id']?>'><?php echo $boxer['Forname']['name'].' '.$boxer['Surname']['name']?></a></div>
                    <div class = 'three-panel-100 left'><?php echo $boxer['Boxer']['age']?></div>
                    <div class = 'three-panel-100 left'><?php echo $regions[$boxer['Boxer']['region']]?></div>
                    <div class = 'three-panel-100 left'>
                    	<?php if($boxer['Boxer']['rank'] == 0 ){
								echo 'Undisputed  Champion';
						}elseif($boxer['Boxer']['rank'] == 1){
								echo 'Champion';
						}else{
								echo $boxer['Boxer']['rank'];
						}
						?>
                    </div>
                    <div class = 'three-panel-150 left'><?php echo $weightClasses[$boxer['Boxer']['weight_type']]?></div>
                    <?php if($boxer['Boxer']['trainer_id'] == null || empty($boxer['Boxer']['trainer_id'])){?>
                    		<div class = 'three-panel-150 left'>No trainer</div>
					<?php }else{?>
                            <div class = 'three-panel-150 left'><a class = 'gold' href = '/trainers/view/<?php echo $boxer['Trainer']['id']?>'><?php echo $boxer['Trainer']['Forname']['name'].' '.$boxer['Trainer']['Surname']['name']?></a></div>
                    <?php }?>
        			<div class = 'three-panel-150 left'>
                    <?php 
						if($this->Session->read('User.manager_id')){
							if($boxer['Boxer']['injured'] == 1){
								echo '<div class = "red">Injured</div>';
							}elseif(!empty($boxer['Fight']) && isset($boxer['Fight'])){
								echo '<a class = "gold" href = "/fights/view/'.$boxer['Fight']['0']['id'].'">Upcoming fight</a>';	
							}else{
								echo '<a class = "gold" href = "/fights/arrange_fight/'.$boxer['Boxer']['id'].'">Arrange Fight</a>';	
							}
						}else{
							echo 'No actions avaliable';
						}
						
						echo '<p><a class = "gold" href = "/boxers/trainer/'.$boxer['Boxer']['id'].'">Change Trainer</a></p>';
						
						echo '<p><a class = "gold" href = "/contracts/view/'.$boxer['Contract']['id'].'">View Contract</a></p>';

                        echo '<p><a class = "gold" href = "/boxers/training_camp/'.$boxer['Boxer']['id'].'">Training Camp</a></p>';

					?>
                    </div>	
         </div>
        <?php $count++;?>        
    <?php }?>
    <div class = 'managers-footer'></div>
</div>