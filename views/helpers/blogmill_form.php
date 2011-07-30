<?php
class BlogmillFormHelper extends AppHelper {
	public $helpers = array('Html', 'Form', 'JavaScript', 'Session');
	private $__typeMap = array(
		'html' => false,
		'image' => false,
		'longtext' => false,
		'values' => false
	);
	
	public function input($field) {
		$Post = ClassRegistry::init('Post');
        $options = array();
        if (is_array($field)) {
            $options = current($field);
            $field = key($field);
        }
		if (!isset($Post->fields[$field])) {
			return $this->Form->input($field, $options);
		}
		$type = $typeDefinition = $Post->fields[$field];
		if (is_array($type)) {
            unset($typeDefinition['type']);
			$type = $type['type'];
		} else {
            $typeDefinition = array();
        }
        $class = 'input ' . $type;
		if (array_key_exists($type, $this->__typeMap)) {
			if ($this->__typeMap[$type] === false)
				return $this->{"__$type"}($field, $typeDefinition);
			$type = $this->__typeMap[$type];
		}
		$options = $options + compact('type') + array('div' => compact('class')) + $typeDefinition;
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
        $label = Inflector::humanize($field);
		if (isset($typeDefinition['label'])) {
            $label = $typeDefinition['label'];
        }
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
        $max = $typeDefinition['count'];
        unset($typeDefinition['count']);
		return $this->Form->input($field,
			array('type' => 'select', 'options' => range(0, $max), 'div' => array('class' => 'input values')) + $typeDefinition
		);
	}
	
	private function __html($field, $typeDefinition) {
    	$view =& ClassRegistry::getObject('view');
        if ( isset($view->viewVars['editor_loaded']) ) {
            $fieldValue = $this->__loadCustomEditor($field, $view->viewVars['editor_loaded']);
            $typeDefinition['value'] = $fieldValue;
        } else  {
            $this->JavaScript->link('jquery.tinymce/jquery.tinymce', false);
        }
	    return $this->Form->input(
            $field,
            array(
                'type' => 'textarea',
                'div' => array('class' => 'input htmleditor')
            ) + $typeDefinition
        );
	}
    
    private function __loadCustomEditor($field, $editor) {
        $plugin = $editor;
        $editorName = $plugin . 'Editor';
        $className = $editorName . 'Helper';
	    $view =& ClassRegistry::getObject('view');
        $htmlField = $this->data['Post'][$field];
        $view->{$editorName}->enable();
        return $view->{$editorName}->transformContent( $htmlField );
    }
	
	private function __longtext($field, $typeDefinition) {
		$Post = ClassRegistry::init('Post');
        $rules = array();
        if (isset($Post->validate[$field])) {
    		$rules = $Post->validate[$field];
        }
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
		return $this->Form->input($field, array('type' => 'textarea', 'div' => array('class' => 'input longtext ' . $class)) + $typeDefinition);
	}
	
	public function inputs($for) {
		$View = ClassRegistry::init('View');
		$fields = $View->viewVars['formLayout'][$for];
		$out = "";
		foreach ($fields as $field) {
			$title = '';
			$class = $class_wrap = '';
			if (is_array($field)) {
                $title = '';
                if (isset($field['title']))
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
				'<div:wrap-class><div:class>:title :group-content<span class="clear">&nbsp;</span></div></div>',
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
		$form = $this->Form->create('Contact', array('url' => array('controller' => 'contacts', 'action' => 'send')));
		$form.= $this->Form->input('name', array('label' => __('Name', true)));
		$form.= $this->Form->input('email', array('label' => __('Email', true)));
		$form.= $this->Form->input('subject', array('label' => __('Subject', true)));
		$form.= $this->Form->input('message', array('label' => __('Message', true), 'type' => 'textarea'));
		$form.= $this->Form->end(__('Submit', true));
		return $form;
	}
}
