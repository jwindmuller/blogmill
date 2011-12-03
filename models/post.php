<?php
App::import('Sanitize');
class Post extends AppModel {
	var $name = 'Post';
	var $actsAs = array(
        'Containable',
        'Sluggable' => array('overwrite' => true, 'translation' => 'utf-8', 'label' => 'display'),
	    'Acl' => array('type' => 'controlled')
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
		),
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
	 * Validation method that makes sure that the data corresponds to a real image.
	 * Requires GD installed
	 *
	 * @param array $data file data
	 * @return void
	 * @author Joaquin Windmuller
	 */
	public function validateImage($data, $width=null, $height=null, $rule=null) {
		if (!$rule) {
			$rule = $height;
			if (!$rule) {
				$rule = $height;
			}
		}
		$field_name = array_pop(array_keys($data));
		$data = $data[$field_name];
		$is_required = isset($rule['required']) && $rule['required'];
		
		// No upload
		if ($data['size'] == 0) {
			if ($is_required) return false;
			else return true;
		}
		
		// Only accept real uploads
		if (!$this->__isUploadedFile($data, $is_required)) {
			return false;
		}
		
		// Allow these image mime-types, all others will be rejected
		$valid_mime_types = array('image/jpeg', 'image/png', 'image/gif');
		$filename = $data['tmp_name'];

		// Catch I/O Errors.
		if (!is_readable($filename)) {
			$this->log(__METHOD__." failed to read input file: {$filename}");
			return false;
		}

		// Retrieve the MimeType of Image, if none is returned, it's invalid
		if (!$mime_type = $this->__getImageMimeType($filename)) {
			$this->log(__METHOD__." Uploaded file does not have a mime-type");
			return false;
		}

		// Check the MimeType against the array of valid ones specified above
		if (!in_array($mime_type, $valid_mime_types)) {
			$this->log(__METHOD__." Uploaded image has rejected Mime Type: {$mime_type}");
			return false;
		}

		if (!$this->__getImageHandleFromFile($filename)) {
			return false;
		}
		
		if (is_numeric($width) || is_numeric($height)) {
			$size = getimagesize($filename);
			if (is_numeric($width) && ($size[0]>$width+10 || $size[0]<$width-10)) {
				return false;
			}
			if (is_numeric($height) && ($size[1]>$height+10 || $size[1]<$height-10)) {
				return false;
			}
		}
		$ext = $this->__extension($mime_type);
		return $this->__upload($field_name, $ext);
	}
	
	private function __extension($mime_type) {
		$ext = str_replace('image/', '', $mime_type);
		$ext = $ext == 'jpeg' ? 'jpg' : $ext;
		return $ext;
	}
	
	/**
	 * Moves the uploaded files to its final destination.
	 *
	 * @param string $field_name name of the field
	 * @param string $ext extension
	 * @return boolean TRUE on success
	 * @author Joaquin Windmuller
	 */
	private function __upload($field_name, $ext) {
		$guide = $this->data['Post']['guide'];
		$upload_dir = WWW_ROOT . 'files' . DS . $guide;
		$upload_file_to = $upload_dir . DS . $field_name . '.' . $ext;
		$tmp_name = $this->data['Post'][$field_name]['tmp_name'];
		$folder = new Folder($upload_dir, true);
		$files = $folder->find("{$field_name}.*");
		foreach ($files as $file) {
			$file = new File($folder->path . DS . $file);
			$file->delete();
		}
		return is_dir($upload_dir) && file_exists($upload_dir) && move_uploaded_file($tmp_name, $upload_file_to);
	}
	
	/**
	 * Basic check for file upload data. Check if is_uploaded_file
	 *
	 * @param string $filename path to file
	 * @return boolean true if is an uploaded file.
	 * @author Joaquin Windmuller
	 */
	private function __isUploadedFile($data, $required) {
		// Check for Basic PHP file errors.
		if ($data['error'] !== 0) {
			return false;
		}
		// Finally, use PHP's own file validation method.
		return is_uploaded_file($data['tmp_name']);
	}

	/**
	 * Function that returns the mime type as defined by LibGD
	 *
	 * @param string $filename path to file
	 * @return mixed string mime type if file is image, false if not.
	 * @author Joaquin Windmuller
	 */
	function __getImageMimeType($filename) {
		// If this error is thrown LibGD is not installed on your server.
		if (!function_exists('getimagesize')) {
			$this->log(__METHOD__." LibGD PHP Extension was not found, please refer to http://www.php.net/manual/en/book.image.php");
			exit();
		}
		$result = getimagesize($filename);
		if (isset($result['mime'])) {
			return $result['mime'];
		}
		return false;
	}

	/**
	 * Returns a file handler for an image. Support jpg, gif and png.
	 *
	 * @param string $filename path to file
	 * @return mixed file handler or false if the mime type is not supported or on errors.
	 * @author Joaquin Windmuller
	 */
	function __getImageHandleFromFile($filename) {
		if (!is_readable($filename)) {
			$this->log(__METHOD__." failed to read input file: {$filename}");
			return false;
		}

		// Retrieve the MimeType of Image, if none is returned, it's invalid
		if (!$mime_type = $this->__getImageMimeType($filename)) {
			$this->log(__METHOD__." failed to assertain MimeType of {$filename}");
			return false;
		}
		$mime_type = str_replace('image/', '', $mime_type);
		switch ($mime_type) {
			case 'jpeg':
			case 'gif':
			case 'png':
				$function = 'imagecreatefrom' . $mime_type;
				if (!function_exists($function)) {
					$this->log(__METHOD__." {$function} not found, install LibGD");
				}
				$handle = @$function($filename);
				break;
			default:
				$this->log(__METHOD__." Didn't know how to handle MimeType: {$mime_type}");
				$handle = false;
				break;
		}
		return $handle;
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
			'display' => true,
			'guide' => true,
			'draft' => true,
			'excerpt' => false
		);
		$data = array_intersect_key($this->data['Post'], $defaultFields);
		$isEdit=isset($data['id']) && !empty($data['id']);
		if ($isEdit) {
			$currentFields = $this->fields($data['id']);
		}
		$fields = array_diff_key($this->data['Post'], $defaultFields);
		if (!$isEdit) {
			$this->data['Post']['guide'] = String::uuid();
		}
		foreach ($fields as $name => $value) {
			if (is_array($value) && isset($value['tmp_name'])) {
				if (empty($value['tmp_name'])) {
					unset($this->data['Post'][$name]);
					continue;
				}
				$value = $this->__extension($this->__getImageMimeType($value['tmp_name']));
			}
			$id = '';
			if ($isEdit && isset($currentFields[$name])) {
				$id = $currentFields[$name]['id'];
			}
			$this->data['Field'][] = compact('name', 'value', 'id');
		}
		foreach ($defaultFields as $field => $keepHtml) {
			if ( isset($this->data['Post'][$field]) && $keepHtml && is_string($this->data['Post'][$field]) ) {
				$this->data['Post'][$field] = Sanitize::html($this->data['Post'][$field]);
			}
		}
	}
	
	/**
	 * Loads $this->data with the display field.
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
		$this->data['Post']['display'] = $this->data['Post'][$display];
		if (isset($class->types[$type]['excerpt']) && (!isset($this->data['Post']['excerpt']) || $this->data['Post']['excerpt']=='')) {
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
