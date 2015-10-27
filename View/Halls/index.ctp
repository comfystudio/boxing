<h1 class = 'manager-h1 gold light centre'>Hall Of Fame - Most Famous Boxer</h1>

<div class = 'managers-index'>
    <div class = 'managers-header'>
        <div class = 'belts-items left'>Name</div>
        <div class = 'three-panel-75 left'>Rank</div>
        <div class = 'belts-items left'>Manager</div>
        <div class = 'belts-items left'>Weight Class</div>
        <div class = 'three-panel-75 left'>Fame</div>
        <div class = 'three-panel-50 left'>Wins</div>
        <div class = 'three-panel-50 left'>Loses</div>
        <div class = 'three-panel-50 left'>Draws</div>
        <div class = 'three-panel-75 left'>Knockouts</div>
        <div class = 'three-panel-75 left'>Floored</div>
        <div class = 'belts-items left'>Date Achieved</div>
    </div>

    <?php if(isset($fame) && $fame != null){?>
        <div class = 'belts-each-item color1'>
            <div class = 'belts-items left'><a class = 'gold' href = '/halls/view/<?php echo $fame['Hall']['id']?>'><?php echo $fame['Hall']['name']?></a></div>
            <div class = 'three-panel-75 left'><?php echo $fame['Hall']['rank']?></div>
            <?php
                if(empty($fame['Hall']['manager_name'])) {
                    echo '<div class = "belts-items left">No Manager</div>';
                } else {
                    echo '<div class = "belts-items left">' .$fame['Hall']['manager_name']. '</div>';
                }
            ?>
            <div class = 'belts-items left'><?php echo $weightClasses[$fame['Hall']['weight_type']]?></div>
            <div class = 'three-panel-75 left'><?php echo $fame['Hall']['fame']?></div>
            <div class = 'three-panel-50 left'><?php echo $fame['Hall']['wins']?></div>
            <div class = 'three-panel-50 left'><?php echo $fame['Hall']['loses']?></div>
            <div class = 'three-panel-50 left'><?php echo $fame['Hall']['draws']?></div>
            <div class = 'three-panel-75 left'><?php echo $fame['Hall']['knockouts']?></div>
            <div class = 'three-panel-75 left'><?php echo $fame['Hall']['floored']?></div>
            <?php $fame['Hall']['game_date_start'] = strtotime($fame['Hall']['game_date_start']);?>
            <div class = 'belts-items left'><?php echo date('M jS Y', $fame['Hall']['game_date_start'])?></div>
        </div>
    <?php }?>
    <div class = 'managers-footer'></div>
</div>

<h1 class = 'manager-h1 gold light centre'>Most Wins</h1>

<div class = 'managers-index'>
	<div class = 'managers-header'>
    	<div class = 'belts-items left'>Name</div>
        <div class = 'three-panel-75 left'>Rank</div>
        <div class = 'belts-items left'>Manager</div>
        <div class = 'belts-items left'>Weight Class</div>
        <div class = 'three-panel-75 left'>Fame</div>
        <div class = 'three-panel-50 left'>Wins</div>
        <div class = 'three-panel-50 left'>Loses</div>
        <div class = 'three-panel-50 left'>Draws</div>
        <div class = 'three-panel-75 left'>Knockouts</div>
        <div class = 'three-panel-75 left'>Floored</div>
        <div class = 'belts-items left'>Date Achieved</div>
	</div>

    <?php if(isset($wins) && $wins != null){?>
        <div class = 'belts-each-item color1'>
            <div class = 'belts-items left'><a class = 'gold' href = '/halls/view/<?php echo $wins['Hall']['id']?>'><?php echo $wins['Hall']['name']?></a></div>
            <div class = 'three-panel-75 left'><?php echo $wins['Hall']['rank']?></div>
            <?php
                if(empty($wins['Hall']['manager_name'])) {
                    echo '<div class = "belts-items left">No Manager</div>';
                } else {
                    echo '<div class = "belts-items left">' .$wins['Hall']['manager_name']. '</div>';
                }
            ?>
            <div class = 'belts-items left'><?php echo $weightClasses[$wins['Hall']['weight_type']]?></div>
            <div class = 'three-panel-75 left'><?php echo $wins['Hall']['fame']?></div>
            <div class = 'three-panel-50 left'><?php echo $wins['Hall']['wins']?></div>
            <div class = 'three-panel-50 left'><?php echo $wins['Hall']['loses']?></div>
            <div class = 'three-panel-50 left'><?php echo $wins['Hall']['draws']?></div>
            <div class = 'three-panel-75 left'><?php echo $wins['Hall']['knockouts']?></div>
            <div class = 'three-panel-75 left'><?php echo $wins['Hall']['floored']?></div>
            <?php $wins['Hall']['game_date_start'] = strtotime($wins['Hall']['game_date_start']);?>
            <div class = 'belts-items left'><?php echo date('M jS Y', $wins['Hall']['game_date_start'])?></div>
        </div>
    <?php }?>
    <div class = 'managers-footer'></div>
