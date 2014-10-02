<?php
App::import('Sanitize');
class BlogmillHelper extends AppHelper {
	
	var $helpers = array('Html', 'Text', 'Javascript', 'Session');
	
    public function beforeRender() {
        $view =& ClassRegistry::getObject('view');
        $scripts_for_bottom = array();
        if ( isset($view->viewVars['scripts_for_bottom']) && is_array($view->viewVars['scripts_for_bottom']) ) {
            $scripts_for_bottom = $view->viewVars['scripts_for_bottom'];
        }
        $scripts = array();
        foreach( $scripts_for_bottom as $url ) {
			$type = 'js';
			if ( is_array($url) ) {
				list($url, $type) = $url;
			}
			$script = '';
			switch ($type) {
				case 'js':
					$script = $this->Javascript->link($url);
					break;
				case 'css':
					$script = $this->Html->css($url);
					break;
				default:
					$script = '<!-- Unknown type: ' . $type . '. Did not load: ' . $url . ' -->';
					break;
			}
           	$scripts[] = $script;
        }
        $view->viewVars['scripts_for_bottom'] = implode("\n\t", $scripts);
    }

	public function postURL($post, $options=array()) {
		$action = 'view';
		if (isset($options['action'])) {
			$action = $options['action'];
		}
		return array(
			'controller' => 'posts',
			'action' => $action,
            'slug' => $this->field($post, 'slug'),
            'date' => str_replace(' ', '@', $this->field($post, 'created')),
            'type' => $this->field($post, 'type'),
			'dashboard' => false
		) + $options;
	}

    public function postShortURL($post) {
        return array(
            'controller' => 'posts',
            'action' => 'view',
            'id'   => $this->field($post, 'id'),
            'dashboard' => false
        );
    }
	public function postLink($post, $options=array(), $html_options=array()) {
		$display = $this->field($post, 'display');
		if (isset($options['display'])) {
			$display = $this->field($post, $options['display']);
			if ($display === false) {
				$display = $options['display'];
			}
			unset($options['display']);
		}
		$default_options = array(
			'action' => 'view',
            'before' => '',
            'after' => ''
		);
		if (!is_array($options)) $options=array();
		$options = array_merge($default_options, $options);
        unset($default_options);
        $display = $options['before']. $display . $options['after'];
        unset($options['before'], $options['after']);
		list($plugin, $type) = explode('.', $this->field($post, 'type'));
		$html_options['escape'] = false;
		return $this->Html->link($display, $this->postURL($post, $options), $html_options);
	}
	
	public function postEditLink($post, $title = null, $html_options = array()) {
		$title = is_null($title) ? __('edit this post', true) : $title;
		list($plugin, $type) = explode('.', $this->field($post, 'type'));
		$options = array('class' => 'call-to-action-link edit');
		if (isset($html_options['class'])) {
			$options['class'].= ' ' . $html_options['class'];
			unset($html_options['class']);
		}
		$options = array_merge($options, $html_options);
		return $this->Html->link(
			$title, array(
                'dashboard' => true,
				'controller' => 'posts',
				'action' => 'edit',
				$plugin, $type,
				$this->field($post, 'id')
			), $options
		);
	}
	public function postDeleteLink($post, $title=null) {
		$title = is_null($title) ? __('Delete', true) : $title;
		return $this->Html->link($title,
			array(
				'controller' => 'posts',
				'action' => 'delete',
				$this->field($post, 'id'),
				'dashboard' => true
			)
		);
	}
	
	/**
	 * Returns the excerpt of a post
	 *
	 * @param string $post post data
	 * @return string exceprt
	 * @author Joaquin Windmuller
	 */
	public function excerpt($post, $lenght = 140) {
		$excerpt = $this->field($post, 'excerpt');
        $excerpt = $this->Text->truncate(
            $excerpt, $lenght,
            array('html' => true, 'exact' => false, 'ending' => '...')
        );
		return $excerpt;
	}
	
	public function field($post, $field) {
		if (!isset($post['Post'][$field])) return false;
		$postType = $post['Post']['type'];
		$View = ClassRegistry::init('View');
		$postTypes = $View->viewVars['postTypes'];
		$fieldDefinitions =  Set::extract($postTypes, $postType);
		$field_value  = $post['Post'][$field];
		return $field_value;
	}
	
	public function guide($post) {
		return $this->field($post, 'guide');
	}

	public function image($post, $name, $html_options = array()) {
		$View = ClassRegistry::init('View');
		$fieldDef = Set::extract($View->viewVars['postTypes'], $this->field($post, 'type') . '.fields.' . $name);
		if (isset($fieldDef['width'])) {
			$html_options['width'] = $fieldDef['width'];
		}
		if (isset($fieldDef['height'])) {
			$html_options['height'] = $fieldDef['height'];
		}
		$ext = $this->field($post, $name);
		if ($ext === false) return; 
		$name = $name . '.' .  $ext;
		$guide = $this->guide($post);
		return $this->Html->image("/files/{$guide}/{$name}", $html_options);
	}

	public function menu($menu_name) {
		$View = ClassRegistry::init('View');
		$menu_setting_key = $View->viewVars['activeThemePlugin'] . '.menu.' . $menu_name;
		$Settings = ClassRegistry::init('Setting');
		$menu = $Settings->find('first', array('conditions' => array('name' => $menu_setting_key)));
		$menu = $menu ? unserialize($menu['Setting']['value']) : array();
		return $menu;
	}

    public function isOwner($post) {
        return $this->field($post, 'user_id') == $this->Session->read('Auth.User.id');
    }

    public function actionsList($actionsList) {
        $html = array();
        foreach ($actionsList as $plugin => $actions) {
            $plugin = Inflector::underscore($plugin);
            foreach ($actions as $action) {
                $html[] = sprintf(
                    '<li>%s</li>',
                    $this->Html->link(
                        $action['label'],
                        array_merge(array('action' => 'execute_action', $plugin), $action['url'])
                    )
                );
            }
        }
        return join($html);
    }
}