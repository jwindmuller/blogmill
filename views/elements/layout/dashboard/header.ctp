<header id="top">
	<nav>
		<?php
            echo $this->Html->link(isset($site_name) ? $site_name : __('Go to Site', true), '/', array('class' => 'home')); ?>
		<?php if ($this->Session->check('Auth.User')): ?>
		<ul>
			<li<?php echo $this->name == 'Posts' ? ' class="current"' : ''; ?>><?php
				echo $html->link(
					__('Posts', true),
					array('controller' => 'posts', 'action' => 'index')
				);
			?></li>
            <?php if($commentsEnabled) : ?>
			<li<?php echo $this->name == 'Comments' ? ' class="current"' : ''; ?>><?php
				echo $html->link(
					__('Comments', true),
					array('controller' => 'comments')
				);
			?></li>
            <?php endif; ?>
			<li<?php echo $this->name == 'Categories' ? ' class="current"' : ''; ?>><?php
				echo $html->link(
					__('Categories', true),
					array('controller' => 'categories', 'action' => 'index')
				);
			?></li>
            <li<?php echo $this->name == 'Users' ? ' class="current"' : ''; ?>><?php
                echo $this->Html->link(
					__('Users', true),
					array('controller' => 'users', 'action' => 'index')
				);
            ?></li>
            <?php foreach( $adminMenus as $plugin => $menu): ?>
                <?php foreach($menu as $item => $label) : ?>
                <?php
                    if (is_numeric($item)) {
                        $item = $label;
                    }
                    $active  =  $this->name == 'Plugins' &&
                                $this->action == 'dashboard_page' &&
                                $this->params['pass'][0] == $plugin &&
                                $this->params['pass'][1] == $item;
                ?>
                <li<?php echo $active ? ' class="current"' : ''; ?>><?php
                        echo $this->Html->link(
                            $label,
				    	    array('controller' => 'plugins', 'action' => 'page', 'dashboard' => true, $plugin, $item)
				        );
                    ?>
                </li>
                <?php endforeach; ?>
            <?php endforeach; ?>           
			<li<?php echo $this->name == 'Settings' ? ' class="current"' : ''; ?>><?php
			 	echo $this->Html->link(
					__('Settings', true),
					array('controller' => 'settings', 'action' => 'index')
				);
				?>
				<ul>
					<li><?php
						echo $this->Html->link(
							__('Current Theme', true),
							array('controller' => 'settings', 'action' => 'themes')
						);
					?></li>
					<li><?php
						echo $this->Html->link(
							__('Plugins', true),
							array('controller' => 'settings', 'action' => 'plugins')
						);
					?></li>
				</ul>
			</li>
			<?php if ($this->Session->read('Auth.User.username')): ?>
			<li class="logout"><?php
				echo $this->Html->link(
					sprintf(__('Logout (%s)', true), $this->Session->read('Auth.User.username')),
					array('controller' => 'users', 'action' => 'logout')
				);
			?></li>
		</ul>
		<?php endif; ?>
	</nav>	
<?php endif ?>
</header>
