<?php
class BlogmillHookComponent extends Object {
	
	protected $Controller;
	
	public function startup($Controller) {
		$this->Controller = $Controller;
	}

    final function call($hook_name, $params = array()) {
        $hookName = Inflector::camelize($hook_name);
        $plugins = Configure::listObjects('plugin');
		foreach ($plugins as $i => $plugin) {
            $plugin_folder = Inflector::underscore($plugin);
            $hooks_path =
                APP . 'plugins' . DS . $plugin_folder . DS .
                'controllers' . DS . 'components' . DS;
            $hookClass = "{$plugin}HooksComponent";
            App::import(
				array(
					'type' => 'File',
					'name' => $hookClass,
					'file' => $hooks_path . $plugin_folder . '_hooks.php'
				)
			);
            if (class_exists($hookClass)) {
                $obj = new $hookClass;
                $obj->Controller = $this->Controller;
                if (method_exists($obj, $hook_name)) {
                    call_user_func(array($obj, $hook_name), $params);
                }
            }
        }
        return $params;
    }

    /**
     * Update all post data related to a field
     *
     * @param array $post post data
     * @param string $fieldName name of the field to update
     * @param mixed $value value to assign
     **/
    final protected function update_post_field($post, $fieldName, $value)
    {
        $post['Post'][$fieldName] = $value;
        foreach ($post['Field'] as $i => $field) {
            if ($field['name'] == $fieldName) {
                $field['value'] = $value;
                $post['Field'][$i] = $field;
                break;
            }
        }
        return $post;
    }

    /**
     * before_comment is executed before saving a new comment.
     *
     * @param array $comment_data modifiable comment information, before saving.
     * @return void
     * @see PostsController::__saveComment
     */
    public function before_comment(&$comment_data = array()) {
    }

    /**
     * comment_is_spam is executed when the user clicks the "spam" link.
     *
     * @param array $comment_data data of the comment. The comment is deleted after this hook.
     * @return void
     * @see CommentsController::dashboard_spam
     */
    public function comment_is_spam($comment_data = array()) {
    }

    /**
     * comment_is_ham is executed when the user clicks the "not spam" link.
     *
     * @param array $comment_data data of the comment. The comment is approved and marked as not spam.
     * @return void
     * @see CommentsController::dashboard_spam
     */
    public function comment_is_ham($comment_data) {
    }

    /**
     * actions_for returns a list of urls to that should show in the current location
     *
     * @param string $location location being executed
     * @param array $urls reference to return urls
     * @return void
     */
    public function actions_for($location, &$urls = array()) {
    }

    /**
     * post_data is executed before showing a post (allows plugins to modify posts before diasplay)
     *
     * @param array $post post data, including fields
     * @return array modified post data
     **/
    public function post_data($post) {
        return compact('post');
    }
}