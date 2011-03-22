<?php
class SetupController extends AppController {
	public $uses = array();

	public function beforeFilter() {
		$this->Auth->allow('*');
	}
	
	private function reset() {
		$User = ClassRegistry::init('User');
		$User->query('TRUNCATE TABLE ' . $User->table);
		$this->Acl->Aro->query('TRUNCATE TABLE ' . $this->Acl->Aro->table);
		$this->Acl->Aco->query('TRUNCATE TABLE ' . $this->Acl->Aco->table);
		$this->Acl->Aco->query('TRUNCATE TABLE ' . $this->Acl->Aco->hasAndBelongsToMany['Aro']['joinTable']);
	}
	
	private function create_acos() {
		$aro =& $this->Acl->Aro;
		// If the visitor Aco is there then all of them *should*
		if ($aro->find('first', array('conditions' => array('alias' => 'visitor', 'parent_id' => null)))) return;
		$groups = array('visitor', 'user', 'admin');
		$parent_id = null;
		foreach ($groups as $alias) {
			$aro->create();
			$aro->save(compact('alias', 'parent_id'));
			$parent_id = $aro->getLastInsertID();
		}
	}
	
	private function create_aros() {
		$aco =& $this->Acl->Aco;
		// Two groups of Acos, data and the controller's actions
		$groups = array('data', 'controllers');
		foreach ($groups as $alias) {
			$conditions = compact('alias');
			$controllersID = $aco->field('id', $conditions);
			if ($controllersID === false) {
				$aco->create();
				$aco->save($conditions);
				$controllersID = $aco->getLastInsertID();
			}
		}

		// We will setup the access to the controller's actions
		$controllers = Configure::listObjects('controller');		
		foreach ($controllers as $controller) {
			if ($controller == 'App') continue;
			
			// 1 Aco per controller
			$conditions = array('alias' => $controller, 'parent_id' => $controllersID);
			$controllerACO = $aco->field('id', $conditions);
			if ($controllerACO === false) {
				$aco->create();
				$aco->save($conditions);
				$controllerACO = $aco->getLastInsertID();
			}
			
			// 1 Aco for each controller's action.
			App::import('Controller', $controller);
			$class = "${controller}Controller";
			$reflection = new ReflectionClass ($class);
			$methods = $reflection->getMethods();
			foreach ($methods as $method) {
				if ($method->class == $class && $method->name[0] != '_') {
					$conditions = array('alias' => $method->name, 'parent_id' => $controllerACO);
					if ($aco->find('first', compact('conditions'))) continue;
					$aco->create();
					$aco->save($conditions);
				}
			}
		}
	}
		
	private function actions_acl() {
		App::import(array(
			'type' => 'File',
			'name' => 'BlogmillPermissions',
			'file' => APP . 'config' . DS . 'permissions.php'
		));
		$permission = new BlogmillPermissions;
		foreach ($permission->definitions as $controllerName => $actions) {
			foreach ($actions as $action => $group) {
				$this->Acl->allow($group, 'controllers/' . $controllerName . '/' . $action);
			}
		}
	}
	
	private function setup_acl_permissions() {
		$this->create_acos();
		$this->create_aros();
		$this->Acl->allow('admin', 'controllers');
		$this->actions_acl();
	}

	public function dashboard_go() {
		if (!empty($this->data)) {
			// Even if the user is not created for validation issues, this is idempotent so it doesn't matter if we repeat it. 
			// TODO: An admin lock should be setup to prevent DoS
			$this->setup_acl_permissions();
			
			$success_url = array('controller' => 'posts', 'action' => 'index', 'dashboard' => true);
			// If there's an user with that login/password don't create another
			if ($this->Auth->login($this->data)) {
				$this->redirect($success_url);
			}
			
			$User = ClassRegistry::init('User');
			$this->data['User']['admin'] = true;
			$User->set($this->data);
			if ($User->validates()) {
				if ($User->save($this->data)) {
					$this->Auth->login($this->data);
					$this->redirect($success_url);
				} else {
					die('Could not create user. Go back and try again please.');
				}
			}
		}
	}
}
