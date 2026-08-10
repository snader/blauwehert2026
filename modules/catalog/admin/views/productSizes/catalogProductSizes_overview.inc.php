<table class="sorted">
    <thead>
    <tr class="topRow">
        <td colspan="3">
            <h2><?= sysTranslations::get('catalog_all_sizes') ?></h2>
            <div class="right">
                <a class="addBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/toevoegen" title="<?= sysTranslations::get('catalog_size_add') ?>"
                   alt="<?= sysTranslations::get('catalog_size_add') ?>"><?= sysTranslations::get('catalog_size_add') ?></a><br/>
                <a class="changeOrderBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/volgorde-wijzigen" title="<?= sysTranslations::get('global_change_order') ?>"
                   alt="<?= sysTranslations::get('global_change_order') ?>"><?= sysTranslations::get('global_change_order') ?></a>
            </div>
        </td>
    </tr>
    <tr>
        <th><?= sysTranslations::get('global_size') ?></th>
        <th class="{sorter:false} nonSorted" style="width: 60px;">&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php

    foreach ($aProductSizes AS $oProductSize) {

        echo '<tr>';
        echo '<td>' . _e($oProductSize->getTranslations('auto-admin')->name) . '</td>';
        echo '<td>';
        echo '<a class="action_icon edit_icon" title="' . sysTranslations::get('catalog_size_edit') . '" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oProductSize->catalogProductSizeId . '"></a>';

        if ($oProductSize->isDeletable()) {
            echo '<a class="action_icon delete_icon" title="' . sysTranslations::get('catalog_size_delete') . '" onclick="return confirmChoice(\'' . _e(
                    $oProductSize->getTranslations('auto-admin')->name
                ) . '\');" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/verwijderen/' . $oProductSize->catalogProductSizeId . '?'. CSRFSynchronizerToken::query() .'"></a>';
        } else {
            echo '<span class="action_icon delete_icon grey" title="' . sysTranslations::get('catalog_size_with_products') . '"></span>';
        }

        echo '</td>';
        echo '</tr>';
    }
    if (empty($aProductSizes)) {
        echo '<tr><td colspan="3"><i>' . sysTranslations::get('catalog_no_sizes') . '</i></td></tr>';
    }
    ?>
    </tbody>
</table>