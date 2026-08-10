<form action="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>" method="POST">
    <input type="hidden" name="filterForm" value="1"/>
    <fieldset style="margin-bottom: 20px;">
        <legend><?= sysTranslations::get('global_filter') ?></legend>
        <table class="withForm">
            <tr>
                <td class="withLabel" style="width: 116px;"><?= sysTranslations::get('catalog_product_type') ?></td>
                <td>
                    <select class="default" id="catalogProductTypeId" name="productPropertyTypeFilter[catalogProductTypeId]">
                        <option value="-1"><?= sysTranslations::get('global_make_choice') ?></option>
                        <?php

                        foreach ($aProductTypes AS $oProductType) {
                            echo '<option ' . ($aProductPropertyTypeFilter['catalogProductTypeId'] == $oProductType->catalogProductTypeId ? 'SELECTED' : '') . ' value="' . $oProductType->catalogProductTypeId . '">' . _e(
                                    $oProductType->getTranslations('auto-admin')->title
                                ) . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="withLabel" style="width: 116px;"><?= sysTranslations::get('catalog_property_group') ?></td>
                <td>
                    <select class="catalogProductPropertyTypeGroupId default" data-productpropertytypeid="-1" <?= $aProductPropertyTypeFilter['catalogProductTypeId'] == -1 ? '' : 'DISABLED style="display: none"' ?>>
                        <option value="-1"><?= sysTranslations::get('catalog_product_type_choose') ?></option>
                    </select>
                    <?php

                    foreach ($aProductTypes AS $oProductType) {
                        ?>
                        <select class="catalogProductPropertyTypeGroupId default" data-productpropertytypeid="<?= $oProductType->catalogProductTypeId ?>"
                                name="productPropertyTypeFilter[catalogProductPropertyTypeGroupId]" <?= $aProductPropertyTypeFilter['catalogProductTypeId'] == $oProductType->catalogProductTypeId ? '' : 'DISABLED style="display: none"' ?>>
                            <option value=""><?= sysTranslations::get('catalog_product_properties_all') ?></option>
                            <?php

                            foreach (CatalogProductPropertyTypeGroupManager::getProductPropertyTypeGroupsByProductTypeId($oProductType->catalogProductTypeId) AS $oProductPropertyTypeGroup) {
                                echo '<option ' . ($aProductPropertyTypeFilter['catalogProductPropertyTypeGroupId'] == $oProductPropertyTypeGroup->catalogProductPropertyTypeGroupId ? 'SELECTED' : '') . ' value="' . $oProductPropertyTypeGroup->catalogProductPropertyTypeGroupId . '">' . _e(
                                        $oProductPropertyTypeGroup->getTranslations('auto-admin')->title
                                    ) . '</option>';
                            }
                            ?>
                        </select>
                        <?php

                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" name="filterProductPropertyTypes" value="<?= sysTranslations::get('catalog_product_properties_filter') ?>"/> <input type="submit" name="resetFilter"
                                                                                                                                                             value="<?= sysTranslations::get('global_reset_filter') ?>"/></td>
            </tr>
        </table>
    </fieldset>
</form>
<table class="sorted">
    <thead>
    <tr class="topRow">
        <td colspan="5"><h2><?= sysTranslations::get('catalog_product_properties_found') ?> (<?= (count($aProductPropertyTypes) != 0 ? ($iStart + 1) : $iStart) . '-' . ($iStart + count($aProductPropertyTypes)) ?>/<?= $iFoundRows ?>)</h2>
            <div class="right"><a class="addBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/toevoegen" title="<?= sysTranslations::get('catalog_product_property_add') ?>"
                                  alt="<?= sysTranslations::get('catalog_product_property_add') ?>"><?= sysTranslations::get('catalog_product_property_add') ?></a><br/><a class="changeOrderBtn textRight"
                                                                                                                                                                           href="<?= ADMIN_FOLDER ?>/<?= http_get(
                                                                                                                                                                               'controller'
                                                                                                                                                                           ) ?>/volgorde-wijzigen"
                                                                                                                                                                           title="<?= sysTranslations::get('global_change_order') ?>"
                                                                                                                                                                           alt="<?= sysTranslations::get(
                                                                                                                                                                               'global_change_order'
                                                                                                                                                                           ) ?>"><?= sysTranslations::get('global_change_order') ?></a></div>
        </td>
    </tr>
    <tr>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('catalog_product_features') ?></th>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('catalog_product_type') ?></th>
        <th class="{sorter:false} nonSorted"><?= ucfirst(sysTranslations::get('global_type')) ?></th>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('catalog_filter_type') ?></th>
        <th class="{sorter:false} nonSorted" style="width: 60px;">&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php

    foreach ($aProductPropertyTypes AS $oProductPropertyType) {
        echo '<tr>';
        echo '<td>' . _e($oProductPropertyType->getTranslations('auto-admin')->title) . '</td>';
        echo '<td>' . ($oProductPropertyType->getProductType() ? _e(
                $oProductPropertyType->getProductType()
                    ->getTranslations('auto-admin')->title
            ) : '') . '</td>';
        echo '<td>' . $oProductPropertyType->type . '</td>';
        echo '<td>' . $oProductPropertyType->filterType . '</td>';
        echo '<td>';
        echo '<a class="action_icon edit_icon" title="' . sysTranslations::get('catalog_product_properties_edit') . '" href="' . ADMIN_FOLDER . '/' . http_get(
                'controller'
            ) . '/bewerken/' . $oProductPropertyType->catalogProductPropertyTypeId . '"></a>';
        echo '<a class="action_icon delete_icon" title="' . sysTranslations::get('catalog_product_properties_delete_tooltip') . '" onclick="return confirmChoice(\'' . _e(
                $oProductPropertyType->getTranslations('auto-admin')->title
            ) . ', en alle koppelingen met producten\');" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/verwijderen/' . $oProductPropertyType->catalogProductPropertyTypeId . '?'. CSRFSynchronizerToken::query() .'"></a>';
        echo '</td>';
        echo '</tr>';
    }
    if (empty($aProductPropertyTypes)) {
        echo '<tr><td colspan="5"><i>' . sysTranslations::get('catalog_no_product_properties') . '</i></td></tr>';
    }
    ?>
    </tbody>
    <tfoot>
    <tr class="bottomRow">
        <td colspan="5">
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
<?php

$sBottomJavascript = <<<EOT
<script>
    // change the catalogProductTypeId-select when catalogProductPropertyTypeGroupId-select changes
    $('select#catalogProductTypeId').change(function() {
        $('select.catalogProductPropertyTypeGroupId').hide();
        $('select.catalogProductPropertyTypeGroupId').prop('disabled', true);
        $('select.catalogProductPropertyTypeGroupId').val('-1');
        $('select.catalogProductPropertyTypeGroupId[data-productpropertytypeid=' + $(this).val() + ']').removeAttr('disabled');
        $('select.catalogProductPropertyTypeGroupId[data-productpropertytypeid=' + $(this).val() + ']').show();
    });
</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);
?>