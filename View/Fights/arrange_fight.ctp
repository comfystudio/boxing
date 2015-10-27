<?php
	$balance = number_format($balance, 0, '.', ',');
?>
<h1 class = 'manager-h1 gold light'>Arrange a fight with <?php echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' (R '.$boxer['Boxer']['rank'].')'?> - balance $<?php echo $balance ?></h1>
<?php foreach($fameValue as $key => $value){
	echo '<div class = "fame-value none" data-id = "'.$key.'" data-value = "'.$value.'"></div>';
}?>

<div class = "clear overflow-hidden">
    <div class = 'belts-filter left'>
    <?php
         echo $this->Form->create('Fight', array('fights/arrange_fight/'.$boxer_id));
                echo $this->Form->hidden('manager_id', array('value' => $this->Session->read('User.manager_id')));
                echo $this->Form->hidden('fighter1_id', array('value' => $boxer_id));
                echo $this->Form->input('fighter2_id', array('label' => 'Select an opponent', 'options' => $opponents) );
                echo $this->Form->input('venue_id', array('label' => 'Venue', 'options' => $venues));
                //echo $this->Form->input('weeks', array('label' => '<div class = "tag-tip" title = "The weeks from the current game time till the fight will happen.">Weeks *</div>', 'options' => $weeks));
                echo $this->Form->input('fee', array('type' => 'text', 'class' => 'currency-format', 'placeholder' => 'Recommend '.reset($fameValue).'', 'label' => '<div class = "tag-tip" title = "Fee is the money you\'re offereing to the fighter that is not yours">Opponents Fee *</div>'));
                echo $this->Form->input('ticket_price', array('type' => 'text', 'class' => 'currency-format', 'placeholder' => 'Recommend 25', 'label' => '<div class = "tag-tip" title = "The ticket price you want to charge, will affect attendence">Ticket Price *</div>'));
                echo $this->Form->input('note', array('label' => '<div class = "tag-tip" title = "Leave note, should only do if boxer is managed by a player">Note *</div>', 'type' => 'textarea'));
                echo $this->Form->submit('offer.png', array('class' => 'right'));
          echo $this->Form->end();
    ?>
    </div>


    <div class = 'two-panel right'>
        <div class = 'three-panel-header'>
            <div class = 'three-panel-150 left'>Name</div>
            <div class = 'three-panel-125 left'>Capacity</div>
            <div class = 'three-panel-125 left'>Cost</div>
        </div>

        <?php $count = 0;?>
        <?php foreach($venuesList as $ven){?>
            <?php if (($count % 2) == 1){?>
                    <div class = 'three-panel-content color1'>
            <?php }else{?>
                    <div class = 'three-panel-content color2'>
            <?php }?>
            <?php
                $ven['Venue']['capacity'] = number_format($ven['Venue']['capacity'], 0, '.', ',');
                $ven['Venue']['cost'] = number_format($ven['Venue']['cost'], 0, '.', ',');
            ?>
                        <div class = 'three-panel-150 left'><?php echo $ven['Venue']['title']?></div>
                        <div class = 'three-panel-125 left'><?php echo $ven['Venue']['capacity']?></div>
                        <div class = 'three-panel-125 left'><?php echo '$'.$ven['Venue']['cost']?></div>
                    <?php $count++;?>
                 </div>
        <?php }?>
        <div class = 'three-panel-footer'></div>
    </div>
</div>

<div class = "w976 clear overflow-hidden">
    <h1 class = 'manager-h1 gold light centre left w325'><?php echo $boxer['Forname']['name'].' '.$boxer['Surname']['name'].' Stats'?></h1>
    <?php if(isset($otherBoxers) && !empty($otherBoxers)){?>
        <h1 class = 'manager-h1 gold light right w325 centre' id = "boxer-stats-name"><?php echo $otherBoxers[0]['Forname']['name'].' '.$otherBoxers[0]['Surname']['name'].' Stats'?></h1>
    <?php } ?>
</div>

<?php echo $this->element('boxer-stats', array('boxer' => $boxer, 'bestTrainer' => $bestTrainer,'noHeader' => false)); ?>

<?php foreach($otherBoxers as $key => $boxer){?>
    <?php if($key == 0){$class = '';}else{$class = 'none';}?>
    <div class = 'three-panel right <?php echo $class?> boxer-stats' id = "boxer-stats_<?php echo $boxer['Boxer']['id']?>" data-name = "<?php echo $boxer['Forname']['name'].' '.$boxer['Surname']['name']?>">
        <?php echo $this->element('boxer-stats', array('boxer' => $boxer, 'bestTrainer' => $bestTrainer, 'noHeader' => true)); ?>
    </div>
<?php }?>