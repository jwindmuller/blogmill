<h1><span><?php __('Add to a menu'); ?></span></h1>
<?php
foreach ($menus as $menu_name => $title) {
	echo $this->Html->link($title, array('controller' => 'settings', 'action' => 'add_to_menu', $menu_name, 'post'=> $this->Blogmill->field($post, 'id')));
}
?>