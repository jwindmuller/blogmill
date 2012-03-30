<?php
    $url = array('action' => 'menu_change_item');
    if (!isset($i)) {
        $url = array('action' => 'menu', $menu_name);
    }
	echo $this->Form->create('Settings', compact('url')), 
	     !isset($i) ? '' : $this->Form->input('i', array('value' => $i, 'type' => 'hidden' )),
	     $this->Form->input('menu_name', array('value' => @$menu_name, 'type' => 'hidden' )),
	     $this->Form->input('title', array('label' => __('Title', true), 'value' => @$item['title'])),
	     $this->Form->input('url', array('value' => @$item['url'], 'class' => 'settings-url form-' . $i)),
         $this->Form->submit(__('Update', true), array('div' => false, 'class' => 'submit')),
         ' ',
		 !isset($i) ? '' : $this->Html->link(__('Cancel', true), array('action' => 'menu', $menu_name)),
		 $this->Form->end();
