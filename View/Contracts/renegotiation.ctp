<?php
	$balance = number_format($contract['Manager']['balance'], 0, '.', ',');
	$contract['Contract']['bonus'] = number_format($contract['Contract']['bonus'], 0, '.', ',');
?>
<h1 class = 'manager-h1 gold light left'>Renegotiation with <?php echo $contract['Boxer']['Forname']['name']. ' '. $contract['Boxer']['Surname']['name']?> - Balance is $<?php echo $balance?> </h1><h1 class = 'manager-h1 gold light right'>Current Contract</h1>

<div class = 'belts-filter left'>
<?php
	 echo $this->Form->create('Contract', array('contracts/renegotiation/'.$contract['Contract']['boxer_id']));
			echo $this->Form->input('percentage', array('label' => '<div class = "tag-tip" title = "Offer precentage(%) you wish to take whenever your boxer makes money. The lower the number the more attractive the contract is to the boxer">Percentage *</div>'));
			echo $this->Form->input('bonus', array('type' => 'text', 'class' => 'currency-format', 'label' => '<div class = "tag-tip" title = "Sweeten the deal with a signing on bonus. Could make the difference between success and failure">Signing on Bonus *</div>'));
		  echo $this->Form->submit('offer.png', array('class' => 'right'));
	  echo $this->Form->end();
?>
</div>


<div class = 'two-panel right'>
	<div class = 'three-panel-header'>
    	<div class = 'three-panel-100 left'>Name</div>
        <div class = 'three-panel-100 left tag-tip' title = "Offer precentage(%) you wish to take whenever your boxer makes money. The lower the number the more attractive the contract is to the boxer">Percentage *</div>
        <div class = 'three-panel-100 left'>Bonus</div>
        <div class = 'three-panel-100 left'>Signing Date</div>
    </div>
    
    <div class = 'belts-each-item color1'>
    	<div class = 'three-panel-100 left'><a class = 'gold' href = '/boxers/view/<?php echo $contract['Boxer']['id']?>'><?php echo $contract['Boxer']['Forname']['name']. ' '. $contract['Boxer']['Surname']['name']?></a></div>
        <div class = 'three-panel-100 left'><?php echo $contract['Contract']['percentage'].'%'?></div>
        <div class = 'three-panel-100 left'><?php echo '$'.$contract['Contract']['bonus']?></div>
    	<div class = 'three-panel-100 left'><?php echo date('M jS Y', strtotime($contract['Contract']['start_date']))?></div>
    </div>
    
    <div class = 'managers-footer'></div>