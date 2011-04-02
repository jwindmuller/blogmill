<h1><span><?php __('New User'); ?></span></h1>
<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset class="group">
        <?php
            echo $this->Form->input('username');
            echo $this->Form->input('name');
            echo $this->Form->input('email');
        ?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
