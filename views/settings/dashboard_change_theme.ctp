<h1><span><?php __('Settings: Change Theme'); ?></span></h1>
<?php foreach ($themes as $theme): ?>
	<h2><?php echo $theme['name']; ?> v<?php echo $theme['version']; ?> &mdash; <?php echo $this->Html->link(__('Activate', true), array('theme' => $theme['id'])) ;?></h2>
<?php endforeach ?>