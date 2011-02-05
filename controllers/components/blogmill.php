<?php 
class BlogmillComponent extends Object {
	
	private $Controller;
	private $Settings;
	private $themes;
	private $postTypes;
	private $pluginSettings;
	private $__plugins;
	private $__configurablePlugins;
	
	public function initialize(&$controller) {
		$this->Controller = $controller;
		$this->Settings = ClassRegistry::init('Setting');
		// Setup current page's information
		$this->__loadPageInfo();
		// Loads all available post types and themes
		$this->__readPlugins();
		// Sets up the current theme (data, helpers)
		$this->__setupCurrentTheme();
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
	
	/**
	 * Marks the current theme settings object as active and
	 * returns the plugin name and settings object for the active theme plugin.
	 *
	 * @return array (plugin_name, settings object)
	 * @author Joaquin Windmuller
	 */
	private function __activateTheme() {
		$plugin = $this->__activeThemePlugin();
		$pluginSettingsClass = "{$plugin}Settings";
		
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
	 * Sets up the theme for public pages.
	 * Loads the required helpers, data and the layout file as defined by the theme.
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	private function __setupCurrentTheme() {
		list($activeThemePlugin, $pluginSettings) = $this->__activateTheme();
		$this->Controller->activeThemePlugin = $activeThemePlugin;
		
		if (!$activeThemePlugin) return;
		
		$themeInfo = $pluginSettings->theme;
		$currentPage = $this->Controller->pageInfo['page'][0];
		if (isset($themeInfo['layouts'][$currentPage])) {
			$layouts = $themeInfo['layouts'];
			
			if (isset($layouts[$currentPage]['helpers'])) {
				$this->__loadThemeHelpers($layouts);
			}
			if (isset($layouts[$currentPage]['data'])) {
				$this->__loadThemeData($layouts);
			}
			if (isset($layouts[$currentPage]['name'])) {
				$this->Controller->layout = $layouts[$currentPage]['name'];
			}
		}
	}
	
	/**
	 * Loads the theme's helpers for the current layout
	 *
	 * @param string $layouts the layouts array defined by the theme.
	 * @return void
	 * @author Joaquin Windmuller
	 */
	private function __loadThemeHelpers($layouts) {
		$currentPage = $this->Controller->pageInfo['page'][0];
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
	
	/**
	 * Loads the theme's data for the current layout
	 *
	 * @param string $layouts the layouts array defined by the theme.
	 * @return void
	 * @author Joaquin Windmuller
	 */
	private function __loadThemeData($layouts) {
		$currentPage = $this->Controller->pageInfo['page'][0];
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

	/**
	 * Loads the plugin's theme into the list of available themes.
	 *
	 * @param string $theme theme data
	 * @param string $plugin plugin name
	 * @param string $plugin_path 
	 * @return void
	 * @author Joaquin Windmuller
	 */
	private function __loadTheme($theme, $plugin, $plugin_path) {
		$theme['plugin'] = $plugin;
		$theme['id'] = md5($plugin_path);
		$this->themes[$theme['id']] = $theme;
	}
	
	/**
	 * Loads the plugin's post types
	 *
	 * @param string $types types defined in the plugin
	 * @param string $plugin plugin name
	 * @return void
	 * @author Joaquin Windmuller
	 */
	private function __loadPostTypes($types, $plugin) {
		foreach ($types as $type => $definition) {
			$this->postTypes[$plugin][$type] = $definition;
		}
	}
	
	/**
	 * Loads the plugin's that have a settings pages.
	 *
	 * @param string $plugin plugin name
	 * @return void
	 * @author Joaquin Windmuller
	 */
	private function __loadPluginSettings($plugin) {
		$this->pluginSettings[] = $plugin;
	}
	
	/**
	 * Reads plugins settings and loads themes, postTypes
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	private function __readPlugins() {
		$plugins = Configure::listObjects('plugin');
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
				$class->name = $plugin;
				$this->__plugins[$plugin] = $class;
				if (isset($class->theme)) {
					$this->__loadTheme($class->theme, $plugin, $plugin_path);
				}
				if (isset($class->types) && is_array($class->types)) {
					$this->__loadPostTypes($class->types, $plugin);
				}
				$isConfigurable = isset($class->configurable) && is_array($class->configurable);
				if ($isConfigurable) {
					$this->__configurablePlugins[] = $plugin;
				}
			}
		}
		$themes = $this->Controller->themes = $this->themes;
		$postTypes = $this->Controller->postTypes = $this->postTypes;
		$this->Controller->set(compact('themes', 'postTypes'));
	}
	
	public function plugins() {
	}
	
	public function getConfigurablePlugins() {
		return $this->__configurablePlugins;
	}
	
	public function getConfigurableKeys($plugin) {
		$keys = array();
		if (in_array($plugin, $this->__configurablePlugins)) {
			$keys = $this->pluginSettings($plugin)->configurable;
		}
		return $keys;
	}
	
	public function pluginSettings($plugin) {
		return $this->__plugins[$plugin];
	}
}