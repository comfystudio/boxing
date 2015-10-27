<?php if(!$noHeader){?>
    <div class = 'three-panel left'>
<?php }?>
    <div class = 'three-panel-header'>
        <div class = 'three-panel-150 left'>Stat</div>
        <div class = 'three-panel-150 left'>Value</div>
    </div>

    <div class = "three-panel-content color2">
        <div class = "three-panel-150 left">Age</div>
        <div class = "three-panel-150 left"><?php echo $boxer['Boxer']['age']?></div>
    </div>

    <div class = "three-panel-content color1">
        <div class = "three-panel-150 left">Rank</div>
        <div class = "three-panel-150 left"><?php echo $boxer['Boxer']['rank']?></div>
    </div>

    <div class = "three-panel-content color2">
        <div class = "three-panel-150 left">Wins</div>
        <div class = "three-panel-150 left"><?php echo $boxer['Boxer']['wins']?></div>
    </div>

    <div class = "three-panel-content color1">
        <div class = "three-panel-150 left">Loses</div>
        <div class = "three-panel-150 left"><?php echo $boxer['Boxer']['loses']?></div>
    </div>

    <div class = "three-panel-content color2">
        <div class = "three-panel-150 left">Draws</div>
        <div class = "three-panel-150 left"><?php echo $boxer['Boxer']['draws']?></div>
    </div>

    <div class = "three-panel-content color1">
        <div class = "three-panel-150 left">Knockouts</div>
        <div class = "three-panel-150 left"><?php echo $boxer['Boxer']['knockouts']?></div>
    </div>

    <div class = 'three-panel-content color2'>
        <div class = 'three-panel-150 left'><abbr class = 'tag-tip' title = 'Technique governs how effective a boxers offensive and defence is.' rel = 'tooltip' data-placement = 'right' data-html = 'true'>Technique *</abbr></div>
        <div class = 'three-panel-150 left'>
            <?php
            if(!isset($bestTrainer['Trainer']) || $bestTrainer['Trainer'] == null){
                echo '<abbr class = "tag-tip" title = "No trainer to evaluate boxer" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-0"></div></abbr>';
            }else{
                if($bestTrainer['Trainer']['scout'] >= 80){
                    $offset = round($boxer['Boxer']['tech'] / 10);
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars3-small-'.$offset.'"></div></abbr>';
                }elseif($bestTrainer['Trainer']['scout'] >= 50){
                    $offset = round($boxer['Boxer']['tech'] / 20);
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars2-small-'.$offset.'"></div></abbr>';
                }elseif($bestTrainer['Trainer']['scout'] >= 20){
                    $offset = round($boxer['Boxer']['tech'] / 25);
                    if($offset <= 1){$offset = 2;}
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-'.$offset.'"></div></abbr>';
                }else{
                    echo '<abbr class = "tag-tip" title = "Trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s doesn\'t know" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-0"></div></abbr>';
                }
            }
            ?>
        </div>
    </div>

    <div class = 'three-panel-content color1'>
        <div class = 'three-panel-150 left'><abbr class = 'tag-tip' title = 'Power governs how much damage a landed punch will do.' rel = 'tooltip' data-placement = 'right' data-html = 'true'>Power *</abbr></div>
        <div class = 'three-panel-150 left'>
            <?php
            if(!isset($bestTrainer['Trainer']) || $bestTrainer['Trainer'] == null){
                echo '<abbr class = "tag-tip" title = "No trainer to evaluate boxer" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-0"></div></abbr>';
            }else{
                if($bestTrainer['Trainer']['scout'] >= 80){
                    $offset = round($boxer['Boxer']['power'] / 10);
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars3-small-'.$offset.'"></div></abbr>';
                }elseif($bestTrainer['Trainer']['scout'] >= 50){
                    $offset = round($boxer['Boxer']['power'] / 20);
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars2-small-'.$offset.'"></div></abbr>';
                }elseif($bestTrainer['Trainer']['scout'] >= 20){
                    $offset = round($boxer['Boxer']['power'] / 25);
                    if($offset <= 1){$offset = 2;}
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-'.$offset.'"></div></abbr>';
                }else{
                    echo '<abbr class = "tag-tip" title = "Trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s doesn\'t know" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-0"></div></abbr>';
                }
            }
            ?>
        </div>
    </div>

    <div class = 'three-panel-content color2'>
        <div class = 'three-panel-150 left'><abbr class = 'tag-tip' title = 'Hand Speed governs how likely a punch will land.' rel = 'tooltip' data-placement = 'right' data-html = 'true'>Hand Speed *</abbr></div>
        <div class = 'three-panel-150 left'>
            <?php
            if(!isset($bestTrainer['Trainer']) || $bestTrainer['Trainer'] == null){
                echo '<abbr class = "tag-tip" title = "No trainer to evaluate boxer" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-0"></div></abbr>';
            }else{
                if($bestTrainer['Trainer']['scout'] >= 80){
                    $offset = round($boxer['Boxer']['hand_speed'] / 10);
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars3-small-'.$offset.'"></div></abbr>';
                }elseif($bestTrainer['Trainer']['scout'] >= 50){
                    $offset = round($boxer['Boxer']['hand_speed'] / 20);
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars2-small-'.$offset.'"></div></abbr>';
                }elseif($bestTrainer['Trainer']['scout'] >= 20){
                    $offset = round($boxer['Boxer']['hand_speed'] / 25);
                    if($offset <= 1){$offset = 2;}
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-'.$offset.'"></div></abbr>';
                }else{
                    echo '<abbr class = "tag-tip" title = "Trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s doesn\'t know" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-0"></div></abbr>';
                }
            }
            ?>
        </div>
    </div>

    <div class = 'three-panel-content color1'>
        <div class = 'three-panel-150 left'><abbr class = 'tag-tip' title = 'Foot Speed governs how likely a punch will be evaded and landed.' rel = 'tooltip' data-placement = 'right' data-html = 'true'>Foot Speed *</abbr></div>
        <div class = 'three-panel-150 left'>
            <?php
            if(!isset($bestTrainer['Trainer']) || $bestTrainer['Trainer'] == null){
                echo '<abbr class = "tag-tip" title = "No trainer to evaluate boxer" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-0"></div></abbr>';
            }else{
                if($bestTrainer['Trainer']['scout'] >= 80){
                    $offset = round($boxer['Boxer']['foot_speed'] / 10);
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars3-small-'.$offset.'"></div></abbr>';
                }elseif($bestTrainer['Trainer']['scout'] >= 50){
                    $offset = round($boxer['Boxer']['foot_speed'] / 20);
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars2-small-'.$offset.'"></div></abbr>';
                }elseif($bestTrainer['Trainer']['scout'] >= 20){
                    $offset = round($boxer['Boxer']['foot_speed'] / 25);
                    if($offset <= 1){$offset = 2;}
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-'.$offset.'"></div></abbr>';
                }else{
                    echo '<abbr class = "tag-tip" title = "Trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s doesn\'t know" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-0"></div></abbr>';
                }
            }
            ?>
        </div>
    </div>

    <div class = 'three-panel-content color2'>
        <div class = 'three-panel-150 left'><abbr class = 'tag-tip' title = 'Block governs how likely a punch will be blocked.' rel = 'tooltip' data-placement = 'right' data-html = 'true'>Block *</abbr></div>
        <div class = 'three-panel-150 left'>
            <?php
            if(!isset($bestTrainer['Trainer']) || $bestTrainer['Trainer'] == null){
                echo '<abbr class = "tag-tip" title = "No trainer to evaluate boxer" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-0"></div></abbr>';
            }else{
                if($bestTrainer['Trainer']['scout'] >= 80){
                    $offset = round($boxer['Boxer']['block'] / 10);
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars3-small-'.$offset.'"></div></abbr>';
                }elseif($bestTrainer['Trainer']['scout'] >= 50){
                    $offset = round($boxer['Boxer']['block'] / 20);
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars2-small-'.$offset.'"></div></abbr>';
                }elseif($bestTrainer['Trainer']['scout'] >= 20){
                    $offset = round($boxer['Boxer']['block'] / 25);
                    if($offset <= 1){$offset = 2;}
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-'.$offset.'"></div></abbr>';
                }else{
                    echo '<abbr class = "tag-tip" title = "Trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s doesn\'t know" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-0"></div></abbr>';
                }
            }
            ?>
        </div>
    </div>

    <div class = 'three-panel-content color1'>
        <div class = 'three-panel-150 left'><abbr class = 'tag-tip' title = 'Defence governs how likely a punch will be evaded.' rel = 'tooltip' data-placement = 'right' data-html = 'true'>Defence *</abbr></div>
        <div class = 'three-panel-150 left'>
            <?php
            if(!isset($bestTrainer['Trainer']) || $bestTrainer['Trainer'] == null){
                echo '<abbr class = "tag-tip" title = "No trainer to evaluate boxer" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-0"></div></abbr>';
            }else{
                if($bestTrainer['Trainer']['scout'] >= 80){
                    $offset = round($boxer['Boxer']['defence'] / 10);
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars3-small-'.$offset.'"></div></abbr>';
                }elseif($bestTrainer['Trainer']['scout'] >= 50){
                    $offset = round($boxer['Boxer']['defence'] / 20);
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars2-small-'.$offset.'"></div></abbr>';
                }elseif($bestTrainer['Trainer']['scout'] >= 20){
                    $offset = round($boxer['Boxer']['defence'] / 25);
                    if($offset <= 1){$offset = 2;}
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-'.$offset.'"></div></abbr>';
                }else{
                    echo '<abbr class = "tag-tip" title = "Trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s doesn\'t know" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-0"></div></abbr>';
                }
            }
            ?>
        </div>
    </div>

    <div class = 'three-panel-content color2'>
        <div class = 'three-panel-150 left'><abbr class = 'tag-tip' title = 'Chin governs how likely a landed punch will cause a knockdown and/or damage.' rel = 'tooltip' data-placement = 'right' data-html = 'true'>Chin *</abbr></div>
        <div class = 'three-panel-150 left'>
            <?php
            if(!isset($bestTrainer['Trainer']) || $bestTrainer['Trainer'] == null){
                echo '<abbr class = "tag-tip" title = "No trainer to evaluate boxer" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-0"></div></abbr>';
            }else{
                if($bestTrainer['Trainer']['scout'] >= 80){
                    $offset = round($boxer['Boxer']['chin'] / 10);
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars3-small-'.$offset.'"></div></abbr>';
                }elseif($bestTrainer['Trainer']['scout'] >= 50){
                    $offset = round($boxer['Boxer']['chin'] / 20);
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars2-small-'.$offset.'"></div></abbr>';
                }elseif($bestTrainer['Trainer']['scout'] >= 20){
                    $offset = round($boxer['Boxer']['chin'] / 25);
                    if($offset <= 1){$offset = 2;}
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-'.$offset.'"></div></abbr>';
                }else{
                    echo '<abbr class = "tag-tip" title = "Trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s doesn\'t know" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-0"></div></abbr>';
                }
            }
            ?>
        </div>
    </div>

    <div class = 'three-panel-content color1'>
        <div class = 'three-panel-150 left'><abbr class = 'tag-tip' title = 'Heart governs how likely a boxer will get up from a knockdown.' rel = 'tooltip' data-placement = 'right' data-html = 'true'>Heart *</abbr></div>
        <div class = 'three-panel-150 left'>
            <?php
            if(!isset($bestTrainer['Trainer']) || $bestTrainer['Trainer'] == null){
                echo '<abbr class = "tag-tip" title = "No trainer to evaluate boxer" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-0"></div></abbr>';
            }else{
                if($bestTrainer['Trainer']['scout'] >= 80){
                    $offset = round($boxer['Boxer']['heart'] / 10);
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars3-small-'.$offset.'"></div></abbr>';
                }elseif($bestTrainer['Trainer']['scout'] >= 50){
                    $offset = round($boxer['Boxer']['heart'] / 20);
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars2-small-'.$offset.'"></div></abbr>';
                }elseif($bestTrainer['Trainer']['scout'] >= 20){
                    $offset = round($boxer['Boxer']['heart'] / 25);
                    if($offset <= 1){$offset = 2;}
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-'.$offset.'"></div></abbr>';
                }else{
                    echo '<abbr class = "tag-tip" title = "Trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s doesn\'t know" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-0"></div></abbr>';
                }
            }
            ?>
        </div>
    </div>

    <div class = 'three-panel-content color2'>
        <div class = 'three-panel-150 left'><abbr class = 'tag-tip' title = 'Cuts governs how likely a boxer can resist cuts and swellings.' rel = 'tooltip' data-placement = 'right' data-html = 'true'>Cuts *</abbr></div>
        <div class = 'three-panel-150 left'>
            <?php
            if(!isset($bestTrainer['Trainer']) || $bestTrainer['Trainer'] == null){
                echo '<abbr class = "tag-tip" title = "No trainer to evaluate boxer" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-0"></div></abbr>';
            }else{
                if($bestTrainer['Trainer']['scout'] >= 80){
                    $offset = round($boxer['Boxer']['cut'] / 10);
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars3-small-'.$offset.'"></div></abbr>';
                }elseif($bestTrainer['Trainer']['scout'] >= 50){
                    $offset = round($boxer['Boxer']['cut'] / 20);
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars2-small-'.$offset.'"></div></abbr>';
                }elseif($bestTrainer['Trainer']['scout'] >= 20){
                    $offset = round($boxer['Boxer']['cut'] / 25);
                    if($offset <= 1){$offset = 2;}
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-'.$offset.'"></div></abbr>';
                }else{
                    echo '<abbr class = "tag-tip" title = "Trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s doesn\'t know" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-0"></div></abbr>';
                }
            }
            ?>
        </div>
    </div>

    <div class = 'three-panel-content color1'>
        <div class = 'three-panel-150 left'><abbr class = 'tag-tip' title = 'Endurance governs how much stamina your fighter has to fight.' rel = 'tooltip' data-placement = 'right' data-html = 'true'>Endurance *</abbr></div>
        <div class = 'three-panel-150 left'>
            <?php
            if(!isset($bestTrainer['Trainer']) || $bestTrainer['Trainer'] == null){
                echo '<abbr class = "tag-tip" title = "No trainer to evaluate boxer" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-0"></div></abbr>';
            }else{
                if($bestTrainer['Trainer']['scout'] >= 80){
                    $offset = round($boxer['Boxer']['endurance'] / 100);
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars3-small-'.$offset.'"></div></abbr>';
                }elseif($bestTrainer['Trainer']['scout'] >= 50){
                    $offset = round($boxer['Boxer']['endurance'] / 200);
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars2-small-'.$offset.'"></div></abbr>';
                }elseif($bestTrainer['Trainer']['scout'] >= 20){
                    $offset = round($boxer['Boxer']['endurance'] / 250);
                    if($offset <= 1){$offset = 2;}
                    echo '<abbr class = "tag-tip" title = "Based on trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s opinion" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-'.$offset.'"></div></abbr>';
                }else{
                    echo '<abbr class = "tag-tip" title = "Trainer '.$bestTrainer['Forname']['name']. ' '. $bestTrainer['Surname']['name']. '\'s doesn\'t know" data-placement = "right"><div class = "star3-boxer-adjustment stars4-small-0"></div></abbr>';
                }
            }
            ?>
        </div>
    </div>

    <div class = 'three-panel-footer'></div>
<?php if(!$noHeader){?>
    </div>
<?php }?>