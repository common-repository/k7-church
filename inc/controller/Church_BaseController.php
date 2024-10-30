<?php
/**
 * @version 1.0.13
 *
 * @package K7Church/inc/controller
 */

defined('ABSPATH') || exit;


class Church_BaseController
{
    public $plugin_path;

    public $plugin_url;

    public $plugin;

    public $managers = array();

    public function __construct()
    {
        $this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));
        $this->plugin_url = plugin_dir_url(dirname(__FILE__, 2));
        $this->plugin = plugin_basename(dirname(__FILE__, 3)) . '/church-plugin.php';
        add_action('init', array( $this, 'ch_load_textdomain'));


        $this->managers = array(
            'cpt_manager' => __('Activate CPT Manager', 'k7-church'),
            'taxonomy_manager' => __('Activate Taxonomy Manager', 'k7-church'),
            'location_manager' => __('Activate Localion Manager', 'k7-church'),
            'location_widget' => __('Activate Location Widget', 'k7-church'),
            'media_widget' => __('Activate Media Widget', 'k7-church'),
            'testimonial_manager' => __('Activate Testimonial Manager', 'k7-church'),
            'notify_manager' => __('Activate Notification', 'k7-church'),
            'templates_manager' => __('Activate Custom Templates', 'k7-church'),
            'participant_manager' => __('Activate Custom Participe', 'k7-church'),
            // 'gallery_manager' => __('Activate Gallery Manager', 'k7-church'),
            // 'login_manager' => __('Activate Ajax Login/Signup', 'k7-church'),
            // 'membership_manager' => __('Activate Membership Manager', 'k7-church'),
            // 'chat_manager' => __('Activate Chat Manager', 'k7-church')
        );
    }

    public function ch_activated(string $key)
    {
        $option = get_option('church_plugin');

        return isset($option[$key]) ? $option[$key] : false;
    }

    
// Load plugin textdomain.

    public function ch_load_textdomain()
    {
        unload_textdomain('k7-church');
        load_plugin_textdomain('k7-church', false, plugin_basename(dirname(__FILE__, 3)) . '/languages');
    }

}