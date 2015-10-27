<h1 class = 'manager-h1 gold light'>All the boxers!</h1>


<div class = 'managers-index'>
	<div class = 'managers-header'>
    	<div class = 'three-panel-150 left underline'><?php echo $this->Paginator->sort('Forname.name', 'Name');?></div>
        <div class = 'three-panel-75 left underline'><?php echo $this->Paginator->sort('age', 'Age');?></div>
        <div class = 'three-panel-150 left underline'><?php echo $this->Paginator->sort('region', 'Region');?></div>
        <div class = 'three-panel-150 left underline'><?php echo $this->Paginator->sort('rank', 'Rank');?></div>
        <div class = 'three-panel-150 left underline'><?php echo $this->Paginator->sort('weight_type', 'Weight Class');?></div>
        <div class = 'three-panel-150 left underline'><?php echo $this->Paginator->sort('manager_id', 'Manager');?></div>
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
                    <div class = 'three-panel-75 left'><?php echo $boxer['Boxer']['age']?></div>
                    <div class = 'three-panel-150 left'><?php echo $regions[$boxer['Boxer']['region']]?></div>
                    <div class = 'three-panel-150 left'>
                  <?php if($boxer['Boxer']['rank'] == NULL){
					  			echo 'Unranked';
						} else if($boxer['Boxer']['rank'] == 0 ){
								echo 'Undisputed  Champion';
						}elseif($boxer['Boxer']['rank'] == 1){
								echo 'Champion';
						}else{
								echo $boxer['Boxer']['rank'];
						}
						?>
                    </div>
                    <div class = 'three-panel-150 left'><?php echo $weightClasses[$boxer['Boxer']['weight_type']]?></div>
                    <?php if($boxer['Boxer']['manager_id'] == null || empty($boxer['Boxer']['manager_id'])){?>
                    	<?php if($this->Session->read('User')){?>
                            	<div class = 'three-panel-150 left'><a class = 'gold' href = '/contracts/boxers/<?php echo $boxer['Boxer']['id']?>'><?php echo 'Offer contract'?></a></div>
                    		<?php }else{ ?>
                            	<div class = 'three-panel-150 left'>Unemployed</div>	
                          <?php } ?>
					<?php }else{?>
                            <div class = 'three-panel-150 left'><a class = 'gold' href = '/managers/view/<?php echo $boxer['Manager']['id']?>'><?php echo 'Employed by '.$boxer['Manager']['User']['username']?></a></div>
                    <?php }?>
        			<div class = 'three-panel-150 left'>
                    <?php 
						if($this->Session->read('User.manager_id')){
							if(false){
								echo '<div class = "red">Injured</div>';
							}elseif(!empty($boxer['Fight']) && isset($boxer['Fight'])){
								echo '<a class = "gold" href = "/fights/view/'.$boxer['Fight']['0']['id'].'">Upcoming fight</a>';	
							}else{
								echo '<a class = "gold" href = "/fights/arrange_fight/'.$boxer['Boxer']['id'].'">Arrange Fight</a>';	
							}
						}else{
							echo 'No actions avaliable';
						}
					?>
                    </div>	
         </div>
        <?php $count++;?>        
    <?php }?>
    <div class = 'managers-footer'></div>
    <?php echo '<div class = "gold">'.$this->element('pagination').'</div>'; ?>
</div>