<?php
class CommentsController extends AppController {

	var $name = 'Comments';

	function dashboard_index() {
		$this->Comment->recursive = 0;
		$this->set('comments', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid comment', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('comment', $this->Comment->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Comment->create();
			if ($this->Comment->save($this->data)) {
				$this->Session->setFlash(__('The comment has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The comment could not be saved. Please, try again.', true));
			}
		}
		$posts = $this->Comment->Post->find('list');
		$this->set(compact('posts'));
	}

	function dashboard_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid comment', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Comment->save($this->data)) {
				$this->Session->setFlash(__('The comment has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The comment could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Comment->read(null, $id);
		}
		$posts = $this->Comment->Post->find('list');
		$this->set(compact('posts'));
	}

    public function dashboard_spam($id = null, $status) {
        if (!$id) {
			$this->Session->setFlash(__('Invalid comment', true));
			$this->redirect(array('action'=>'index'));
		}
        $spam = $status == 'yes';
        $this->Comment->recursive = -1;
        $comment = $this->Comment->read(null, $id);
        if ($comment) {
            if ($spam) {
                $this->BlogmillHook->call('comment_is_spam', $comment);
                if (!$this->Comment->delete($id)) {
                    $this->Session->setFlash(__('Could not delete the comment', true));
                }
            } else {
                $this->BlogmillHook->call('comment_is_ham', $comment);
                $comment['Comment']['spam'] = false;
                $comment['Comment']['approved'] = true;
                if ($this->Comment->save($comment)) {
                    $this->Session->setFlash(__('Comment approved', true));
                }
            }
        }
        $this->redirect(array('action' => 'index'));
    }

    public function dashboard_approve($id = null, $status) {
        $approved = $status === 'yes';
        $Comment =  array('id' => $id, 'approved' => $approved, 'spam' => false);
        $this->Comment->save(compact('Comment'), false, array_keys($Comment));
        $this->redirect(array('action' => 'index'));
    }

	function dashboard_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid comment', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Comment->delete($id)) {
			$this->Session->setFlash(__('Comment deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Comment was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}