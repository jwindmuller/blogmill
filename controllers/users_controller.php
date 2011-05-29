<?php
class UsersController extends AppController {

	var $name = 'Users';
    var $components = array('BlogmillEmail');

	function dashboard_index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

	function dashboard_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

	function dashboard_add() {
		if (!empty($this->data)) {
            $confirmation = $this->data['User']['confirmation'] = md5(time());
			$this->User->create();
			if ($this->User->save($this->data)) {
                
                if ($this->__sendConfirmationEmail($this->data, $confirmation)) {
                    $this->Session->setFlash(__('The user was created and an email was sent to the user', true));
    				$this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash(__('The user was created but the email could not be sent', true));
    				$this->redirect(array('action' => 'notify', $confirmation));
                }

			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
			$this->data['User']['password'] = $password;
			$this->data['User']['password_confirm'] = $password_confirm;
		}
	}

    public function __sendConfirmationEmail($user, $confirmation) {
        $Setting = ClassRegistry::init('Setting');
        $blogmill_site_name = $Setting->get('BlogmillDefault.blogmill_site_name');
        $options = array(
            'to' => array(
                'name' => $user['User']['name'],
                'email' => $user['User']['email']
            ),
            'from' => array(
                'name' => '',
                'email' => $Setting->get('BlogmillDefault.blogmill_contact_email')
            ),
            'subject' =>  __('Activate your account', true),
            'template' => 'user_added_activation',
            'data' => compact('confirmation', 'blogmill_site_name')
        );
        return $this->BlogmillEmail->sendNotification($options);
    }

    public function dashboard_notify($confirmation, $send=false) {
        $user = $this->User->findByConfirmation($confirmation);
        if ($send === 'send') {
            if ($this->__sendConfirmationEmail($user, $confirmation)) {
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
			$this->Session->setFlash(__('Invalid user', true));
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
				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
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
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->User->delete($id)) {
			$this->Session->setFlash(__('User deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('User was not deleted', true));
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

    public function dashboard_recover($confirmation=null) {
        if ($confirmation) {
            $user = $this->User->findByConfirmation($confirmation);
            if (!$user) {
                $this->Session->setFlash(__('Invalid access', true));
                $this->redirect(array('action' => 'login'));
            }
            if (!empty($this->data)) {
                if ($user['User']['answer'] == $this->data['User']['answer']) {
                    $this->User->set($user);
                    $this->User->saveField('confirmation', '');
                    $this->Auth->login($user);
                    $this->Session->setFlash(__('Update your password', true));
                    $this->redirect(array('action' => 'edit', $user['User']['id']));
                }
            }
            $this->set('question', $user['User']['question']);
            $this->set(compact('confirmation'));
            $this->render('dashboard_recover_step2');
            return;
        }
        if (!empty($this->data)) {
            $user = $this->User->findByUsername($this->data['User']['username']);
            if ($user) {
                unset($user['User']['password']);
                $this->User->set($user);
                $confirmation =  md5(time());
                $this->User->saveField('confirmation', $confirmation);

                if ($this->__sendRecoveryEmail($user, $confirmation)) {
                    $this->Session->setFlash(__('Check your email for instructions on how to reset your password', true));
                    $this->redirect(array('controller' => 'users', 'action' => 'login', 'dashboard' => true));
                } else {
                    $this->Session->setFlash(__('There was an error sending you an email with instructions on how to reset your password, please try again.', true));
                }
            }
        }
    }

    private function __sendRecoveryEmail($user, $confirmation) {
        $Setting = ClassRegistry::init('Setting');
        $blogmill_site_name = $Setting->get('BlogmillDefault.blogmill_site_name');
        $options = array(
            'to' => array(
                'name' => $user['User']['name'],
                'email' => $user['User']['email']
            ),
            'from' => array(
                'name' => '',
                'email' => $Setting->get('BlogmillDefault.blogmill_contact_email')
            ),
            'subject' =>  __('Password Recovery', true),
            'template' => 'user_password_recovery',
            'data' => compact('confirmation', 'blogmill_site_name', 'user'),
            'debug' => true
        );
        return $this->BlogmillEmail->sendNotification($options);

    }

}
?>
