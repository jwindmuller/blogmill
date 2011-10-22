<?php
App::import(array('type' => 'File', 'name' => 'BlogmillSettings', 'file' => APP . 'config/blogmill_settings.php'));
class AppController extends Controller {
	
	private $__userLevel;
	public $activeThemePlugin;
	/**
	 * Default Helpers
	 *
	 * @var array helper names
	 */
	var $helpers = array('Text', 'Html', 'Form', 'Javascript', 'Session', 'Blogmill', 'BlogmillForm', 'Time');
	var $components = array('Session', 'Acl', 'RequestHandler', 'Blogmill', 'Cookie',
		'Auth' => array(
			'authorize' => 'controller',
			'loginAction' => array('controller' => 'users', 'action' => 'login', 'dashboard' => true),
			'logoutAction' => array('controller' => 'users', 'action' => 'logout', 'dashboard' => true),
			'loginRedirect' => array('controller' => 'posts', 'action' => 'index', 'dashboard' => true),
			'logoutRedirect' => '/'
		)
	);
	var $postTypes = array();
	var $themes = array();
	var $pageInfo = array();
	
	/**
	 * Before Filter callback.
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	public function beforeFilter() {
        $this->Blogmill->checkUpgradeRequired();
        $this->__setLanguage();
		if ($this->Auth) {
			// Allow access to public areas to visitors
			if (!$this->Auth->user() && $this->isAuthorized()) {
				$this->Auth->allow('*');
			}
		}
	}

	/**
	 * Checks wether the current user is authorized to view the current page
	 *
	 * @return boolean
	 * @author Joaquin Windmuller
	 */
	public function isAuthorized() {
		$aro = 'visitor';
		if ($this->Auth->user('id')) {
			$aro = array('model' => 'User', 'foreign_key' => $this->Auth->user('id'));
		}
		$isAuthorized = $this->Acl->check($aro,'controllers/' . $this->name . '/' . $this->action);
		return $isAuthorized;
	}
	
	/**
	 * Setup layout for prefixed actions.
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	public function beforeRender() {
		if (isset($this->params['prefix'])) {
			$this->layout = $this->params['prefix'];
		}
        if ($this->name !== 'CakeError') {
            $this->__requestRecieved();
        }
		// HOOK: allow plugins to load js and css resource files
		$this->Blogmill->pluginsAttached('load_resources');
	}

    public function __requestRecieved() {
        $plugins = $this->Blogmill->pluginsAttached('request_received');
    }

    /**
     * Loads the locale.php file inside config
     */
    private function __setLanguage() {
        App::import(array(
            'type' => 'File', 
            'name' => 'BlogmillLocaleSetting',
            'file' => APP . 'config' . DS . 'locale.php'
        ));
    }

    protected function _blogmill404Error() {
        App::import('Core', 'Error');
        include_once (APP . 'app_error.php');
        $error = new AppError('error404', array(), &$this);
        $error->error404(array());
    }
}
?>
