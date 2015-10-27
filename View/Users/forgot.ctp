<div class = 'login'>
	<h1 class = 'gold'>Reset Password</h1>
    <div class = 'login-form'>
    	<?php 
			  //Login form
			  echo $this->Form->create('User', array('action' => 'forgot'));
				  echo $this->Form->input('username', array('class' => 'textbox', 'label' => 'Username', 'placeholder' => 'username...'));
				  echo $this->Form->input('email', array('class' => 'textbox', 'label' => 'Email', 'placeholder' => 'email...'));
				  echo $this->Form->submit('reset.png', array('class' => 'right'));
			  echo $this->Form->end();
		?> 
    </div>
</div>