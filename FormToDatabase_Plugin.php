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

    public function getPluginSlug() {
        return 'form-to-database';
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
        function ftd_admin_enqueue_style() {
            if (strpos($_SERVER['REQUEST_URI'], 'form-to-database') !== false) {
                wp_enqueue_style('ftd-datatables-css', 'https://cdn.datatables.net/v/dt/jszip-3.1.3/pdfmake-0.1.27/dt-1.10.15/b-1.3.1/b-colvis-1.3.1/b-flash-1.3.1/b-html5-1.3.1/b-print-1.3.1/r-2.1.1/datatables.min.css');
                wp_enqueue_style('ftd-admin-css', plugins_url('/admin/css/admin.css', __FILE__));
            }
        }
        function ftd_admin_enqueue_script() {
            if (strpos($_SERVER['REQUEST_URI'], 'form-to-database') !== false) {
                wp_enqueue_script('ftd-datatables-js', 'https://cdn.datatables.net/v/dt/jszip-3.1.3/pdfmake-0.1.27/dt-1.10.15/b-1.3.1/b-colvis-1.3.1/b-flash-1.3.1/b-html5-1.3.1/b-print-1.3.1/r-2.1.1/datatables.min.js');
                wp_enqueue_script('ftd-admin-js', plugins_url('/admin/js/admin.js', __FILE__));
            }
        }
        add_action( 'admin_enqueue_scripts', 'ftd_admin_enqueue_style' );
        add_action( 'admin_enqueue_scripts', 'ftd_admin_enqueue_script' );


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
                // Find proper name
                $name = '';
                if (array_key_exists('contact_name',$form_data)) {
                    $name = $form_data['contact_name'];
                } elseif (array_key_exists('lname',$form_data)) {
                    $name = $form_data['fname'].' '.$form_data['lname'];
                }
                $wpdb->insert( $table_name, array(
                    'name' => $name,
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
            'Form Entries',
            'Form Entries',
            'manage_options',
            'form-to-database',
            array($this, 'ftd_render_admin_page'),
            plugins_url('/form-to-database/img/ftd-icon.png',__DIR__)
            );
      }

    public function ftd_render_admin_page() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . $this->getPluginSlug() . '/admin/form-to-database-admin.php';
    }


}
