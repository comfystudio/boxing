<?php 
	$balance = number_format($balance, 0, '.', ',');
?>
<h1 class = 'manager-h1 gold light'>Offer Contract to Trainer - <?php echo $trainer['Forname']['name']. ' ' .$trainer['Surname']['name'];?> - Balance is $<?php echo $balance?></h1>

<div class = 'belts-filter'>
<?php
	 echo $this->Form->create('Contract', array('contracts/trainers/'.$trainer_id));
	 		echo $this->Form->hidden('manager_id', array('value' => $this->Session->read('User.manager_id')));
			echo $this->Form->hidden('trainer_id', array('value' => $trainer_id));
			//echo $this->Form->input('salary', array('label' => '<div class = "tag-tip" title = "Offer salary to trainer. this is a per week salary so you need to be careful with your budget">Salary p/w *</div>'));
			echo $this->Form->input('bonus', array('type' => 'text', 'placeholder' => 'Recommend 1000', 'label' => '<div class = "tag-tip" title = "Fee you want to pay this trainer">Fee *</div>', 'class' => 'currency-format'));
		  echo $this->Form->submit('offer.png', array('class' => 'right'));
	  echo $this->Form->end();
?>
</div>