<table class="sorted">
    <thead>
    <tr class="topRow">
        <td colspan="5"><h2><?= sysTranslations::get('catalog_product_types_all') ?></h2>
            <div class="right"><a class="addBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/toevoegen" title="<?= sysTranslations::get('catalog_product_type_add') ?>"
                                  alt="<?= sysTranslations::get('catalog_product_type_add') ?>"><?= sysTranslations::get('catalog_product_type_add') ?></a><br/><a class="changeOrderBtn textRight"
                                                                                                                                                                   href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/volgorde-wijzigen"
                                                                                                                                                                   title="<?= sysTranslations::get('global_change_order') ?>"
                                                                                                                                                                   alt="<?= sysTranslations::get(
                                                                                                                                                                       'global_change_order'
                                                                                                                                                                   ) ?>"><?= sysTranslations::get('global_change_order') ?></a></div>
        </td>
    </tr>
    <tr>
        <th><?= sysTranslations::get('catalog_product_type') ?></th>
        <th><?= sysTranslations::get('catalog_with_sizes') ?></th>
        <th><?= sysTranslations::get('catalog_with_colors') ?></th>
        <th><?= sysTranslations::get('catalog_with_genders') ?></th>
        <th class="{sorter:false} nonSorted" style="width: 60px;">&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php

    foreach ($aAllProductTypes AS $oProductType) {
        echo '<tr>';
        echo '<td>' . _e($oProductType->getTranslations('auto-admin')->title) . '</td>';
        echo '<td>' . ($oProductType->withSizes ? sysTranslations::get('global_yes') : sysTranslations::get('global_no')) . '</td>';
        echo '<td>' . ($oProductType->withColors ? sysTranslations::get('global_yes') : sysTranslations::get('global_no')) . '</td>';
        echo '<td>' . ($oProductType->withGenders ? sysTranslations::get('global_yes') : sysTranslations::get('global_no')) . '</td>';
        echo '<td>';
        echo '<a class="action_icon edit_icon" title="' . sysTranslations::get('catalog_product_type_edit') . '" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oProductType->catalogProductTypeId . '"></a>';

        if ($oProductType->isDeletable()) {
            echo '<a class="action_icon delete_icon" title="' . sysTranslations::get('catalog_product_type_delete') . '" onclick="return confirmChoice(\'' . _e(
                    $oProductType->getTranslations('auto-admin')->title
                ) . '\');" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/verwijderen/' . $oProductType->catalogProductTypeId . '?'. CSRFSynchronizerToken::query() .'"></a>';
        } else {
            echo '<span class="action_icon delete_icon grey" title="' . sysTranslations::get('catalog_product_type_with_products') . '"></span>';
        }
        echo '</td>';
        echo '</tr>';
    }
    if (empty($aAllProductTypes)) {
        echo '<tr><td colspan="5"><i>' . sysTranslations::get('catalog_no_product_types') . '</i></td></tr>';
    }
    ?>
    </tbody>
</table>