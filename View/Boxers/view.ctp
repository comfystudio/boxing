<h1 class = 'manager-h1 gold light'>Boxer - <?php echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'];?> 
	<?php if($boxer['Boxer']['retired'] == 1){?> 
    	<span class  = "red">Retired</span>
    <?php } else if ($boxer['Boxer']['injured'] == 1) {?>
    	<span class  = "red">Injured - <?php echo $boxer['Boxer']['injured_text']?></span>
    <?php }?>
</h1>
<div class = 'managers-index'>
	<div class = 'managers-header'>
        <div class = 'three-panel-75 left'>Ranked</div>
        <div class = 'three-panel-100 left'>Weight Class</div>
        <div class = 'three-panel-75 left'>Age</div>
        <div class = 'three-panel-75 left'>Wins</div>
        <div class = 'three-panel-75 left'>Loses</div>
        <div class = 'three-panel-75 left'>Draws</div>
        <div class = 'three-panel-75 left'>Knockouts</div>
        <div class = 'three-panel-75 left'>Region</div>
        <div class = 'three-panel-100 left'>Manager</div>
        <div class = 'three-panel-100 left'>Trainer</div>
        <div class = 'three-panel-150 left'>Actions</div>
	</div>
   <?php if($boxer['Boxer']['rank'] == NULL){ $boxer['Boxer']['rank'] = 'Unranked'; }else if ($boxer['Boxer']['rank'] == 0){$boxer['Boxer']['rank'] = 'Undisputed Champion';}elseif($boxer['Boxer']['rank'] == 1){$boxer['Boxer']['rank'] = 'Champion';}?>
    <div class = 'belts-each-item color1'>
    	<div class = 'three-panel-75 left'><?php echo $boxer['Boxer']['rank'];?></div>
        <div class = 'three-panel-100 left'><?php echo $weightClasses[$boxer['Boxer']['weight_type']]?></div>
        <div class = 'three-panel-75 left'><?php echo $boxer['Boxer']['age']?></div>
        <div class = 'three-panel-75 left'><?php echo $boxer['Boxer']['wins']?></div>
        <div class = 'three-panel-75 left'><?php echo $boxer['Boxer']['loses']?></div>
        <div class = 'three-panel-75 left'><?php echo $boxer['Boxer']['draws']?></div>
        <div class = 'three-panel-75 left'><?php echo $boxer['Boxer']['knockouts']?></div>
        <div class = 'three-panel-75 left'><?php echo $regions[$boxer['Boxer']['region']];?></div>
        
      	<?php if ($boxer['Boxer']['manager_id'] == null || empty($boxer['Boxer']['manager_id'])){?>
        		<div class = 'three-panel-100 left'><a class = 'gold' href = '/contracts/boxers/<?php echo $boxer['Boxer']['id']?>'><?php echo 'Offer contract'?></a></div>
        <?php }else{?>
        		<div class = 'three-panel-100 left'><a class = 'gold' href = '/managers/view/<?php echo $boxer['Manager']['id']?>'><?php echo $boxer['Manager']['User']['username']?></a></div>
        <?php }?>
        
        <?php if ($boxer['Boxer']['trainer_id'] == null || empty($boxer['Boxer']['trainer_id'])){?>
        		<div class = 'three-panel-100 left'><?php echo 'No Trainer'?></div>
        <?php }else{?>
        		<div class = 'three-panel-100 left'><a class = 'gold' href = '/trainers/view/<?php echo $boxer['Trainer']['id']?>'><?php echo $boxer['Trainer']['Forname']['name'].' '.$boxer['Trainer']['Surname']['name']?></a></div>
        <?php }?>
        <div class = 'three-panel-150 left'>
                    <?php 
						if($this->Session->read('User.manager_id')){
							if($boxer['Boxer']['injured'] == 1){
								echo '<div class = "red">Injured</div>';
							}elseif(!empty($fight['Fight']) && isset($fight['Fight'])){
								echo '<a class = "gold" href = "/fights/view/'.$fight['Fight']['0']['id'].'">Upcoming fight</a>';	
							}else{
								echo '<a class = "gold" href = "/fights/arrange_fight/'.$boxer['Boxer']['id'].'">Arrange Fight</a>';	
							}
						}else{
							echo 'No actions avaliable';
						}
						
						if($this->Session->read('User.manager_id') == $boxer['Boxer']['manager_id']){
							echo '<p><a class = "gold" href = "/boxers/trainer/'.$boxer['Boxer']['id'].'">Change Trainer</a></p>';
						
							echo '<p><a class = "gold" href = "/contracts/view/'.$boxer['Contract']['id'].'">View Contract</a></p>';

                            echo '<p><a class = "gold" href = "/boxers/training_camp/'.$boxer['Boxer']['id'].'">Training Camp</a></p>';

						}
					?>
       </div>	
    </div>
    <div class = 'managers-footer'></div>
