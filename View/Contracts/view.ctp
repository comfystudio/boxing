<h1 class = 'manager-h1 gold light'>View Contract!</h1>

<div class = 'managers-index'>
	<div class = 'managers-header'>
    	<div class = 'three-panel-175 left'>Manager</div>
        <?php if($contract['Contract']['boxer_id'] != null){?>
        	 <div class = 'three-panel-175 left'>Boxer</div>
        <?php }else{?>
        	 <div class = 'three-panel-175 left'>Trainer</div>
        <?php }?>
        <div class = 'three-panel-75 left'>Percentage</div>
        <!--<div class = 'three-panel-150 left'>Salary</div>-->
        <div class = 'three-panel-175 left'>Bonus</div>
        <div class = 'three-panel-175 left'>Signed Date</div>
        <div class = 'three-panel-175 left'>Actions</div>
	</div>
    
    <div class = 'belts-each-item color1'>
    	<div class = 'three-panel-175 left'><a class = 'gold' href="/managers/view/<?php echo $contract['Contract']['manager_id']?>"><?php echo $contract['Manager']['User']['username']?></a></div>
   		<?php if(!empty($contract['Contract']['boxer_id'])){?>
        	<div class = 'three-panel-175 left'><a class = 'gold' href="/boxers/view/<?php echo $contract['Contract']['boxer_id']?>"><?php echo $contract['Boxer']['Forname']['name'].' '.$contract['Boxer']['Surname']['name']?></a></div>
    	<?php }else{?>
        	<div class = 'three-panel-175 left'><a class = 'gold' href="/trainers/view/<?php echo $contract['Contract']['trainer_id']?>"><?php echo $contract['Trainer']['Forname']['name'].' '.$contract['Trainer']['Surname']['name']?></a></div>
        <?php } ?>
        
        <?php if($contract['Contract']['percentage'] != null){?>
        	 <div class = 'three-panel-75 left'><?php echo $contract['Contract']['percentage'].'%'?></div>
        <?php }else{?>
        	 <div class = 'three-panel-75 left'>N/A</div>
        <?php }?>
    	
       
        
        <?php //if($contract['Contract']['salary'] != null){?>
        	<?php
				//$contract['Contract']['salary'] = number_format($contract['Contract']['salary'], 0, '.', ',');
			?>
        	<!--<div class = 'three-panel-150 left'><?php //echo $contract['Contract']['salary'].' p/w'?></div>-->
        <?php //}else{?>
        	<!--<div class = 'three-panel-150 left'>N/A</div>-->
        <?php // }?>
        <?php
			$contract['Contract']['bonus'] = number_format($contract['Contract']['bonus'], 0, '.', ',');
		?>
        <div class = 'three-panel-175 left'><?php echo '$'.$contract['Contract']['bonus']?></div>
        <div class = 'three-panel-175 left'><?php echo date('M jS Y', strtotime($contract['Contract']['created']))?></div>

        <div class = 'three-panel-175 left'>
            <?php
                if($contract['Contract']['boxer_id'] != null){
                    echo '<p><a onclick="return confirm(\'are you sure?\');" class = "gold" href = "/contracts/release/'.$contract['Contract']['id'].'">Cancel Contract</a></p>';
                    echo '<p><a class = "gold" href = "/contracts/renegotiation/'.$contract['Contract']['boxer_id'].'">Renegotiate Contract</a></p>';
                }elseif($contract['Contract']['trainer_id'] != null){
                    echo '<a onclick="return confirm(\'are you sure?\');" class = "gold" href = "/contracts/release/'.$contract['Contract']['id'].'">Cancel Contract</a>';
                    echo '<p><a class = "gold" href = "/contracts/renegotiationTrainer/'.$contract['Contract']['trainer_id'].'">Renegotiate Contract</a></p>';
                }
            ?>
        </div>
    </div>
    
    <div class = 'managers-footer'></div>
</div>