<?php
/**
 * @version 1.0.13
 *
 * @package K7Church/inc/controller
 */

defined('ABSPATH') || exit;


class Church_Deactivate
{
    public static function ch_deactivate()
    {
        flush_rewrite_rules();
    }
}