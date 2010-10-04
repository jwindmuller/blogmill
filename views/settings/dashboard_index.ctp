<h1><span><?php __('Settings'); ?></span></h1>
<h2><?php __('Menus') ?></h2> <p><?php __('This are the menus defined in the active theme'); ?></p>
<?php if (isset($theme['menus']) && count($theme['menus'])): ?>
	
	<ul>
	<?php foreach ($theme['menus'] as $key => $name): ?>
		<li><?php echo $this->Html->link($name, array('controller' => 'settings', 'action' => 'menu', $key)); ?></li>
	<?php endforeach ?>
	</ul>
<?php else: ?>
	<p><?php __('No menus defined in this theme'); ?></p>
<?php endif ?>