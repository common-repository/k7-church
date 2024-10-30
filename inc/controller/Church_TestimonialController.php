<?php
/**
 * @version 1.0.13
 *
 * @package K7Church/inc/controller
 */

defined('ABSPATH') || exit;


class Church_TestimonialController extends Church_BaseController
{
    public $settings;

    public $callbacks;

    public function ch_register()
    {
        if (!$this->ch_activated('testimonial_manager')) return;

        $this->settings = new Church_SettingsApi();

        $this->callbacks = new Church_TestimonialCallbacks();

        add_action('init', array($this, 'ch_testimonial_cpt'));
        add_action('add_meta_boxes', array($this, 'ch_add_meta_boxes'));
        add_action('save_post', array($this, 'ch_save_meta_box'));
        add_action('manage_testimonial_posts_columns', array($this, 'ch_set_custom_columns'));
        add_action('manage_testimonial_posts_custom_column', array($this, 'ch_set_custom_columns_data'), 10, 2);
        add_filter('manage_edit-testimonial_sortable_columns', array($this, 'ch_set_custom_columns_sortable'));
        add_shortcode('testimonial-form', array($this, 'ch_testimonial_form'));
        add_shortcode('testimonial-slideshow', array($this, 'ch_testimonial_slideshow'));
        add_action('wp_ajax_submit_testimonial', array($this, 'ch_submit_testimonial'));
        add_action('wp_ajax_nopriv_submit_testimonial', array($this, 'ch_submit_testimonial'));
    }


    public function ch_submit_testimonial()
    {
        if (!DOING_AJAX || !check_ajax_referer('testimonial-nonce', 'nonce')) {
            return $this->return_json('error');
        }

        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $message = sanitize_textarea_field($_POST['message']);

        $data = array(
            'name' => $name,
            'email' => $email,
            'approved' => 0,
            'featured' => 0,
        );

        $args = array(
            'post_title' => __( 'Testimonial from ' . $name, 'k7-church'),
            'post_content' => $message,
            'post_author' => 1,
            'post_status' => 'publish',
            'post_type' => 'testimonial',
            'meta_input' => array(
                '_church_testimonial_key' => $data
            )
        );

        $postID = wp_insert_post($args);

        if ($postID) {
            return $this->ch_return_json('success');
        }

        return $this->ch_return_json('error');
    }

    public function ch_return_json($status)
    {
        $return = array(
            'status' => $status
        );
        wp_send_json($return);

        wp_die();
    }

    public function ch_testimonial_form()
    {
        ob_start();
        echo "<link rel=\"stylesheet\" href=\"$this->plugin_url/assets/form.css\" type=\"text/css\" media=\"all\" />";
        require_once("$this->plugin_path/templates/contact-form.php");
        echo "<script src=\"$this->plugin_url/assets/form.js\"></script>";
        return ob_get_clean();
    }

    public function ch_testimonial_slideshow()
    {
        ob_start();
        echo "<link rel=\"stylesheet\" href=\"$this->plugin_url/assets/slider.css\" type=\"text/css\" media=\"all\" />";
        require_once("$this->plugin_path/templates/slider.php");
        echo "<script src=\"$this->plugin_url/assets/slider.js\"></script>";
        return ob_get_clean();
    }

    public function ch_testimonial_cpt()
    {
        $labels = array(
            'name' => __('Testimonials', 'k7-church'),
            'singular_name' => __('Testimonial', 'k7-church')
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => false,
            'menu_icon' => 'dashicons-testimonial',
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'supports' => array('title', 'editor'),
            'show_in_rest' => true
        );

        register_post_type('testimonial', $args);
    }

    public function ch_add_meta_boxes()
    {
        add_meta_box(
            'testimonial_author',
            __( 'Testimonial Options', 'k7-church'),
            array($this, 'ch_render_features_box'),
            'testimonial',
            'side',
            'default'
        );
    }

