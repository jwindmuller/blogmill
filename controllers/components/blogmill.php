<?php 
App::import('Lib', 'Migrations.MigrationVersion');
App::import('Component', 'BlogmillHook');
class BlogmillComponent extends Object {
	
	private $Controller;
	private $Settings;
	private $themes;
	private $postTypes;
	private $pluginSettings;
    private $attachmentDefinitions = array();
	private $__plugins = array();
	private $__configurablePlugins;
    private $__adminMenus;
    private $__hookableSettings;

    private $__scriptsForBottom;
	
	public function startup($controller) {
        $this->__scriptsForBottom = 
        $this->__adminMenus = 
        $this->__hookableSettings = 
        array();

		$this->Controller = &$controller;
		$this->Settings = ClassRegistry::init('Setting');
		// Setup current page's information
		$this->__loadPageInfo();
		// Loads all available post types and themes
		$this->__readPlugins();
		// Sets up the current theme (data, helpers)
		$this->__setupCurrentTheme();
        // Sets up the current site name
        $site_name = $this->Settings->get('BlogmillDefault.blogmill_site_name');
        // Sets up the current site description
        $site_description = $this->Settings->get('BlogmillDefault.blogmill_site_description');
        $this->Controller->set(compact('site_name', 'site_description'));
	}
    
    public function beforeRender($controller) {
        if (!is_array($this->__scriptsForBottom)) {
            $this->__scriptsForBottom = array();
        }
        $controller->set('scripts_for_bottom', $this->__scriptsForBottom);
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
        $this->Controller->set('pageInfo', $this->Controller->pageInfo);
	}


    public function loadHtmlEditor() {
        $Setting = ClassRegistry::init('Setting');
        $htmlEditor = 'BlogmillDefault.blogmill_editor:user-' . $this->Controller->Session->read('Auth.User.id');
        $editor = $Setting->get($htmlEditor);
        if ( $editor !== false && $editor !== 'html') {
            $this->Controller->helpers[] = $editor . '.' . $editor . 'Editor';
            $this->Controller->set('editor_loaded', $editor);
        }
    }

    public function transformHtmlEditorData($data) {
        $Setting = ClassRegistry::init('Setting');
        $htmlEditor = 'BlogmillDefault.blogmill_editor:user-' . $this->Controller->Session->read('Auth.User.id');
        $editor = $Setting->get($htmlEditor);
        if ( $editor !== false && $editor !== 'html') {
            $txName = 'HtmlTransformer';
            $txClass = $txName . 'Component';
            App::import('Component', $editor . '.' . $txName);

            $tx = new $txClass();
            $data = $tx->transform($data);
        }
        return $data;
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
            case $this->Controller->name == 'CakeError':
                $page = 'error';
                break;
			case $this->Controller->name == 'Posts' && $this->Controller->action == 'home':
				$page = 'home';
				break;
			case $this->Controller->name == 'Posts':
				$page = 'inner';
				if ($this->Controller->action == 'index') $page .= ' listing';
				if ($this->Controller->action == 'view') $page .= ' single';
				break;
            case $this->Controller->name == 'Contacts':
                $page = 'contact';
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
			return 'BlogmillDefault';
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
        $this->Controller->set('activeThemePlugin', $plugin);
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
        $commentsEnabled = true;
        if (isset($themeInfo['commentsEnabled'])) {
            $commentsEnabled = $themeInfo['commentsEnabled'];
        }
        $this->Controller->commentsEnabled = $commentsEnabled;
        $this->Controller->set(compact('commentsEnabled'));

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
            $type = null;
            if (isset($definition['type'])) {
                $type = $definition['type'];
            }
            $conditions = array();
            if ($type !== null) {
                $conditions = compact('type');
            }
            if ($type == "*") {
                $conditions = array('type <>' => '');
            }
            $conditions['draft'] = false;
			$conditions['published NOT'] = null;
			$options = compact('conditions') + array('limit' => $definition['limit']);
			if (isset($definition['order'])) {
				if (!is_array($definition['order'])) {
					$definition['order'] = array($definition['order']);
				}
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
	
    private function __loadAttachmentDefinitions($definitions, $plugin) {
        foreach ($definitions as $name => $definition) {
            $this->attachmentDefinitions[$plugin][$name] = $definition;
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
                if (isset($class->adminMenu) && is_array($class->adminMenu)) {
                    $this->__adminMenus = array_merge(
                        $this->__adminMenus,
                        array($plugin => $class->adminMenu)
                    );
                }
                if ( isset( $class->hookableSettings ) ) {
                    $this->__hookableSettings = array_merge(
                        $this->__hookableSettings,
                        array($plugin => $class->hookableSettings)
                    );
                }
				$isConfigurable = isset($class->configurable) && is_array($class->configurable);
				if ($isConfigurable) {
					$this->__configurablePlugins[] = $plugin;
				}
                if (isset($class->attachmentDefinitions) && is_array($class->attachmentDefinitions)) {
                    $this->__loadAttachmentDefinitions($class->attachmentDefinitions, $plugin);
                }
			}
		}
		$themes = $this->Controller->themes = $this->themes;
		$postTypes = $this->Controller->postTypes = $this->postTypes;
        $adminMenus = $this->__adminMenus;
        $attachmentDefinitions = $this->attachmentDefinitions;
        $this->Controller->hookableSettings = $this->__hookableSettings;
		$this->Controller->set(compact('themes', 'postTypes', 'adminMenus', 'attachmentDefinitions'));
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
	
	public function activeThemeDecorators() {
		list($activeThemePlugin, $pluginSettings) = $this->__activateTheme();
		if (!isset($pluginSettings->theme['post_type_decorators'])) return array();
		return $pluginSettings->theme['post_type_decorators'];
	}
	
	public function pluginSettings($plugin) {
        if (isset($this->__plugins[$plugin]))
    		return $this->__plugins[$plugin];
        return null;
	}
    
    public function pluginsAttached($to, $env = array()) {
        foreach( $this->__plugins as $name => $plugin) {
            if (isset($plugin->attachTo)) {
                $componentName = $plugin->name . 'Hooks';
                $component = ClassRegistry::getObject($componentName);
                if (!$component) {
                    $component = App::import(array(
                        'type' => 'Component',
                        'name' => $componentName,
                        'search' =>  APP . 'plugins' . DS . Inflector::underscore($plugin->name) . DS . 'controllers' . DS . 'components'
                    ));
                    $componentNameClass = $componentName . 'Component';
                    $component = new $componentNameClass();
                    if (!$component) {
                        continue;
                    }
                    $component->startup($this->Controller);
                    ClassRegistry::addObject($componentName, $component);
                }

                if (!in_array($to, $plugin->attachTo)) continue;
                call_user_func_array(array($component, $to), $env);
            }
        }
    }

    public function scriptForBottom($url, $type = 'js') {
        $this->__scriptsForBottom[] = array($url, $type);
    }

    public function checkUpgradeRequired() {
        $Version = new MigrationVersion(array(
			'connection' => 'default'
		));
        $mapping = $Version->getMapping('app');
        $latestVersion = $Version->getVersion('app');
        end($mapping);
        $last = key($mapping);
        if ( $latestVersion < $last ) {
            $options['version'] = $last;
            $options['type'] = 'app';
            $Version->run($options);
        }
    }
}
