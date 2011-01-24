<?php
class SettingsController extends AppController {
	public $name = 'Settings';
	
	public function beforeRender() {
		$this->set('theme', $this->__themeSettings());
		parent::beforeRender();
	}
	public function dashboard_index() {}
	
	private function __themeSettings() {
		$themePluginSettings = ClassRegistry::getObject("{$this->_activeThemePlugin}Settings");
		return $themePluginSettings->theme;
	}
	
	private function __themeMenu($menu_name) {
		$menu_settings_key = $this->_activeThemePlugin . '.menu.' . $menu_name;
		$menu = $this->Setting->find('first', array('name' => $menu_settings_key));
		$id = null;
		if ($menu) {
			$id = $menu['Setting']['id'];
			if (isset($menu['Setting']['value'])) {
				$menu = unserialize($menu['Setting']['value']);	
			}
		} else {
			$menu = array();
		}
		return array($menu_settings_key, $menu,$id);
	}
	
	private function __themeMenus() {
		$theme = $this->__themeSettings();
		return $theme['menus'];
	}
	
	public function dashboard_menu($menu_name) {
		list($menu_settings_key, $menu , $id) = $this->__themeMenu($menu_name);
		if (!empty($this->data)) {
			$menu = array_values($this->data);
			$last = end($menu);
			if (empty($last['title']) || empty($last['url'])) {
				array_pop($menu);
			}
			$success = $this->Setting->save(array('id' => $id, 'name' => $menu_settings_key, 'value' => serialize($menu)));
			if ($this->RequestHandler->isAjax()) {
				die($success ? 'OK' : 'ERROR');
			} else {
				$flashMessage = $success ? __('Menu updated!', true) : __('Could not update the menu', true);
				$this->Session->setFlash($flashMessage);
				$this->redirect(array('action' => 'dashboard_menu', $menu_name));
			}
		}
		$themePluginSettings = ClassRegistry::getObject("{$this->_activeThemePlugin}Settings");
		$theme = $themePluginSettings->theme;
		$menu_title = $theme['menus'][$menu_name];
		$this->set(compact('menu_name', 'menu_title', 'menu'));
	}
	
	public function dashboard_add_to_menu($menu_name = null) {
		if (!isset($this->params['named']['post'])) {
			$this->Session->setFlash(__('Invalid access, no post selected', true));
			$this->redirect(array('controller' => 'posts', 'action' => 'index'));
		}
		$PostModel = ClassRegistry::init('Post');
		$post = $PostModel->read(null, $this->params['named']['post']);
		if ($menu_name) {
			list($menu_settings_key, $menu, $id) = $this->__themeMenu($menu_name);
			$menu[] = array(
				'title' => $post['Post']['display'],
				'url' => array(
					'controller' => 'posts',
					'action' => 'view', 'dashboard' => false,
					'id' => $post['Post']['id'],
					'slug' => $post['Post']['id'],
					'type' => $post['Post']['type']
				)
			);
			if ($this->Setting->save(array('id' => $id, 'name' => $menu_settings_key, 'value' => serialize($menu)))) {
				$this->Session->setFlash(sprintf(__('Menu updated!', true)));
				$this->redirect(array('action' => 'dashboard_menu', $menu_name));
			} else {
				$this->Session->setFlash(sprintf(__('Could not updated the menu!', true)));
				$this->redirect(array('controller' => 'posts', 'action' => 'index'));
			}
		}
		$menus = $this->__themeMenus();
		$this->set(compact('menus', 'post'));
	}
	
	public function dashboard_remove_from_menu($menu_name, $index) {
		list($menu_settings_key, $menu, $id) = $this->__themeMenu($menu_name);
		unset($menu[$index]);
		$menu = array_values($menu);
		if ($this->Setting->save(array('id' => $id, 'name' => $menu_settings_key, 'value' => serialize($menu)))) {
			$this->Session->setFlash(sprintf(__('Menu updated!', true)));
			$this->redirect(array('action' => 'dashboard_menu', $menu_name));
		} else {
			$this->Session->setFlash(sprintf(__('Could not updated the menu!', true)));
			$this->redirect(array('controller' => 'posts', 'action' => 'index'));
		}
	}
	
	public function dashboard_change_theme() {
		if(isset($this->params['named']['theme'])) {
			$theme_id = $this->params['named']['theme'];
			foreach ($this->themes as $theme) {
				if ($theme['id'] == $theme_id) {
					if ($this->Setting->store('active_theme', $theme_id)) {
						$this->Session->setFlash(__('Theme Changed!', true));
						$this->redirect(array('controller' => 'settings', 'action' => 'index'));
					} else {
						$this->Session->setFlash(__('Could not change the theme, please try again.', true));
					}
				}
			}
		}
	}
	
	public function dashboard_plugins() {
		
	}
}