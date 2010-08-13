<ul id="menu" class="rounded-10">
	<li><?php echo $this->Html->link(__('Home', true), '/'); ?></li>
	<li><?php echo $this->Html->link(__('Reviews', true), array('controller' => 'posts', 'action' => 'index', 'type' => 'BookReviews')); ?></li>
	<li><a href="#menu-link">About</a></li>
	<li><?php echo $this->Html->link(__('Notepad', true), array('controller' => 'posts', 'action' => 'index', 'type' => 'Journal')); ?></li>
	<li><a href="#menu-link">Contact</a></li>
</ul>