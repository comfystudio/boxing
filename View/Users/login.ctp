<div class = 'login'>
	<h1 class = 'gold'>Login</h1>
    <div class = 'login-form'>
    	<?php 
			  //Login form
			  echo $this->Form->create('User', array('action' => 'login'));
				  echo $this->Form->input('username', array('class' => 'textbox', 'label' => 'Username', 'placeholder' => 'username...'));
				  echo $this->Form->input('password', array('class' => 'textbox', 'label' => 'Password'));
				  echo '<a href = "/users/forgot">Forgotten Your Password?</a>';
				  echo $this->Form->submit('login.png', array('class' => 'right'));
				  echo '<p><a href = "/users/register">Need an account? Click here!</a></p>';
			  echo $this->Form->end();
		?> 
    </div>
</div>