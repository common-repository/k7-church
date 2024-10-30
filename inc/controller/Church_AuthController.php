<?php
/**
 * @version 1.0.13
 *
 * @package K7Church/inc/controller
 */

defined('ABSPATH') || exit;


class Church_AuthController extends Church_BaseController
{
    public function ch_register()
    {
        if (!$this->ch_activated('login_manager')) return;

        add_action('wp_enqueue_scripts', array($this, 'ch_enqueue'));
        add_action('wp_head', array($this, 'ch_add_auth_template'));
        add_action('wp_ajax_nopriv_church_login', array($this, 'login'));
    }

    public function ch_enqueue()
    {

        wp_enqueue_script('authscript', $this->plugin_url . 'assets/auth.js');
    }

    public function ch_add_auth_template()
    {
        if (is_user_logged_in()) return;

        $file = $this->plugin_path . 'templates/auth.php';

        if (file_exists($file)) {
            load_template($file, true);
        }


    }

    public function login()
    {
        check_ajax_referer('ajax-login-nonce', 'church_auth');

        $info = array();

        $info['user_login'] = $_POST['username'];
        $info['user_password'] = $_POST['password'];
        $info['remember'] = true;

        $user_lognon = wp_signon($info, false);
        if (is_wp_error($user_lognon)) {
            echo json_encode(
                array(
                    'status' => false,
                    'message' => 'Wrong username or password .'
                )
            );

            die;
        }
        echo json_encode(
            array(
                'status' => false,
                'message' => 'Login sucessful, redirecting....'
            )
        );

        die;


    }
}