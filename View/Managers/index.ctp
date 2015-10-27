<h1 class = 'manager-h1 gold light'>The greatest and richest managers!</h1>

<div class = 'managers-index'>
	<div class = 'managers-header'>
    	<div class = 'belts-items left'>Position</div>
        <div class = 'feed-items-200 left underline'><?php echo $this->Paginator->sort('User.username', 'Name');?></div>
        <div class = 'belts-items left underline'><?php echo $this->Paginator->sort('balance', 'Balance');?></div>
        <div class = 'feed-items-200 left underline'><abbr class = 'tag-tip' title = 'This is the number of weeks the manager has had champions.' rel = 'tooltip' data-placement = 'right' data-html = 'true'><?php echo $this->Paginator->sort('career_belts_total', 'Champion Points *');?></abbr></div>
        <div class = 'feed-items-200 left underline'><?php echo $this->Paginator->sort('belts_held', 'Current Belts Held');?></div>
        <div class = 'belts-items left underline'><?php echo $this->Paginator->sort('created', 'Created');?></div>
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
    <?php echo '<div class = "gold">'.$this->element('pagination').'</div>'; ?>
</div>