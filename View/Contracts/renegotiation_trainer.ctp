<?php
	$balance = number_format($contract['Manager']['balance'], 0, '.', ',');
	$contract['Contract']['bonus'] = number_format($contract['Contract']['bonus'], 0, '.', ',');
?>
<h1 class = 'manager-h1 gold light left'>Renegotiation with <?php echo $contract['Trainer']['Forname']['name']. ' '. $contract['Trainer']['Surname']['name']?> - Balance is $<?php echo $balance?> </h1><h1 class = 'manager-h1 gold light right'>Current Contract</h1>

<div class = 'belts-filter left'>
<?php
	 echo $this->Form->create('Contract', array('contracts/renegotiationTrainer/'.$contract['Contract']['trainer_id']));
			//echo $this->Form->input('salary', array('label' => '<div class = "tag-tip" title = "Offer salary to trainer. this is a per week salary so you need to be careful with your budget">Salary p/w *</div>'));
			echo $this->Form->input('bonus', array('type' => 'text', 'class' => 'currency-format', 'label' => '<div class = "tag-tip" title = "Fee you want to pay this trainer">Fee *</div>'));
		  echo $this->Form->submit('offer.png', array('class' => 'right'));
	  echo $this->Form->end();
?>
</div>


<div class = 'two-panel right'>
	<div class = 'three-panel-header'>
    	<div class = 'three-panel-125 left'>Name</div>
        <!--<div class = 'three-panel-100 left tag-tip' title = "Offer salary to trainer. this is a per week salary so you need to be careful with your budget">Salary p/w *</div>-->
        <div class = 'three-panel-125 left'>Bonus</div>
        <div class = 'three-panel-125 left'>Signing Date</div>
    </div>
    
    <div class = 'belts-each-item color1'>
    	<div class = 'three-panel-125 left'><a class = 'gold' href = '/trainers/view/<?php echo $contract['Trainer']['id']?>'><?php echo $contract['Trainer']['Forname']['name']. ' '. $contract['Trainer']['Surname']['name']?></a></div>
        <!--<div class = 'three-panel-100 left'><?php //echo $contract['Contract']['salary'].'%'?></div>-->
        <div class = 'three-panel-125 left'><?php echo '$'.$contract['Contract']['bonus']?></div>
    	<div class = 'three-panel-125 left'><?php echo date('M jS Y', strtotime($contract['Contract']['start_date']))?></div>
    </div>
    
    <div class = 'managers-footer'></div>