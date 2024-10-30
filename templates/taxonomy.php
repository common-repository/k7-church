<div class="wrap">
    <h1><?php _e('Taxonomy Manager', 'k7-church'); ?></h1>
    <?php settings_errors(); ?>

    <ul class="nav nav-tabs">
        <li class="<?php echo !isset($_POST["edit_taxonomy"]) ? 'active' : '' ?>"><a
                    href="#tab-1"><?php _e('Your Taxonomies', 'k7-church'); ?></a></li>
        <li class="<?php echo isset($_POST["edit_taxonomy"]) ? 'active' : '' ?>">
            <a href="#tab-2">
                <?php echo isset($_POST["edit_taxonomy"]) ? 'Edit' : 'Add' ?><?php _e('Taxonomy', 'k7-church'); ?>
            </a>
        </li>
        <li><a href="#tab-3"><?php _e('Export', 'k7-church'); ?></a></li>
    </ul>

    <div class="tab-content">
        <div id="tab-1" class="tab-pane <?php echo !isset($_POST["edit_taxonomy"]) ? 'active' : '' ?>">

            <h3><?php _e('Manage Your Custom Taxonomies', 'k7-church'); ?></h3>

            <?php
            $options = get_option('church_plugin_tax') ?: array();

            echo '<table class="cpt-table"><tr><th>' . __('ID', 'k7-church') . '</th><th>' . __('Singular Name', 'k7-church') . '</th><th class="text-center">' . __('Hierarchical', 'k7-church') . '</th><th class="text-center">' . __('Actions', 'k7-church') . '</th></tr>';

            foreach ($options as $option) {
                $hierarchical = isset($option['hierarchical']) ? "TRUE" : "FALSE";

                echo "<tr><td>{$option['taxonomy']}</td><td>{$option['singular_name']}</td><td class=\"text-center\">{$hierarchical}</td><td class=\"text-center\">";

                echo '<form method="post" action="" class="inline-block">';
                echo '<input type="hidden" name="edit_taxonomy" value="' . $option['taxonomy'] . '">';
                submit_button('Edit', 'primary small', 'submit', false);
                echo '</form> ';

                echo '<form method="post" action="options.php" class="inline-block">';
                settings_fields('church_plugin_tax_settings');
                echo '<input type="hidden" name="remove" value="' . $option['taxonomy'] . '">';
                submit_button('Delete', 'delete small', 'submit', false, array(
                    'onclick' => 'return confirm("' . __("Are you sure you want to delete this Custom Taxonomy? The data associated with it will not be deleted", "k7-church") . '" );'
                ));
                echo '</form></td></tr>';
            }

            echo '</table>';
            ?>

        </div>

        <div id="tab-2" class="tab-pane <?php echo isset($_POST["edit_taxonomy"]) ? 'active' : '' ?>">
            <form method="post" action="options.php">
                <?php
                settings_fields('church_plugin_tax_settings');
                do_settings_sections('church_taxonomy');
                submit_button();
                ?>
            </form>
        </div>

        <div id="tab-3" class="tab-pane">
            <h3><?php _e('Export Your Taxonomies', 'k7-church'); ?></h3>

        </div>
    </div>
</div>