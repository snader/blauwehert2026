<table class="sorted">
    <thead>
    <tr class="topRow">
        <td colspan="3">
            <h2><?= sysTranslations::get('catalog_all_colors') ?></h2>
            <div class="right">
                <a class="addBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/toevoegen" title="<?= sysTranslations::get('catalog_color_add') ?>" alt="Kleur toevoegen"><?= sysTranslations::get(
                        'catalog_color_add'
                    ) ?></a><br/>
                <a class="changeOrderBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/volgorde-wijzigen" title="<?= sysTranslations::get('global_change_order') ?>" alt="Volgorde wijzigen"><?= sysTranslations::get(
                        'global_change_order'
                    ) ?></a>
            </div>
        </td>
    </tr>
    <tr>
        <th><?= sysTranslations::get('global_color') ?></th>
        <th class="{sorter:false} nonSorted" style="width: 60px;">&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php

    foreach ($aProductColors AS $oProductColor) {

        echo '<tr>';
        echo '<td>' . _e($oProductColor->getTranslations('auto-admin')->name) . '</td>';
        echo '<td>';
        echo '<a class="action_icon edit_icon" title="' . sysTranslations::get('catalog_color_edit') . '" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oProductColor->catalogProductColorId . '"></a>';

        if ($oProductColor->isDeletable()) {
            echo '<a class="action_icon delete_icon" title="' . sysTranslations::get('catalog_color_delete') . '" onclick="return confirmChoice(\'' . _e(
                    $oProductColor->getTranslations('auto-admin')->name
                ) . '\');" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/verwijderen/' . $oProductColor->catalogProductColorId . '?'. CSRFSynchronizerToken::query() .'"></a>';
        } else {
            echo '<span class="action_icon delete_icon grey" title="' . sysTranslations::get('catalog_color_with_products') . '"></span>';
        }

        echo '</td>';
        echo '</tr>';
    }
    if (empty($aProductColors)) {
        echo '<tr><td colspan="3"><i>' . sysTranslations::get('catalog_no_colors') . '</i></td></tr>';
    }
    ?>
    </tbody>
</table>