</div>


<h1 class = 'manager-h1 gold light centre'>Most Loses</h1>

<div class = 'managers-index'>
	<div class = 'managers-header'>
    	<div class = 'belts-items left'>Name</div>
        <div class = 'three-panel-75 left'>Rank</div>
        <div class = 'belts-items left'>Manager</div>
        <div class = 'belts-items left'>Weight Class</div>
        <div class = 'three-panel-75 left'>Fame</div>
        <div class = 'three-panel-50 left'>Wins</div>
        <div class = 'three-panel-50 left'>Loses</div>
        <div class = 'three-panel-50 left'>Draws</div>
        <div class = 'three-panel-75 left'>Knockouts</div>
        <div class = 'three-panel-75 left'>Floored</div>
        <div class = 'belts-items left'>Date Achieved</div>
	</div>

    <?php if(isset($loses) && $loses != null){?>
        <div class = 'belts-each-item color1'>
            <div class = 'belts-items left'><a class = 'gold' href = '/halls/view/<?php echo $loses['Hall']['id']?>'><?php echo $loses['Hall']['name']?></a></div>
            <div class = 'three-panel-75 left'><?php echo $loses['Hall']['rank']?></div>
            <?php
                if(empty($loses['Hall']['manager_name'])) {
                    echo '<div class = "belts-items left">No Manager</div>';
                } else {
                    echo '<div class = "belts-items left">' .$loses['Hall']['manager_name']. '</div>';
                }
            ?>
            <div class = 'belts-items left'><?php echo $weightClasses[$loses['Hall']['weight_type']]?></div>
            <div class = 'three-panel-75 left'><?php echo $loses['Hall']['fame']?></div>
            <div class = 'three-panel-50 left'><?php echo $loses['Hall']['wins']?></div>
            <div class = 'three-panel-50 left'><?php echo $loses['Hall']['loses']?></div>
            <div class = 'three-panel-50 left'><?php echo $loses['Hall']['draws']?></div>
            <div class = 'three-panel-75 left'><?php echo $loses['Hall']['knockouts']?></div>
            <div class = 'three-panel-75 left'><?php echo $loses['Hall']['floored']?></div>
            <?php $loses['Hall']['game_date_start'] = strtotime($loses['Hall']['game_date_start']);?>
            <div class = 'belts-items left'><?php echo date('M jS Y', $loses['Hall']['game_date_start'])?></div>
        </div>
    <?php }?>
    <div class = 'managers-footer'></div>
</div>


<h1 class = 'manager-h1 gold light centre'>Most Draws</h1>

