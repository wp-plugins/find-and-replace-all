<?php
$findstr = isset($_POST['findstr']) ? esc_attr($_POST['findstr']) : '';
$replacestr = isset($_POST['replacestr']) ? esc_attr($_POST['replacestr']) : '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' and $findstr!='') {
    global $wpdb;
    $tables = $wpdb->get_results('SHOW TABLES FROM ' . DB_NAME, ARRAY_N);

    foreach ($tables as $tablearr) {
        $table = $tablearr[0];
        $columns = $wpdb->get_results("SHOW COLUMNS FROM $table");
        foreach ($columns as $column) {
            if (isset($column->Key) and $column->Key != 'PRI') {
                $field = $column->Field;
                $csql = "UPDATE $table SET `$field` = REPLACE(`$field`, '$findstr', '$replacestr');";
                $wpdb->get_results($csql);
            }
        }
        echo "<br />Replacing in table $table";
    }
    echo "<br />All done. Okay!!";
}
?>
<script>
    function validate_frform(obj) {
        if (jQuery('#replacestr').val() == '') {
            var c = confirm('All the occurrence of Find String will be replaced with empty string. Do you want continue?');
            if (!c) {
                return false;
            }
        }

        var c = confirm('Once replaced, there is no undo option. Do you want to continue?');
        if (!c) {
            return false;
        }
        return true;
    }
</script>
<div class="wrap">
    <h2>Find and Replace</h2>
    <p class="description">It's case sensitive.</p>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        ?>
        <div class="updated settings-error" id="setting-error-settings_updated"> 
            <p><strong>Successfully replaced.</strong></p>
        </div>
        <?php
    }
    ?>
    <form method="post" onsubmit="return validate_frform(this);">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="findstr">Find String</label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" value="<?php echo $findstr; ?>" required="" id="findstr" name="findstr" />
                        <p class="description">Find this string in all the fields of all the tables</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="replacestr">Replace with String</label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" value="<?php echo $replacestr; ?>" id="replacestr" name="replacestr" />
                        <p class="description">Replace with this string in all the fields of all the tables</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" value="Replace Now" class="button button-primary" id="submit" name="submit">
        </p>
        <p class="description">Please be careful there is no undo.</p>
    </form>
</div>