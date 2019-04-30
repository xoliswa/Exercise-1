<?php
/**
 * @package Update_Custom_Post_Meta
 * @version 1.0
 */
/*
Plugin Name: Update Custom Post Meta
Description:  Post meta via REST, rewrite endpoint, and WP CLI
Author: Xoliswa Shandu
Version: 1.0
*
*/

abstract class Arbitrary_Meta_Box
{
    public static function add()
    {
        $meta_boxes = ['post', 'page'];
        foreach ($meta_boxes as $meta_box) {
            add_meta_box(
                'meta_box_id',          // Unique ID
                'Arbitrary Meta Data',   // Box title
                [self::class, 'html'],  // Content callback, must be of type callable
                $meta_box               // Post type
            );
        }
    }

    public static function save($post_id)
    {
        if (array_key_exists('arbitrary_meta_field', $_POST)) {
            update_post_meta(
                $post_id,
                '_arbitrary_meta_key',
                $_POST['arbitrary_meta_field']
            );
        }
    }

    public static function html($post)
    {
        $value = get_post_meta($post->ID, '_arbitrary_meta_key', true);
        ?>
        <label for="arbitrary_meta_field">Add Meta Post Data</label> 
        <input name="arbitrary_meta_field" id="arbitrary_meta_field" value="<?php echo $value; ?>">
        <?php
    }
}

// Creating custom meta_box and saving on post save
add_action('add_meta_boxes', ['Arbitrary_Meta_Box', 'add']);
add_action('save_post', ['Arbitrary_Meta_Box', 'save']);




function get_custom_post_meta(){
  register_rest_route('custom', 'post-meta-value', array(
    'methods' => 'GET', 
    'callback' => 'display_custom_post_meta'
  ));
}

function display_custom_post_meta(){
 $meta = get_post_meta( 1 ); 
 return  $meta['_arbitrary_meta_key'];
}


// Retrieving custom meta post data via custom api route
add_action('rest_api_init',  'get_custom_post_meta');

?>