<div class = 'managers-index'>
	<div class = 'managers-header'>
    	<div class = 'belts-items left'>Name</div>
        <div class = 'three-panel-75 left'>Rank</div>
        <div class = 'belts-items left'>Manager</div>
        <div class = 'belts-items left'>Weight Class</div>
        <div class = 'three-panel-75 left'>Fame</div>
        <div class = 'three-panel-50 left'>Wins</div>
        <div class = 'three-panel-50 left'>Loses</div>
        <div class = 'three-panel-50 left'>Draws</div>
        <div class = 'three-panel-75 left'>Knockouts</div>
        <div class = 'three-panel-75 left'>Floored</div>
        <div class = 'belts-items left'>Date Achieved</div>
	</div>

    <?php if(isset($draws) && $draws != null){?>
        <div class = 'belts-each-item color1'>
            <div class = 'belts-items left'><a class = 'gold' href = '/halls/view/<?php echo $draws['Hall']['id']?>'><?php echo $draws['Hall']['name']?></a></div>
            <div class = 'three-panel-75 left'><?php echo $draws['Hall']['rank']?></div>
            <?php
                if(empty($draws['Hall']['manager_name'])) {
                    echo '<div class = "belts-items left">No Manager</div>';
                } else {
                    echo '<div class = "belts-items left">' .$draws['Hall']['manager_name']. '</div>';
                }
            ?>
            <div class = 'belts-items left'><?php echo $weightClasses[$draws['Hall']['weight_type']]?></div>
            <div class = 'three-panel-75 left'><?php echo $draws['Hall']['fame']?></div>
            <div class = 'three-panel-50 left'><?php echo $draws['Hall']['wins']?></div>
            <div class = 'three-panel-50 left'><?php echo $draws['Hall']['loses']?></div>
            <div class = 'three-panel-50 left'><?php echo $draws['Hall']['draws']?></div>
            <div class = 'three-panel-75 left'><?php echo $draws['Hall']['knockouts']?></div>
            <div class = 'three-panel-75 left'><?php echo $draws['Hall']['floored']?></div>
            <?php $draws['Hall']['game_date_start'] = strtotime($draws['Hall']['game_date_start']);?>
            <div class = 'belts-items left'><?php echo date('M jS Y', $draws['Hall']['game_date_start'])?></div>
        </div>
    <?php }?>
    <div class = 'managers-footer'></div>
</div>

<h1 class = 'manager-h1 gold light centre'>Most Knockouts</h1>

<div class = 'managers-index'>
	<div class = 'managers-header'>
    	<div class = 'belts-items left'>Name</div>
        <div class = 'three-panel-75 left'>Rank</div>
        <div class = 'belts-items left'>Manager</div>
        <div class = 'belts-items left'>Weight Class</div>
        <div class = 'three-panel-75 left'>Fame</div>
        <div class = 'three-panel-50 left'>Wins</div>
        <div class = 'three-panel-50 left'>Loses</div>
        <div class = 'three-panel-50 left'>Draws</div>
        <div class = 'three-panel-75 left'>Knockouts</div>
        <div class = 'three-panel-75 left'>Floored</div>
        <div class = 'belts-items left'>Date Achieved</div>
	</div>

    <?php if(isset($knockouts) && $knockouts != null){?>
        <div class = 'belts-each-item color1'>
            <div class = 'belts-items left'><a class = 'gold' href = '/halls/view/<?php echo $knockouts['Hall']['id']?>'><?php echo $knockouts['Hall']['name']?></a></div>
            <div class = 'three-panel-75 left'><?php echo $knockouts['Hall']['rank']?></div>
            <?php
                if(empty($knockouts['Hall']['manager_name'])) {
                    echo '<div class = "belts-items left">No Manager</div>';
                } else {
                    echo '<div class = "belts-items left">' .$knockouts['Hall']['manager_name']. '</div>';
                }
            ?>
            <div class = 'belts-items left'><?php echo $weightClasses[$knockouts['Hall']['weight_type']]?></div>
            <div class = 'three-panel-75 left'><?php echo $knockouts['Hall']['fame']?></div>
            <div class = 'three-panel-50 left'><?php echo $knockouts['Hall']['wins']?></div>
            <div class = 'three-panel-50 left'><?php echo $knockouts['Hall']['loses']?></div>
            <div class = 'three-panel-50 left'><?php echo $knockouts['Hall']['draws']?></div>
            <div class = 'three-panel-75 left'><?php echo $knockouts['Hall']['knockouts']?></div>
            <div class = 'three-panel-75 left'><?php echo $knockouts['Hall']['floored']?></div>
            <?php $knockouts['Hall']['game_date_start'] = strtotime($knockouts['Hall']['game_date_start']);?>
            <div class = 'belts-items left'><?php echo date('M jS Y', $knockouts['Hall']['game_date_start'])?></div>
        </div>
    <?php } ?>
    <div class = 'managers-footer'></div>
</div>


<h1 class = 'manager-h1 gold light centre'>Most Floored</h1>

