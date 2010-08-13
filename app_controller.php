<?php
App::import(array('type' => 'File', 'name' => 'Settings', 'file' => APP . 'config/settings.php'));
class AppController extends Controller {
	
	private $__userLevel;
	/**
	 * Default Helpers
	 *
	 * @var array helper names
	 */
	var $helpers = array('Text', 'Html', 'Form', 'Javascript', 'Session', 'Blogmill', 'BlogmillForm', 'Time');
	var $components = array('Session', 'Acl',
		'Auth' => array(
			'authorize' => 'controller',
			'loginAction' => array('controller' => 'users', 'action' => 'login', 'dashboard' => true),
			'logoutAction' => array('controller' => 'users', 'action' => 'logout', 'dashboard' => true),
			'logoutRedirect' => '/'
		)
	);
	var $postTypes = array();
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
		$this->__setupTypes();
		$this->__loadPageInfo();
	}
	
	public function isAuthorized() {
		$aro = 'visitor';
		if ($this->Auth->user('id')) {
			$aro = array('model' => 'User', 'foreign_key' => $this->Auth->user('id'));
		}
		// $aro = $this->Acl->Aro->find(array('model' => 'User', 'foreign_key' => $this->Auth->user('id')));
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
		$this->layout = isset($this->params['prefix']) ? $this->params['prefix'] : 'default';
		$this->__setupCurrentTheme();
	}
	
	/**
	 * This function loads the different available post types defined in the plugins.
	 * Use $postTypes in the view
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	private function __setupTypes() {
		$plugins = Configure::listObjects('plugin');
		$postTypes = array();
		foreach ($plugins as $i => $plugin) {
			$class = "{$plugin}Settings";
			App::import(
				array(
					'type' => 'File',
					'name' => $class,
					'file' => APP . 'plugins' . DS . Inflector::underscore($plugin) . DS . 'config' . DS . 'settings.php'
				)
			);
			if (class_exists($class)) {
				$class = new $class;
				if (isset($class->types) && is_array($class->types)) {
					foreach ($class->types as $type => $definition) {
						$postTypes[$plugin][$type] = $definition;
					}
				}
			} else {
				unset($plugins[$i]);
			}
		}
		$this->postTypes = $postTypes;
		$this->set(compact('postTypes'));
	}

	/**
	 * Sets up the theme for public pages
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	private function __setupCurrentTheme() {
		$activeThemePlugin = $this->__getActiveThemePluginName();
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
			$currentPage = $this->pageInfo['page'];
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
	 * Returns the plugin name that defines the active Theme.
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	private function __getActiveThemePluginName()
	{
		return 'BookReviews';
	}
	
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
		switch (true) {
			case $this->name == 'Posts':
				return $this->action;
			default:
				return 'other';
		}
	}
}
?>