    public function ch_render_features_box($post)
    {
        wp_nonce_field('church_testimonial', 'church_testimonial_nonce');

        $data = get_post_meta($post->ID, '_church_testimonial_key', true);
        $name = isset($data['name']) ? $data['name'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $approved = isset($data['approved']) ? $data['approved'] : false;
        $featured = isset($data['featured']) ? $data['featured'] : false;
        ?>
        <p>
            <label class="meta-label" for="church_testimonial_author"><?php _e('Author Name', 'k7-church'); ?></label>
            <input type="text" id="church_testimonial_author" name="church_testimonial_author" class="widefat"
                   value="<?php echo esc_attr($name); ?>">
        </p>
        <p>
            <label class="meta-label" for="church_testimonial_email"><?php _e('Author Email', 'k7-church'); ?></label>
            <input type="email" id="church_testimonial_email" name="church_testimonial_email" class="widefat"
                   value="<?php echo esc_attr($email); ?>">
        </p>
        <div class="meta-container">
            <label class="meta-label w-50 text-left"
                   for="church_testimonial_approved"><?php _e('Approved', 'k7-church'); ?></label>
            <div class="text-right w-50 inline">
                <div class="ui-toggle inline"><input type="checkbox" id="church_testimonial_approved"
                                                     name="church_testimonial_approved"
                                                     value="1" <?php echo $approved ? 'checked' : ''; ?>>
                    <label for="church_testimonial_approved">
                        <div></div>
                    </label>
                </div>
            </div>
        </div>
        <div class="meta-container">
            <label class="meta-label w-50 text-left"
                   for="church_testimonial_featured"><?php _e('Featured', 'k7-church'); ?></label>
            <div class="text-right w-50 inline">
                <div class="ui-toggle inline"><input type="checkbox" id="church_testimonial_featured"
                                                     name="church_testimonial_featured"
                                                     value="1" <?php echo $featured ? 'checked' : ''; ?>>
                    <label for="church_testimonial_featured">
                        <div></div>
                    </label>
                </div>
            </div>
        </div>
        <?php
    }

    public function ch_save_meta_box($post_id)
    {
        if (!isset($_POST['church_testimonial_nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['church_testimonial_nonce'];
        if (!wp_verify_nonce($nonce, 'church_testimonial')) {
            return $post_id;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        $data = array(
            'name' => sanitize_text_field($_POST['church_testimonial_author']),
            'email' => sanitize_email($_POST['church_testimonial_email']),
            'approved' => isset($_POST['church_testimonial_approved']) ? 1 : 0,
            'featured' => isset($_POST['church_testimonial_featured']) ? 1 : 0,
        );
        update_post_meta($post_id, '_church_testimonial_key', $data);
    }

    public function ch_set_custom_columns($columns)
    {
        $title = $columns['title'];
        $date = $columns['date'];
        unset($columns['title'], $columns['date']);

        $columns['name'] = __('Author Name', 'k7-church');
        $columns['title'] = $title;
        $columns['approved'] = __('Approved', 'k7-church');
        $columns['featured'] = __('Featured', 'k7-church');
        $columns['date'] = $date;

        return $columns;
    }

    public function ch_set_custom_columns_data($column, $post_id)
    {
        $data = get_post_meta($post_id, '_church_testimonial_key', true);
        $name = isset($data['name']) ? $data['name'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $approved = isset($data['approved']) && $data['approved'] === 1 ? '<strong>'. __( 'YES', 'k7-church').'</strong>' : __(  'NO', 'k7-church');
        $featured = isset($data['featured']) && $data['featured'] === 1 ? '<strong>'. __( 'YES', 'k7-church').'</strong>' : __(  'NO', 'k7-church');

        switch ($column) {
            case 'name':
                echo '<strong>' . $name . '</strong><br/><a href="mailto:' . $email . '">' . $email . '</a>';
                break;

            case 'approved':
                echo $approved;
                break;

            case 'featured':
                echo $featured;
                break;
        }
    }

    public function ch_set_custom_columns_sortable($columns)
    {
        $columns['name'] = __( 'name', 'k7-church');
        $columns['approved'] = __( 'approved', 'k7-church');
        $columns['featured'] = __( 'featured', 'k7-church');

        return $columns;
    }
}