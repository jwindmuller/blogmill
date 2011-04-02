<h1><span><?php __('Notify user') ?></span></h1>
<div class="form-wrapper">
<div class="cell form-main">
    <h2 class="theme"><?php printf(__('This user has not activated his account in %s', true), $blogmill_site_name); ?></h2>
    <h3><?php echo $user['User']['name']; ?> (<?php echo $user['User']['username']; ?>)</h3>
    <p>
        <?php printf(__('Please provide the following address to the user, or send it to <strong>%s</strong>:', true), $user['User']['email']); ?>
    </p>
    <?php
        echo $this->Form->create('');
        echo $this->Form->input('url', array('value' => Router::url(array('action' => 'notify', $confirmation), true), 'label' => false));
        echo $this->Form->end();
    ?>
</div>

<div class="cell form-sidebar">
    <h3><?php __('Automatically re-send'); ?></h3>
    <?php echo $this->Html->link(__('Send the email', true), array('controller' => 'users', 'action' => 'notify', $confirmation, 'send'), array('class' => 'cta')); ?>
    <p class="note"><?php __('If the person does not receive the email, use the instructions outlined in this page'); ?></p>
</div>
</div>
