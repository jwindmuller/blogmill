<?php printf(__('Hi %s', true), $user['User']['username']); ?> 

<?php __('You have requested a to reset your password, please go to the following address to complete the process:'); ?> 

<?php echo Router::url(array('controller' => 'users', 'action' => 'recover', $confirmation), true); ?>


-----------------------------------------------------------------------------
+ <?php __('If you did not request to change your password, please ignore this email.'); ?> +
-----------------------------------------------------------------------------

