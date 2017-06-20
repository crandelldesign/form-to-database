<?php
global $wpdb;
    $table_name = $wpdb->prefix . 'form_to_database';
    $form_names = $wpdb->get_results(
        "SELECT DISTINCT form_name, form_slug
            FROM $table_name"
    );
    ?>
    <div class="wrap">
        <h1>Form to Database</h1>
        <table class="wp-list-table widefat plugins">
            <thead>
                <tr>
                    <th>Form Title</th>
                    <th>Entries</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($form_names as $form_name) {
                    $entries = $wpdb->get_results(
                        $wpdb->prepare("
                            SELECT count(data) as entries
                            FROM $table_name
                            WHERE form_slug = %s",
                            $form_name->form_slug)
                        );
                    echo '<tr>
                        <td><a href="'.admin_url('admin.php?page=form-to-database&form='.$form_name->form_slug).'">'.$form_name->form_name.'</a></td>
                        <td>'.$entries[0]->entries.'</td>
                    </tr>
                    ';
                }
                ?>
            </tbody>
        </table>
        <?php if(isset($_GET['form']) && !empty($_GET['form'])) { ?>
            <?php
                $form_slug = $_GET['form'];
                $results = $wpdb->get_results(
                    $wpdb->prepare("SELECT * FROM $table_name WHERE form_slug=%s ORDER BY created_at ASC",$form_slug)
                );
                $columns_results = $wpdb->get_row(
                    $wpdb->prepare("SELECT * FROM $table_name WHERE form_slug=%s AND form_columns IS NOT NULL AND TRIM(form_columns) <> '' ORDER BY created_at DESC",$form_slug)
                );

                $columns = json_decode($columns_results->form_columns, true);
                if (empty($columns)) {
                    $columns = json_decode($columns_results->data, true);
                }
                $column_count = count($columns);

                ?>
                <table class="wp-list-table widefat plugins ftd-data-table">
                    <thead>
                        <tr>
                        <?php
                            if (!empty($columns)) {
                                unset($columns['g-recaptcha-response']);
                                foreach ($columns as $column) {
                                    echo '<th>'.$column.'</th>';
                                }
                            } else {
                                unset($columns['g-recaptcha-response']);
                                foreach ($columns as $key => $value) {
                                    echo '<th>'.ucfirst(str_replace('_', '', $key)).'</th>';
                                }
                            }
                        ?>
                        </tr>
                    </thead>
                    <tbody id="the-list">
                    <?php
                        // Loop through form columns and if no match, make cell blank (nbsp)
                        foreach ($results as $result) {
                            $row = json_decode($result->data, true);
                            unset($row['g-recaptcha-response']);
                            echo '<tr>';
                            foreach ($row as $cell) {
                                //echo '<td>'.$cell.'&nbsp;</th>';
                            }
                            foreach ($columns as $key => $value) {
                                echo '<td>'.(array_key_exists($key, $row)?$row[$key]:'').'&nbsp;</th>';
                            }
                            echo '</tr>';
                        }
                    ?>
                    </tbody>
                </table>

        <?php } ?>
    </div>
<?php
