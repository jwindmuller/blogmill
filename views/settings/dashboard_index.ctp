<h1><span><?php echo __('Settings'); ?></span></h1>
<p><?php
	printf(
		__('Here you can see the settings available for the current theme %s', true),
		$this->Html->link($activeTheme, array('action' => 'themes'))
	);
	echo ', ';
	printf(
		__('or you can %s.', true),
		$this->Html->link(__('change the theme', true), array('action' => 'change_theme'))
	);
?>
</p>
<p><?php
	printf(
		__('Change settings for %s', true),
		$this->Html->link(__('some plugins', true), array('action' => 'plugins'))
	);
?></p>