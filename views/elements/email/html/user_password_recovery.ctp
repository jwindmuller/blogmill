<h1><?php printf(__('Hi %s', true), $user['User']['username']); ?></h1>

<p><?php __('You have requested a to reset your password, please go to the following address to complete the process:'); ?></p>

<p><?php echo $this->Html->link(Router::url(array('controller' => 'users', 'action' => 'recover', $confirmation), true)); ?></p>


<p><?php __('If you did not request to change your password, please ignore this email.'); ?></p>

