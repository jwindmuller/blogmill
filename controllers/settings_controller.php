<?php
class SettingsController extends AppController {
	public $name = 'Settings';
	
	public function beforeRender() {
		$this->set('theme', $this->__themeSettings());
		parent::beforeRender();
	}
	public function dashboard_index() {
		$settings = $this->Blogmill->pluginSettings($this->activeThemePlugin);
        $activeTheme = $settings->theme['name'];
        $this->set(compact('activeTheme'));
	}
	
	private function __themeSettings() {
		$themePluginSettings = ClassRegistry::getObject("{$this->activeThemePlugin}Settings");
        if ($themePluginSettings) {
    		return $themePluginSettings->theme;
        }
        return null;
	}
	
	private function __themeMenu($menu_name) {
		$menu_settings_key = $this->activeThemePlugin . '.menu.' . $menu_name;
        $menu = $this->Setting->findByName($menu_settings_key);
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
		list($menu_settings_key, $menu, $id) = $this->__themeMenu($menu_name);
		if (!empty($this->data)) {
			$menu[] = $this->data['Settings'];
			$success = $this->Setting->store($menu_settings_key, serialize($menu));
			if ($this->RequestHandler->isAjax()) {
				die($success ? 'OK' : 'ERROR');
			} else {
				$flashMessage = $success ? __('Menu updated!', true) : __('Could not update the menu', true);
				$this->Session->setFlash($flashMessage);
				$this->redirect(array('action' => 'dashboard_menu', $menu_name));
			}
		}
		$themePluginSettings = ClassRegistry::getObject("{$this->activeThemePlugin}Settings");
		$theme = $themePluginSettings->theme;
		$menu_title = $theme['menus'][$menu_name];
		$menu_description = '';
		if (is_array($menu_title)) {
			$menu_description = $menu_title['description'];
			$menu_title = $menu_title['name'];
		}
		$edit = false;
		if (isset($this->params['named']['edit'])) {
			$edit = $this->params['named']['edit'];
		}
		$this->set(compact('menu_name', 'menu_title', 'menu_description', 'menu', 'edit'));
	}
	
	public function dashboard_menu_change_item() {
		$i = $this->data['Settings']['i'];
		$menu_name = $this->data['Settings']['menu_name'];
		$title = $this->data['Settings']['title'];
        $url = $this->data['Settings']['url'];

		list($menu_settings_key, $menu, $id) = $this->__themeMenu($menu_name);
		
		$menu[$i]['title'] = $title;
		$menu[$i]['url'] = $url;
		$success = $this->Setting->store($menu_settings_key, serialize($menu));
		if ($success) {
			$this->Session->setFlash(__('The menu was updated', true));
		} else {
			$this->Session->setFlash(__('Could not update the menu', true));
		}
		$this->redirect(array('controller' => 'settings', 'action' => 'menu', $menu_name, 'dashboard' => true));
	}
	
	public function dashboard_menu_move_item($menu_name,$direction,$i) {
		list($menu_settings_key, $menu, $id) = $this->__themeMenu($menu_name);
		if ($direction == 'up') {
			$swap = $i-1;
		} elseif($direction == 'down') {
			$swap = $i+1;
		}
		if(isset($menu[$i]) && isset($swap) && isset($menu[$swap])) {
			$item = $menu[$i];
			$menu[$i] = $menu[$swap];
			$menu[$swap] = $item;
		}
		$success = $this->Setting->store($menu_settings_key, serialize($menu));
		if ($success) {
			$this->Session->setFlash(__('The menu was updated', true));
		} else {
			$this->Session->setFlash(__('Could not update the menu', true));
		}
		$this->redirect(array('controller' => 'settings', 'action' => 'menu', $menu_name, 'dashboard' => true));
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
    
    public function dashboard_menu_reorder($menu_name) {
        $i = $this->data['Setting']['i'];
        $new_pos = $this->data['Setting']['new_pos'];
       	
        list($menu_settings_key, $menu, $id) = $this->__themeMenu($menu_name);
        $item = array($menu[$i]);
        unset($menu[$i]);
        array_splice($menu,$new_pos,0,$item);
        $this->set('saved', $this->Setting->store($menu_settings_key, serialize($menu)));
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
	
	public function dashboard_themes() {
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
	
	public function dashboard_plugins($plugin = false) {
		$this->set('configurable_plugins', $this->Blogmill->getConfigurablePlugins());
		if ($plugin) {
			$configurable_keys = $this->Blogmill->getConfigurableKeys($plugin);
			$this->set(compact('plugin', 'configurable_keys'));
			if (!empty($this->data)) {
				$errors = array();
				foreach ($this->data['Setting'] as $key => $value) {
					if (!$this->Setting->store("{$plugin}.{$key}", $value)) {
						$key = str_replace($plugin . '.', '', $key);
						$errors[$key] = $this->Setting->validationErrors['value'];
					}
				}
				if (empty($errors)) {
					$this->Session->setFlash(sprintf(__('Correctly saved the settings for plugin %s', true), $plugin));
					$this->redirect(array('controller' => 'settings', 'action' => 'plugins'));
				}
				$this->Setting->validationErrors = $errors;
			} else {
				foreach ($configurable_keys as $key => $value) {
					$this->data['Setting'][$key] = $this->Setting->get("{$plugin}.{$key}");
				}
			}
		}
	}
}