</div>

<h1 class = 'manager-h1 gold light left w325'><?php echo '<abbr class = "tag-tip" data-placement = "top" title = "Based on your highest scouting amoung your trainers">Boxers stats *</abbr>'?></h1>
<h1 class = 'manager-h1 gold light left w650'><?php echo 'Fight Record'?></h1>

<?php
   // if(isset($boxer) && isset($scouting)){
        echo $this->element('boxer-stats', array('boxer' => $boxer, 'bestTrainer' => $scouting,'noHeader' => false));
   // }else{
      //  echo $this->element('boxer-stats', array('boxer' => NULL, 'bestTrainer' => NULL,'noHeader' => false));
   // }
?>

<div class = 'w650-panel'>
	<div class = 'w650-panel-header'>
    	<div class = 'three-panel-100 left'>Result</div>
        <div class = 'three-panel-100 left'>Fighter 1</div>
        <div class = 'three-panel-100 left'>VS</div>
        <div class = 'three-panel-100 left'>Fighter 2</div>
        <div class = 'three-panel-100 left'>Overview</div>
        <div class = 'three-panel-100 left'>Date</div>
    </div>
    
    <?php $count = 0;?>
    <?php foreach($boxer['Fight'] as $fight){?>
    	<?php if (($count % 2) == 1){?>
    			<div class = 'three-panel-content color1'>
    	<?php }else{?>
        		<div class = 'three-panel-content color2'>
        <?php }?>
        			<?php if($fight['winner_id'] == $boxer['Boxer']['id']){
						echo '<div class = "three-panel-100 left green">W</div>';
					}elseif ($fight['winner_id'] == 0){
						echo '<div class = "three-panel-100 left gold">D</div>';
					}else{
						echo '<div class = "three-panel-100 left red">L</div>';
					}
					?>
                    
                    <?php if($fight['fighter1_id'] == $boxer['Boxer']['id']){
						echo '<div class = "three-panel-100 left green"><a href = "/boxers/view/'.$fight['fighter1_id'].'">'.$fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].'</a></div>';
					}else{
						echo '<div class = "three-panel-100 left gold"><a href = "/boxers/view/'.$fight['fighter1_id'].'">'.$fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].'</a></div>';
					}?>
                    
                    <div class = 'three-panel-100 left'>vs</div>
                    
                    <?php if($fight['fighter2_id'] == $boxer['Boxer']['id']){
						echo '<div class = "three-panel-100 left green"><a href = "/boxers/view/'.$fight['fighter2_id'].'">'.$fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].'</a></div>';
					}else{
						echo '<div class = "three-panel-100 left gold"><a href = "/boxers/view/'.$fight['fighter2_id'].'">'.$fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].'</a></div>';
					}?>
                    <div class = 'three-panel-100 left'><a class = gold href = '/fights/view/<?php echo $fight['id']?>'><?php echo $this->Text->truncate($fight['overview'],18)?></a></div>
                    <div class = 'three-panel-100 left'><?php echo date('M jS Y', strtotime($fight['created']))?></div>
                  <?php $count++;?>
        	 </div>
    <?php }?>
    <div class = 'three-panel-footer'></div>
</div>

