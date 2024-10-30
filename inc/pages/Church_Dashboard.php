<?php
/**
 * @version 1.0.13
 *
 * @package K7Church/inc/controller
 */

defined('ABSPATH') || exit;


class Church_Dashboard extends Church_BaseController
{
    public $settings;

    public $callbacks;

    public $callbacks_mngr;

    public $pages = array();

    public function ch_register()
    {
        $this->settings = new Church_SettingsApi();

        $this->callbacks = new Church_AdminCallbacks();

        $this->callbacks_mngr = new Church_ManagerCallbacks();

        $account = new Church_AccountController();

        $this->ch_setPages();

        $this->ch_setSettings();
        $this->ch_setSections();
        $this->ch_setFields();

        $this->settings->ch_addPages($this->pages)->ch_withSubPage('Dashboard')->ch_register();

    }

    public function ch_setPages()
    {
        $this->pages = array(
            array(
                'page_title' => 'Church Plugin',
                'menu_title' => 'Church',
                'capability' => 'manage_options',
                'menu_slug' => 'church_plugin',
                'callback' => array($this->callbacks, 'ch_adminDashboard'),
                'icon_url' => 'dashicons-store',
                'position' => 110
            )
        );
    }

    public function ch_setSettings()
    {
        $args = array(
            array(
                'option_group' => 'church_plugin_settings',
                'option_name' => 'church_plugin',
                'callback' => array($this->callbacks_mngr, 'ch_checkboxSanitize')
           )
        );

        $this->settings->ch_setSettings($args);
    }

    public function ch_setSections()
    {
        $args = array(
            array(
                'id' => 'church_admin_index',
                'title' => 'Settings Manager',
                'callback' => array($this->callbacks_mngr, 'ch_adminSectionManager'),
                'page' => 'church_plugin'
            )
        );

        $this->settings->ch_setSections($args);
    }

    public function ch_setFields()
    {
        $args = array();

        foreach ($this->managers as $key => $value) {
            $args[] = array(
                'id' => $key,
                'title' => $value,
                'callback' => array($this->callbacks_mngr, 'ch_checkboxField'),
                'page' => 'church_plugin',
                'section' => 'church_admin_index',
                'args' => array(
                    'option_name' => 'church_plugin',
                    'label_for' => $key,
                    'class' => 'ui-toggle'
                )
            );
        }

        $this->settings->ch_setFields($args);
    }

}