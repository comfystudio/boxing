<h1 class = 'manager-h1 gold light'>In the red need to restart? Delete you're manager!</h1>

<div class = 'belts-filter'>
<?php
		echo $this->Form->create('User', array('users/options/'.$this->Session->read('User.manager_id'), 'autocomplete' => 'off', 'class' => 'UserOptionsForm'));
		 		echo $this->Form->hidden('id', array('value' => $this->Session->read('User.manager_id')));
		 		echo $this->Form->input('username', array('placeholder' => 'Username...', 'value' => '', 'default' => ''));
				echo $this->Form->input('password', array('type' => 'password', 'placeholder' => 'Password...', 'value' => '', 'default' => ''));
				echo $this->Form->submit('delete.png', array('class' => 'left'));
		echo $this->Form->end();
?>
</div>


<h1 class = 'manager-h1 gold light'>Want to change you're password? Look no further!</h1>
<div class = 'belts-filter'>
<?php
		//echo $this->Form->create('User', array('users/newPassword/'.$this->Session->read('User.manager_id'), 'autocomplete' => 'off'));
		echo $this->Form->create('User', array('action' => 'newPassword', 'autocomplete' => 'off', 'class' => 'UserOptionsForm'));
		 		echo $this->Form->hidden('id', array('value' => $this->Session->read('User.id')));
		 		echo $this->Form->input('old password', array('type' => 'password', 'placeholder' => 'Old Password...', 'value' => '', 'default' => ''));
				echo $this->Form->input('new password', array('type' => 'password', 'placeholder' => 'New Password...', 'value' => '', 'default' => ''));
				echo $this->Form->submit('accept.png', array('class' => 'left'));
		echo $this->Form->end();
?>
</div>