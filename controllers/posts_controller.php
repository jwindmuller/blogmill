<?php
class PostsController extends AppController {

	public $name = 'Posts';
	public $components = array('HtmlCleaner');
    private $tagWhitelist = array(
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'ul', 'ol', 'li',
        'blockquote', 'code', 'pre',
        'p' => array('class'),
        'strong', 'em', 'br', 'u',
        'a' => array('href', 'title'),
        'img' => array('title', 'alt', 'src'),
        'span' => array('class'),
        'hr' => array('class'),
        'cite',
    );

    public function beforeFilter() {
        parent::beforeFilter();
        // Bind attachments here to prevent migrations from failing
        $this->Post->bindModel(array(
            'hasMany' => array(
                'Attachment'
            )
        ));

    }
	
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
        if ( empty($types) ) {
            $this->_blogmill404Error();
        }
		$this->paginate = array(
            'conditions' => array('type' => $types, 'draft' => false, 'published <=' => date('Y-m-d H:i:j')),
            'order'  => 'published DESC',
            'contain' => array('Field', 'User(id,username)', 'Category', 'Attachment')
        );
        $posts = $this->paginate();
		$this->set(compact('posts', 'plugin', 'types'));
        $this->__indexRSS($plugin, $types);
	}

    private function __indexRSS($plugin, $types) {
        if ( !$this->RequestHandler->isRss() ) return;
        $this->layout = 'default';
        $this->plugin = null;

        $title = '';

        $type = implode(',', $types);
        if ( BlogmillRouteFunctions::getIndexName($type) !== false ) {
            $title = BlogmillRouteFunctions::getIndexName($type);
        } else if ( count($types) == 1) {
            $type = $types[0];
            list($plugin, $type) = pluginSplit($type);
            $title = Inflector::pluralize($this->postTypes[$plugin][$type]['name']);
        }
        $Setting = ClassRegistry::init('Setting');
        $channel = array(
            'title' => $Setting->get('BlogmillDefault.blogmill_site_name') . ' - ' . $title,
            'link' => Router::url('/', true),
            'description'=> $Setting->get('BlogmillDefault.blogmill_site_description')
        );
        $themeIconFile = App::pluginPath($this->activeThemePlugin) . 'webroot' . DS . 'favicon.ico';
        if ( file_exists($themeIconFile) ) {
            $iconUrl = '/' . strtolower($this->activeThemePlugin) . '/favicon.ico';
            $channel['icon'] = $channel['logo'] = Router::url($iconUrl, true);
        }
        $this->set(compact('channel'));
    }
	
	function view($id, $slug = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid post', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->__saveComment($id, $slug);
		}
        $post = $this->Post->read(null, $id);
        if ( $post===false || $post['Post']['draft'] ) {
			if ($post['Post']['user_id'] !== $this->Auth->user('id')) {
            	$this->_blogmill404Error();
			}
        }
        if ( $post['Post']['slug'] !== $slug ) {
            $this->redirect(array(
                'action' => 'view',
                'id' => $id,
                'type' => $post['Post']['type'],
                'slug' => $post['Post']['slug']
            ), 301);
        }
        $title_for_layout = $post['Post']['display'];
		$this->set(compact('post', 'title_for_layout'));
	}

	function dashboard_index($type=false) {
        if ($type) {
            $this->paginate['conditions'] = compact('type');
        }
        if (!isset($this->params['named']['sort'])) {
            $this->paginate['order'] = 'Post.id DESC';
        }
		$this->Post->contain(array('Field', 'Category', 'Attachment'));
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
        $formLayout['form-sidebar'][] = array(
			'title' => __('Category', true),
			'fields' => array(
				array('category_id' => array('empty' => true))
			)
		);
        $formLayout['form-sidebar'][] = array(
			'title' => __('Publish Date', true),
			'fields' => array(
				array('published' => array()),
			)
		);
		$formLayout['form-sidebar'][] = array(
			'title' => __('Draft', true),
			'fields' => array(
				array(
					'draft' => array(
						'label' =>  __('Keep as Draft', true),
						'after' => sprintf('<span class="note">%s</span>', __('When the publish date arrives it will not be published.', true))
					)
				),
			)
		);
		$this->Post->fields['published'] = array('label' => __('Published', true), 'type' => 'date', 'timeFormat' => 24);
		$this->set(compact('plugin', 'type', 'formLayout'));
	}
	
	private function __setCategories() {
		$categories = $this->Post->Category->find('list');
		$this->set(compact('categories'));
	}
	
	private function __savePost($type) {
		if (empty($this->data)) return;
		$this->data['Post']['type'] = $type;
        $this->data['Post']['user_id'] = $this->Auth->user('id');
		$this->__prepareData();
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
            if ( isset($this->data['Post'][$field]) ) {
                $fieldData = $this->data['Post'][$field];
                switch ($type) {
                    case 'html':
                        $fieldData = $this->Blogmill->transformHtmlEditorData( $fieldData );
                        $fieldData = str_replace('<p>&nbsp;</p>', '', $fieldData);
                        $fieldData = $this->HtmlCleaner->cleanup( $fieldData, $this->tagWhitelist );
                        break;
                    default:
                        $fieldData = $this->HtmlCleaner->cleanup( $fieldData, array() );
                        break;
                }
                $this->data['Post'][$field] = $fieldData;
            }
        }
        $this->Post->loadDisplayFields($this->data);
        $this->data = $this->Post->data;
        $excerpt = $this->data['Post']['excerpt'];
        $excerpt = preg_replace('/<br\/?\s*>/' , ' ', $excerpt);
        $excerpt = $this->HtmlCleaner->cleanup( $excerpt, array());

        $this->data['Post']['excerpt'] = $excerpt;
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
        $this->data['Comment']['approved'] = false;
        $this->data = $this->BlogmillHook->call('before_comment', $this->data);
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
        if ($plugin == '_FixedPages') {
            $this->render('menu_fixed_pages');
        } else {
    		$this->index("$plugin.$type");
        }
	}
}
