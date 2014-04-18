# Meta Box addons

Add new field types and helpers to meta-box wordpress plugin (http://wordpress.org/plugins/meta-box/ - http://www.deluxeblogtips.com/meta-box/ - https://github.com/rilwis/meta-box/).

## Description

Adds an embed field that works with oembed, has a button to test and see if the link posted is embeddible, and a link field with protocol verification and a open link button to test the validity of the link upon entering.


Corresponding helpers for rwmb_meta formatting are present, and new formatters for text fields (text and textarea) have been added : 'qtranslate' that allows qtranslate field translation (needs qTranslate plugin), and 'nl2p' that creates html breaks in place of line breaks / paragraphs in place of double line breaks.

See [usage](#usage) section for more information

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

## <a name="usage"></a>Usage

Declare your meta boxes as usual, you now have two new fields types at your disposal, 'embed' and 'st_link'.

```PHP
function my_register_meta_boxes( $meta_boxes ) {
	$prefix = 'my-prefix-';
	
	$meta_boxes[] = array(
        'id' => $prefix . 'page_info_box',
        'title' => __( 'Page information', 'my-textdomain' ),
        'pages' => array( 'page' ),
        'context' => 'normal',
            'fields' => array( //Add a repeatable link field
				array(
                    'name'		=> __( 'My additional text', 'my-textdomain' ),
                    'id'		=> $prefix . 'text',
                    'type'		=> 'text',
                    'size' 		=> 10
                ),
			    array(
                    'name'	=> __( 'Link(s)', 'my-textdomain' ),
                    'id'	=> $prefix . 'links',
                    'type'	=> 'st_link',
                    'clone'	=> true,
                    'size'	=> 40
                ),
				array( //Add a repeatable embed field
					'name' 	=> __( 'Video(s)', 'my-textdomain' ),
					'id' 	=> $prefix . 'videos',
					'type' 	=> 'embed',
					'desc' 	=> __( 'Enter the link of the video', 'my-textdomain' ),
					'clone'	=> true,
				),
             )
     );
}
add_filter( 'rwmb_meta_boxes', 'my_register_meta_boxes' );
```

To retrieve formatted values, use rwmb_meta function as such :

```PHP
//LINK FIELD

//raw link
$meta = rwmb_meta( 'my-prefix-links', array(), $post_id );
//formatted link / list
$meta = rwmb_meta( 'my-prefix-links', array( 'type' => 'st_link', 'target' => '_blank', 'format' => 'list', 'separator' => ' | ' ), $post_id );

//EMBED FIELD

//raw embed url
$video_embeds = rwmb_meta( 'my-prefix-videos', array(), $post_id );
//oembed with forced dimensions (depending on provider)
$video_embeds = rwmb_meta( 'my-prefix-videos', array( 'type' => 'embed', 'width' => '940', 'height' => '250' ) );

//TEXT FIELDS FORMATTING (translation + paragraphs and line breaks)
$meta = rwmb_meta( 'my-prefix-text', array( 'type' => 'text', 'qtranslate' => true, 'nl2p' => $nl2p ), $post_id );

```

Please go to http://www.deluxeblogtips.com/meta-box/helper-function-to-get-meta-value/ for reference information.

## Update

This plugin can be auto-updated via git with this plugin : https://github.com/afragen/github-updater

## Planned

Add an advanced taxonomy field, made but not integrated in this plugin already.
