<?php
/* Attachment Fixture generated on: 2012-10-05 22:42:22 : 1349476942 */
class AttachmentFixture extends CakeTestFixture {
    var $name = 'Attachment';

    var $fields = array(
        'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
        'post_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
        'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 256, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
        'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 256, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
        'extension' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 160, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
        'contents' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
    );

    var $records = array(
        array(
            'id' => '506f624e-8c18-4982-9d6c-4062fb8c9e6b',
            'post_id' => 1,
            'type' => 'Lorem ipsum dolor sit amet',
            'name' => 'Lorem ipsum dolor sit amet',
            'extension' => 'Lorem ipsum dolor sit amet',
            'contents' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.'
        ),
    );
}
