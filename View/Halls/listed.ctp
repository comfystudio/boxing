<h1 class = 'manager-h1 gold light centre'><?php echo $weightClasses[$weight].' Champions'?></h1>
<div class = 'managers-index'>
<div class = 'managers-header'>
    <div class = 'belts-items left'>Name</div>
    <div class = 'belts-items left'>Manager</div>
    <div class = 'belts-items left'>Weight Class</div>
    <div class = 'three-panel-75 left'>Fame</div>
    <div class = 'three-panel-50 left'>Wins</div>
    <div class = 'three-panel-50 left'>Loses</div>
    <div class = 'three-panel-50 left'>Draws</div>
    <div class = 'three-panel-75 left'>Knockouts</div>
    <div class = 'three-panel-75 left'>Floored</div>
    <div class = 'three-panel-100 left'>Date Start</div>
    <div class = 'three-panel-100 left'>Date End</div>
</div>

<?php $count = 0;?>
<?php foreach ($boxers as $boxer) {?>
<?php if (($count % 2) == 1){?>
    <div class = 'belts-each-item color1'>
<?php }else{?>
    <div class = 'belts-each-item color2'>
<?php }?>
        <div class = 'belts-items left'><a class = 'gold' href = '/halls/view/<?php echo $boxer['Hall']['id']?>'><?php echo $boxer['Hall']['name']?></a></div>
        <?php
        if(empty($boxer['Hall']['manager_name'])) {
            echo '<div class = "belts-items left">No Manager</div>';
        } else {
            echo '<div class = "belts-items left">' .$boxer['Hall']['manager_name']. '</div>';
        }
        ?>
        <div class = 'belts-items left'><?php echo $weightClasses[$boxer['Hall']['weight_type']]?></div>
        <div class = 'three-panel-75 left'><?php echo $boxer['Hall']['fame']?></div>
        <div class = 'three-panel-50 left'><?php echo $boxer['Hall']['wins']?></div>
        <div class = 'three-panel-50 left'><?php echo $boxer['Hall']['loses']?></div>
        <div class = 'three-panel-50 left'><?php echo $boxer['Hall']['draws']?></div>
        <div class = 'three-panel-75 left'><?php echo $boxer['Hall']['knockouts']?></div>
        <div class = 'three-panel-75 left'><?php echo $boxer['Hall']['floored']?></div>
        <div class = 'three-panel-100 left'><?php echo date('M jS Y', strtotime($boxer['Hall']['game_date_start']))?></div>
        <?php if(($boxer['Hall']['game_date_end'] == null)){ ?>
            <div class = 'three-panel-100 left'>Current</div>
        <?php } else { ?>
            <?php $boxer['Hall']['game_date_end'] = strtotime($boxer['Hall']['game_date_end']);?>
            <div class = 'three-panel-100 left'><?php echo date('M jS Y', $boxer['Hall']['game_date_end'])?></div>
        <?php } ?>

        <?php $count++;?>
    </div>
<?php }?>
    <div class = 'managers-footer'></div>
</div>