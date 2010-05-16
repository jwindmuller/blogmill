<?php
class PostTypesController extends AppController {

	var $name = 'PostTypes';

	function index() {
		$this->PostType->recursive = 0;
		$this->set('postTypes', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'post type'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('postType', $this->PostType->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->PostType->create();
			if ($this->PostType->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'post type'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'post type'));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'post type'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->PostType->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'post type'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'post type'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->PostType->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid id for %s', true), 'post type'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->PostType->delete($id)) {
			$this->Session->setFlash(sprintf(__('%s deleted', true), 'Post type'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(sprintf(__('%s was not deleted', true), 'Post type'));
		$this->redirect(array('action' => 'index'));
	}
}
?>