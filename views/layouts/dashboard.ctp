<!DOCTYPE html>
<html lang="en">
<head>
	<?php echo $html->charset(); ?>
	<title>
		<?php __('Dashboard'); ?>
		<?php echo $title_for_layout; ?>
		- <?php __('Blogmill'); ?>
	</title>
	<?php echo $this->Javascript->link('jquery'); ?>
	<!-- <script language="javascript" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script> -->
	<?php echo $javascript->link('fake_dropdown'); ?> 
	<?php echo $javascript->link('dashboard'); ?> 
	<?php echo $javascript->link('tiny_mce/tiny_mce'); ?> 
	<?php echo $javascript->link('tiny_mce/jquery.tinymce'); ?> 
	<?php echo $html->css('dashboard'); ?>
	<?php echo $html->css('jquery_ui_theme/style'); ?>
	<?php echo $this->Javascript->link('jquery.ui/jquery.ui.core'); ?>
	<?php echo $this->Javascript->link('jquery.ui/jquery.ui.widget'); ?>
	<?php echo $this->Javascript->link('jquery.ui/jquery.ui.mouse'); ?>
	<?php echo $this->Javascript->link('jquery.ui/jquery.ui.draggable'); ?>
	<?php echo $this->Javascript->link('jquery.ui/jquery.ui.droppable'); ?>
	<?php echo $this->Javascript->link('jquery.ui/jquery.ui.sortable'); ?>
	<?php echo $this->Javascript->link('jquery.ui/jquery.ui.position'); ?>
	<?php echo $this->Javascript->link('jquery.ui/jquery.ui.dialog'); ?>
	<?php echo $scripts_for_layout; ?>
<body>
	<header id="top">
		<nav>
			<?php echo $this->Html->link(__('Go to Site', true), '/', array('class' => 'home')); ?>
			<ul>
				<li<?php echo $this->name == 'Posts' ? ' class="current"' : ''; ?>><?php
					echo $html->link(
						__('Posts', true),
						array('controller' => 'posts')
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
				<li><?php echo $this->Html->link(
						__('Settings', true),
						array('controller' => 'settings', 'action' => 'index')
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
