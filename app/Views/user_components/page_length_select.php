<?php

if (isset($current_page_length))
    $currPageLength = $current_page_length;
else
    $currPageLength = 15;


?>

<div class="form-group mb-3">
    <label class="form-label">
        Page Length
    </label>
    <select class="form-select" name="<?= $name ?? 'pageLength' ?>">
        <?php
        foreach ($lengths as &$length) {
            $current = $currPageLength == $length ? 'selected' : '';
            echo "<option value=\"$length\" $current>$length</option>";
        }
        ?>
    </select>
</div>