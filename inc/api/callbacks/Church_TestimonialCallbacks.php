<?php
/**
 * @version 1.0.13
 *
 * @package K7Church/inc/api/callbacks
 */

defined('ABSPATH') || exit;


class Church_TestimonialCallbacks extends Church_BaseController
{
    public function ch_shortcodePage()
    {
        return require_once("$this->plugin_path/templates/testimonial.php");
    }
}
