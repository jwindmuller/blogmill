<?php
class PostsController extends AppController {

	var $name = 'Posts';

	function index() {
		$this->Post->recursive = 0;
		$this->set('posts', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'post'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('post', $this->Post->read(null, $id));
	}

	function add($type) {
		$modelName = Inflector::Camelize($type);
		$model = ClassRegistry::init("$modelName.$modelName");
		$this->Post->validate = $model->validate;
		$fields = array_keys($model->validate); 
		$fieldTypes = $model->types;
		if (!empty($this->data)) {
			$this->data['Post']['type'] = $type;
			$this->Post->create();
			if ($this->Post->savePost($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'post'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'post'));
			}
		}
		$users = $this->Post->User->find('list');
		$categories = $this->Post->Category->find('list');
		$this->set(compact('users', 'categories', 'fields', 'fieldTypes'));
	}
	
	
	private function __boo() {
		
	}
	function edit($type, $id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'post'));
			$this->redirect(array('action' => 'index'));
		}
		$modelName = Inflector::Camelize($type);
		$model = ClassRegistry::init("$modelName.$modelName");
		$this->Post->validate = $model->validate;
		$fields = array_keys($model->validate); 
		$fieldTypes = $model->types;
		if (!empty($this->data)) {
			$this->data['Post']['type'] = $type;
			if ($this->Post->savePost($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'post'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'post'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Post->read(null, $id);
		}
		$users = $this->Post->User->find('list');
		$categories = $this->Post->Category->find('list');
		$this->set(compact('users', 'categories', 'type', 'fields', 'fieldTypes'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid id for %s', true), 'post'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Post->delete($id)) {
			$this->Session->setFlash(sprintf(__('%s deleted', true), 'Post'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(sprintf(__('%s was not deleted', true), 'Post'));
		$this->redirect(array('action' => 'index'));
	}
}
?>