<?php if(isset($scouting['Trainer']) && $scouting['Trainer']['scout'] != null){?>
<h1 class = 'manager-h1 gold light clear'><?php echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].'\'s personality based on trainer '.$scouting['Forname']['name'].' '. $scouting['Surname']['name'].'\'s opinion'?></h1>
<div class = 'managers-index clear'>
	<div class = 'managers-header'></div>
    
    <?php if ($scouting['Trainer']['scout'] >= 10){?>
        <div class = 'belts-each-item color1 centre'>
        <?php if($boxer['Boxer']['confidence'] == 0){$boxer['Boxer']['confidence'] = 1;}?>
            <?php switch ($boxer['Boxer']['confidence']){
					case ($boxer['Boxer']['confidence'] >= 150):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is extremely confident and believes he is unbeatable';
						break;
					case ($boxer['Boxer']['confidence'] >= 100):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is very confident and believes on the right night he could beat anyone';
						break;
					case ($boxer['Boxer']['confidence'] >= 50):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is lacking confidence and believes he can only beat low ranking fighters';
						break;
					case ($boxer['Boxer']['confidence'] < 25):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is completely without confidence and goes into fights expecting to lose';
						break;
				}
			?> 
        </div>
    <?php }?>
    
    <?php if ($scouting['Trainer']['scout'] >= 20){?>
        <div class = 'belts-each-item color2 centre'>
        <?php if($boxer['Boxer']['happiness'] == 0){$boxer['Boxer']['happiness'] = 1;}?>
            <?php switch ($boxer['Boxer']['happiness']){
					case ($boxer['Boxer']['happiness'] >= 75):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is extremely happy with how his career is going';
						break;
					case ($boxer['Boxer']['happiness'] >= 50):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is fairly happy with how his career is going';
						break;
					case ($boxer['Boxer']['happiness'] >= 25):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is unhappy with how his career is going.';
						break;
					case ($boxer['Boxer']['happiness'] < 25):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is deeply unhappy with how his career is progressing.';
						break;
				}
			?> 
        </div>
    <?php }?>
    
    <?php if ($scouting['Trainer']['scout'] >= 30){?>
        <div class = 'belts-each-item color1 centre'>
        <?php if($boxer['Boxer']['ambition'] == 0){$boxer['Boxer']['ambition'] = 1;}?>
            <?php switch ($boxer['Boxer']['ambition']){
					case ($boxer['Boxer']['ambition'] >= 75):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is extremely ambitious and wants to reach the top';
						break;
					case ($boxer['Boxer']['ambition'] >= 50):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is fairly ambitious and wants to be a high ranking fighter';
						break;
					case ($boxer['Boxer']['ambition'] >= 25):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is not really ambitious and fairley uninterested about ranking high';
						break;
					case ($boxer['Boxer']['ambition'] < 25):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' has almost no ambition and doesn\'t care about his rank';
						break;
				}
			?> 
        </div>
    <?php }?>
    
    <?php if ($scouting['Trainer']['scout'] >= 40){?>
        <div class = 'belts-each-item color2 centre'>
        <?php if($boxer['Boxer']['greed'] == 0){$boxer['Boxer']['greed'] = 1;}?>
            <?php switch ($boxer['Boxer']['greed']){
					case ($boxer['Boxer']['greed'] >= 75):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is extremely greedy and is motivated by money';
						break;
					case ($boxer['Boxer']['greed'] >= 50):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is fairly greedy and wants to make alot of money';
						break;
					case ($boxer['Boxer']['greed'] >= 25):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is not really greedy and only cares about money as a by product';
						break;
					case ($boxer['Boxer']['greed'] < 25):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' has no interest in money';
						break;
				}
			?> 
        </div>
    <?php }?>
    
    <?php if ($scouting['Trainer']['scout'] >= 50){?>
        <div class = 'belts-each-item color1 centre'>
         <?php if($boxer['Boxer']['aggression'] == 0){$boxer['Boxer']['aggression'] = 1;}?>
            <?php switch ($boxer['Boxer']['aggression']){
					case ($boxer['Boxer']['aggression'] >= 75):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is extremely aggressive and wants to tear his opponents apart';
						break;
					case ($boxer['Boxer']['aggression'] >= 50):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is fairly aggressive and likes to put alot of pressure on his opponents';
						break;
					case ($boxer['Boxer']['aggression'] >= 25):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is not really aggressive and fights conservatively';
						break;
					case ($boxer['Boxer']['aggression'] < 25):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is not aggressive at all and will sit back and let his opponents do most of the work';
						break;
				}
			?> 
        </div>
    <?php }?>
    
    <?php if ($scouting['Trainer']['scout'] >= 60){?>
        <div class = 'belts-each-item color2 centre'>
        <?php if($boxer['Boxer']['discipline'] == 0){$boxer['Boxer']['discipline'] = 1;}?>
            <?php switch ($boxer['Boxer']['discipline']){
					case ($boxer['Boxer']['discipline'] >= 75):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is extremely disciplined and applys himself fully to his craft';
						break;
					case ($boxer['Boxer']['discipline'] >= 50):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is fairly disciplined and will stay on course with regards to his training';
						break;
					case ($boxer['Boxer']['discipline'] >= 25):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is not really disciplined and will often not apply himself to his training';
						break;
					case ($boxer['Boxer']['discipline'] < 25):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is not disciplined and will miss training sessions often';
						break;
				}
			?> 
        </div>
    <?php }?>
    
    <?php if ($scouting['Trainer']['scout'] >= 70){?>
        <div class = 'belts-each-item color1 centre'>
        <?php if($boxer['Boxer']['dirty'] == 0){$boxer['Boxer']['dirty'] = 1;}?>
            <?php switch ($boxer['Boxer']['dirty']){
					case ($boxer['Boxer']['dirty'] >= 75):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is an extremely dirty fighter and will often look to foul his opponents';
						break;
					case ($boxer['Boxer']['dirty'] >= 50):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is a fairly dirty fighter and will somtimes look to get an upperhand with a foul blow';
						break;
					case ($boxer['Boxer']['dirty'] >= 25):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is not really a dirty fighter and will rarely intentionally foul his opponent';
						break;
					case ($boxer['Boxer']['dirty'] < 25):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is not a dirty fighter and will not intentionally foul his opponent';
						break;
				}
			?> 
        </div>
    <?php }?>
    
    <?php if ($scouting['Trainer']['scout'] >= 80){?>
        <div class = 'belts-each-item color2 centre'>
        <?php if($boxer['Boxer']['lifestyle'] == 0){$boxer['Boxer']['lifestyle'] = 1;}?>
            <?php switch ($boxer['Boxer']['lifestyle']){
					case ($boxer['Boxer']['lifestyle'] >= 75):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' has an extremely active lifestyle that often hampers his training';
						break;
					case ($boxer['Boxer']['lifestyle'] >= 50):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' has a fairly active lifestyle that sometimes affects his training';
						break;
					case ($boxer['Boxer']['lifestyle'] >= 25):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' has a quiet lifestyle that rarely affects his training';
						break;
					case ($boxer['Boxer']['lifestyle'] < 25):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' lives a spartan like life and never lets his lifestyle get in the way of his training';
						break;
				}
			?> 
        </div>
    <?php }?>
    
    <?php if ($scouting['Trainer']['scout'] >= 90){?>
        <div class = 'belts-each-item color1 centre'>
        <?php if($boxer['Boxer']['injury_prone'] == 0){$boxer['Boxer']['injury_prone'] = 1;}?>
            <?php switch ($boxer['Boxer']['injury_prone']){
					case ($boxer['Boxer']['injury_prone'] >= 75):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is extremely injury prone and will spend alot of the time in A&E';
						break;
					case ($boxer['Boxer']['injury_prone'] >= 50):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' is fairly injury prone and will often pick up injuries';
						break;
					case ($boxer['Boxer']['injury_prone'] >= 25):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' sometimes picks up injuries';
						break;
					case ($boxer['Boxer']['injury_prone'] < 25):
						echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' very rarely picks up injuries';
						break;
				}
			?> 
        </div>
    <?php }?>
    
    <div class = 'managers-footer'></div>
</div>
<?php } ?>
