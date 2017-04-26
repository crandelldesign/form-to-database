<?php


include_once('FormToDatabase_LifeCycle.php');

class FormToDatabase_Plugin extends FormToDatabase_LifeCycle {

    /**
     * See: http://plugin.michael-simpson.com/?page_id=31
     * @return array of option meta data.
     */
    public function getOptionMetaData() {
        //  http://plugin.michael-simpson.com/?page_id=31
        return array(
            //'_version' => array('Installed Version'), // Leave this one commented-out. Uncomment to test upgrades.
            'ATextInput' => array(__('Enter in some text', 'my-awesome-plugin')),
            'AmAwesome' => array(__('I like this awesome plugin', 'my-awesome-plugin'), 'false', 'true'),
            'CanDoSomething' => array(__('Which user role can do something', 'my-awesome-plugin'),
                                        'Administrator', 'Editor', 'Author', 'Contributor', 'Subscriber', 'Anyone')
        );
    }

//    protected function getOptionValueI18nString($optionValue) {
//        $i18nValue = parent::getOptionValueI18nString($optionValue);
//        return $i18nValue;
//    }

    protected function initOptions() {
        $options = $this->getOptionMetaData();
        if (!empty($options)) {
            foreach ($options as $key => $arr) {
                if (is_array($arr) && count($arr > 1)) {
                    $this->addOption($key, $arr[1]);
                }
            }
        }
    }

    public function getPluginDisplayName() {
        return 'Form to Database';
    }

    protected function getMainPluginFileName() {
        return 'form-to-database.php';
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Called by install() to create any database tables if needed.
     * Best Practice:
     * (1) Prefix all table names with $wpdb->prefix
     * (2) make table names lower case only
     * @return void
     */
    protected function installDatabaseTables() {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("CREATE TABLE IF NOT EXISTS `$tableName` (
        //            `id` INTEGER NOT NULL");
        global $wpdb;
        $table_name = $wpdb->prefix . 'form_to_database';

        try
        {
            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                id bigint(20) NOT NULL AUTO_INCREMENT,
                email varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                name varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                data text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
                form_name varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                form_slug varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                form_columns text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
                PRIMARY KEY (id)
            ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Drop plugin-created tables on uninstall.
     * @return void
     */
    protected function unInstallDatabaseTables() {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("DROP TABLE IF EXISTS `$tableName`");
        global $wpdb;
        $table_name = $wpdb->prefix . 'form_to_database';

        try
        {
            $charset_collate = $wpdb->get_charset_collate();

            $sql = "DROP TABLE IF EXISTS  $table_name";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }


    /**
     * Perform actions when upgrading from version X to version Y
     * See: http://plugin.michael-simpson.com/?page_id=35
     * @return void
     */
    public function upgrade() {
        $this->installDatabaseTables();
    }

    public function addActionsAndFilters() {

        // Add options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        //add_action('admin_menu', array(&$this, 'addSettingsSubMenuPage'));
        /*function ftd_admin_page_menu(){
            add_menu_page('Form to Database', 'Form to Database', 'manage_options', __FILE__, 'ftd_admin_page_action', plugins_url('/img/icon.png',__DIR__));
            //add_menu_page('My Plugin', 'My Plugin', 'manage_options', __FILE__, 'clivern_render_plugin_page', plugins_url('/img/icon.png',__DIR__));
        }*/
        add_action('admin_menu', array($this, 'ftd_admin_menu'));

        // Example adding a script & style just for the options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        //        if (strpos($_SERVER['REQUEST_URI'], $this->getSettingsSlug()) !== false) {
        //            wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));
        //            wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
        //        }


        // Add Actions & Filters
        // http://plugin.michael-simpson.com/?page_id=37
        function ftd_test_action()
        {
            echo 'test';
        }
        add_action('ftd_test', 'ftd_test_action');

        function ftd_insert_data_action($form_name, $form_data, $form_columns = NULL)
        {
            // Remove Recaptcha Results
            unset($form_data['g-recaptcha-response']);
            unset($form_columns['g-recaptcha-response']);
            global $wpdb;
            try
            {
                $table_name = $wpdb->prefix . 'form_to_database';
                $wpdb->insert( $table_name, array(
                    'name' => (array_key_exists('contact_name',$form_data))?$form_data['contact_name']:'',
                    'email' => (array_key_exists('email',$form_data))?$form_data['email']:'',
                    'form_name' => $form_name,
                    'form_slug' => sanitize_title_with_dashes($form_name, null, 'save'),
                    'data' => json_encode($form_data),
                    'form_columns' => ($form_columns)?json_encode($form_columns):''
                ) );
            }
            catch(Exception $e)
            {
                echo $e->getMessage();
            }
        }
        add_action('ftd_insert_data', 'ftd_insert_data_action', 10, 3);


        // Adding scripts & styles to all pages
        // Examples:
        //        wp_enqueue_script('jquery');
        //        wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
        //        wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));


        // Register short codes
        // http://plugin.michael-simpson.com/?page_id=39


        // Register AJAX hooks
        // http://plugin.michael-simpson.com/?page_id=41

    }

    public function ftd_admin_menu() {
       $this->my_plugin_screen_name = add_menu_page(
            'Form to Database',
            'Form to Database',
            'manage_options',
            'form-to-database',
            array($this, 'ftd_render_admin_page'),
            plugins_url('/form-to-database/img/ftd-icon.png',__DIR__)
            );
      }

    public function ftd_render_admin_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'form_to_database';
        $form_names = $wpdb->get_results(
            "SELECT DISTINCT form_name, form_slug FROM $table_name"
        );
    ?>
        <div class="wrap">
            <h2>Form to Database</h2>
            <ul>
                <li><?php
                    foreach ($form_names as $form_name) {
                        echo '<li><a href="'.admin_url('admin.php?page=form-to-database&form='.$form_name->form_slug).'">'.$form_name->form_name.'</a></li>';
                    }
                ?></li>
            </ul>
            <?php if(isset($_GET['form']) && !empty($_GET['form'])) { ?>
                <?php
                    $form_slug = $_GET['form'];
                    $results = $wpdb->get_results(
                        $wpdb->prepare("SELECT * FROM $table_name WHERE form_slug=%s ORDER BY created_at ASC",$form_slug)
                    );
                    $columns_results = $wpdb->get_row(
                        $wpdb->prepare("SELECT * FROM $table_name WHERE form_slug=%s AND form_columns IS NOT NULL AND TRIM(form_columns) <> '' ORDER BY created_at DESC",$form_slug)
                    );

                    $columns = json_decode($columns_results->form_columns);
                    $column_count = count($columns);
                    ?>
                    <table class="table">
                        <tr>
                        <?php
                            if (!empty($columns)) {
                                foreach ($columns as $column) {
                                    echo '<th>'.$column.'</th>';
                                }
                            } else {
                                $columns = json_decode($columns_results->data);
                                foreach ($columns as $key => $value) {
                                    echo '<th>'.ucfirst(str_replace('_', '', $key)).'</th>';
                                }
                            }
                        ?>
                        </tr>
                        <?php
                            foreach ($results as $result) {
                                $row = json_decode($result->data);
                                echo '<tr>';
                                foreach ($row as $cell) {
                                    echo '<td>'.$cell.'</th>';
                                }
                                echo '</tr>';
                            }
                            echo '<br><br>';
                        ?>
                    </table>

            <?php } ?>
        </div>
    <?php
    }


}
