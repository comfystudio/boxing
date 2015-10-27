<h1 class = 'manager-h1 gold light'></h1>
<div class = 'managers-index'>
	<div class = 'managers-header'>
    </div>
    	
    	<div class="belts-each-item color1 home">
    		<h1 class = 'manager-h1 gold light centre'>Free Online Boxing Manager Game</h1>
        	<p class = "centre"><strong>boxing.wtfitness.net</strong> lets you manage boxers to compete against other managers to show who's the greatest!</p>
            <a href = '/users/register' class = "cursor"><img src="../../webroot/img/register.png"/></a>
            <p class = "centre">Already a Member? </p>
            <a href = '/users/login' class = 'cursor'><img src="../../webroot/img/login.png"/></a>
            <p></p>
		</div>
        
        <div class = "belts-each-item color2 home">
            <div class = 'base-showcase'>
            <img id = 'img-boxer'src='../../webroot/img/home/boxer.png'></img>
            <img style='display:none' id = 'img-contract' src='../../webroot/img/home/contract.png'></img>
            <img style='display:none' id = 'img-trainer' src='../../webroot/img/home/trainer.png'></img>
            <img style='display:none' id = 'img-arrange'src='../../webroot/img/home/arrange.png'></img>
            <img style='display:none' id = 'img-fight'src='../../webroot/img/home/fight.png'></img>
            <img style='display:none' id = 'img-item'src='../../webroot/img/home/item.png'></img>
                <ul id = 'base-showcase-text'>
                    <li>View boxers and review their past fights and stats</li>
                    <li>Offer contracts to boxers you want to manage</li>
                    <li>Employ trainers to get the most out of your boxers</li>
                    <li>Arrange fights for your boxers and turn a nice profit</li>
                    <li>Review boxers fights or watch them live</li>
                    <li>Spend cash on equipment to give your boxers an edge</li>
                </ul>
                
                <ul id = 'base-showcase-botton'>
                    <li class = 'active' id = 'base_boxer'>Boxers</li>
                    <li id = 'base_contract'>Contract</li>
                    <li id = 'base_trainer'>Trainers</li>
                    <li id = 'base_arrange'>Create Fights</li>
                    <li id = 'base_fight'>Fights</li>
                    <li id = 'base_item'>Equipment</li>
                </ul>
            </div>
        </div>
        
        <div class = "belts-each-item color1 home">
        	<div class = "base-info">
            	<div class = 'info'>
                	<h2 class = "centre gold">Multiplayer - Online</h2>
                    <p class = "centre">Always online and multiplayer means you and your friends
                    can compete with people around the world to see who the greatest
                    manager really is!</p>
                </div>
                
                <div class = 'info'>
                	<h2 class = "centre gold">Real Time</h2>
                    <p class = "centre">Every two hours in real time advances the game time by a
                    week. This means you do not have to be glued to the screen or constantly online
                    to be a top class manager</p>
                </div>
                
                <div class = 'info'>
                	<h2 class = "centre gold">Manage Many Boxers</h2>
                    <p class = "centre">Theres no need to just manage one boxer! Maximise your profits
                    by collecting a stable of would be champions, surely one of them will have what it takes
                    to make it to the top</p>
                </div>
                
                <div class = 'info'>
                	<h2 class = "centre gold">Always Changing</h2>
                    <p class = "centre">Even without managers, NPC boxers will create their own fights
                    amongst themselves, boxers will retire and new ones brought in, means the game is always
                    changing and managers will need to be on their toes to stay at the top.</p>
                </div>
            </div>
        </div>
    <div class = 'managers-footer'></div>
     
    <h1 class = 'manager-h1 gold light centre'>The greatest and richest managers!</h1>
    <div class = 'managers-header'>
    	<div class = 'belts-items left'>Position</div>
        <div class = 'feed-items-200 left underline'><?php echo 'Name';?></div>
        <div class = 'belts-items left underline'><?php echo 'Balance';?></div>
        <div class = 'feed-items-200 left underline'><abbr class = 'tag-tip' title = 'This is the number of weeks the manager has had champions.' rel = 'tooltip' data-placement = 'right' data-html = 'true'><?php echo 'Champion Points *';?></abbr></div>
        <div class = 'feed-items-200 left underline'><?php echo 'Current Belts Held';?></div>
        <div class = 'belts-items left underline'><?php echo 'Created';?></div>
	</div>
   
    <?php $count = 1;?>
    <?php foreach($managers as $manager){?>
    	<?php if (($count % 2) == 1){?>
    			<div class = 'belts-each-item color1'>
    	<?php }else{?>
        		<div class = 'belts-each-item color2'>
        <?php }?>
        <?php
			$manager['Manager']['balance'] = number_format($manager['Manager']['balance'], 0, '.', ',');
		?>
                    <div class = 'belts-items left'><?php echo $count?></div>
                    <div class = 'feed-items-200 left'><a class = 'gold' href = '/managers/view/<?php echo $manager['Manager']['id']?>'><?php echo $manager['User']['username']?></a></div>
                    <div class = 'belts-items left'><?php echo '$'.$manager['Manager']['balance']?></div>
                    <div class = 'feed-items-200 left'><?php echo number_format($manager['Manager']['career_belts_total'], 0, '.', ',');?></div>
                    <div class = 'feed-items-200 left'><?php echo $manager['Manager']['belts_held']?></div>
                    <div class = 'belts-items left'><?php echo date('M jS Y', strtotime($manager['Manager']['created']))?></div>
                    <?php $count++;?>
                </div>
    <?php }?>
    <div class = 'managers-footer'></div>
     
       
    <h1 class = 'manager-h1 gold light centre'>The most recent and epic fights!</h1>


	<div class = 'managers-header'>
    	<div class = 'three-panel-150 left'>Fighter 1</div>
        <div class = 'three-panel-75 left'>VS</div>
        <div class = 'three-panel-150 left'>Fighter 2</div>
        <div class = 'three-panel-150 left'>Winner</div>
        <div class = 'feed-items-wider left'>Overview</div>
        <div class = 'three-panel-75 left'>Breakdown</div>
        <div class = 'feed-items left'>Game Date</div>
	</div>
    
    <?php $count = 0;?>
    <?php foreach($fights as $fight){?>
    	<?php if (($count % 2) == 1){?>
    			<div class = 'belts-each-item color1'>
    	<?php }else{?>
        		<div class = 'belts-each-item color2'>
        <?php }?>
        
					<?php //$username = $this->requestAction('users/getUsername/'.$feed['Manager']['user_id'])?>
                    <div class = 'three-panel-150 left'><a class = 'blue' href = '/boxers/view/<?php echo $fight['Fight']['fighter1_id']?>'><?php echo $fight['Fighter1']['Forname']['name'].' '.$fight['Fighter1']['Surname']['name']?></a></div>
                    <div class = 'three-panel-75 left'>vs</div>
                    <div class = 'three-panel-150 left'><a class = 'red' href = '/boxers/view/<?php echo $fight['Fight']['fighter2_id']?>'><?php echo $fight['Fighter2']['Forname']['name'].' '.$fight['Fighter2']['Surname']['name']?></a></div>
                    <?php if($fight['Fight']['winner_id'] == 0) { ?>
                    	<div class = 'three-panel-150 left'><a class = 'gold'>Draw</a></div>
                    <?php  } else {?>
                    	<div class = 'three-panel-150 left'><a class = 'gold' href = '/boxers/view/<?php echo $fight['Fight']['winner_id']?>'><?php echo $fight['Winner']['Forname']['name'].' '.$fight['Winner']['Surname']['name']?></a></div>
                    <?php } ?>
                    <div class = 'feed-items-wider left'><?php echo $this->Text->truncate($fight['Fight']['overview'],38)?></div>
                    <div class = 'three-panel-75 left'><a class = 'gold' href = '/fights/view/<?php echo $fight['Fight']['id']?>'>View</a></div>
                    <div class = 'feed-items left'><?php echo date('M jS Y', strtotime($fight['Fight']['game_time']))?></div>
                    <?php $count++;?>
        	 </div>
    <?php }?>





	<div class = 'managers-footer'></div>
</div>