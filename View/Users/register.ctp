<div class = 'login'>
	<h1 class = 'gold'>Register</h1>
    <div class = 'login-form'>
    	<?php 
			  //Register form
			  echo $this->Form->create('User', array('action' => 'register'));
				  echo $this->Form->input('username', array('class' => 'textbox', 'label' => 'Username', 'placeholder' => 'username...'));
				  echo $this->Form->input('password', array('class' => 'textbox', 'label' => 'Password'));
				  echo $this->Form->input('email', array('class' => 'textbox', 'label' => 'Email', 'placeholder' => 'email...'));
				 // echo '<a href = "users/forgot">Forgotten Your Password?</a>';
				  echo $this->Form->submit('register.png');
			  echo $this->Form->end();
		?> 
    </div>
</div>