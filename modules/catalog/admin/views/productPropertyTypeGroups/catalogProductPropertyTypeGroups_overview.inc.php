<form action="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>" method="POST">
    <input type="hidden" name="filterForm" value="1"/>
    <fieldset style="margin-bottom: 20px;">
        <legend><?= sysTranslations::get('global_filter') ?></legend>
        <table class="withForm">
            <tr>
                <td class="withLabel" style="width: 116px;"><?= ucfirst(sysTranslations::get('global_type')) ?></td>
                <td>
                    <select class="default" name="productPropertyTypeGroupFilter[catalogProductTypeId]">
                        <option value="-1"><?= sysTranslations::get('global_make_choice') ?></option>
                        <?php

                        foreach (CatalogProductTypeManager::getAllProductTypes() AS $oProductType) {
                            echo '<option ' . ($aProductPropertyTypeGroupFilter['catalogProductTypeId'] == $oProductType->catalogProductTypeId ? 'SELECTED' : '') . ' value="' . $oProductType->catalogProductTypeId . '">' . _e(
                                    $oProductType->getTranslations('auto-admin')->title
                                ) . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" name="filterProductPropertyTypeGroups" value="<?= sysTranslations::get('catalog_filter_property_groups') ?>"/> <input type="submit" name="resetFilter"
                                                                                                                                                               value="<?= sysTranslations::get('global_reset_filter') ?>"/></td>
            </tr>
        </table>
    </fieldset>
</form>
<table class="sorted">
    <thead>
    <tr class="topRow">
        <td colspan="3"><h2><?= sysTranslations::get('catalog_property_group_found') ?> (<?= (count($aProductPropertyTypeGroups) != 0 ? ($iStart + 1) : $iStart) . '-' . ($iStart + count($aProductPropertyTypeGroups)) ?>/<?= $iFoundRows ?>
                )</h2>
            <div class="right"><a class="addBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/toevoegen" title="<?= sysTranslations::get('catalog_property_group_add') ?>"
                                  alt="<?= sysTranslations::get('catalog_property_group_add') ?>"><?= sysTranslations::get('catalog_property_group_add') ?></a><br/><a class="changeOrderBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get(
                    'controller'
                ) ?>/volgorde-wijzigen" title="<?= sysTranslations::get('global_change_order') ?>" alt="Volgorde wijzigen"><?= sysTranslations::get('global_change_order') ?></a></div>
        </td>
    </tr>
    <tr>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('global_group_name') ?></th>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('catalog_product_type') ?></th>
        <th class="{sorter:false} nonSorted" style="width: 60px;">&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php

    foreach ($aProductPropertyTypeGroups AS $oProductPropertyTypeGroup) {
        echo '<tr>';
        echo '<td>' . $oProductPropertyTypeGroup->getTranslations('auto-admin')->title . '</td>';
        echo '<td>' . ($oProductPropertyTypeGroup->getProductType() ? _e(
                $oProductPropertyTypeGroup->getProductType()
                    ->getTranslations('auto-admin')->title
            ) : '') . '</td>';
        echo '<td>';
        echo '<a class="action_icon edit_icon" title="' . sysTranslations::get('catalog_property_group_edit') . '" href="' . ADMIN_FOLDER . '/' . http_get(
                'controller'
            ) . '/bewerken/' . $oProductPropertyTypeGroup->catalogProductPropertyTypeGroupId . '"></a>';

        if ($oProductPropertyTypeGroup->isDeletable()) {
            echo '<a class="action_icon delete_icon" title="' . sysTranslations::get('catalog_property_group_delete') . '" onclick="return confirmChoice(\'' . _e(
                    $oProductPropertyTypeGroup->getTranslations('auto-admin')->title
                ) . ', en alle koppelingen met producten\');" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/verwijderen/' . $oProductPropertyTypeGroup->catalogProductPropertyTypeGroupId . '?'. CSRFSynchronizerToken::query() .'"></a>';
        } else {
            echo '<span class="action_icon delete_icon grey" title="' . sysTranslations::get('catalog_property_group_with_properties') . '"></span>';
        }

        echo '</td>';
        echo '</tr>';
    }
    if (empty($aProductPropertyTypeGroups)) {
        echo '<tr><td colspan="3"><i>' . sysTranslations::get('catalog_not_property_group') . '</i></td></tr>';
    }
    ?>
    </tbody>
    <tfoot>
    <tr class="bottomRow">
        <td colspan="3">
            <form method="POST">
                <?= generatePaginationHTML($iPageCount, $iCurrPage) ?>
                <input type="hidden" name="setPerPage" value="1"/>
                <select name="perPage" onchange="$(this).closest('form').submit();">
                    <option value="<?= $iNrOfRecords ?>"><?= sysTranslations::get('global_all') ?></option>
                    <option <?= $iPerPage == 10 ? 'SELECTED' : '' ?> value="10">10</option>
                    <option <?= $iPerPage == 25 ? 'SELECTED' : '' ?> value="25">25</option>
                    <option <?= $iPerPage == 50 ? 'SELECTED' : '' ?> value="50">50</option>
                    <option <?= $iPerPage == 100 ? 'SELECTED' : '' ?> value="100">100</option>
                </select> <?= sysTranslations::get('global_per_page') ?>
            </form>
        </td>
    </tr>
    </tfoot>
</table>