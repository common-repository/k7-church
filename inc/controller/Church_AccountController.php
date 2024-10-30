<?php
/**
 * @version 1.0.13
 *
 * @package K7Church/inc/controller
 */

defined('ABSPATH') || exit;


class Church_AccountController extends Church_BaseController
{


    public function __construct()
    {
        add_shortcode('information', array($this, 'ch_PanelShortcode'));
        add_action('wp', array($this, 'ch_userOnlineUpdate'));

        $admin = new Church_AdminCallbacks();
        $admin->painel();


    }

    /**
     * @return string
     */
    public function ch_PanelShortcode()
    {
        ob_start();
        $this->ch_AccountUser();
        return ob_get_clean();
    }

    public function ch_AccountUser()
    {


        $user_id = get_current_user_id();
        $user_name = get_user_meta($user_id, 'first_name', true);
        $welcome_message = __('Welcome', 'k7-church') . ' ' . $user_name;

        if (is_user_logged_in()) {
            echo '<div class="ch-row ch-left"><a href="' . home_url('profile') . '">' . $welcome_message . '</a></div>';

            echo '<ul class="ch-nav ch-right">';
            echo '<li id="ch-list-nav">';
            echo '<a href="' . wp_logout_url(home_url()) . '">' . __('Log out', 'k7-church') . '</a>';
            echo '</li>';
            echo '</ul>';
            // $user_info = get_userdata($user_id);

            global $current_user;
            do_action('ch_UserPanelPublic', $user_id, 200, $current_user);


        } else {

            echo "<strong class='ch-text-center'><h3>" . __('You must be registered', 'k7-church') . "</h3></strong>" . '<h3><a class="ch-text-center" href="' . wp_login_url() . '">' . __('Log in', 'k7-church') . '</h3</a>';
        }

    }

    function ch_userOnlineUpdate()
    {

        if (is_user_logged_in()) {

            // get the user activity the list
            $logged_in_users = get_transient('online_status');

            // get current user ID
            $user = wp_get_current_user();

            // check if the current user needs to update his online status;
            // he does if he doesn't exist in the list
            $no_need_to_update = isset($logged_in_users[$user->ID])

                // and if his "last activity" was less than let's say ...1 minutes ago
                && $logged_in_users[$user->ID] > (time() - (1 * 60));

            // update the list if needed
            if (!$no_need_to_update) {
                $logged_in_users[$user->ID] = time();
                set_transient('online_status', $logged_in_users, (2 * 60)); // 2 mins
            }
        }
    }

    function ch_DisplayLoggedInUsers()
    {
        // get the user activity the list
        $logged_in_users = get_transient('online_status');

        if (!empty($logged_in_users)) {
            echo '<br/><strong>' . ec_html__('Logged in users are as following :', 'k7-church') . '</strong>';
            foreach ($logged_in_users as $key => $value) {
                $user = get_user_by('id', $key);
                echo '<br/>' . ec_html__(' Looged in user name is ', 'k7-church') . $user->display_name;
            }
        } else {
            echo '<br/><strong>' . ec_html__('No user is logged in.', 'k7-church') . '</strong>';
        }

    }

}