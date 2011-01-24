<?php 
class BlogmillComponent extends Object {
	
	private $Controller;
	private $Settings;
	public $themes;
	public $postTypes;
	
	public function initialize(&$controller) {
		$this->Controller = $controller;
		$this->Settings = ClassRegistry::init('Setting');
		// Setup current page's information
		$this->__loadPageInfo();
		// Loads all available post types and themes
		$this->__loadTypesAndThemes();
		// Sets up the current theme
		$this->__setupCurrentTheme();
		$this->__loadHelpers();
	}
	
	/**
	 * Sets up information for the current status and type of page.
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	private function __loadPageInfo() {
		$this->Controller->pageInfo =  array(
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
			case $this->Controller->name == 'Posts' && $this->Controller->action == 'home':
				$page = 'home';
				break;
			case $this->Controller->name == 'Posts':
				$page = 'inner';
				if ($this->Controller->action == 'index') $page .= ' listing';
				if ($this->Controller->action == 'view') $page .= ' single';
				break;
		}
		return explode(' ', $page);
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
		$this->themes = $this->Controller->themes = $themes;
		$this->postTypes = $this->Controller->postTypes = $postTypes;
		$this->Controller->set(compact('themes', 'postTypes'));
	}
	
	/**
	 * Return the plugin name of the active theme.
	 *
	 * @return string
	 * @author Joaquin Windmuller
	 */
	private function __activeThemePlugin() {
		$theme_id = $this->Settings->get('active_theme');
		if (!isset($this->themes[$theme_id]['plugin'])) {
			return false;
		}
		return $this->themes[$theme_id]['plugin'];
	}
	
	
	private function __activeThemeSettings() {
		$plugin = $this->__activeThemePlugin();
		$pluginSettingsClass = "{$plugin}Settings";
		// var_dump($pluginSettingsClass);
		
		$pluginSettings = ClassRegistry::init($pluginSettingsClass);
		if (!$pluginSettings->activated) {
			// Tell Cake Where to find the theme layout
			$_app = App::getInstance();
			array_unshift($_app->views, APP . 'plugins' . DS . Inflector::underscore($plugin) . DS . 'views' . DS);
			$pluginSettings = new $pluginSettingsClass;
			ClassRegistry::addObject($pluginSettingsClass, $pluginSettings);
		}
		$pluginSettings->activated = true;
		return array($plugin, $pluginSettings);
	}
	/**
	 * Sets up the theme for public pages
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	private function __setupCurrentTheme() {
		list($activeThemePlugin, $pluginSettings) = $this->__activeThemeSettings();

		if (!$activeThemePlugin) return;
		
		$themeInfo = $pluginSettings->theme;
		$currentPage = $this->Controller->pageInfo['page'][0];
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
				$this->Controller->set(compact('themeData'));
			}
			if (isset($layouts[$currentPage]['name'])) {
				$this->Controller->layout = $layouts[$currentPage]['name'];
			}
		}
	}

	/**
	 * Loads the helper files from the plugins as indicated by the current theme's plugin settings.
	 * BlogmillSettings->theme[layouts][xyz][helpers]
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	private function __loadHelpers() {      
		list($plugin, $pluginSettings) = $this->__activeThemeSettings();

		if (!$pluginSettings) return;

		$themeInfo = $pluginSettings->theme;
		$currentPage = $this->Controller->pageInfo['page'][0];
		if (isset($themeInfo['layouts'][$currentPage])) {
			$layouts = $themeInfo['layouts'];
			if (isset($layouts[$currentPage]['helpers'])) {
				$helpers = $layouts[$currentPage]['helpers'];
				foreach ($helpers as $helper) {
					list($pluginName, $helper) = explode('.', $helper);
					App::import(
						array(
							'type' => 'Helper',
							'name' => $helper . 'Helper',
							'file' => APP . 'plugins' . DS . Inflector::underscore($pluginName) . DS . 'views' . DS . 'helpers' . DS . strtolower($helper) . '.php'
						)
					);
					$this->Controller->helpers[] = strtolower($helper);
				}
			}
		}
	}
}