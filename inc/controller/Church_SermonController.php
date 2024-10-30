<?php
/**
 * @version 1.0.13
 *
 * @package K7Church/inc/controller
 */

defined('ABSPATH') || exit;


class Church_SermonController extends Church_BaseController

{
    private static $sermon_trading_hour_days = array();
    public $settings;
    public $callbacks;

    public static function ch_Sermon_cpt()
    {

        //Labels for post type
        $labels = array(
            'name' => __('Sermon', 'k7-church'),
            'singular_name' => __('Sermon', 'k7-church'),
            'menu_name' => __('Sermons', 'k7-church'),
            'name_admin_bar' => __('Sermon', 'k7-church'),
            'add_new' => __('Add New', 'k7-church'),
            'add_new_item' => __('Add New Sermon', 'k7-church'),
            'new_item' => __('New Sermon', 'k7-church'),
            'edit_item' => __('Edit Sermon', 'k7-church'),
            'view_item' => __('View Sermon', 'k7-church'),
            'all_items' => __('All Sermons', 'k7-church'),
            'search_items' => __('Search Sermons', 'k7-church'),
            'parent_item_colon' => __('Parent Sermon:', 'k7-church'),
            'not_found' => 'No Sermons found.',
            'not_found_in_trash' => __('No Sermons found in Trash.', 'k7-church'),
        );
        //arguments for post type
        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_nav' => true,
            'query_var' => true,
            'hierarchical' => true,
            'supports' => array('title', 'thumbnail', 'editor', 'comments'),
            'has_archive' => true,
            'menu_position' => 20,
            'show_in_admin_bar' => true,
            'menu_icon' => 'dashicons-welcome-write-blog',
            'rewrite' => array('slug' => 'sermon', 'with_front' => 'true')
        );
        //register post type
        register_post_type('sermon', $args);
    }


    //shortcode display

    public function ch_register()
    {

        $this->settings = new Church_SettingsApi();

        $this->callbacks = new Church_SermonCallbacks();


        add_action('init', array($this, 'ch_Sermon_cpt')); //register sermon content type
        add_action('add_meta_boxes', array($this, 'ch_add_sermon_meta_boxes')); //add meta boxes
        add_action('save_post_sermon', array($this, 'ch_save_sermon')); //save sermon
        add_filter('the_content', array($this, 'ch_prepend_sermon_meta_to_content')); //gets our meta data and dispayed it before the content
        add_shortcode('sermon', array($this, 'ch_sermon_shortcode_output'));
    }
    
    public function ch_sermon_shortcode_output($atts, $content = '', $tag)
    {

        //build default arguments
        $arguments = extract(shortcode_atts(array(
                'sermon_id' => '',
                'number_of_sermon' => -1)
           , $atts, $tag));


        //uses the main output function of the sermon class
        return $this->ch_get_sermon_output($arguments);

    }

    //adding meta boxes for the sermon content type*/

    public function ch_get_sermon_output($arguments = "")
    {


        //default args
        $default_args = array(
            'sermon_id' => '',
            'number_of_sermon' => -1
        );

        //update default args if we passed in new args
        if (!empty($arguments) && is_array($arguments)) {
            //go through each supplied argument
            foreach ($arguments as $arg_key => $arg_val) {
                //if this argument exists in our default argument, update its value
                if (array_key_exists($arg_key, $default_args)) {
                    $default_args[$arg_key] = $arg_val;
                }
            }
        }

        //find sermon
        $sermon_args = array(
            'post_type' => 'sermon',
            'posts_per_page' => $default_args['number_of_sermon'],
            'post_status' => 'publish'
        );
        //if we passed in a single sermon to display
        if (!empty($default_args['sermon_id'])) {
            $sermon_args['include'] = $default_args['sermon_id'];
        }

        //output
        $html = '';
        $sermon = get_posts($sermon_args);
        //if we have sermon
        if ($sermon) {
            $html .= '<article class="ch-col-12 sermon_list cf">';
            //foreach sermon
            foreach ($sermon as $sermon) {
                $html .= '<section class="ch-col-12 sermon">';
                //collect sermon data
                $sermon_id = $sermon->ID;
                $sermon_title = get_the_title($sermon_id);
                $sermon_thumbnail = get_the_post_thumbnail($sermon_id, 'thumbnail');
                $sermon_content = apply_filters('the_content', $sermon->post_content);

                if (!empty($sermon_content)) {
                    $sermon_content = strip_shortcodes(wp_trim_words($sermon_content, 40, '...'));
                }
                $sermon_permalink = get_permalink($sermon_id);
                $sermon_vers = get_post_meta($sermon_id, 'sermon_vers', true);
                $sermon_author = get_post_meta($sermon_id, 'sermon_author', true);
                // $sermon_image = get_post_meta($sermon_id, 'sermon_image', true);

                //apply the filter before our main content starts
                //(lets third parties hook into the HTML output to output data)
                $html = apply_filters('sermon_before_main_content', $html);

                //title
                $html .= '<h2 class="ch-title">';
                $html .= '<a href="' . esc_url($sermon_permalink) . '" title="' . esc_attr__('view sermon', 'k7-church') . '">';
                $html .= $sermon_title;
                $html .= '</a>';
                $html .= '</h2>';


                //image & content
                if (!empty($sermon_thumbnail) || !empty($sermon_content)) {

                    if (!empty($sermon_thumbnail)) {
                        $html .= '<p class="image_content">';
                        $html .= $sermon_thumbnail;
                        $html .= '</p>';
                    }
                    if (!empty($sermon_content)) {
                        $html .= '<p>';
                        $html .= $sermon_content;
                        $html .= '</p>';
                    }

                }

                //phone & email output
                if (!empty($sermon_vers) || !empty($sermon_author)) {
                    $html .= '<p class="phone_email">';
                    if (!empty($sermon_vers)) {
                        $html .= '<b>' . __('Passages', 'k7-church') . ': </b>' . $sermon_vers . '</br>';
                    }
                    if (!empty($sermon_author)) {
                        $html .= '<b>' . __('Author', 'k7-church') . ': </b>' . $sermon_author;
                    }
                    $html .= '</p>';
                }

                //apply the filter after the main content, before it ends
                //(lets third parties hook into the HTML output to output data)
                $html = apply_filters('sermon_after_main_content', $html);

                //readmore
                $html .= '<a class="link" href="' . esc_url($sermon_permalink) . '" title="' . esc_attr__('view sermon', 'k7-church') . '">' . __('View Sermon', 'k7-church') . '</a>';
                $html .= '</section>';
            }
            $html .= '</article>';
            $html .= '<div class="cf"></div>';
        }

        return $html;
    }

    //display function used for our custom sermon meta box*/

    public function ch_add_sermon_meta_boxes()
    {

        add_meta_box(
            'sermon_meta_box', //id
            __('Sermon Information', 'k7-church'), //name
            array($this, 'ch_sermon_meta_box_display'), //display function
            'sermon', //post type
            'normal', //sermon
            'default' //priority
        );

    }

    public function ch_sermon_meta_box_display($post)
    {

        //set nonce field
        wp_nonce_field('sermon_nonce', 'sermon_nonce_field');

        //collect variables
        $sermon_vers = get_post_meta($post->ID, 'sermon_vers', true);
        $sermon_author = get_post_meta($post->ID, 'sermon_author', true);
        $sermon_description = get_post_meta($post->ID, 'sermon_description', true);
        // $sermon_image = get_post_meta($post->ID, 'sermon_image', true);
        $sermon_video = get_post_meta($post->ID, 'sermon_video', true);


        ?>
        <p><?php _e('Enter additional information about your sermon', 'k7-church'); ?></p>
        <div class="field-container">
            <?php
            //before main form elementst hook
            do_action('sermon_admin_form_start');
            ?>
            <div class="field">
                <label for="sermon_vers"><?php _e('Passages for the sermon', 'k7-church'); ?></label><br/>
                <small><?php _e('Biblical Passages', 'k7-church'); ?></small>
                <input type="text" name="sermon_vers" spellcheck="true" id="sermon_vers"
                       value="<?php echo $sermon_vers; ?>" autocomplete="off"/>
            </div>
            <hr>
            <div class="field">
                <label for="sermon_author"><?php _e('Author', 'k7-church'); ?></label><br/>
                <input type="text" name="sermon_author" id="sermon_author"
                       value="<?php echo $sermon_author; ?>" autocomplete="off"/>
            </div>
            <?php /**
            <div class="field">
                <label for="sermon_author"><?php _e('Author', 'k7-church'); ?></label><br/>
            <input class="widefat image-upload" id=" "
                   name="sermon_image" type="text"
                   value="<?php echo $sermon_image; ?>">
            <button type="button" class="button button-primary js-image-upload">Select Image</button>
            <img src="<?php echo $sermon_image; ?>">
            </div>
            */?>
            <div class="field">
                <label for="sermon_video"><?php _e('Embed video URL', 'k7-church'); ?></label><br/>
                <input type="text" name="sermon_video" id="sermon_video"
                       value="<?php echo $sermon_video; ?>" autocomplete="off"/>
            </div>

            <hr>
            <div class="field">
                <label for="sermon_description"><?php _e('Description', 'k7-church'); ?></label><br/>
                <textarea name="sermon_description"
                          id="sermon_description"><?php echo $sermon_description; ?></textarea>
            </div>

            <?php
            //after main form elementst hook
            do_action('sermon_admin_form_end');
            ?>
        </div>
        <?php

    }

    //main function for displaying sermon (used for our shortcodes and widgets)

    public function ch_prepend_sermon_meta_to_content($content)
    {

        global $post, $post_type;

        //display meta only on our sermon (and if its a single sermon)
        if ($post_type == 'sermon' && is_singular('sermon')) {

            //collect variables
            $sermon_id = $post->ID;
            $sermon_vers = get_post_meta($post->ID, 'sermon_vers', true);
            $sermon_author = get_post_meta($post->ID, 'sermon_author', true);
            $sermon_video = get_post_meta($post->ID, 'sermon_video', true);
            $sermon_description = get_post_meta($post->ID, 'sermon_description', true);

            //display
            $html = '';

            $html .= '<section class="ch-col-12 meta-data">';

            //hook for outputting additional meta data (at the start of the form)
            do_action('sermon_meta_data_output_start', $sermon_id);

            $html .= '<p classs="ch-row ch-col-12"><br>';
            $html .= the_title( sprintf('<h2 class="ch-text-a"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>' );
            //Passages of the sermon:
            if (!empty($sermon_vers)) {
                $html .= '<b>' . __('Passages of the sermon:', 'k7-church') . '</b> ' . esc_html($sermon_vers) . '</br>';
            }
            //Author of the sermon
            if (!empty($sermon_author)) {
                $html .= '<b>' . __('Author of the sermon:', 'k7-church') . '</b> ' . esc_html($sermon_author) . '</br>';
            }
            //description
            if (!empty($sermon_description)) {
                $html .= '<b class="ch-right">' . __('Description of the Sermon:', 'k7-church') . '</b><br><br><i>' .  esc_html($sermon_description) . '</i></br>';
            }

            // video
            if (!empty($sermon_video)) {

                $html .= wp_oembed_get($sermon_video) . '</br>';
            }

            $html .= '</p><img src=' .$this->plugin_url.'/assets/icon/straight-horizontal-line.svg style="width:45%; height:100px;"><img src=' .$this->plugin_url.'/assets/icon/cross.svg style="width:40px; height:80px;"><img src=' .$this->plugin_url.'/assets/icon/straight-horizontal-line.svg style="width:45%; height:100px;">';

            //hook for outputting additional meta data (at the end of the form)
            do_action('sermon_meta_data_output_end', $sermon_id);

            $html .= '</section>';
            $html .= $content;

            return $html;


        } else {
            return $content;
        }

    }

    //triggered when adding or editing a sermon

    public function ch_save_sermon($post_id)
    {

        //check for nonce
        if (!isset($_POST['sermon_nonce_field'])) {
            return $post_id;
        }
        //verify nonce
        if (!wp_verify_nonce($_POST['sermon_nonce_field'], 'sermon_nonce')) {
            return $post_id;
        }
        //check for autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        //get our phone, email and description fields
        $sermon_vers = isset($_POST['sermon_vers']) ? sanitize_text_field($_POST['sermon_vers']) : '';
        $sermon_author = isset($_POST['sermon_author']) ? sanitize_text_field($_POST['sermon_author']) : '';
        $sermon_description = isset($_POST['sermon_description']) ? sanitize_textarea_field($_POST['sermon_description']) : '';
        // $sermon_image = isset($_POST['sermon_image']) ? sanitize_textarea_field($_POST['sermon_image']) : '';
        $sermon_video = isset($_POST['sermon_video']) ? sanitize_textarea_field($_POST['sermon_video']) : '';

        //update phone, memil and description fields
        update_post_meta($post_id, 'sermon_vers', $sermon_vers);
        update_post_meta($post_id, 'sermon_author', $sermon_author);
        update_post_meta($post_id, 'sermon_description', $sermon_description);
        update_post_meta($post_id, 'sermon_video', $sermon_video);


        //sermon save hook
        //used so you can hook here and save additional post fields added via 'sermon_meta_data_output_end' or 'sermon_meta_data_output_end'
        do_action('sermon_admin_save', $post_id, $_POST);


    }

}

