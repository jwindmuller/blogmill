<?php
class BlogmillFormHelper extends AppHelper {
	public $helpers = array('Html', 'Form', 'JavaScript');
	private $__typeMap = array(
		'html' => false,
		'image' => false,
		'longtext' => false,
		'values' => false
	);
	
	public function input($field) {
		$Post = ClassRegistry::init('Post');
		if (!isset($Post->fields[$field])) {
			return $this->Form->input($field);
		}
		$type = $typeDefinition = $Post->fields[$field];
		$label = false;
		$class = 'input ' . $type;
		if (is_array($type)) {
			if(isset($type['label'])) {
				$label = $type['label'];
			}
			$type = $type['type'];
			$class = 'input ' . $type;
		}
		if (is_array($type)) {
			$typeDefinition = $type;
			$type = $type['type'];
		}
		if (array_key_exists($type, $this->__typeMap)) {
			$typeDefinition['label'] = $label;
			if ($this->__typeMap[$type] === false)
				return $this->{"__$type"}($field, $typeDefinition);
			$type = $this->__typeMap[$type];
		}
		$options = compact('type') + array('div' => compact('class'));
		if ($label) {
			$options['label'] = $label;
		}
		return $this->Form->input($field, $options);
	}
	
	private function __image($field, $typeDefinition) {
		$Post = ClassRegistry::init('Post');
		$width = $height = false;
		if (isset($Post->validate[$field])) {
			$rules = $Post->validate[$field];
			foreach ($rules as $ruleName => $rule) {
				if (is_array($rule['rule']) && count($rule['rule']) >= 2) {
					$width = $rule['rule'][1];
					$height = @$rule['rule'][2];
					break;
				}
			}
		}
		$label = $typeDefinition['label'];
		if (!$label)
			$label = Inflector::humanize($field);
		if ($width || $height) {
			if ($width && $height) $label .= " (${width}x${height})";
			elseif ($width) $label .= " (${width}px width)";
			elseif ($height) $label .= " (${height}px height)";
		}
		return $this->Form->input($field, array('type' => 'file', 'label' => $label));
	}
	
	private function __values($field, $typeDefinition) {
		$this->Html->css('../js/jquery.ui.stars/jquery.ui.stars', null, array('inline' => false));
		$this->JavaScript->link('jquery.ui/jquery.ui.core', false);
		$this->JavaScript->link('jquery.ui/jquery.ui.widget', false);
		$this->JavaScript->link('jquery.ui.stars/jquery.ui.stars', false);
		$this->JavaScript->codeBlock(
			'$(function() {$("div.input.values").stars({inputType: "select", disableValue : false});})',
			array('inline' => false)
		);
		return $this->Form->input($field,
			array('type' => 'select', 'options' => range(0, $typeDefinition['count']), 'div' => array('class' => 'input values'))
		);
	}
	
	private function __html($field, $typeDefinition) {
		$this->JavaScript->link('jquery.tinymce/jquery.tinymce', false);
		return $this->Form->input($field, array('type' => 'textarea', 'div' => array('class' => 'input htmleditor')));
	}
	
	private function __longtext($field, $typeDefinition) {
		$Post = ClassRegistry::init('Post');
		$rules = $Post->validate[$field];
		$maxLength = false;
		foreach ($rules as $rule => $ruleDef) {
			if (isset($ruleDef['rule']) && is_array($ruleDef['rule']) && $ruleDef['rule'][0] == 'maxLength') {
				$maxLength =$ruleDef['rule'][1];
				break;
			}
		}
		$class = '';
		if ($maxLength !== false) {
			$class = 'maxlength' . time();
			$this->JavaScript->link('jquery.maxlength/jquery.maxlength.min', false);
			$this->JavaScript->codeBlock(
				'$(function() {$(".' . $class . ' textarea").maxlength({maxCharacters:' . $maxLength . ', statusText:""});})',
				array('inline' => false)
			);
		}
		return $this->Form->input($field, array('type' => 'textarea', 'div' => array('class' => 'input longtext ' . $class)));
	}
	
	public function inputs($for) {
		$View = ClassRegistry::init('View');
		$fields = $View->viewVars['formLayout'][$for];
		$out = "";
		foreach ($fields as $field) {
			$title = '';
			$class = $class_wrap = '';
			if (is_array($field)) {
				$title = sprintf('<strong class="title">%s</strong>', $field['title']); 
				$class = ' class="group"';
				$class_wrap = ' class="group-wrap"';
				if (isset($field['width'])) 
					$class_wrap .= ' style="width:' . $field['width'] . ';"';
				$field = $field['fields'];
			} else {
				$field = array($field);
			}
			$inputs = array();
			foreach ($field as $f) {
				$inputs[] = $this->input($f);
			}
			$out .= String::insert(
				'<div:wrap-class><div:class>:title :group-content</div></div>',
				array('group-content' => join("", $inputs), 'title' => $title, 'class' => $class, 'wrap-class' => $class_wrap)
			);
		}
		return $out;
	}
	
	/**
	 * Created the markup for a generic contact form.
	 *
	 * @return string markup
	 * @see ContactsController::send
	 * @see Modify Routes configuration for custom url
	 * @author Joaquin Windmuller
	 */
	public function contactForm() {
		$form = $this->Form->create('Message', array('url' => array('controller' => 'contacts', 'action' => 'send')));
		$form.= $this->Form->input('Name', array('label' => __('Name', true)));
		$form.= $this->Form->input('Email', array('label' => __('Email', true)));
		$form.= $this->Form->input('Subject', array('label' => __('Subject', true)));
		$form.= $this->Form->input('Message', array('label' => __('Message', true), 'type' => 'textarea'));
		$form.= $this->Form->end(__('Submit', true));
		return $form;
	}
}
