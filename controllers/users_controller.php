<?php
class UsersController extends AppController {

	var $name = 'Users';
    var $components = array('Email');

	function dashboard_index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

	function dashboard_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'user'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

	function dashboard_add() {
		if (!empty($this->data)) {
            $confirmation = $this->data['User']['confirmation'] = md5(time());
			$this->User->create();
			if ($this->User->save($this->data)) {
                
                if ($this->__sendNotification($this->data, $confirmation)) {
                    $this->Session->setFlash(__('The user was created and an email was sent to the user', true));
    				$this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash(__('The user was created but the email could not be sent', true));
    				$this->redirect(array('action' => 'notify', $confirmation));
                }

			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'user'));
			}
			$this->data['User']['password'] = $password;
			$this->data['User']['password_confirm'] = $password_confirm;
		}
	}

    public function __sendNotification($user, $confirmation) {
        $name  = $user['User']['name'];
        $email = $user['User']['email'];
        $this->Email->from = sprintf('%s <%s>', $name, $email);
        $this->Email->subject = __('Activate your account', true);
        
        $Setting = ClassRegistry::init('Setting');
        $this->Email->replyTo = $this->Email->from = $Setting->get('BlogmillDefault.blogmill_contact_email'); 
        
        $this->Email->template = 'user_added_activation'; 
        $this->Email->sendAs = 'both';

        $blogmill_site_name = $Setting->get('BlogmillDefault.blogmill_site_name');
        $this->set(compact('confirmation', 'blogmill_site_name'));
        return $this->Email->send();

    }

    public function dashboard_notify($confirmation, $send=false) {
        $user = $this->User->findByConfirmation($confirmation);
        if ($send === 'send') {
            if ($this->__sendNotification($user, $confirmation)) {
                $this->Session->setFlash(__('The notification email was sent!', true));
    			$this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The notification could not be sent', true));
    			$this->redirect(array('action' => 'notify', $confirmation, 'dashboard' => true));

            }
        }
        if (!$user) {
            $this->redirect(array('controller' => 'users', 'action' => 'index'));
        }
        
        $Setting = ClassRegistry::init('Setting');
        $blogmill_site_name = $Setting->get('BlogmillDefault.blogmill_site_name');
        $this->set(compact('user', 'confirmation', 'blogmill_site_name'));
    }

	function dashboard_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'user'));
			$this->redirect(array('action' => 'index'));
		}
        $user = $this->User->findById($id);
        if (!empty($this->data)) {
            $this->data['User']['username'] = $user['User']['username'];
            if (empty($this->data['User']['password'])) {
                unset($this->data['User']['password']);
                unset($this->data['User']['password_confirm']);
            }
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'user'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'user'));
			}
		}
		if (empty($this->data)) {
			$this->data = $user;
		}
        unset($this->data['User']['password']);
        unset($this->data['User']['password_confirm']);
	}

    public function dashboard_activate($confirmation) {
        $this->set(compact('confirmation'));
        $user = $this->User->findByConfirmation($confirmation);
        if ($user) {
            $username = $user['User']['username'];
            $this->set(compact('username'));
            if (!empty($this->data)) {
                unset($this->data['User']['username']);
                $this->data['User']['id'] = $user['User']['id'];
                $this->data['User']['confirmation'] = '';
                if ($this->User->save($this->data)) {
                    $this->data['User']['username'] = $username;
                    $this->Auth->login($this->data);
                    $this->Session->setFlash(__('Welcome!', true));
                    $this->redirect(array('action' => 'edit', $user['User']['id']));
                } else {
                    $this->Session->setFlash(__('Could not activate your user, please try again or contact the administrator.!', true));
                    $this->redirect(array('controller' => 'contacts', 'action' => 'send', 'dashboard' => false));
                }
            }
        } else {
            $this->Session->setFlash(__('Invalid request, please try again or contact the administrator.!', true));
            $this->redirect(array('controller' => 'contacts', 'action' => 'send', 'dashboard' => false));
        }
    }

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid id for %s', true), 'user'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->User->delete($id)) {
			$this->Session->setFlash(sprintf(__('%s deleted', true), 'User'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(sprintf(__('%s was not deleted', true), 'User'));
		$this->redirect(array('action' => 'index'));
	}

	public function dashboard_login() {
		if ($this->Auth->user()) {
			$this->redirect(array('controller' => 'posts', 'action' => 'index', 'dashboard' => true));
		}
	}
	public function dashboard_logout() {
		$this->redirect($this->Auth->logout());
	}
}
?>
