<header id="top">
	<nav>
		<?php echo $this->Html->link(__('Go to Site', true), '/', array('class' => 'home')); ?>
		<ul>
			<li<?php echo $this->name == 'Posts' ? ' class="current"' : ''; ?>><?php
				echo $html->link(
					__('Posts', true),
					array('controller' => 'posts', 'action' => 'index')
				);
			?></li>
			<li<?php echo $this->name == 'Comments' ? ' class="current"' : ''; ?>><?php
				echo $html->link(
					__('Comments', true),
					array('controller' => 'comments')
				);
			?></li>
			<li<?php echo $this->name == 'Categories' ? ' class="current"' : ''; ?>><?php
				echo $html->link(
					__('Categories', true),
					array('controller' => 'categories')
				);
			?></li>
			<li<?php echo $this->name == 'Settings' && $this->action != 'dashboard_plugins' ? ' class="current"' : ''; ?>><?php
			 	echo $this->Html->link(
					__('Settings', true),
					array('controller' => 'settings', 'action' => 'index')
				);
			?></li>
			<li<?php echo $this->name == 'Settings' && $this->action == 'dashboard_plugins' ? ' class="current"' : ''; ?>><?php
				echo $this->Html->link(
					__('Plugins', true),
					array('controller' => 'settings', 'action' => 'plugins')
				);
			?></li>
			<?php if ($this->Session->read('Auth.User.username')): ?>
			<li class="logout"><?php
				echo $this->Html->link(
					sprintf(__('Logout (%s)', true), $this->Session->read('Auth.User.username')),
					array('controller' => 'users', 'action' => 'logout')
				);
			?></li>
			<?php endif; ?>
		</ul>
	</nav>
</header>