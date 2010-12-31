<h1><span><?php echo __('Settings'); ?></span></h1>
<h2><?php printf(__('Current Theme: %s &mdash; %s', true), $theme['name'], $this->Html->link(__('Change Theme', true), array('controller' => 'settings', 'action' => 'change_theme'))); ?></h2>

<h3><?php __('Menus') ?></h3>
<p><?php __('This are the menus defined in the active theme'); ?></p>
<?php if (isset($theme['menus']) && count($theme['menus'])): ?>
	
	<ul>
	<?php foreach ($theme['menus'] as $key => $name): ?>
		<li><?php echo $this->Html->link($name, array('controller' => 'settings', 'action' => 'menu', $key)); ?></li>
	<?php endforeach ?>
	</ul>
<?php else: ?>
	<p><?php __('No menus defined in this theme'); ?></p>
<?php endif ?>