<?php
class CategoriesController extends AppController {

	var $name = 'Categories';
	
	function view($slug) {
		$id = $this->Category->field('id', compact('slug'));
		if (!$id) {
			die('Error');
		}
		$this->paginate['contain'] = array('User(username,name)', 'Comment(id)', 'Field', 'Category');
		$this->set('posts', $this->paginate($this->Category->Post));
		$this->render('/posts/index');
	}

	function dashboard_index() {
		$this->Category->recursive = 0;
		$this->set('categories', $this->paginate());
	}

	function dashboard_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid category', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('category', $this->Category->read(null, $id));
	}

	function dashboard_add() {
		if (!empty($this->data)) {
			$this->Category->create();
			if ($this->Category->save($this->data)) {
				$this->Session->setFlash(__('The category has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The category could not be saved. Please, try again.', true));
			}
		}
		$categories = $this->Category->find('list');
		$this->set(compact('categories'));
	}

	function dashboard_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid category', true));
			$this->redirect(array('action' => 'index'));
		}
        $this->dashboard_add();
		if (empty($this->data)) {
			$this->data = $this->Category->read(null, $id);
		}
	}

	function dashboard_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid category', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Category->delete($id)) {
			$this->Session->setFlash(__('Category deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Category was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
