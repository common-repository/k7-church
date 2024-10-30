<?php
/**
 * @version 1.0.13
 *
 * @package K7Church/inc/controller
 */

defined('ABSPATH') || exit;


class Church_TemplateController extends Church_BaseController
{
    public $templates;

    public function ch_register()
    {
        if (!$this->ch_activated('templates_manager')) return;

        $this->templates = array(
            'page-templates/location-template.php' => __('Location  Layout', 'k7-church')
        );

        add_filter('theme_page_templates', array($this, 'ch_custom_template'));
        add_filter('template_include', array($this, 'ch_load_template'));
    }

    public function ch_custom_template($templates)
    {
        $templates = array_merge($templates, $this->templates);

        return $templates;
    }

    public function ch_load_template($template)
    {
        global $post;

        if (!$post) {
            return $template;
        }

        // If is the front page, load a custom template
        if (is_front_page()) {
            $file = $this->plugin_path . 'page-templates/front-page.php';

            if (file_exists($file)) {
                return $file;
            }
        }

        $template_name = get_post_meta($post->ID, '_wp_page_template', true);

        if (!isset($this->templates[$template_name])) {
            return $template;
        }

        $file = $this->plugin_path . $template_name;

        if (file_exists($file)) {
            return $file;
        }

        return $template;
    }
}