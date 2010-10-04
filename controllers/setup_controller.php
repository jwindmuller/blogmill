<?php
class SetupController extends AppController {
	public $uses = array();
	
	public function beforeFilter() {
		$this->Auth->allow('*');
	}
	
	public function reset() {
		$User = ClassRegistry::init('User');
		$User->query('TRUNCATE TABLE ' . $User->table);
		$this->Acl->Aro->query('TRUNCATE TABLE ' . $this->Acl->Aro->table);
		$this->Acl->Aco->query('TRUNCATE TABLE ' . $this->Acl->Aco->table);
		$this->Acl->Aco->query('TRUNCATE TABLE ' . $this->Acl->Aco->hasAndBelongsToMany['Aro']['joinTable']);
	}

	public function acl() {
		$this->acos();
		$this->aros();
		$this->permissions();
		// die;
	}
	public function acos() {
		$aro =& $this->Acl->Aro;
		$groups = array('visitor', 'user', 'admin');
		$parent_id = null;
		foreach ($groups as $alias) {
			$aro->create();
			$aro->save(compact('alias', 'parent_id'));
			$parent_id = $aro->getLastInsertID();
		}
	}
	
	public function aros() {
		$aco =& $this->Acl->Aco;
		$groups = array('data', 'controllers');
		foreach ($groups as $alias) {
			$aco->create();
			$aco->save(compact('alias'));
		}
		$controllersID = $aco->getLastInsertID();
		$controllers = Configure::listObjects('controller');
		foreach ($controllers as $controller) {
			if ($controller == 'App') continue;
			$aco->create();
			$aco->save(array('alias' => $controller, 'parent_id' => $controllersID));
			$controllerACO = $aco->getLastInsertID();
			App::import('Controller', $controller);
			$class = "${controller}Controller";
			$reflection = new ReflectionClass ($class);
			$methods = $reflection->getMethods();
			foreach ($methods as $method) {
				if ($method->class == $class && $method->name[0] != '_') {
					$aco->create();
					$aco->save(array('alias' => $method->name, 'parent_id' => $controllerACO));
				}
			}
		}
	}
	
	public function permissions() {
		$this->Acl->allow('admin', 'controllers');
		$this->actions_acl();
		// $this->Acl->allow('visitor', 'controllers/Posts/index');
		// $this->Acl->allow('visitor', 'controllers/Posts/home');
		// $this->Acl->allow('admin', 'posts');
		// $this->Acl->allow('user', 'posts');
		// $this->Acl->allow('visitor', 'posts');
		// $this->Acl->deny('visitor', 'posts', 'add');
		// $this->Acl->deny('visitor', 'posts', 'edit');
		// $this->Acl->deny('visitor', 'posts', 'delete');
	}

	public function actions_acl() {
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

	public function go() {
		if (!empty($this->data)) {
			$User = ClassRegistry::init('User');
			$this->data['User']['group_id'] = '4';
			$User->set($this->data);
			if ($User->validates()) {
				$this->acl();
				$this->permissions();
				$this->actions_acl();
				if ($User->save($this->data)) {
					$this->redirect('/');
				} else die('hi');
			}
		}
		$this->reset();
	}
}