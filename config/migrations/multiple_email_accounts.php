<?php
class M4f7208182064464a979e40b9fb8c9e6b extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
    public $description = '';
    private $emailSettingKey = 'BlogmillDefault.blogmill_contact_email';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
    public $migration = array(
        'up' => array(
        ),
        'down' => array(
        ),
    );

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
    public function before($direction) {
        return true;
    }

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
    public function after($direction) {
        $this->Setting = ClassRegistry::init('Setting');
        if ( $direction == 'up') {
            $this->__emailToArray();
        } else {
            $this->__arrayToEmail();
        }
        return true;
    }

    private function __emailToArray() {
        $newEmailData = array();
        $blogmillContactEmail = $this->Setting->get($this->emailSettingKey);
        if ( !is_array($blogmillContactEmail) && !empty($blogmillContactEmail) ) {
            $newEmailData = array(array(
                'name' => __('Default Contact Email', true),
                'email' => $blogmillContactEmail
            ));
        }
        $this->Setting->store( $this->emailSettingKey, $newEmailData );
    }

    public function __arrayToEmail() {
        $newEmailData = '';
        $blogmillContactEmail = $this->Setting->get($this->emailSettingKey);
        if ( is_array($blogmillContactEmail) ) {
            $newEmailData = $blogmillContactEmail[0]['email'];
        }
        $this->Setting->store( $this->emailSettingKey, $newEmailData );
    }
}
