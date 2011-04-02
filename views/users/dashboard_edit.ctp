<?php
    $is_profile = $this->Session->read('Auth.User.id') == $this->data['User']['id'];
?>
 <?php if ( $is_profile ) : ?>
    <h1><span><?php __('Your profile'); ?></span></h1>
<?php else : ?>
    <h1><span><?php printf(__('Edit %s', true), __('User', true)); ?></span></h1>
<?php endif; ?>

<?php echo $this->Form->create('User');?>
<?php echo $this->Form->input('id'); ?>
<div class="form-wrapper">
    <div class="cell form-main">
       <div class="group">
            <strong class="title"><?php __('Contact information'); ?></strong>
            <?php
                echo $this->Form->input('name');
    	    	echo $this->Form->input('email');
            ?>
        </div>
        <div class="group">
            <strong class="title"><?php __('Profile'); ?></strong>
            <?php
    		    echo $this->Form->input('profile');
    	    	echo $this->Form->input('url');
            ?>
        </div>
    </div>
    <div class="cell form-sidebar">
        <?php if($is_profile) : ?>
	        <div class="group">
                <strong class="title"><?php __('Login Information'); ?></strong>
    	        <?php
    	    	    echo $this->Form->input('username', array('readonly' => 'readonly'));
                    echo $this->Form->input('password', array('type' => 'password', 'label' => __('Password', true)));
                    echo $this->Form->input('password_confirm', array('type' => 'password'));
                ?>
            </div>
        <?php endif; ?>
        <div class="group">
    	    <strong class="title"><?php __('Security'); ?></strong>
            <?php
	        	echo $this->Form->input('question');
		        echo $this->Form->input('answer');
        	?>
        </div>
    </div>
</div>
<?php echo $this->Form->end(__('Submit', true));?>
