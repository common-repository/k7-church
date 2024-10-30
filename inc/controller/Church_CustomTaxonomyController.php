<?php
/**
 * @version 1.0.13
 *
 * @package K7Church/inc/controller
 */

defined('ABSPATH') || exit;


class Church_CustomTaxonomyController extends Church_BaseController
{
    public $settings;

    public $callbacks;

    public $tax_callbacks;

    public $subpages = array();

    public $taxonomies = array();

    public function ch_register()
    {
        if (!$this->ch_activated('taxonomy_manager')) return;

        $this->settings = new Church_SettingsApi();

        $this->callbacks = new Church_AdminCallbacks();

        $this->tax_callbacks = new Church_TaxonomyCallbacks();

        $this->ch_setSubpages();

        $this->ch_setSettings();

        $this->ch_setSections();

        $this->ch_setFields();

        $this->settings->ch_addSubPages($this->subpages)->ch_register();

        $this->ch_storeCustomTaxonomies();

        if (!empty($this->taxonomies)) {
            add_action('init', array($this, 'ch_registerCustomTaxonomy'));
        }
    }

    public function ch_setSubpages()
    {
        $this->subpages = array(
            array(
                'parent_slug' => 'church_plugin',
                'page_title' => __('Custom Taxonomies', 'k7-church'),
                'menu_title' => __('Taxonomy Manager', 'k7-church'),
                'capability' => 'manage_options',
                'menu_slug' => 'church_taxonomy',
                'callback' => array($this->callbacks, 'ch_adminTaxonomy')
            )
        );
    }

    public function ch_setSettings()
    {
        $args = array(
            array(
                'option_group' => 'church_plugin_tax_settings',
                'option_name' => 'church_plugin_tax',
                'callback' => array($this->tax_callbacks, 'ch_taxSanitize')
            )
        );

        $this->settings->ch_setSettings($args);
    }

    public function ch_setSections()
    {
        $args = array(
            array(
                'id' => 'church_tax_index',
                'title' => __('Custom Taxonomy Manager', 'k7-church'),
                'callback' => array($this->tax_callbacks, 'ch_taxSectionManager'),
                'page' => 'church_taxonomy'
            )
        );

        $this->settings->ch_setSections($args);
    }

    public function ch_setFields()
    {
        $args = array(
            array(
                'id' => 'taxonomy',
                'title' => __('Custom Taxonomy ID', 'k7-church'),
                'callback' => array($this->tax_callbacks, 'ch_textField'),
                'page' => 'church_taxonomy',
                'section' => 'church_tax_index',
                'args' => array(
                    'option_name' => 'church_plugin_tax',
                    'label_for' => 'taxonomy',
                    'placeholder' => __('eg. genre', 'k7-church'),
                    'array' => 'taxonomy'
                )
            ),
            array(
                'id' => 'singular_name',
                'title' => __('Singular Name', 'k7-church'),
                'callback' => array($this->tax_callbacks, 'ch_textField'),
                'page' => 'church_taxonomy',
                'section' => 'church_tax_index',
                'args' => array(
                    'option_name' => 'church_plugin_tax',
                    'label_for' => 'singular_name',
                    'placeholder' => __('eg. Genre', 'k7-church'),
                    'array' => 'taxonomy'
                )
            ),
            array(
                'id' => 'hierarchical',
                'title' => __('Hierarchical', 'k7-church'),
                'callback' => array($this->tax_callbacks, 'ch_checkboxField'),
                'page' => 'church_taxonomy',
                'section' => 'church_tax_index',
                'args' => array(
                    'option_name' => 'church_plugin_tax',
                    'label_for' => 'hierarchical',
                    'class' => 'ui-toggle',
                    'array' => 'taxonomy'
                )
            ),
            array(
                'id' => 'objects',
                'title' => __('Post Types', 'k7-church'),
                'callback' => array($this->tax_callbacks, 'ch_checkboxPostTypesField'),
                'page' => 'church_taxonomy',
                'section' => 'church_tax_index',
                'args' => array(
                    'option_name' => 'church_plugin_tax',
                    'label_for' => 'objects',
                    'class' => 'ui-toggle',
                    'array' => 'taxonomy'
                )
            )
        );

        $this->settings->ch_setFields($args);
    }

    public function ch_storeCustomTaxonomies()
    {
        $options = get_option('church_plugin_tax') ?: array();

        foreach ($options as $option) {
            $labels = array(
                'name' => $option['singular_name'],
                'singular_name' => $option['singular_name'],
                'search_items' => 'Search ' . $option['singular_name'],
                'all_items' => 'All ' . $option['singular_name'],
                'parent_item' => 'Parent ' . $option['singular_name'],
                'parent_item_colon' => 'Parent ' . $option['singular_name'] . ':',
                'edit_item' => 'Edit ' . $option['singular_name'],
                'update_item' => 'Update ' . $option['singular_name'],
                'add_new_item' => 'Add New ' . $option['singular_name'],
                'new_item_name' => 'New ' . $option['singular_name'] . ' Name',
                'menu_name' => $option['singular_name'],
            );

            $this->taxonomies[] = array(
                'hierarchical' => isset($option['hierarchical']) ? true : false,
                'labels' => $labels,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'show_in_rest' => true,
                'rewrite' => array('slug' => $option['taxonomy']),
                'objects' => isset($option['objects']) ? $option['objects'] : null
            );

        }
    }


    public function ch_registerCustomTaxonomy()
    {
        foreach ($this->taxonomies as $taxonomy) {
            $objects = isset($taxonomy['objects']) ? array_keys($taxonomy['objects']) : null;
            register_taxonomy($taxonomy['rewrite']['slug'], $objects, $taxonomy);
        }
    }
}