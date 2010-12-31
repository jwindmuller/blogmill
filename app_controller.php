<?php
App::import(array('type' => 'File', 'name' => 'BlogmillSettings', 'file' => APP . 'config/blogmill_settings.php'));
class AppController extends Controller {
	
	private $__userLevel;
	protected $_activeThemePlugin;
	/**
	 * Default Helpers
	 *
	 * @var array helper names
	 */
	var $helpers = array('Text', 'Html', 'Form', 'Javascript', 'Session', 'Blogmill', 'BlogmillForm', 'Time');
	var $components = array('Session', 'Acl', 'RequestHandler',
		'Auth' => array(
			'authorize' => 'controller',
			'loginAction' => array('controller' => 'users', 'action' => 'login', 'dashboard' => true),
			'logoutAction' => array('controller' => 'users', 'action' => 'logout', 'dashboard' => true),
			'logoutRedirect' => '/'
		)
	);
	var $postTypes = array();
	var $themes = array();
	var $pageInfo = array();
	
	/**
	 * Before Filter callback. Sets available types for dashboard
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	public function beforeFilter() {
		// $this->Auth->allow('*');
		if ($this->Auth) {
			if ($this->Auth->user()) {
				// debug($this->Auth->user());
			} else {
				if ($this->isAuthorized()) {
					$this->Auth->allow('*');
				}
			}
		}
		$this->__loadTypesAndThemes();
		$this->__loadPageInfo();
		$this->__setupCurrentTheme();
	}
	
	public function isAuthorized() {
		$aro = 'visitor';
		if ($this->Auth->user('id')) {
			$aro = array('model' => 'User', 'foreign_key' => $this->Auth->user('id'));
		}
		$isAuthorized = $this->Acl->check($aro,'controllers/' . $this->name . '/' . $this->action);
		return $isAuthorized;
	}
	
	/**
	 * Setup layout and types
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	public function beforeRender() {
		if (isset($this->params['prefix'])) {
			$this->layout = $this->params['prefix'];
		}
	}
	
	/**
	 * This function loads the different post types and themes defined in the plugins.
	 * For post types: Use $postTypes in the views and $this->postTypes in the controllers.
	 * For themes: Use $themes in the views and $this->themes in the controllers.
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	private function __loadTypesAndThemes() {
		$plugins = Configure::listObjects('plugin');
		$postTypes = $themes = array();
		foreach ($plugins as $i => $plugin) {
			$class = "{$plugin}Settings";
			$plugin_path = APP . 'plugins' . DS . Inflector::underscore($plugin);
			App::import(
				array(
					'type' => 'File',
					'name' => $class,
					'file' => $plugin_path . DS . 'config' . DS . 'settings.php'
				)
			);
			if (class_exists($class)) {
				$class = new $class;
				if (isset($class->theme)) {
					$class->theme['plugin'] = $plugin;
					$class->theme['id'] = md5($plugin_path);
					$themes[$class->theme['id']] = $class->theme;
				}
				if (isset($class->types) && is_array($class->types)) {
					foreach ($class->types as $type => $definition) {
						$postTypes[$plugin][$type] = $definition;
					}
				}
			} else {
				unset($plugins[$i]);
			}
		}
		$this->themes = $themes;
		$this->postTypes = $postTypes;
		$this->set(compact('postTypes', 'themes'));
	}
	
	/**
	 * Sets up the theme for public pages
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	private function __setupCurrentTheme() {
		$this->_activeThemePlugin = $activeThemePlugin = $this->__getActiveThemePluginName();
		$this->set(compact('activeThemePlugin'));
		$pluginSettingsClass = "{$activeThemePlugin}Settings";
		App::import(
			array(
				'type' => 'File',
				'name' => $pluginSettingsClass,
				'file' => APP . 'plugins' . DS . Inflector::underscore($activeThemePlugin) . DS . 'config' . DS . 'settings.php'
			)
		);
		if (class_exists($pluginSettingsClass)) {
			// Tell Cake Where to find the theme layout
			$_app = App::getInstance();
			array_unshift($_app->views, APP . 'plugins' . DS . Inflector::underscore($activeThemePlugin) . DS . 'views' . DS);

			// Create the settigns class and register it
			$pluginSettings = new $pluginSettingsClass;
			ClassRegistry::addObject($pluginSettingsClass, $pluginSettings);
			$themeInfo = $pluginSettings->theme;
			$currentPage = $this->pageInfo['page'][0];
			if (isset($themeInfo['layouts'][$currentPage])) {
				$layouts = $themeInfo['layouts'];
				if (isset($layouts[$currentPage]['data'])) {
					$requiredData = $layouts[$currentPage]['data'];
					$postModel = ClassRegistry::init('Post');
					$themeData = array();
					foreach ($requiredData as $index => $definition) {
						$options = array(
							'conditions' => array('type' => @$definition['type']),
							'limit' => $definition['limit']
						);
						if (isset($definition['order'])) {
							$options['order'] = $definition['order'];
						}
						$themeData[$index] = $postModel->find('all', $options);
					}
					$this->set(compact('themeData'));
				}
				if (isset($layouts[$currentPage]['name'])) {
					$this->layout = $layouts[$currentPage]['name'];
				}
			}
		}
	}
	
	/**
	 * Returns the name of the plugin that has the currently active Theme.
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	private function __getActiveThemePluginName() {
		$Settings = ClassRegistry::init('Setting');
		$theme_id = $Settings->get('active_theme');
		if (!isset($this->themes[$theme_id]['plugin'])) {
			return false;
		}
		return $this->themes[$theme_id]['plugin'];
	}
	
	/**
	 * Sets up information for the current status and type of page.
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	private function __loadPageInfo() {
		$this->pageInfo =  array(
			'page' => $this->__currentPage()
		);
	}
	
	/**
	 * Returns the page type.
	 * This eventually will return things like: home (home page), post_listing (an index of posts), static (static pages)
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	private function __currentPage() {
		$page = 'other';
		switch (true) {
			case $this->name == 'Posts' && $this->action == 'home':
				$page = 'home';
				break;
			case $this->name == 'Posts':
				$page = 'inner';
				if ($this->action == 'index') $page .= ' listing';
				if ($this->action == 'view') $page .= ' single';
				break;
		}
		return explode(' ', $page);
	}
}
?>