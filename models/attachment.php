<?php
class Attachment extends AppModel {
    var $name = 'Attachment';
    var $displayField = 'name';
    private $type;
    private $behaviorName;

    public $validate = array(
        'post_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'plugin' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'type' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        )
    );

    public function setup($plugin, $type, $guide) {
        $id = $this->data['Attachment']['id'] = String::uuid();
        $this->data['Attachment']['post_id'] = $guide;
        $this->data['Attachment']['plugin'] = $plugin;
        $this->data['Attachment']['type'] = $type;
        $this->type = $type;
        $behaviorName = $plugin . '.' . Inflector::camelize($type) . 'Uploadable';

        $this->validate[$type] = array('valid' => array(
            'rule' => 'validate' . Inflector::camelize($type),
            'required' => true,
            'message' => __('Attachment is invalid', true)
        ));
        $this->Behaviors->attach(
            $behaviorName,
            array('uploadName' => $id)
        );
    }

    public function beforeValidate() {
        $dataForType = $this->data[$this->alias][$this->type];
        $behaviorName = Inflector::camelize($this->type) . 'Uploadable';
        $this->data[$this->alias]['contents'] = $this->contentsForData($dataForType);
        return true;
    }
}