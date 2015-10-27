<?php
	echo $this->Html->script(array('live-fight'));
?>
<h1 class = 'manager-h1 gold light'></h1>

<?php for($i = 1; $i <= 12; $i++){
	if($fight['Fight']['round'.$i.'_description'] != NULL){
		echo '<div class = "none" id = "descriptionRound-'.$i.'">';
			echo $fight['Fight']['round'.$i.'_description'];
		echo '</div>';
		
		echo '<div class = "none" id = "fighter1Round-'.$i.'">';
			echo $fight['Fight']['fighter1_r'.$i.'_stats'];
		echo '</div>';
		
		echo '<div class = "none" id = "fighter2Round-'.$i.'">';
			echo $fight['Fight']['fighter2_r'.$i.'_stats'];
		echo '</div>';
	}
	
}?>
<div class = "none" id = "descriptionRound-13">
	<?php echo $fight['Fight']['overview'];?>
</div>
<div class = "none" id = "fighter1Round-13">
	<?php echo $fight['Fight']['fighter1_total_stats'];?>
    <?php if($fight['Fighter1']['id'] == $fight['Fight']['winner_id'] && $fight['Fighter1']['rank'] == 1){
        echo $this->Html->image('champ.png', array('alt' => 'Champ!'));
    }elseif ($fight['Fighter1']['id'] == $fight['Fight']['winner_id']){
        echo $this->Html->image('winner.png', array('alt' => 'winner!'));
    }?>
</div>

<div class = "none" id = "fighter2Round-13">
	<?php echo $fight['Fight']['fighter2_total_stats'];?>
    <?php if($fight['Fighter2']['id'] == $fight['Fight']['winner_id'] && $fight['Fighter2']['rank'] == 1){
        echo $this->Html->image('champ.png', array('alt' => 'Champ!'));
    }elseif ($fight['Fighter2']['id'] == $fight['Fight']['winner_id']){
        echo $this->Html->image('winner.png', array('alt' => 'winner!'));
    }?>
</div>

<div class = 'live-fight-header'>
    <ul>
        <?php for($i = 1; $i <= 12; $i++){
            if($fight['Fight']['round'.$i.'_description'] != NULL){
                if($i == 1){
                    echo '<li class = "active" id = "round-'.$i.'"><p class = "gold">Round '.$i.'</p></li>';
                }else{
                    echo '<li id = "round-'.$i.'"><p class = "gold" >Round '.$i.'</p></li>';
                }
            }
        }?>
        <li id = "round-13"><p class = "gold">Overview</p></li>
    </ul>
</div>
	
<div class = 'managers-index'>
	<div class = 'managers-header'>
    	<div class = 'feed-items-wider left blue'><?php echo $fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].' stats'?></div>
        <div class = 'feed-items-widish left gold'>FIGHT!</div>
        <div class = 'feed-items-wider left red'><?php echo $fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].' stats'?></div>
	</div>

	<div class = 'live-fight-adjust'>
		<div id = "fighter1-stats" class = 'live-fight-wider left'>
            <div class = "fighter1_stats none"><?php echo $fight['Fight']['fighter1_r1_stats']?>
            </div>
        </div>
		<div class = 'live-fight-widish left'><?php echo $fight['Fight']['round1_description']?></div>
		<div id = "fighter2-stats" class = 'live-fight-wider left'>
            <div class = "fighter2_stats none"><?php echo $fight['Fight']['fighter2_r1_stats']?>
            </div>
        </div>
    </div>
    <div class = 'managers-footer'></div>
</div>



