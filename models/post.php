<?php
class Post extends AppModel {
	var $name = 'Post';
	var $actsAs = array('containable');
	var $validate = array(
		'post_type_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'category_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
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
			'dependent' => false,
			'conditions' => '',
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
			'dependent' => false,
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
	 * Instead of using save and saveAll, we use savePost an it does what is needed.
	 *
	 * @param string $data data of the post
	 * @return boolean true on sucess
	 * @see Post::__prepareData
	 * @author Joaquin Windmuller
	 */
	public function savePost($data = null) {
		$this->data = $data;
		$this->__prepareData();
		return parent::saveAll($this->data, array('validate' => true));
	}
	
	/**
	 * This method modifies $this->data, generating Field entries for SaveAll.
	 * All fields except created, modified, type and id are converted to Field.
	 * If there is a post id found it sets the data to update the Fields instead of creating new ones. 
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	private function __prepareData() {
		$defaultFields = array('created' => true, 'modified' => true, 'type' => true, 'id' => true);
		$data = array_intersect_key($this->data['Post'], $defaultFields);
		$isEdit=isset($data['id']) && !empty($data['id']);
		if ($isEdit) {
			$currentFields = $this->fields($data['id']);
		}
		$fields = array_diff_key($this->data['Post'], $defaultFields);
		foreach ($fields as $name => $value) {
			$id = '';
			if ($isEdit) {
				$id = $currentFields[$name]['id'];
			}
			$this->data['Field'][] = compact('name', 'value', 'id');
		}
	}
	
	/**
	 * After a find we need to transfortm Fields into indexes of the Post data.
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
			$fields = $results[0]['Field'];
			foreach ($fields as $field) {
				$results[0]['Post'][$field['name']] = $field['value'];
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
}
?>