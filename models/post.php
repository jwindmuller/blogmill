<?php
App::import('Sanitize');
class Post extends AppModel {
	var $name = 'Post';

	var $actsAs = array(
        'Containable',
        'Sluggable' => array('overwrite' => true, 'translation' => 'utf-8', 'label' => 'slug'),
        'Acl' => array('type' => 'controlled'),
        'BlogmillDefault.ImageUploadable' => array('subfolderName' => false, 'contentField' => '')
    );

	var $validate = array(
		'type' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'required' => true
			)
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			)
		),
		'category_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
                'required' => true,
                'allowEmpty' => true
			)
		)
	);

	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
            'counterCache' => true,
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Category' => array(
			'className' => 'Category',
			'foreignKey' => 'category_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $hasMany = array(
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'post_id',
			'dependent' => true,
			'conditions' => array('Comment.approved' => true, 'Comment.spam' => false),
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Field' => array(
			'className' => 'Field',
			'foreignKey' => 'post_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
	/**
	 * Initialize Validation
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	protected function __initializeValidation() {
		$this->defineErrorMessage('type.required', __('Post Type not available', true));
		$this->defineErrorMessage('category_id.numeric', __('Please select a category', true));        
	}

	/**
	 * Instead of using save and saveAll, we use savePost an it does what is needed.
	 *
	 * @param string $data data of the post
	 * @return boolean true on sucess
	 * @see Post::__prepareData
	 * @author Joaquin Windmuller
	 */
	public function savePost($data) {
		$this->data = $data;
		$this->__prepareData();
        // validate => true because we're validating Fields in the main Post model. 
		$save = parent::saveAll($this->data, array('validate' => true));
		return $save;
	}
	
	/**
	 * This method modifies $this->data, generating Field entries for SaveAll.
	 * All fields except the ones listed in $defaultFields are converted to Field.
	 * If there is a post id found it sets the data to update the Fields instead of creating new ones. 
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	private function __prepareData() {
		$this->loadDisplayFields();
		$defaultFields = array(
			'id' => true,
			'created' => true,
			'modified' => true,
            'published' => true,
			'type' => true,
			'category_id' => true,
			'user_id' => true,
			'display' => false,
			'guide' => true,
			'draft' => true,
			'excerpt' => false,
			'slug' => false
		);
		$data = array_intersect_key($this->data['Post'], $defaultFields);
		$this->data['Post']['slug'] = $this->data['Post']['display'];
		$isEdit=isset($data['id']) && !empty($data['id']);
		if ($isEdit) {
			$currentFields = $this->fields($data['id']);
		}
		$fields = array_diff_key($this->data['Post'], $defaultFields);
		$post_id = $this->data['Post']['id'];
		foreach ($fields as $name => $value) {
			if (is_array($value) && isset($value['tmp_name'])) {
				if (empty($value['tmp_name'])) {
					continue;
				}
				$mime = $this->Behaviors->ImageUploadable->getImageMimeType($value['tmp_name']);
				$value = $this->Behaviors->ImageUploadable->extension($mime);
			}
			if ($isEdit && isset($currentFields[$name])) {
				$id = $currentFields[$name]['id'];
			}
			$this->data['Field'][] = compact('name', 'value', 'post_id');
		}
		foreach ($defaultFields as $field => $removeHtml) {
			if ( isset($this->data['Post'][$field]) && is_string($this->data['Post'][$field]) && $removeHtml ) {
				$this->data['Post'][$field] = Sanitize::html($this->data['Post'][$field]);
			}
		}
	}
	
	/**
	 * Loads $this->data with the display and excerpt fields.
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	public function loadDisplayFields($data = null) {
		if ($data) {
			$this->data = $data;
		}
		$type = $this->data['Post']['type'];
		list($plugin, $type) = explode('.', $type);
		$class = "${plugin}Settings";
		App::import(
			array(
				'type' => 'File',
				'name' => $class,
				'file' => APP . 'plugins' . DS . $plugin . DS . 'config' . DS . 'settings.php'
			)
		);
		if (!class_exists($class)) return;
		$class = new $class;
		$display = $class->types[$type]['display'];
		$this->data['Post']['excerpt'] = @$this->data['Post']['excerpt'] . '';
		$this->data['Post']['display'] = $this->data['Post'][$display];
		if (isset($class->types[$type]['excerpt']) && empty($this->data['Post']['excerpt'])) {
			$excerpt = $class->types[$type]['excerpt'];
			$this->data['Post']['excerpt'] = $this->data['Post'][$excerpt];
		}
	}
	
	/**
	 * After a find we need to transform Fields into indexes of the Post data.
	 *
	 * @param string $results results from Cake
	 * @param string $primary is primary model
	 * @return void updated results.
	 * @author Joaquin Windmuller
	 */
	public function afterFind($results, $primary) {
		if ($primary && isset($results[0]) && !isset($results[0][0]['count'])) {
			if (!isset($results[0]['Field']) || empty($results[0]['Field'])) {
				return $results;
			}
			foreach ($results as $i => $result) {
				$fields = $result['Field'];
				foreach ($fields as $field) {
					$results[$i]['Post'][$field['name']] = $field['value'];
				}
			}
		}
		return $results;
	}
	
	/**
	 * Returns the fields of a post indexed by their name.
	 *
	 * @param string $post_id id of the post
	 * @return array Fields, indexed by the name: [name] => array([id] => ..., [name] => ..., [value] => ...)
	 * @author Joaquin Windmuller
	 */
	public function fields($post_id) {
		$this->Field->recursive = -1;
		$fields = $this->Field->find('all', array('conditions' => compact('post_id'), 'fields' => array('id', 'name')));
		$fieldsByName = array();
		foreach ($fields as $i => $field) {
			$fieldsByName[$field['Field']['name']] = $field['Field'];
		}
		return $fieldsByName;
	}

    public function parentNode() {
        return 'data';
    }
}
?>
