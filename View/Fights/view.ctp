<h1 class = 'manager-h1 gold light'><?php echo '<a href = "/boxers/view/'.$fight['Fighter1']['id'].'">'.$fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].'</a> vs <a href = "/boxers/view/'.$fight['Fighter2']['id'].'">'.$fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].'</a> - '.date('M jS Y', strtotime($fight['Fight']['created']))?></h1>
<?php if($fight['Fight']['winner_id'] != NULL){?>
    <div class = 'managers-index'>
        
        <div class = 'managers-header'>
            <div class = 'feed-items-wider left blue'><?php echo $fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].' Total stats'?></div>
            <div class = 'feed-items-widish left'>Overview</div>
            <div class = 'feed-items-wider left red'><?php echo $fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].' Total stats'?></div>
        </div>
        
        <div class = 'belts-each-item color1'>
            <div class = 'feed-items-wider left'>
                <?php echo $fight['Fight']['fighter1_total_stats']?>
                <?php if($fight['Fighter1']['id'] == $fight['Fight']['winner_id'] && $fight['Fighter1']['rank'] == 1){
                    echo $this->Html->image('champ.png', array('alt' => 'Champ!'));
                }elseif ($fight['Fighter1']['id'] == $fight['Fight']['winner_id']){
                    echo $this->Html->image('winner.png', array('alt' => 'winner!'));
                }?>
            </div>
            <div class = 'feed-items-widish left'><?php echo $fight['Fight']['overview']?></div>
            <div class = 'feed-items-wider left'>
                <?php echo $fight['Fight']['fighter2_total_stats']?>
                <?php if($fight['Fighter2']['id'] == $fight['Fight']['winner_id'] && $fight['Fighter2']['rank'] == 1){
                    echo $this->Html->image('champ.png', array('alt' => 'Champ!'));
                }elseif ($fight['Fighter2']['id'] == $fight['Fight']['winner_id']){
                    echo $this->Html->image('winner.png', array('alt' => 'winner!'));
                }?>
            </div>
        </div>
        <div class = 'managers-footer'></div>
    </div>
    
    
    <div class = 'managers-index'>
        
        <div class = 'managers-header'>
            <div class = 'feed-items-wider left blue'><?php echo $fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].' round 1 stats'?></div>
            <div class = 'feed-items-widish left'>Round 1 Breakdown</div>
            <div class = 'feed-items-wider left red'><?php echo $fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].' round 1 stats'?></div>
        </div>
        
        <div class = 'belts-each-item color1'>
            <div class = 'feed-items-wider left'><?php echo $fight['Fight']['fighter1_r1_stats']?></div>
            <div class = 'feed-items-widish left'><?php echo $fight['Fight']['round1_description']?></div>
            <div class = 'feed-items-wider left'><?php echo $fight['Fight']['fighter2_r1_stats']?></div>
        </div>
        <div class = 'managers-footer'></div>
    </div>
    
    <?php if($fight['Fight']['round2_description'] != null){?>
    
    <div class = 'managers-index'>
        
        <div class = 'managers-header'>
            <div class = 'feed-items-wider left blue'><?php echo $fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].' round 2 stats'?></div>
            <div class = 'feed-items-widish left'>Round 2 Breakdown</div>
            <div class = 'feed-items-wider left red'><?php echo $fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].' round 2 stats'?></div>
        </div>
        
        <div class = 'belts-each-item color1'>
            <div class = 'feed-items-wider left'><?php echo $fight['Fight']['fighter1_r2_stats']?></div>
            <div class = 'feed-items-widish left'><?php echo $fight['Fight']['round2_description']?></div>
            <div class = 'feed-items-wider left'><?php echo $fight['Fight']['fighter2_r2_stats']?></div>
        </div>
        <div class = 'managers-footer'></div>
    </div>
    
    <?php } ?>
    
    <?php if($fight['Fight']['round3_description'] != null){?>
    
    <div class = 'managers-index'>
        
        <div class = 'managers-header'>
            <div class = 'feed-items-wider left blue'><?php echo $fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].' round 3 stats'?></div>
            <div class = 'feed-items-widish left'>Round 3 Breakdown</div>
            <div class = 'feed-items-wider left red'><?php echo $fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].' round 3 stats'?></div>
        </div>
        
        <div class = 'belts-each-item color1'>
            <div class = 'feed-items-wider left'><?php echo $fight['Fight']['fighter1_r3_stats']?></div>
            <div class = 'feed-items-widish left'><?php echo $fight['Fight']['round3_description']?></div>
            <div class = 'feed-items-wider left'><?php echo $fight['Fight']['fighter2_r3_stats']?></div>
        </div>
        <div class = 'managers-footer'></div>
    </div>
    
    <?php } ?>
    
    <?php if($fight['Fight']['round4_description'] != null){?>
    
    <div class = 'managers-index'>
        
        <div class = 'managers-header'>
            <div class = 'feed-items-wider left blue'><?php echo $fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].' round 4 stats'?></div>
            <div class = 'feed-items-widish left'>Round 4 Breakdown</div>
            <div class = 'feed-items-wider left red'><?php echo $fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].' round 4 stats'?></div>
        </div>
        
        <div class = 'belts-each-item color1'>
            <div class = 'feed-items-wider left'><?php echo $fight['Fight']['fighter1_r4_stats']?></div>
            <div class = 'feed-items-widish left'><?php echo $fight['Fight']['round4_description']?></div>
            <div class = 'feed-items-wider left'><?php echo $fight['Fight']['fighter2_r4_stats']?></div>
        </div>
        <div class = 'managers-footer'></div>
    </div>
    
    <?php } ?>
    
    <?php if($fight['Fight']['round5_description'] != null){?>
    
    <div class = 'managers-index'>
        
        <div class = 'managers-header'>
            <div class = 'feed-items-wider left blue'><?php echo $fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].' round 5 stats'?></div>
            <div class = 'feed-items-widish left'>Round 5 Breakdown</div>
            <div class = 'feed-items-wider left red'><?php echo $fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].' round 5 stats'?></div>
        </div>
        
        <div class = 'belts-each-item color1'>
            <div class = 'feed-items-wider left'><?php echo $fight['Fight']['fighter1_r5_stats']?></div>
            <div class = 'feed-items-widish left'><?php echo $fight['Fight']['round5_description']?></div>
            <div class = 'feed-items-wider left'><?php echo $fight['Fight']['fighter2_r5_stats']?></div>
        </div>
        <div class = 'managers-footer'></div>
    </div>
    
    <?php } ?>
    
    <?php if($fight['Fight']['round6_description'] != null){?>
    
    <div class = 'managers-index'>
        
        <div class = 'managers-header'>
            <div class = 'feed-items-wider left blue'><?php echo $fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].' round 6 stats'?></div>
            <div class = 'feed-items-widish left'>Round 6 Breakdown</div>
            <div class = 'feed-items-wider left red'><?php echo $fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].' round 6 stats'?></div>
        </div>
        
        <div class = 'belts-each-item color1'>
            <div class = 'feed-items-wider left'><?php echo $fight['Fight']['fighter1_r6_stats']?></div>
            <div class = 'feed-items-widish left'><?php echo $fight['Fight']['round6_description']?></div>
            <div class = 'feed-items-wider left'><?php echo $fight['Fight']['fighter2_r6_stats']?></div>
        </div>
        <div class = 'managers-footer'></div>
    </div>
    
    <?php } ?>
    
    <?php if($fight['Fight']['round7_description'] != null){?>
    
    <div class = 'managers-index'>
        
        <div class = 'managers-header'>
            <div class = 'feed-items-wider left blue'><?php echo $fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].' round 7 stats'?></div>
            <div class = 'feed-items-widish left'>Round 7 Breakdown</div>
            <div class = 'feed-items-wider left red'><?php echo $fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].' round 7 stats'?></div>
        </div>
        
        <div class = 'belts-each-item color1'>
            <div class = 'feed-items-wider left'><?php echo $fight['Fight']['fighter1_r7_stats']?></div>
            <div class = 'feed-items-widish left'><?php echo $fight['Fight']['round7_description']?></div>
            <div class = 'feed-items-wider left'><?php echo $fight['Fight']['fighter2_r7_stats']?></div>
        </div>
        <div class = 'managers-footer'></div>
    </div>
    
    <?php } ?>
    
    <?php if($fight['Fight']['round8_description'] != null){?>
    
    <div class = 'managers-index'>
        
        <div class = 'managers-header'>
            <div class = 'feed-items-wider left blue'><?php echo $fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].' round 8 stats'?></div>
            <div class = 'feed-items-widish left'>Round 8 Breakdown</div>
            <div class = 'feed-items-wider left red'><?php echo $fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].' round 8 stats'?></div>
        </div>
        
        <div class = 'belts-each-item color1'>
            <div class = 'feed-items-wider left'><?php echo $fight['Fight']['fighter1_r8_stats']?></div>
            <div class = 'feed-items-widish left'><?php echo $fight['Fight']['round8_description']?></div>
            <div class = 'feed-items-wider left'><?php echo $fight['Fight']['fighter2_r8_stats']?></div>
        </div>
        <div class = 'managers-footer'></div>
    </div>
    
    <?php } ?>
    
    
    <?php if($fight['Fight']['round9_description'] != null){?>
    
    <div class = 'managers-index'>
        
        <div class = 'managers-header'>
            <div class = 'feed-items-wider left blue'><?php echo $fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].' round 9 stats'?></div>
            <div class = 'feed-items-widish left'>Round 9 Breakdown</div>
            <div class = 'feed-items-wider left red'><?php echo $fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].' round 9 stats'?></div>
        </div>
        
        <div class = 'belts-each-item color1'>
            <div class = 'feed-items-wider left'><?php echo $fight['Fight']['fighter1_r9_stats']?></div>
            <div class = 'feed-items-widish left'><?php echo $fight['Fight']['round9_description']?></div>
            <div class = 'feed-items-wider left'><?php echo $fight['Fight']['fighter2_r9_stats']?></div>
        </div>
        <div class = 'managers-footer'></div>
    </div>
    
    <?php } ?>
    
    <?php if($fight['Fight']['round10_description'] != null){?>
    
    <div class = 'managers-index'>
        
        <div class = 'managers-header'>
            <div class = 'feed-items-wider left blue'><?php echo $fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].' round 10 stats'?></div>
            <div class = 'feed-items-widish left'>Round 10 Breakdown</div>
            <div class = 'feed-items-wider left red'><?php echo $fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].' round 10 stats'?></div>
        </div>
        
        <div class = 'belts-each-item color1'>
            <div class = 'feed-items-wider left'><?php echo $fight['Fight']['fighter1_r10_stats']?></div>
            <div class = 'feed-items-widish left'><?php echo $fight['Fight']['round10_description']?></div>
            <div class = 'feed-items-wider left'><?php echo $fight['Fight']['fighter2_r10_stats']?></div>
        </div>
        <div class = 'managers-footer'></div>
    </div>
    
    <?php } ?>
    
    
    <?php if($fight['Fight']['round11_description'] != null){?>
    
    <div class = 'managers-index'>
        
        <div class = 'managers-header'>
            <div class = 'feed-items-wider left blue'><?php echo $fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].' round 11 stats'?></div>
            <div class = 'feed-items-widish left'>Round 11 Breakdown</div>
            <div class = 'feed-items-wider left red'><?php echo $fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].' round 11 stats'?></div>
        </div>
        
        <div class = 'belts-each-item color1'>
            <div class = 'feed-items-wider left'><?php echo $fight['Fight']['fighter1_r11_stats']?></div>
            <div class = 'feed-items-widish left'><?php echo $fight['Fight']['round11_description']?></div>
            <div class = 'feed-items-wider left'><?php echo $fight['Fight']['fighter2_r11_stats']?></div>
        </div>
        <div class = 'managers-footer'></div>
    </div>
    
    <?php } ?>
    
    
    <?php if($fight['Fight']['round12_description'] != null){?>
    
    <div class = 'managers-index'>
        
        <div class = 'managers-header'>
            <div class = 'feed-items-wider left blue'><?php echo $fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name'].' round 12 stats'?></div>
            <div class = 'feed-items-widish left'>Round 12 Breakdown</div>
            <div class = 'feed-items-wider left red'><?php echo $fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name'].' round 12 stats'?></div>
        </div>
        
        <div class = 'belts-each-item color1'>
            <div class = 'feed-items-wider left'><?php echo $fight['Fight']['fighter1_r12_stats']?></div>
            <div class = 'feed-items-widish left'><?php echo $fight['Fight']['round12_description']?></div>
            <div class = 'feed-items-wider left'><?php echo $fight['Fight']['fighter2_r12_stats']?></div>
        </div>
        <div class = 'managers-footer'></div>
    </div>
    
    <?php } ?>
<?php } ?>

