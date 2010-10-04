<?php $topMenu = $this->Blogmill->menu('top_menu'); ?>
<ul id="menu" class="rounded-10"> 
	<?php
		foreach ($topMenu as $menu) {
			printf('<li>%s</li>', $this->Html->link($menu['title'], $menu['url']));
		}
	?>
	<!-- <li><?php echo $this->Html->link(__('Home', true), '/'); ?></li>
	<li><?php echo $this->Html->link(__('Reviews', true), array('controller' => 'posts', 'action' => 'index', 'type' => 'BookReviews')); ?></li>
	<li><?php echo $this->Html->link(__('About', true), array('controller' => 'posts', 'action' => 'view', 'type' => 'Page', 'id' => 7, 'slug' => 'paje')) ?></li>
	<li><?php echo $this->Html->link(__('Notepad', true), array('controller' => 'posts', 'action' => 'index', 'type' => 'Journal')); ?></li>
	<li><a href="#menu-link">Contact</a></li> -->
</ul>