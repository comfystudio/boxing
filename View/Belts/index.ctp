<h1 class = 'manager-h1 gold light'>Boxing Game Championship (BGC) Rankings</h1>
<!--<div class = 'belts-filter'><?php //pr($belts);?>
<?php
		 /*echo $this->Form->create('Belt', array('belts/index/'));
			  echo $this->Form->input('weight', array('label' => 'Select Weight', 'options' => $options, 'default' => '16'));
			  echo $this->Form->submit('filter.png', array('class' => 'right'));
		  echo $this->Form->end();*/

?>
</div>-->

<div class = 'managers-index'>
	<div class = 'managers-header'>
    	<div class = 'three-panel-100 left underline'><?php echo $this->Paginator->sort('position', 'Position');?></div>
        <div class = 'three-panel-75 left underline'><?php echo $this->Paginator->sort('Boxer.age', 'Age');?></div>
        <div class = 'three-panel-175 left'>Name</div>
        <div class = 'belts-items left underline'><?php echo $this->Paginator->sort('Boxer.wins', 'Wins');?></div>
        <div class = 'belts-items left underline'><?php echo $this->Paginator->sort('Boxer.loses', 'Loses');?></div>
        <div class = 'belts-items left underline'><?php echo $this->Paginator->sort('Boxer.draws', 'Draws');?></div>
        <div class = 'belts-items left underline'><?php echo $this->Paginator->sort('Boxer.knockouts', 'Knockouts');?></div>
        <div class = 'belts-items left underline'><?php echo $this->Paginator->sort('Boxer.floored', 'Floored');?></div>
	</div>
    
    <?php $count = 0;?>
    <?php foreach($belts as $belt){?>
    	<?php if (($count % 2) == 1){?>
    			<div class = 'belts-each-item color1'>
    	<?php }else{?>
        		<div class = 'belts-each-item color2'>
        <?php }?>

                    <?php $name = $this->requestAction('names/getName/'.$belt['Boxer']['forname_id'].'/'.$belt['Boxer']['surname_id'])?>
                    <div class = 'three-panel-100 left'><?php echo $belt['Belt']['position']?></div>
                    <div class = 'three-panel-75 left'><?php echo $belt['Boxer']['age']?></div>
                    <div class = 'three-panel-175 left'><a class = 'gold' href = '/boxers/view/<?php echo $belt['Boxer']['id']?>'><?php echo $name?></a></div>
                    <div class = 'belts-items left'><?php echo $belt['Boxer']['wins']?></div>
                    <div class = 'belts-items left'><?php echo $belt['Boxer']['loses']?></div>
                    <div class = 'belts-items left'><?php echo $belt['Boxer']['draws']?></div>
                    <div class = 'belts-items left'><?php echo $belt['Boxer']['knockouts']?></div>
                    <div class = 'belts-items left'><?php echo $belt['Boxer']['floored']?></div>
                    <?php $count++;?>
                </div>
    <?php }?>
    <div class = 'managers-footer'></div>
</div>