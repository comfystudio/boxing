<h1 class = 'manager-h1 gold light left w650 align-left'>Retrain - <?php echo $trainer['Forname']['name'].' '.$trainer['Surname']['name'];?>
<h1 class = 'manager-h1 gold light left w325'><?php echo 'Training Boxers'?></h1>

    <div class = 'belts-filter left'>
        <?php
        echo $this->Form->create('Trainer', array('trainers/retrain/'.$trainer['Trainer']['id']));
        echo $this->Form->input('stat', array('label' => "<abbr class = 'tag-tip' title = 'Select the stat you wish to retrain' rel = 'tooltip' data-placement = 'right' data-html = 'true'>Select Stat *</abbr>", 'options' => $statOptions));
        echo '<br/><br/>';
        echo $this->Form->input('trainer', array('label' => "<abbr class = 'tag-tip' title = 'Select the trainer you wish to use. The better the trainer the more likely a high value will be gained' rel = 'tooltip' data-placement = 'right' data-html = 'true'>Select Trainer *</abbr>", 'options' => $retainOptions));
        echo $this->Form->submit('select.png', array('class' => 'right'));
        echo $this->Form->end();
        ?>
    </div>

    <div class = 'three-panel right'>
        <div class = 'three-panel-header'>
            <div class = 'three-panel-150 left'>Name</div>
            <div class = 'three-panel-100 left'>Weight</div>
            <div class = 'three-panel-50 left'>Rank</div>
        </div>

        <?php $count = 0;?>
        <?php foreach($trainer['Boxer'] as $boxer){?>
        <?php if (($count % 2) == 1){?>
        <div class = 'three-panel-content color1'>
            <?php }else{?>
            <div class = 'three-panel-content color2'>
                <?php }?>
                <?php if ($boxer['rank'] == 0){$boxer['rank'] = 'Undisputed Champion';}elseif($boxer['rank'] == 1){$boxer['rank'] = 'Champ';}?>
                <div class = 'three-panel-150 left'><a class = 'gold' href = '/boxers/view/<?php echo $boxer['id']?>'><?php echo $boxer['Forname']['name'].' '.$boxer['Surname']['name']?></a></div>
                <div class = 'three-panel-100 left'><?php echo $weights[$boxer['weight_type']];?></div>
                <div class = 'three-panel-50 left'><?php echo $boxer['rank']?></div>

                <?php $count++;?>
            </div>
            <?php } ?>

            <div class = 'three-panel-footer'></div>
        </div>


    <h1 class = 'manager-h1 gold light left w650 clear'><?php echo 'Trainers Stats'?></h1>

    <div class = 'w650-panel clear'>
        <div class = 'w650-panel-header'>
            <div class = 'feed-items-widish left'>Stat</div>
            <div class = 'three-panel-100 left'>Value %</div>
        </div>

        <div class = 'three-panel-content color1'>
            <div class = 'feed-items-widish left'><abbr class = 'tag-tip' title = 'The more scouting your trainer has the more accurate the estimation of a boxers abilties is' rel = 'tooltip' data-placement = 'right' data-html = 'true'>Scouting *</abbr></div>
            <div class = 'three-panel-100 left'>
                <?php $offset = round($trainer['Trainer']['scout'] / 10);?>
                <div class = "star2-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
            </div>
        </div>

        <div class = 'three-panel-content color2'>
            <div class = 'feed-items-widish left'><abbr class = 'tag-tip' title = 'Technique governs how effective a boxers offensive and defence is. A high trainer techinque means a boxer may improve more frequently in this stat' rel = 'tooltip' data-placement = 'right' data-html = 'true'>Technique *</abbr></div>
            <div class = 'three-panel-100 left'>
                <?php $offset = round($trainer['Trainer']['tech'] / 10);?>
                <div class = "star2-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
            </div>
        </div>

        <div class = 'three-panel-content color1'>
            <div class = 'feed-items-widish left'><abbr class = 'tag-tip' title = 'Power governs how much damage a landed punch will do. A high trainer pwoer means a boxer may improve more frequently in this stat' rel = 'tooltip' data-placement = 'right' data-html = 'true'>Power *</abbr></div>
            <div class = 'three-panel-100 left'>
                <?php $offset = round($trainer['Trainer']['power'] / 10);?>
                <div class = "star2-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
            </div>
        </div>

        <div class = 'three-panel-content color2'>
            <div class = 'feed-items-widish left'><abbr class = 'tag-tip' title = 'Hand Speed governs how likely a punch will land. A high trainer hand speed means a boxer may improve more frequently in this stat' rel = 'tooltip' data-placement = 'right' data-html = 'true'>Hand Speed *</abbr></div>
            <div class = 'three-panel-100 left'>
                <?php $offset = round($trainer['Trainer']['hand_speed'] / 10);?>
                <div class = "star2-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
            </div>
        </div>

        <div class = 'three-panel-content color1'>
            <div class = 'feed-items-widish left'><abbr class = 'tag-tip' title = 'Foot Speed governs how likely a punch will be evaded and landed. A high trainer foot speed means a boxer may improve more frequently in this stat' rel = 'tooltip' data-placement = 'right' data-html = 'true'>Foot Speed *</abbr></div>
            <div class = 'three-panel-100 left'>
                <?php $offset = round($trainer['Trainer']['foot_speed'] / 10);?>
                <div class = "star2-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
            </div>
        </div>

        <div class = 'three-panel-content color2'>
            <div class = 'feed-items-widish left'><abbr class = 'tag-tip' title = 'Block governs how likely a punch will be blocked. A high trainer block means a boxer may improve more frequently in this stat' rel = 'tooltip' data-placement = 'right' data-html = 'true'>Block *</abbr></div>
            <div class = 'three-panel-100 left'>
                <?php $offset = round($trainer['Trainer']['block'] / 10);?>
                <div class = "star2-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
            </div>
        </div>

        <div class = 'three-panel-content color1'>
            <div class = 'feed-items-widish left'><abbr class = 'tag-tip' title = 'Defence governs how likely a punch will be evaded. A high trainer defence means a boxer may improve more frequently in this stat' rel = 'tooltip' data-placement = 'right' data-html = 'true'>Defence *</abbr></div>
            <div class = 'three-panel-100 left'>
                <?php $offset = round($trainer['Trainer']['defence'] / 10);?>
                <div class = "star2-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
            </div>
        </div>

        <div class = 'three-panel-content color2'>
            <div class = 'feed-items-widish left'><abbr class = 'tag-tip' title = 'Chin governs how likely a landed punch will cause a knockdown and/or damage. A high trainer chin means a boxer may improve more frequently in this stat' rel = 'tooltip' data-placement = 'right' data-html = 'true'>Chin *</abbr></div>
            <div class = 'three-panel-100 left'>
                <?php $offset = round($trainer['Trainer']['chin'] / 10);?>
                <div class = "star2-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
            </div>
        </div>

        <div class = 'three-panel-content color1'>
            <div class = 'feed-items-widish left'><abbr class = 'tag-tip' title = 'Heart governs how likely a boxer will get up from a knockdown. A high trainer heart means a boxer may improve more frequently in this stat' rel = 'tooltip' data-placement = 'right' data-html = 'true'>Heart *</abbr></div>
            <div class = 'three-panel-100 left'>
                <?php $offset = round($trainer['Trainer']['heart'] / 10);?>
                <div class = "star2-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
            </div>
        </div>

        <div class = 'three-panel-content color2'>
            <div class = 'feed-items-widish left'><abbr class = 'tag-tip' title = 'Cuts governs how likely a boxer can resist cuts and swellings. A high trainer cut means a boxer may improve more frequently in this stat' rel = 'tooltip' data-placement = 'right' data-html = 'true'>Cuts </abbr></div>
            <div class = 'three-panel-100 left'>
                <?php $offset = round($trainer['Trainer']['cut'] / 10);?>
                <div class = "star2-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
            </div>
        </div>

        <div class = 'three-panel-content color1'>
            <div class = 'feed-items-widish left'><abbr class = 'tag-tip' title = 'Endurance governs how much stamina your fighter has to fight. A high trainer endurance means a boxer may improve more frequently in this stat' rel = 'tooltip' data-placement = 'right' data-html = 'true'>Endurance *</abbr></div>
            <div class = 'three-panel-100 left'>
                <?php $offset = round($trainer['Trainer']['endurance'] / 10);?>
                <div class = "star2-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
            </div>
        </div>

        <div class = 'three-panel-content color2'>
            <div class = 'feed-items-widish left'><abbr class = 'tag-tip' title = 'Cornering governs how well the trainer can deal with cuts and motiviation during a fight.' rel = 'tooltip' data-placement = 'right' data-html = 'true'>Cornering *</abbr></div>
            <div class = 'three-panel-100 left'>
                <?php $offset = round($trainer['Trainer']['corner'] / 10);?>
                <div class = "star2-trainer-adjustment stars3-small-<?php echo $offset?>"></div>
            </div>
        </div>

        <div class = 'three-panel-content color1'>
            <div class = 'feed-items-widish left'><abbr class = 'tag-tip' title = 'Overall rating of the trainer.' rel = 'tooltip' data-placement = 'right' data-html = 'true'>Overall *</abbr></div>
            <div class = 'three-panel-100 left'>
                <div class = "stars-<?php echo $trainer['Trainer']['overall']?>"></div>
            </div>
        </div>

        <div class = 'three-panel-footer'></div>
    </div>


