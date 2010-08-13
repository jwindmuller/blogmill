<?php
class SetupController extends AppController {
	public $uses = array();
	
	public function beforeFilter() {
		$this->Auth->allow('*');
	}
	
	public function reset() {
		var_dump($this->Acl->Aro->query('TRUNCATE TABLE ' . $this->Acl->Aro->table));
		var_dump($this->Acl->Aco->query('TRUNCATE TABLE ' . $this->Acl->Aco->table));
		var_dump($this->Acl->Aco->query('TRUNCATE TABLE ' . $this->Acl->Aco->hasAndBelongsToMany['Aro']['joinTable']));
		die;
	}

	public function acl() {
		$this->acos();
		$this->aros();
		$this->permissions();
		die;
	}
	public function acos() {
		$aro =& $this->Acl->Aro;
		$groups = array('visitor', 'user', 'admin');
		foreach ($groups as $alias) {
			$aro->create();
			$aro->save(compact('alias'));
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
}