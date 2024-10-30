<?php
/**
 * @version 1.0.16
 *
 * @package K7Church
 */

/**
 *  Plugin Name: K7 Church
 *  Description:  K7 Church is a Wordpress plugin for churches that claims to be simple and objective for your church's website.
 *  Version:      1.0.21
 *  Author:       Márcio Zebedeu
 *  Author URI:    https://profiles.wordpress.org/marcio-zebedeu
 *  License:      GPL2
 *  License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 *  Text Domain:  k7-church
 *  Domain Path: /languages
 */

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2015 Automattic, Inc.
*/

// If this file is called firectly, abort!!!
defined('ABSPATH') or die('Hey, what are you doing here? You silly human!');

require_once(dirname(__FILE__) . '/inc/Church_Init.php');
require_once(dirname(__FILE__) . '/inc/controller/Church_Activate.php');
require_once(dirname(__FILE__) . '/inc/controller/Church_Deactivate.php');
include_once(dirname(__FILE__) . '/inc/controller/Church_BaseController.php');

require_once(dirname(__FILE__) . '/inc/pages/Church_Dashboard.php');
require_once(dirname(__FILE__) . '/inc/api/Church_SettingsApi.php');
require_once(dirname(__FILE__) . '/inc/api/Church_API_Client.php');
require_once(dirname(__FILE__) . '/inc/api/Church_Currency.php');
require_once(dirname(__FILE__) . '/inc/api/Church_Country.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/Church_AdminCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/Church_SermonCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/Church_EventCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/Church_ManagerCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/Church_LocationCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/Church_NotificationCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/Church_TestimonialCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/Church_CptCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/Church_TaxonomyCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/Church_ParticipantCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/widgets/Church_MediaWidget.php');
require_once(dirname(__FILE__) . '/inc/api/widgets/Church_LocationWidget.php');
require_once(dirname(__FILE__) . '/inc/controller/Church_Enqueue.php');
require_once(dirname(__FILE__) . '/inc/controller/Church_SettingsLinks.php');
require_once(dirname(__FILE__) . '/inc/controller/Church_CustomPostTypeController.php');
require_once(dirname(__FILE__) . '/inc/controller/Church_CustomTaxonomyController.php');
require_once(dirname(__FILE__) . '/inc/controller/Church_CustomTaxonomyController.php');
require_once(dirname(__FILE__) . '/inc/controller/Church_GalleryController.php');
require_once(dirname(__FILE__) . '/inc/controller/Church_TestimonialController.php');
require_once(dirname(__FILE__) . '/inc/controller/Church_TemplateController.php');
require_once(dirname(__FILE__) . '/inc/controller/Church_AuthController.php');
require_once(dirname(__FILE__) . '/inc/controller/Church_MembershipController.php');
require_once(dirname(__FILE__) . '/inc/controller/Church_ChatController.php');
require_once(dirname(__FILE__) . '/inc/controller/Church_LocationController.php');
require_once(dirname(__FILE__) . '/inc/controller/Church_SermonController.php');
require_once(dirname(__FILE__) . '/inc/controller/Church_RegisterController.php');
require_once(dirname(__FILE__) . '/inc/controller/Church_NotificationController.php');
require_once(dirname(__FILE__) . '/inc/controller/Church_EmailController.php');
require_once(dirname(__FILE__) . '/inc/controller/Church_AccountController.php');
require_once(dirname(__FILE__) . '/inc/controller/Church_WidgetController.php');
require_once(dirname(__FILE__) . '/inc/controller/Church_EventController.php');
require_once(dirname(__FILE__) . '/inc/controller/Church_ParticipantController.php');  


/**
 * The code that runs during plugin activation
 */
function activate_church_plugin()
{
    Church_Activate::ch_activate();
}

register_activation_hook(__FILE__, 'activate_church_plugin');

/**
 * The code that runs during plugin deactivation
 */
function deactivate_church_plugin()
{
    Church_Deactivate::ch_deactivate();
}

register_deactivation_hook(__FILE__, 'deactivate_church_plugin');

/**
 * Initialize all the core classes of the plugin
 */
if (class_exists('Church_Init')) {

    Church_Init::ch_registerServices();

}
