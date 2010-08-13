<!DOCTYPE html>
<html lang="en">
<head>
	<?php echo $html->charset(); ?>
	<title>
		<?php __('Dashboard'); ?>
		<?php echo $title_for_layout; ?>
		- <?php __('Blogmill'); ?>
	</title>
	<script language="javascript" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
	<?php echo $javascript->link('fake_dropdown'); ?> 
	<?php echo $javascript->link('dashboard'); ?> 
	<?php echo $javascript->link('tiny_mce/tiny_mce'); ?> 
		<?php echo $javascript->link('tiny_mce/jquery.tinymce'); ?> 
	<?php echo $html->css('dashboard'); ?>
	<?php echo $scripts_for_layout; ?>
<body>
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
						array('controller' => 'comments', 'dashboard' => true, 'plugin' => null)
					);
				?></li>
				<li<?php echo $this->name == 'Categories' ? ' class="current"' : ''; ?>><?php
					echo $html->link(
						__('Categories', true),
						array('controller' => 'categories', 'dashboard' => true, 'plugin' => null)
					);
				?></li>
				<li><?php
					echo $this->Html->link(
						sprintf(__('Logout (%s)', true), $this->Session->read('Auth.User.username')),
						array('controller' => 'users', 'action' => 'logout')
					);
				?></li>
				<!-- <li><a href="#"><?php __('Settings') ?></a></li> -->
			</ul>
		</nav>
	</header>
	<div id="main">
		<aside id="sidebar">
			<?php echo $this->element('layout/dashboard/sidebar'); ?>
		</aside>
		<section id="content">
			<?php echo $session->flash(); ?>
			<?php echo $session->flash('auth'); ?>
			<div class="wrap">
				<?php echo $content_for_layout; ?>
			</div>
		</section>
	</div>
<footer><p>The footer</p></footer>
</body>
</html>
