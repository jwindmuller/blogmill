Blogmill
========

Blogmill is a CMS based on custom post types and is developed on top of CakePHP.

Blogmill provides a generic engine for defining posts and it's fields and uses CakePHP
plugins to define those post types. It also allows plugins to define themes, modify 
the admin area to add options and expose utility functions for different actions like 
interacting with twitter, flickr, etc.

Installation
------------

To install Blogmill, you need to: 

1. Setup CakePHP 1.3 in your server (for this example, http://localhost/blogmill).
2. Replace the app folder with Blogmill.
3. Add the plugins that you're interested in to the APP/plugins folder.
4. Head to http://localhost/blogmill to setup the first user.

Post Types & Fields
-------------------

Post Types are data objects made up of fields. They are the center of the extensibility of Blogmill because they allow anyone to define their own types to solve their own needs.

Some examples of Post Types can be a journal entry, a book review, a link, etc. Each one of those have different fields that define them. For example:

* A journal entry has a title and a content.
* A book review can have a title, a content, and 5 star ratings for character development, story and writing style.
* A link will have a title, URL and description.

As you can see, each post type has different fields and each field can have different types (the content of the journal entry will allow html but the URL field of the link should be a valid URL).

### Defining Post Types
Post types can be defined by plugins, and for each post type it's fields. To define a 
post type you need to define it in the config/settings.php file of the plugin, in this example we define the Journal post type:

    $this->types = array(
        'Journal' => array(
            'name' => __d('journal', 'Journal Entry', true),
            'fields' => array(
                'title' => array(
                    'label' => __d('journal', 'Title', true),
                    'type' => 'text',
                ),
                'content' => array(
                    'label' => __d('journal', 'Content', true),
                    'type' => 'html',
                ),
            ),  
            'form_layout' => array(
                'form-main' => array(
                    array(
                        'fields' => array('title', 'content'),
                        'width' => '100%'
                    )
                ),
                'form-sidebar' => array()
            ),
            'display' => 'title'
        )
    );

The types available for fields are:

* **html** - it will show a textarea and will be transformed into a TinyMCE html editor.
Validation of these fields will remove all except the allowed tags (see PostController::tagWhitelist).
* **image** - it will show a file field and will handle validation of dimensions automatically.
* **values** - it will show a star rating selector.
* **longtext** - normal textarea (no html).

Themes
------

Blogmill currently comes default with a bare bones (no styles) theme which will be replaced
eventually with a real generic theme that will show the basics of theme development for 
Blogmill.

Plugins may define themes, to do that the config/settings.php file needs something like this:

    $this->theme = array(
        'name' => 'ThemeName',
        'version' => '1.0',
        'menus' => array(
            'top_menu' => array(
                'name' => 'Top Menu',
                'description' => 'Menu at the top of the website'
            )
        ),
        'layouts' => array(
            'home' => array(
                'name' => 'home',
                'data' => array(
                    'latest_post' => array(
                            'type' => 'Journal.Journal',
                            'limit' => 1,
                            'order' => 'Post.created DESC'
                        )
                ),
                'load_menus' => array('top_menu'),
                'helpers' => array()
            ),
            'inner' => array(
                'name' => 'inner',
            ),
            'contact' => array(
                'name' => 'inner'
            )
        ),
        'post_type_decorators' => array()
    );

The variable this->theme is an array that allows the following keys:

* **name** - Name of the theme.
* **version** - Version of the theme.
* **menus** - definition of menus available on the theme.
* **layouts** - definition of layout to use by page type (see BlogmillComponent::__currentPage to see the pages available). Each layout definition allows the following keys:
    * **name** - name of the file to use (placed on views/layouts/).
    * **data** - data to load, an array of data sets (the data set name is the key) that the layout file expects:
        * **type** - post type to use, use PluginName.PostType syntax.
        * **limit** - how many posts the layout needs.
        * **order** - sort order.
    * **post\_type\_decorators** - see the Post Type Decorators section.

Post Type Decorators
--------------------

TODO

Hooks
-----

Blogmill has support to define new hooks where plugins can attach its functions. Currently 
the following hooks are available:

* **Custom menus** - BlogmillHelper::menu can be used by themes to define menus. The interface
for selecting the contents of the menu will be available automatically in the admin area.
* **Custom html editor** - themes can define new ways to edit html fields, for example instead of TinyMCE (the default) use Markdown.
* **Script for bottom** - allow plugins to load javascript at the end of the theme instead of
the head.

