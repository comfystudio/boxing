<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
    	<?php echo __('Boxing.wtfitness.net'); ?>
		<?php echo $title_for_layout; ?>
        <?php echo 'Boxing Manager Game';?>
	</title>
	<?php
		echo $this->Html->meta('favicon.ico', '/webroot/favicon.ico', array('type' => 'icon'));
		echo $this->Html->meta('keywords', 'Boxing game manager management online MMO');
		echo $this->Html->meta('description', 'Boxing.wtfitness.net lets users manage any number of boxers from chump to champ online against friends');
		
		echo $this->Html->css(array('style','bootstrap'));
		echo $this->Html->script(array('jquery-1.8.2', 'bootstrap.min', 'app'));
	?>
    
    <?php
		$currentPath = substr($this->Html->here, strlen($this->Html->base));
	?>
</head>
<body>
	<div id="container">
    	<div id = 'header-wrapper'>
            <div id="header">
                <div class = 'header-logo'>
                    <a href = '/'><img src="/webroot/img/boxing-gloves-small.png" title = 'Boxing.wtfitness.net Logo' alt = 'Boxing.wtfitness.net logo'/></a>
                </div>
                <div class = 'header-details'>
                	<?php if ($this->Session->read('User.id')){?>
                		<h1><?php echo $this->Session->read('User.username')?></h1>
                        <h2><?php echo '$'.number_format($this->requestAction('managers/getBalance/'. $this->Session->read('User.manager_id')), 0, '.', ',');?></h2>
                    <?php }else{?>
                    	<h1>Unknown</h1>
                    <?php }?>
                </div>

                <div class = "update-game">
                   
                </div>
                
                <div class = 'header-buttons'>
                	<ul>
                    	<li class='<?php echo $this->App->highlighter('/^\/belts\/?/')?>'><a href = '/belts/index'>Belts</a></li>
                        <li class='<?php echo $this->App->highlighter('/^\/feeds\/?/')?>'><a href = '/feeds'>Feed</a></li>
                        <li class='<?php echo $this->App->highlighter('/^\/fights\/?$|fights\/view\/?/')?>'><a href = '/fights'>Fights</a></li>
                        <li class='<?php echo $this->App->highlighter('/^\/halls\/?$|halls\/view\/?|halls\/listed\/?/')?>'><a href = '/halls'>Hall Of Fame</a></li>
                        <li class='<?php echo $this->App->highlighter('/^\/managers$|\/managers\/view\/?|managers\/index\/?/')?>'><a href = '/managers'>Managers</a></li>
                        <?php if($this->Session->read('User.id')){?>
                        	<li><a href = '/users/logout'>Logout</a></li>
                        <?php }else{?>
                        	<li><a href = '/users/login'>Login</a></li>
                        <?php }?>
                    </ul>
                </div>
                 
            </div>
        </div>
        <?php if($this->Session->read('User.id')){?>
            <div class = 'nav-wrapper'>
                <div class = 'nav'>
                    <ul>
                        <li class='<?php echo $this->App->highlighter('/^\/managers\/home\/?/')?>'><a href = '/managers/home'>Home</a></li>
                        <li class='<?php echo $this->App->highlighter('/^\/items\/?/')?>'><a href = '/items'>Shop</a></li>
                        <li class='<?php echo $this->App->highlighter('/^\/users\/options\//')?>'><a href = '/users/options/<?php echo $this->Session->read('User.manager_id')?>'>Options</a></li>
                        <li class='<?php echo $this->App->highlighter('/^\/boxers\/yours\//')?>'><a href = '/boxers/yours/<?php echo $this->Session->read('User.manager_id')?>'>Your Boxers</a></li>
                        <li class='<?php echo $this->App->highlighter('/^\/trainers\/yours\//')?>'><a href = '/trainers/yours/<?php echo $this->Session->read('User.manager_id')?>'>Your Trainers</a></li>
                        <li class='<?php echo $this->App->highlighter('/^\/boxers$|\/boxers\/index\/?|boxers\/view\/?/')?>'><a href = '/boxers'>All Boxers</a></li>
                        <li class='<?php echo $this->App->highlighter('/^\/trainers$|\/trainers\/index\/?|trainers\/view\/?/')?>'><a href = '/trainers'>All Trainers</a></li>
                    </ul>
                </div>
            </div>
        <?php }else{?>
            <div class = 'nav-wrapper'>
                <div class = 'nav'>
                    <ul>
                        <!--<li class='<?php //echo $this->App->highlight('/^\/managers\/?/')?>'><a href = '/managers/home'>Home</a></li>
                        <li><a href = '#'>Feed</a></li>
                        <li><a href = '#'>Fights</a></li>
                        <li><a href = '#'>Your Boxers</a></li>
                        <li><a href = '#'>Your Trainers</a></li>
                        <li><a href = '#'>All Boxers</a></li>
                        <li><a href = '#'>All Trainers</a></li>-->
                    </ul>
                </div>
            </div>
        <?php } ?>
        
        <?php
			if(preg_match('/^\/managers\/home\/?/', $currentPath)){
		?>
            <div class = 'sub-nav-wrapper'>
                <div class = 'sub-nav'>
                    <ul>
                        <li class='<?php echo $this->App->highlighter('/^\/managers\/home\/?$/')?>'><a href = '/managers/home'>All news</a></li>
                        <li class="<?php echo $this->App->highlighter('/^\/managers\/home\/offers\/?$/'); ?>"><a href = '/managers/home/offers'>Offers</a></li>
                        <li class="<?php echo $this->App->highlighter('/^\/managers\/home\/alerts\/?$/'); ?>"><a href = '/managers/home/alerts'>Alerts</a></li>
                        <li class="<?php echo $this->App->highlighter('/^\/managers\/home\/results\/?$/'); ?>"><a href = '/managers/home/results'>Results</a></li>
                    </ul>
                </div>
            </div>
       <?php } ?>
       
        <?php
			if(preg_match('/^\/boxers$|\/boxers\/index/', $currentPath)){
		?>
            <div class = 'sub-nav-wrapper'>
                <div class = 'sub-nav'>
                    <ul>
                        <li class='<?php echo $this->App->highlighter('/^\/boxers$|\/boxers\/index\/sort/')?>'><a href = '/boxers'>All</a></li>
                        <li class="<?php echo $this->App->highlighter('/^\/boxers\/index\/2\/?/'); ?>"><a href = '/boxers/index/2'>Flyweight</a></li>
                        <li class="<?php echo $this->App->highlighter('/^\/boxers\/index\/12\/?/'); ?>"><a href = '/boxers/index/12'>Middleweight</a></li>
                        <li class="<?php echo $this->App->highlighter('/^\/boxers\/index\/16\/?/'); ?>"><a href = '/boxers/index/16'>Heavyweight</a></li>
                    </ul>
                </div>
            </div>
       <?php } ?>
       
       <?php
			if(preg_match('/^\/belts$|\/belts\/index/', $currentPath)){
		?>
            <div class = 'sub-nav-wrapper'>
                <div class = 'sub-nav'>
                    <ul>
                        <li class="<?php echo $this->App->highlighter('/^\/belts\/index\/2\/?/'); ?>"><a href = '/belts/index/2'>Flyweight</a></li>
                        <li class="<?php echo $this->App->highlighter('/^\/belts\/index\/12\/?/'); ?>"><a href = '/belts/index/12'>Middleweight</a></li>
                        <li class="<?php echo $this->App->highlighter('/^\/belts\/index$|\/belts\/index\/16\/?|\/belts\/index\/sort/'); ?>"><a href = '/belts/index/16'>Heavyweight</a></li>
                    </ul>
                </div>
            </div>
       <?php } ?>
       
       
        
		<div id="content">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
        
		<div id="footer">
        	<ul class = 'left'>
               <li><a href = '/help'>Help</a></li>
                <li><a href = '/contact'>Contact</a></li>
                <li><a href = '/terms'>Terms</a></li>
                <li><a href = '/privacy'>Privacy Policy</a></li>
                <li><a href = '/about'>About</a></li>
            </ul>
            <p class = 'right'>Copyright © <?php echo date("Y"); ?> boxing.wtfitness.net. All Rights Reserved. </p>
		</div>
	</div>
    <script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-40085251-1']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	
	</script>
</body>
</html>
