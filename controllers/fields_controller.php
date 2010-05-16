<?php
class FieldsController extends AppController {

	var $name = 'Fields';

	function index() {
		$this->Field->recursive = 0;
		$this->set('fields', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'field'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('field', $this->Field->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Field->create();
			if ($this->Field->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'field'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'field'));
			}
		}
		$posts = $this->Field->Post->find('list');
		$this->set(compact('posts'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'field'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Field->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'field'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'field'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Field->read(null, $id);
		}
		$posts = $this->Field->Post->find('list');
		$this->set(compact('posts'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid id for %s', true), 'field'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Field->delete($id)) {
			$this->Session->setFlash(sprintf(__('%s deleted', true), 'Field'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(sprintf(__('%s was not deleted', true), 'Field'));
		$this->redirect(array('action' => 'index'));
	}
}
?>