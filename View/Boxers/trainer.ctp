<h1 class = 'manager-h1 gold light left w650 align-left'>Select a trainer for <?php echo $boxer['Forname']['name'].' '.$boxer['Surname']['name']?></h1>
<h1 class = 'manager-h1 gold light left w325'><?php echo '<abbr class = "tag-tip" data-placement = "top" title = "Based on your highest scouting among your trainers">Boxers stats *</abbr>'?></h1>

<div class = 'belts-filter left'>
<?php	
	 echo $this->Form->create('Boxer', array('boxers/trainer/'.$boxer['Boxer']['id']));
		  echo $this->Form->input('filter', array('label' => 'Select Trainer', 'options' => $options, 'default' => $boxer['Boxer']['trainer_id']));
		  echo $this->Form->submit('select.png', array('class' => 'right'));
	  echo $this->Form->end();
?>
</div>

<div class = 'three-panel right'>
    <?php echo $this->element('boxer-stats', array('boxer' => $boxer, 'bestTrainer' => $trainerData, 'noHeader' => true)); ?>
</div>

<div class = 'managers-index clear'>
    <div class = 'managers-header'>
        <div class = 'three-panel-150 left'>Name</div>
        <div class = 'three-panel-75 left'>Scout</div>
        <div class = 'three-panel-75 left'>Tech</div>
        <div class = 'three-panel-75 left'>Pwr</div>
        <div class = 'three-panel-75 left'>HSpd</div>
        <div class = 'three-panel-75 left'>FSpd</div>
        <div class = 'three-panel-75 left'>Block</div>
        <div class = 'three-panel-50 left'>Def</div>
        <div class = 'three-panel-50 left'>Chin</div>
        <div class = 'three-panel-50 left'>Hrt</div>
        <div class = 'three-panel-50 left'>Cut</div>
        <div class = 'three-panel-50 left'>End</div>
        <div class = 'feed-items left'>Overall</div>
    </div>

    <?php $count = 1;?>
    <?php foreach($boxer['Manager']['Trainer'] as $trainer){?>
        <?php if (($count % 2) == 1){?>
            <div class = 'belts-each-item color1'>
        <?php }else{?>
            <div class = 'belts-each-item color2'>
        <?php }?>
                <div class = 'three-panel-150 left'><a class = 'gold' href = '/trainers/view/<?php echo $trainer['id']?>'><?php echo $trainer['Forname']['name'].' '.$trainer['Surname']['name']?></a></div>
                <div class = 'three-panel-75 left'>
                    <?php $offset = round($trainer['scout'] / 10);?>
                    <div class = "star1-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
                </div>
                <div class = 'three-panel-75 left'>
                    <?php $offset = round($trainer['tech'] / 10);?>
                    <div class = "star1-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
                </div>
                <div class = 'three-panel-75 left'>
                    <?php $offset = round($trainer['power'] / 10);?>
                    <div class = "star1-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
                </div>
                <div class = 'three-panel-75 left'>
                    <?php $offset = round($trainer['hand_speed'] / 10);?>
                    <div class = "star1-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
                </div>
                <div class = 'three-panel-75 left'>
                    <?php $offset = round($trainer['foot_speed'] / 10);?>
                    <div class = "star1-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
                </div>
                <div class = 'three-panel-75 left'>
                    <?php $offset = round($trainer['block'] / 10);?>
                    <div class = "star1-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
                </div>
                <div class = 'three-panel-50 left'>
                    <?php $offset = round($trainer['defence'] / 10);?>
                    <div class = "star1-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
                </div>
                <div class = 'three-panel-50 left'>
                    <?php $offset = round($trainer['chin'] / 10);?>
                    <div class = "star1-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
                </div>
                <div class = 'three-panel-50 left'>
                    <?php $offset = round($trainer['heart'] / 10);?>
                    <div class = "star1-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
                </div>
                <div class = 'three-panel-50 left'>
                    <?php $offset = round($trainer['cut'] / 10);?>
                    <div class = "star1-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
                </div>
                <div class = 'three-panel-50 left'>
                    <?php $offset = round($trainer['endurance'] / 10);?>
                    <div class = "star1-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
                </div>
                <div class = 'feed-items left'>
                    <div class = "stars-<?php echo $trainer['overall']?>"></div>
                </div>
            </div>
        <?php $count++;?>
    <?php }?>

    <div class = 'managers-footer'></div>
</div>