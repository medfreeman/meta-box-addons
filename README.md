# Meta Box addons

Add new field types and helpers to meta-box wordpress plugin (http://wordpress.org/plugins/meta-box/ - https://github.com/rilwis/meta-box).

## Description

Adds an embed field that works with oembed, has a button to test and see if the link posted is embeddible, and a link field with protocol verification and a open link button to test the validity of the link upon entering.
Corresponding helpers for formatting are present, and new formatters for text fields also.

## Requirements
 * WordPress 3.5 (tested up to 3.8.1)
 * PHP 5
 * Meta Box >= 4.3.6

## Installation

### Upload

1. Download the latest tagged archive (choose the "zip" option).
2. Go to the __Plugins -> Add New__ screen and click the __Upload__ tab.
3. Upload the zipped archive directly.
4. Go to the Plugins screen and click __Activate__.

### Manual

1. Download the latest tagged archive (choose the "zip" option).
2. Unzip the archive.
3. Copy the folder to your `/wp-content/plugins/` directory.
4. Go to the Plugins screen and click __Activate__.

Check out the Codex for more information about [installing plugins manually](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

### Git

Using git, browse to your `/wp-content/plugins/` directory and clone this repository:

`git clone git@github.com:medfreeman/meta-box-addons.git`

Then go to your Plugins screen and click __Activate__.

## Usage

Declare your meta boxes as usual, you now have two new fields types at your disposal, 'embed' and 'st_link'.

```PHP
function my_register_meta_boxes( $meta_boxes ) {
	$prefix = 'my-prefix';
	
	$meta_boxes[] = array(
        'id' => $prefix . 'page_info_box',
        'title' => __( 'Page information', 'my-textdomain' ),
        'pages' => array( 'pge' ),
        'context' => 'normal',
            'fields' => array( //Add a repeatable link field
			    array(
                    'name'	=> __( 'Link', 'my-textdomain' ),
                    'id'	=> $prefix . 'link',
                    'type'	=> 'st_link',
                    'clone'	=> true,
                    'size'	=> 40
                ),
				array( //Add a repeatable embed field
					'name' 	=> __( 'Videos', 'my-textdomain' ),
					'id' 	=> $prefix . 'video_links',
					'type' 	=> 'embed',
					'desc' 	=> __( 'Enter the link of the video', 'my-textdomain' ),
					'clone'	=> true,
				),
             )
     );
}
add_filter( 'rwmb_meta_boxes', 'my_register_meta_boxes' );
```

## Update

This plugin can be auto-updated via git with this plugin : https://github.com/afragen/github-updater

## Planned

Add an advanced taxonomy editor, already made but not integrated already.
