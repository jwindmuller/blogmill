<?php
class BlogmillDefaultSettings extends BlogmillSettings {
    var $types = array();
    var $configurable;
    var $theme;
    var $attachmentDefinitions = array();

    function __construct() {
        $this->theme = array(
            'name' => __('Blogmill Default Theme', true),
            'description' => __('Bare bones theme', true),
            'author' => 'Windmill Information Technology Inc.',
            'author_url' => 'http://windmuller.ca',
            'version' => '1.0',
            'menus' => array(),
            'layouts' => array(
                'home' => array(
                    'name' => 'default',
                    'load_menus' => array(),
                    'data' => array(
                        'posts' => array(
                            'type' => '*',
                            'limit' => 10,
                            'order' => 'Post.created DESC'
                        )
                    )
                ),
                'inner' => array(
                    'name' => 'default',
                    'load_menus' => array()
                )
            ),
            'post_type_decorators' => array()
        );

        $this->configurable = array(
            'blogmill_site_name' => array(
                'label' => __('Website name', true)
            ),
            'blogmill_site_description' => array(
                'label' => __('Description or tagline for the site', true)
            ),
            'blogmill_contact_email' => array(
                'label' => __('Contact email', true),
                'longdesc' => __('Write the contact email where you want to receive emails.', true)
            ),
            'blogmill_editor' => array(
                'label' => __('Editor to use', true),
                'longdesc' => __('Select the preferred editor to write html content.', true),
                'by_user' => true,
                'hookable' => true,
                'default' => 'html'
            ),
        );

        $this->attachmentDefinitions = array(
            'image' => array(
                'name' => __('Images', true),
                'label' => __('Attach an image to this post', true),
                'type' => 'image'
            )
        );
    }
}
