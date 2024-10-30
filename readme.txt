=== K7 Church ===
Contributors: Márcio Zebedeu
Tags: Form, Register, Locations, Location Widget, Media Widget, Custom Post Type Manager, Custom Taxonomy Manager, Testimonial, Sermon post, Advanced Notification System,  
Requires at least: 5.2
Tested up to: 5.2.4
Requires PHP: 5.6.20
Stable tag: 1.0.21
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

K7 Church is a Wordpress plugin for churches that claims to be simple and objective for your church's website.
== Description ==
K7 Church is a Wordpress plugin for churches that claims to be simple and objective for your church's website.

You can easily publish your sermons and add the following details to them:

* Name of author
* Title
* description
* url of a video (example youtube etc)
* Notify users by email when a sermon is posted

==Also the plugin you can: ==
add events
Add places where there is a church

There is a Post Type manager where you can create several post type according to your will well with your texamonias.

== FIlters ==

* location_cult_hours_days : With this filter you can create a function that returns an array with new fields for your location meta box with days of the week and hours

* location_cult_title: serves to change the title that appears above the hours of cults

* {pot_type}_before_main_content & {post_type}_after_main_content:  This will add extra content before the title of  and  before the button.. 

== Actions ==

* {pot_type}_admin_form_start & {post_type}_admin_form_end : With these filters you can use to add html and css codes to stylize your own meta box

* {pot_type}_meta_data_output_end & {post_type}_meta_data_output_end: allows you to get the data that comes from the post meta. The function receives an ID.

Options like CPT, Taxonomies, Template manager,  Location Manager, Location Widget,  Media Widget,  Testimonial Manager,  Notification, Custom Templates, can be activated and deactivated whenever you want.

== Installation ==

1. Upload the `church_admin` directory to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings » Permalinks, and simply click on Save Changes button.

== Shortcode ==

Testimonial Form Shortcode
[testimonial-form]

Testimonial SlideShow Shortcode
[testimonial-slideshow]

add location on a page: location for default 
[locations location_id=1]

Location for member the post

[locations location_id=1 number_of_locations=1]
[locations location_id="1" number_of_locations=1 post_status="publish"]

add event on a page
[event]

add sermon on a page:
[sermon]

== Frequently Asked Questions ==

== Screenshots ==
1. Sermon Post
2. Sermon Post 2
3. quick view of the sermon
4. full sermon view
5. Post Testimonial
6. slide view of the testimony
7. location post
8. quick view of the location
9. full location view
10. Notify subscribers
11. Location  Layout and front-page
12. Event Post
13. Event List
14. Event Information
15. Limit of participants
16. Form for registration to the event


== Changelog ==

=1.0.21 =
add currency and price for events
=1.0.20=
added a limit of participants
Added a form for registration to the event

= 1.0.19 =
fix bug
add filters: location_cult_hours_days, location_cult_title

=1.0.18 =
fix Text Domain

= 1.0.17 =
fix bug: correction of language loading

=1.0.16 =
add icon for location
fix bug

=1.0.15 =
add Event

=1.0.14=
fix bug

=1.0.13= 
fix bug
add tabs abdbs pages for Sermon, testimony and location

= 1.0.12 =
filter by country to notify subscribers that a post or other event on the site has been triggered
Advanced Notification System
bug fix in Location  Layout and front-page 

= 1.0.11 =
Minimum update of the required PHP version

= 1.0.10 =
Add Custom Post Type Manager, Custom Taxonomy Manager
fix bug

= 1.0.9 =
fix bug 

= 1.0.8 =
fix bug 

= 1.0.7 =
fix bug 

= 1.0.6 =
fix bug 

= 1.0.5 =
fix bug 

= 1.0.4 =
fix bug 

= 1.0.3 =

CSS style sheet repair, page fix for user account,
= 1.0.2 =

has been added to the login page, register page, recaptcha login page
fixe bug
 
= 1.0.1 =

fixe bug
= 1.0.0 =

fixing various bugs

== Upgrade Notice ==
=1.017 =

fix bug: correction of language loading
= 1.0.13 =
This version corrects an error related to security and others . Update immediately.