<div class = 'managers-index'>
	<div class = 'managers-header'>
    	<div class = 'belts-items left'>Name</div>
        <div class = 'three-panel-75 left'>Rank</div>
        <div class = 'belts-items left'>Manager</div>
        <div class = 'belts-items left'>Weight Class</div>
        <div class = 'three-panel-75 left'>Fame</div>
        <div class = 'three-panel-50 left'>Wins</div>
        <div class = 'three-panel-50 left'>Loses</div>
        <div class = 'three-panel-50 left'>Draws</div>
        <div class = 'three-panel-75 left'>Knockouts</div>
        <div class = 'three-panel-75 left'>Floored</div>
        <div class = 'belts-items left'>Date Achieved</div>
	</div>

    <?php if(isset($floored) && $floored != null){?>
        <div class = 'belts-each-item color1'>
            <div class = 'belts-items left'><a class = 'gold' href = '/halls/view/<?php echo $floored['Hall']['id']?>'><?php echo $floored['Hall']['name']?></a></div>
            <div class = 'three-panel-75 left'><?php echo $floored['Hall']['rank']?></div>
            <?php
                if(empty($floored['Hall']['manager_name'])) {
                    echo '<div class = "belts-items left">No Manager</div>';
                } else {
                    echo '<div class = "belts-items left">' .$floored['Hall']['manager_name']. '</div>';
                }
            ?>
            <div class = 'belts-items left'><?php echo $weightClasses[$floored['Hall']['weight_type']]?></div>
            <div class = 'three-panel-75 left'><?php echo $floored['Hall']['fame']?></div>
            <div class = 'three-panel-50 left'><?php echo $floored['Hall']['wins']?></div>
            <div class = 'three-panel-50 left'><?php echo $floored['Hall']['loses']?></div>
            <div class = 'three-panel-50 left'><?php echo $floored['Hall']['draws']?></div>
            <div class = 'three-panel-75 left'><?php echo $floored['Hall']['knockouts']?></div>
            <div class = 'three-panel-75 left'><?php echo $floored['Hall']['floored']?></div>
            <?php $floored['Hall']['game_date_start'] = strtotime($floored['Hall']['game_date_start']);?>
            <div class = 'belts-items left'><?php echo date('M jS Y', $floored['Hall']['game_date_start'])?></div>
        </div>
    <?php } ?>
    <div class = 'managers-footer'></div>
</div>

<?php 
foreach ($weightClasses as $key => $weightClass) {
?>
<h1 class = 'manager-h1 gold light centre'><?php echo $weightClass.' Champions'?> - <a class = "green" href = "/halls/listed/<?php echo $key?>">View More</a></h1>

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
    <?php foreach(${$weightClass} as $class){?>
    	<?php if (($count % 2) == 1){?>
    			<div class = 'belts-each-item color1'>
    	<?php }else{?>
        		<div class = 'belts-each-item color2'>
        <?php }?>
                    <div class = 'belts-items left'><a class = 'gold' href = '/halls/view/<?php echo $class['Hall']['id']?>'><?php echo $class['Hall']['name']?></a></div>
                    <?php 
                        if(empty($class['Hall']['manager_name'])) {
                            echo '<div class = "belts-items left">No Manager</div>';
                        } else {
                            echo '<div class = "belts-items left">' .$class['Hall']['manager_name']. '</div>';
                        }
                    ?>
                    <div class = 'belts-items left'><?php echo $weightClasses[$class['Hall']['weight_type']]?></div>
                    <div class = 'three-panel-75 left'><?php echo $class['Hall']['fame']?></div>
                    <div class = 'three-panel-50 left'><?php echo $class['Hall']['wins']?></div>
                    <div class = 'three-panel-50 left'><?php echo $class['Hall']['loses']?></div>
                    <div class = 'three-panel-50 left'><?php echo $class['Hall']['draws']?></div>
                    <div class = 'three-panel-75 left'><?php echo $class['Hall']['knockouts']?></div>
                    <div class = 'three-panel-75 left'><?php echo $class['Hall']['floored']?></div>
                    <div class = 'three-panel-100 left'><?php echo date('M jS Y', strtotime($class['Hall']['game_date_start']))?></div>
                    <?php if(($class['Hall']['game_date_end'] == null)){ ?>
						<div class = 'three-panel-100 left'>Current</div>
					<?php } else { ?>
                    	<?php $class['Hall']['game_date_end'] = strtotime($class['Hall']['game_date_end']);?>
						<div class = 'three-panel-100 left'><?php echo date('M jS Y', $class['Hall']['game_date_end'])?></div>
					<?php } ?>
                    
            <?php $count++;?>
                </div>
    <?php }?>
       <div class = 'managers-footer'></div>
</div>
	
<?php } ?>