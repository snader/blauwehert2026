<form action="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>" method="POST">
    <input type="hidden" name="filterForm" value="1"/>
    <fieldset style="margin-bottom: 20px;">
        <legend><?= sysTranslations::get('global_filter') ?></legend>
        <table class="withForm">
            <tr>
                <td class="withLabel" style="width: 140px;"><?= ucfirst(sysTranslations::get('global_name')) ?>
                    <div class="hasTooltip tooltip" title="<?= sysTranslations::get('catalog_tooltip_filter_name') ?>">&nbsp;</div>
                </td>
                <td><input class="default" type="text" name="productFilter[q]" value="<?= _e($aProductFilter['q']) ?>"/></td>
            </tr>
            <tr>
                <td class="withLabel"><?= ucfirst(sysTranslations::get('global_brand')) ?></td>
                <td>
                    <select class="default" name="productFilter[catalogBrandId]">
                        <option value=""><?= sysTranslations::get('catalog_all_brands') ?></option>
                        <?php

                        foreach (CatalogBrandManager::getAllBrands() AS $oBrand) {
                            echo '<option ' . ($aProductFilter['catalogBrandId'] == $oBrand->catalogBrandId ? 'SELECTED' : '') . ' value="' . $oBrand->catalogBrandId . '">' . _e($oBrand->getTranslations('auto-admin')->name) . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="withLabel"><?= ucfirst(sysTranslations::get('global_type')) ?></td>
                <td>
                    <select class="default" name="productFilter[catalogProductTypeId]">
                        <option value=""><?= sysTranslations::get('catalog_all_types') ?></option>
                        <?php

                        foreach (CatalogProductTypeManager::getAllProductTypes() AS $oProductType) {
                            echo '<option ' . ($aProductFilter['catalogProductTypeId'] == $oProductType->catalogProductTypeId ? 'SELECTED' : '') . ' value="' . $oProductType->catalogProductTypeId . '">' . _e(
                                    $oProductType->getTranslations('auto-admin')->title
                                ) . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="withLabel"><?= ucfirst(sysTranslations::get('global_category')) ?></td>
                <td>
                    <select class="default" name="productFilter[catalogProductCategoryId]">
                        <option value=""><?= sysTranslations::get('global_all_categories') ?></option>
                        <?php

                        foreach (CatalogProductCategoryManager::getProductCategoriesByFilter() AS $oProductCategory) {
                            echo '<option ' . ($aProductFilter['catalogProductCategoryId'] == $oProductCategory->catalogProductCategoryId ? 'SELECTED' : '') . ' value="' . $oProductCategory->catalogProductCategoryId . '">' . _e(
                                    $oProductCategory->getTranslations('auto-admin')->name
                                ) . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><?= sysTranslations::get('global_show_homepage') ?>
                    <div class="hasTooltip tooltip" title="<?= sysTranslations::get('catalog_search_homepage') ?>">&nbsp;</div>
                </td>
                <td>
                    <input <?= !empty($aProductFilter['showOnHome']) ? 'checked' : '' ?> class="alignCheckbox" name="productFilter[showOnHome]" id="showOnHome" type="checkbox" value="showOnHome"/> <label
                            for="showOnHome"><?= sysTranslations::get('global_yes') ?></label>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" name="filterProducts" value="<?= sysTranslations::get('catalog_filter_products') ?>"/> <input type="submit" name="resetFilter" value="<?= sysTranslations::get('global_reset_filter') ?>"/></td>
            </tr>
        </table>
    </fieldset>
</form>

<!-- Products -->
<table class="sorted">
    <thead>
    <tr class="topRow">
        <td colspan="9"><h2><?= sysTranslations::get('catalog_found_products') ?> (<?= (count($aProducts) != 0 ? ($iStart + 1) : $iStart) . '-' . ($iStart + count($aProducts)) ?>/<?= $iFoundRows ?>)</h2>
            <div class="right"><a class="addBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/toevoegen" title="Product toevoegen" alt="Product toevoegen"><?= sysTranslations::get('catalog_add_product') ?></a></div>
        </td>
    </tr>
    <tr>
        <th class="{sorter:false} nonSorted onlineOffline">&nbsp;</th>
        <th class="{sorter:false} nonSorted"><?= ucfirst(sysTranslations::get('global_name')) ?></th>
        <th class="{sorter:false} nonSorted"><?= ucfirst(sysTranslations::get('global_brand')) ?></th>
        <th class="{sorter:false} nonSorted"><?= ucfirst(sysTranslations::get('global_type')) ?></th>
        <th class="{sorter:false} nonSorted"><?= Settings::get('taxIncluded') ? ucfirst(sysTranslations::get('global_price')) . ' (' . sysTranslations::get('global_inc_taxes') . ')' : ucfirst(
                    sysTranslations::get('global_price')
                ) . ' (' . sysTranslations::get('global_excl_taxes') . ')' ?></th>
        <th class="{sorter:false} nonSorted"><?= Settings::get('taxIncluded') ? ucfirst(sysTranslations::get('catalog_reduced_price')) . ' (' . sysTranslations::get('global_inc_taxes') . ')' : ucfirst(
                    sysTranslations::get('catalog_reduced_price')
                ) . ' (' . sysTranslations::get('global_excl_taxes') . ')' ?></th>
        <th class="{sorter:false} nonSorted"><?= Settings::get('taxIncluded') ? ucfirst(sysTranslations::get('catalog_purchase_price')) . ' (' . sysTranslations::get('global_inc_taxes') . ')' : ucfirst(
                    sysTranslations::get('catalog_purchase_price')
                ) . ' (' . sysTranslations::get('global_excl_taxes') . ')' ?></th>
        <th class="{sorter:false} nonSorted"><?= ucfirst(sysTranslations::get('catalog_margin')) ?></th>
        <th class="{sorter:false} nonSorted" style="width: 60px;">&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php

    foreach ($aProducts AS $oProduct) {
        echo '<tr>';
        echo '<td>';
        # online offline button
        echo '<a id="product_' . $oProduct->catalogProductId . '_online_1" title="' . sysTranslations::get(
                'catalog_set_offline'
            ) . '" class="action_icon ' . ($oProduct->online ? '' : 'hide') . ' online_icon" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/ajax-setOnline/' . $oProduct->catalogProductId . '/?online=0&'. CSRFSynchronizerToken::query() .'"></a>';
        echo '<a id="product_' . $oProduct->catalogProductId . '_online_0" title="' . sysTranslations::get(
                'catalog_set_online'
            ) . '" class="action_icon ' . ($oProduct->online ? 'hide' : '') . ' offline_icon" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/ajax-setOnline/' . $oProduct->catalogProductId . '/?online=1&'. CSRFSynchronizerToken::query() .'"></a>';
        echo '</td>';
        echo '<td>' . _e($oProduct->getTranslations('auto-admin')->name) . '</td>';
        echo '<td>' . ($oProduct->getBrand() ? _e(
                $oProduct->getBrand()
                    ->getTranslations('auto-admin')->name
            ) : sysTranslations::get('global_unknown')) . '</td>';
        echo '<td>' . ($oProduct->getProductType() ? _e(
                $oProduct->getProductType()
                    ->getTranslations('auto-admin')->title
            ) : sysTranslations::get('global_unknown')) . '</td>';
        echo '<td>' . decimal2valuta($oProduct->getSalePrice(Settings::get('taxIncluded'), null, null, true, false)) . '</td>';
        echo '<td>' . decimal2valuta($oProduct->getReducedPrice(Settings::get('taxIncluded'))) . '</td>';
        echo '<td>' . decimal2valuta($oProduct->getPurchasePrice(Settings::get('taxIncluded'))) . '</td>';
        echo '<td>' . decimal2valuta($oProduct->getSalePrice(Settings::get('taxIncluded'), null, null, true, true) - $oProduct->getPurchasePrice(Settings::get('taxIncluded'))) . '</td>';
        echo '<td>';
        echo '<a class="action_icon edit_icon" title="' . sysTranslations::get('catalog_edit_product') . '" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oProduct->catalogProductId . '"></a>';
        echo '<a class="action_icon delete_icon" title="' . sysTranslations::get('catalog_delete_product') . '" onclick="return confirmChoice(\'' . _e(
                $oProduct->getTranslations('auto-admin')->name
            ) . '\');" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/verwijderen/' . $oProduct->catalogProductId . '?'. CSRFSynchronizerToken::query() .'"></a>';
        echo '</td>';
        echo '</tr>';
    }
    if (empty($aProducts)) {
        echo '<tr><td colspan="9"><i>' . sysTranslations::get('catalog_no_products') . '</i></td></tr>';
    }
    ?>
    </tbody>
    <tfoot>
    <tr class="bottomRow">
        <td colspan="9">
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

# create necessary javascript
$sController       = http_get('controller');
$sStatusOffline    = sysTranslations::get('catalog_status_offline');
$sStatusOnline     = sysTranslations::get('catalog_status_online');
$sStatusNotChanged = sysTranslations::get('catalog_status_not_changed');
$sBottomJavascript = <<<EOT
<script>
    $("a.online_icon, a.offline_icon").click(function(e){
        $.ajax({
            type: "GET",
            url: $(this).prop('href'),
            data: 'ajax=1',
            async: true,
            success: function(data){
                var dataObj = eval('(' + data + ')');

                /* On success */
                if (dataObj.success == true){
                    $("#product_"+dataObj.catalogProductId+"_online_0").hide(); // hide offline button
                    $("#product_"+dataObj.catalogProductId+"_online_1").hide(); // hide online button
                    $("#product_"+dataObj.catalogProductId+"_online_"+dataObj.online).css('display', 'inline-block'); // show button based on online value
                    if(dataObj.online == 0)    
                        showStatusUpdate("$sStatusOffline");
                    if(dataObj.online == 1)    
                        showStatusUpdate("$sStatusOnline");                            
                } else {
                    showStatusUpdate("$sStatusNotChanged");
                }
            }
        });
        e.preventDefault();
    });
</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);
?>