<?php
App::import('Sanitize');
class BlogmillHelper extends AppHelper {
	
	var $helpers = array('Html', 'Text', 'Javascript');
	
    public function beforeRender() {
        $view =& ClassRegistry::getObject('view');
        $scripts_for_bottom = $view->viewVars['scripts_for_bottom'];
        $scripts = array();
        foreach( $scripts_for_bottom as $url ) {
            $scripts[] = $this->Javascript->link($url);
        }
        $view->viewVars['scripts_for_bottom'] = implode("\n\t", $scripts);
    }

	public function postURL($post, $options=array()) {
		if (isset($post['Post'])) {
			$post = $post['Post'];
		}
		$action = 'view';
		if (isset($options['action'])) {
			$action = $options['action'];
		}
		return array(
			'controller' => 'posts',
			'action' => $action,
			'id' => $post['id'], 'slug' => $post['slug'], 'type' => $post['type'],
			'dashboard' => false
		) + $options;
	}
	public function postLink($post, $options=array(), $html_options=array()) {
		if (isset($post['Post'])) {
			$post = $post['Post'];
		}
		$display = $post['display'];
		if (isset($options['display'])) {
			$display=$options['display'];
			unset($options['display']);
		}
		$default_options = array(
			'action' => 'view'
		);
		if (!is_array($options)) $options=array();
		$options = array_merge($default_options, $options);
		list($plugin, $type) = explode('.', $post['type']);
		return $this->Html->link($display, $this->postURL($post, $options), $html_options);
	}
	
	public function postEditLink($post, $title = null, $html_options = array()) {
		$title = is_null($title) ? __('edit this post', true) : $title;
		list($plugin, $type) = explode('.', $post['Post']['type']);
		$options = array('class' => 'call-to-action-link edit');
		if (isset($html_options['class'])) {
			$options['class'].= ' ' . $html_options['class'];
			unset($html_options['class']);
		}
		$options = array_merge($options, $html_options);
		return $this->Html->link(
			$title, array(
				'controller' => 'posts',
				'action' => 'edit',
				$plugin, $type,
				$post['Post']['id']
			), $options
		);
	}
	public function postDeleteLink($post, $title=null) {
		$title = is_null($title) ? __('Delete', true) : $title;
		return $this->Html->link($title,
			array(
				'controller' => 'posts',
				'action' => 'delete',
				$post['Post']['id'],
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
		$excerpt = $post['Post']['excerpt'];
		if (empty($excerpt)) {
			$content = Sanitize::html($post['Post']['content'], array('remove' => true));
			$excerpt = $this->Text->truncate($content, $lenght, array('html' => true, 'ending' => '...'));
		}
		return $excerpt;
	}
	
	public function field($post, $field) {
		if (!isset($post['Post'][$field])) return false;
		return $post['Post'][$field];
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

}
?>
