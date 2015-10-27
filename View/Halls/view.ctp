<h1 class = 'manager-h1 gold light centre'>Boxer - <?php echo $boxer['Hall']['name']?></h1>

<div class = 'managers-index'>
	<div class = 'managers-header'>
    	<div class = 'belts-items left'>Name</div>
        <div class = 'three-panel-75 left'>Rank</div>
        <div class = 'belts-items left'>Manager</div>
        <div class = 'belts-items left'>Weight Class</div>
        <div class = 'belts-items left'>Region</div>
        <div class = 'three-panel-75 left'>Fame</div>
        <div class = 'three-panel-50 left'>Wins</div>
        <div class = 'three-panel-50 left'>Loses</div>
        <div class = 'three-panel-50 left'>Draws</div>
        <div class = 'three-panel-75 left'>Knockouts</div>
        <div class = 'three-panel-75 left'>Floored</div>
	</div>
    
        <div class = 'belts-each-item color1'>
        <div class = 'belts-items left'><a class = 'gold' href = '/halls/view/<?php echo $boxer['Hall']['id']?>'><?php echo $boxer['Hall']['name']?></a></div>
        <div class = 'three-panel-75 left'><?php echo $boxer['Hall']['rank']?></div>
        <?php 
			if(empty($boxer['Hall']['manager_name'])) {
				echo '<div class = "belts-items left">No Manager</div>';
			} else {
				echo '<div class = "belts-items left">' .$boxer['Hall']['manager_name']. '</div>';
			}
		?>
        <div class = 'belts-items left'><?php echo $weightClasses[$boxer['Hall']['weight_type']]?></div>
        <div class = 'belts-items left'><?php echo $regions[$boxer['Hall']['region']]?></div>
        <div class = 'three-panel-75 left'><?php echo $boxer['Hall']['fame']?></div>
        <div class = 'three-panel-50 left'><?php echo $boxer['Hall']['wins']?></div>
        <div class = 'three-panel-50 left'><?php echo $boxer['Hall']['loses']?></div>
        <div class = 'three-panel-50 left'><?php echo $boxer['Hall']['draws']?></div>
        <div class = 'three-panel-75 left'><?php echo $boxer['Hall']['knockouts']?></div>
        <div class = 'three-panel-75 left'><?php echo $boxer['Hall']['floored']?></div>
    </div>
    <div class = 'managers-footer'></div>
</div>

<?php 
if (!isset($boxerRetired) || empty($boxerRetired)) {
	$boxerRetired['Boxer']['retired'] = 1;	
}

if ($boxerRetired['Boxer']['retired'] == 1) { 

?>
<h1 class = 'manager-h1 gold light centre'>Boxers Stats</h1>

<div class = 'managers-index'>
	<div class = 'managers-header'>
    	<div class = 'three-panel-100 left'>Technique</div>
        <div class = 'three-panel-100 left'>Power</div>
        <div class = 'three-panel-100 left'>Hand Speed</div>
        <div class = 'three-panel-100 left'>Foot Speed</div>
        <div class = 'three-panel-100 left'>Block</div>
        <div class = 'three-panel-100 left'>Defence</div>
        <div class = 'three-panel-75 left'>Chin</div>
        <div class = 'three-panel-100 left'>Heart</div>
        <div class = 'three-panel-100 left'>Cuts</div>
        <div class = 'three-panel-100 left'>Endurance</div>
	</div>
    
    <div class = 'belts-each-item color1'>
    	<div class = 'three-panel-100 left'>
            <?php $offset = round($boxer['Hall']['tech'] / 10);?>
            <div class = "star2-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
        </div>
        <div class = 'three-panel-100 left'>
            <?php $offset = round($boxer['Hall']['power'] / 10);?>
            <div class = "star2-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
        </div>
        <div class = 'three-panel-100 left'>
            <?php $offset = round($boxer['Hall']['hand_speed'] / 10);?>
            <div class = "star2-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
        </div>
        <div class = 'three-panel-100 left'>
            <?php $offset = round($boxer['Hall']['foot_speed'] / 10);?>
            <div class = "star2-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
        </div>
        <div class = 'three-panel-100 left'>
            <?php $offset = round($boxer['Hall']['block'] / 10);?>
            <div class = "star2-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
        </div>
        <div class = 'three-panel-100 left'>
            <?php $offset = round($boxer['Hall']['defence'] / 10);?>
            <div class = "star2-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
        </div>
        <div class = 'three-panel-75 left'>
            <?php $offset = round($boxer['Hall']['chin'] / 10);?>
            <div class = "star2-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
        </div>
        <div class = 'three-panel-100 left'>
            <?php $offset = round($boxer['Hall']['heart'] / 10);?>
            <div class = "star2-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
        </div>
        <div class = 'three-panel-100 left'>
            <?php $offset = round($boxer['Hall']['cut'] / 10);?>
            <div class = "star2-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
        </div>
        <div class = 'three-panel-100 left'>
            <?php $offset = round($boxer['Hall']['endurance'] / 100);?>
            <div class = "star2-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
        </div>
    </div>
    <div class = 'managers-footer'></div>
</div>
<?php } ?>