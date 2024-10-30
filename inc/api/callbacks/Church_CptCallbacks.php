<?php
/**
 * @version 1.0.13
 *
 * @package K7Church/inc/api/callbacks
 */

defined('ABSPATH') || exit;


class Church_CptCallbacks
{

    public function ch_cptSectionManager()
    {
        echo 'Create as many Custom Post Types as you want.';
    }

    public function ch_cptSanitize($input)
    {
        $output = get_option('church_plugin_cpt');

        if (isset($_POST["remove"])) {
            unset($output[$_POST["remove"]]);

            return $output;
        }

        if (count($output) == 0) {
            $output[$input['post_type']] = $input;

            return $output;
        }

        foreach ($output as $key => $value) {
            if ($input['post_type'] === $key) {
                $output[$key] = $input;
            } else {
                $output[$input['post_type']] = $input;
            }
        }

        return $output;
    }

    public function ch_textField($args)
    {
        $name = $args['label_for'];
        $option_name = $args['option_name'];
        $value = '';

        if (isset($_POST["edit_post"])) {
            $input = get_option($option_name);
            $value = $input[$_POST["edit_post"]][$name];
        }

        echo '<input type="text" class="regular-text" id="' . $name . '" name="' . $option_name . '[' . $name . ']" value="' . $value . '" placeholder="' . $args['placeholder'] . '" required>';
    }

    public function ch_checkboxField($args)
    {
        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $checked = false;

        if (isset($_POST["edit_post"])) {
            $checkbox = get_option($option_name);
            $checked = isset($checkbox[$_POST["edit_post"]][$name]) ?: false;
        }

        echo '<div class="' . $classes . '"><input type="checkbox" id="' . $name . '" name="' . $option_name . '[' . $name . ']" value="1" class="" ' . ($checked ? 'checked' : '') . '><label for="' . $name . '"><div></div></label></div>';
    }
}