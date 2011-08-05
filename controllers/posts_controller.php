<?php
class PostsController extends AppController {

	public $name = 'Posts';
	public $components = array('HtmlCleaner');
    private $tagWhitelist = array(
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'ul', 'ol', 'li',
        'blockquote', 'code', 'pre',
        'p', 'strong', 'em', 'br', 'u'
    );

	
	public function home() {
	}
	
	function index($type) {
        $types = array();
        if (strpos($type, ',')) {
            $type = explode(',', $type);
        }
        if (is_string($type)) {
            $type = array($type);
        }
        foreach( $type as $t ) {
            list($plugin, $t) =  pluginSplit($t);
            if ( empty($plugin) ) {
                $plugin = $t;
            }
            if ( isset( $this->postTypes[$plugin][$t] ) ) {
                $types[] = "$plugin.$t";
            }
        } 
		$this->paginate = array(
            'conditions' => array('type' => $types),
            'order'  => 'created DESC',
            'contain' => array('Field', 'User(id,username)', 'Category')
        );
        $posts = $this->paginate();
		$this->set(compact('posts', 'plugin', 'types'));
	}
	
	function view($id, $slug = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid post', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->__saveComment($id, $slug);
		}
		$this->set('post', $this->Post->read(null, $id));
	}

	function dashboard_index($type=false) {
        if ($type) {
            $this->paginate['conditions'] = compact('type');
        }
		$this->Post->contain(array('Field', 'Category'));
		$this->set('posts', $this->paginate());
	}

	function dashboard_add($plugin, $type) {
        $this->Blogmill->loadHtmlEditor();    
		$this->__prepareModel($plugin, $type);
		$this->__setCategories();
		$this->Post->create();
		$this->__savePost("$plugin.$type");
	}
	
	function dashboard_edit($plugin, $type, $id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid post', true));
			$this->redirect(array('action' => 'index'));
		}
        $this->Blogmill->loadHtmlEditor();
		$this->__prepareModel($plugin, $type);
		$this->__setCategories();
		$this->__savePost("$plugin.$type");
		if (empty($this->data)) {
			$this->data = $this->Post->read(null, $id);
		}
	}
    
	private function __prepareModel($plugin, $type) {
		$modelName = Inflector::Camelize($type);
		$model = ClassRegistry::init("$plugin.$modelName");
		$this->Post->validate += $model->validate;
		$fields = array_keys($model->validate);
		$settings = ClassRegistry::init($plugin . 'Settings');
		$formLayout = $settings->types[$modelName]['form_layout'];
		$this->Post->fields = $this->postTypes[$plugin][$type]['fields'];
		$post_type_decorators = $this->Blogmill->activeThemeDecorators();
		foreach ($post_type_decorators as $group => $decorator) {
			if (!in_array("$plugin.$modelName", $decorator['types'])) continue;
			$decorators_group = array('title' => $decorator['label'], 'fields' => array());
			foreach ($decorator['fields'] as $name => $field_definition) {
				$validation = $field_definition['validation'];
				unset($field_definition['validation']);
				$this->Post->fields[$name] = $field_definition;
				$this->Post->validate[$name] = $validation;
				$decorators_group['fields'][] = $name;
			}
			if (count($decorators_group['fields'])>0)
				$formLayout['form-sidebar'][] = $decorators_group;
		}
        $formLayout['form-sidebar'][] = array('title' => __('Category', true), 'fields' => array(array('category_id' => array('empty' => true))));
		$this->set(compact('plugin', 'type', 'formLayout'));
	}
	
	private function __setCategories() {
		$categories = $this->Post->Category->find('list');
		$this->set(compact('categories'));
	}
	
	private function __savePost($type) {
		if (empty($this->data)) return;
		$this->__prepareData();
		$this->data['Post']['type'] = $type;
        $this->data['Post']['user_id'] = $this->Auth->user('id');
		if ($this->Post->savePost($this->data)) {
			$this->Session->setFlash(__('The post has been saved', true));
			$this->redirect(array('action' => 'index'));
		} else {
			$this->Session->setFlash(__('The post could not be saved. Please, try again.', true));
		}
	}
	
	private function __prepareData() {
		$fields = $this->Post->fields;
		foreach ($fields as $field => $type) {
            if ( is_array($type) ) {
                $type = $type['type'];
            }
			if ($type == 'html' && isset($this->data['Post'][$field])) {
                $fieldData = $this->data['Post'][$field];
                $fieldData = $this->Blogmill->transformHtmlEditorData( $fieldData );
				$this->data['Post'][$field] = $this->HtmlCleaner->cleanup( $fieldData, $this->tagWhitelist);
			}
		}
	}

	function dashboard_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid post', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Post->delete($id)) {
			$this->Session->setFlash(__('Post deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Post was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
		
	private function __saveComment($post_id, $slug) {
		$this->data['Comment']['post_id'] = $post_id;
		if ($this->Post->Comment->save($this->data)) {
			$this->Session->setFlash(__('Thanks for leaving your comment', true));
			$this->redirect(array(
				'controller' => 'posts',
				'action' => 'view',
				'id' => $post_id,
                'type' => $this->Post->field('type', array('id' => $post_id)),
				'slug' => $slug,
				'#' => 'comments'
			));
		}
	}


	public function dashboard_list() {
		$plugin = $this->params['url']['plugin'];
		$type = $this->params['url']['type'];
        $this->set(compact('type'));
        if ($plugin == '_FixedPages') {
            $this->render('menu_fixed_pages');
        } else {
    		$this->index($plugin, $type);
        }
	}
}
?>
