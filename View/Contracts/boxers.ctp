<?php
	$balance = number_format($balance, 0, '.', ',');
?>
<h1 class = 'manager-h1 gold light'>Offer Contract to <?php echo $boxer['Forname']['name']. ' ' .$boxer['Surname']['name']. ' (R '.$boxer['Boxer']['rank'].') ';?> - Balance is $<?php echo $balance?></h1>

<div class = 'belts-filter'>
<?php
	 echo $this->Form->create('Contract', array('contracts/boxers/'.$boxer_id));
	 		echo $this->Form->hidden('manager_id', array('value' => $this->Session->read('User.manager_id')));
			echo $this->Form->hidden('boxer_id', array('value' => $boxer_id));
			echo $this->Form->input('percentage', array('placeholder' => 'Recommend 50', 'label' => '<div class = "tag-tip" title = "Offer precentage(%) you wish to take whenever your boxer makes money. The lower the number the more attractive the contract is to the boxer">Percentage *</div>'));
			echo $this->Form->input('bonus', array('type' => 'text', 'class' => 'currency-format', 'placeholder' => 'Recommend '.$boxersValue.'', 'label' => '<div class = "tag-tip" title = "Sweeten the deal with a signing on bonus. Could make the difference between success and failure">Signing on Bonus *</div>'));
		  echo $this->Form->submit('offer.png', array('class' => 'right'));
	  echo $this->Form->end();
?>
</div>