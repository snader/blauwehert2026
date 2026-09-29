<table class="sorted-delayed full-width w-100" style="width: 100%; table-layout: fixed;">
    <thead>
    <tr>
        <th>titel</th>
        <th style="width: 50px; text-align: center;">actie</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if (!empty($aMapping)) {
        foreach ($aMapping AS $oObject) {
            echo '<tr>';
            echo '<td>' . _e($oObject->label) . '</td>';
            echo '<td class="text-align-center" style="width:50px; padding: 4px 0;">';
            echo '<button type="button" class="btn btn-xs btn-danger unlinkItem" title="' . sysTranslations::get('pages_unlink') . '" data-linked-id="' . $oObject->value . '" aria-label="' . sysTranslations::get('pages_unlink') . '" style="padding: 2px 6px; line-height: 1; font-size: 11px;"><i class="fas fa-trash"></i></button>';
            echo '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="2"><i>' . sysTranslations::get('autocomplete_no_connected_items') . '</i></td></tr>';
    }
    ?>
    </tbody>
</table>
