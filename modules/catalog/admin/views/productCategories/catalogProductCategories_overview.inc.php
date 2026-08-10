<div class="likeTopRow cf">
    <h2><?= sysTranslations::get('catalog_categories_all') ?></h2>
    <div class="right">
        <a class="addBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/toevoegen"><?= sysTranslations::get('catalog_add_main_category') ?></a><br/>
        <a class="changeOrderBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/structuur-wijzigen"><?= sysTranslations::get('global_change_order') ?></a>
    </div>
</div>
<?php
# list categories in unordered list

function makeListTree(array $aProductCategories = [], $iLevel, $iMaxLevels)
{
    if (count($aProductCategories) > 0 && $iLevel == 1) {
        echo '<ol class="nestedSortable level' . $iLevel . '">';
    } elseif (count($aProductCategories) > 0) {
        echo '<ol class="level' . $iLevel . '">';
    }

    $iT = 0;
    foreach ($aProductCategories AS $oProductCategory) {
        echo '<li id="productCategory_' . $oProductCategory->catalogProductCategoryId . '">';

        $sClasses = '';
        if ($iT > 0 && $iLevel == 1) {
            $sClasses .= ' mainPage';
        }
        if ($iT == 0 && $iLevel == 1) {
            $sClasses .= ' first-mainPage';
        }
        if ($iT > 0 && $iLevel > 1) {
            $sClasses .= ' sub';
        }
        if ($iT == 0 && $iLevel > 1) {
            $sClasses .= ' first-sub';
        }

        # add sub category
        echo '<div class="' . $sClasses . (($iLevel < $iMaxLevels) ? '"><a class="action_icon add_icon" alt="' . sysTranslations::get('catalog_add_sub_category') . '" title="' . sysTranslations::get(
                    'catalog_add_sub_category'
                ) . '" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/toevoegen?parentCatalogProductCategoryId=' . $oProductCategory->catalogProductCategoryId . '"></a>' : ' no-action-icons">');

        echo _e($oProductCategory->getTranslations('auto-admin')->name);

        echo '<div class="actionIconsHolder">';

        # online offline button
        echo '<a id="productCategory_' . $oProductCategory->catalogProductCategoryId . '_online_1" productCategoryTitle="' . _e($oProductCategory->getTranslations('auto-admin')->name) . '" title="' . sysTranslations::get(
                'catalog_category_set_offline'
            ) . '" class="action_icon ' . ($oProductCategory->online ? '' : 'hide') . ' online_icon" href="' . ADMIN_FOLDER . '/' . http_get(
                'controller'
            ) . '/ajax-setOnline/' . $oProductCategory->catalogProductCategoryId . '/?online=0&'. CSRFSynchronizerToken::query() .'"></a>';
        echo '<a id="productCategory_' . $oProductCategory->catalogProductCategoryId . '_online_0" productCategoryTitle="' . _e($oProductCategory->getTranslations('auto-admin')->name) . '" title="' . sysTranslations::get(
                'catalog_category_set_online'
            ) . '" class="action_icon ' . ($oProductCategory->online ? 'hide' : '') . ' offline_icon" href="' . ADMIN_FOLDER . '/' . http_get(
                'controller'
            ) . '/ajax-setOnline/' . $oProductCategory->catalogProductCategoryId . '/?online=1&'. CSRFSynchronizerToken::query() .'"></a>';

        #edit button
        echo '<a title="' . sysTranslations::get('catalog_category_edit') . '" class="action_icon edit_icon" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oProductCategory->catalogProductCategoryId . '"></a>';

        # delete button
        if ($oProductCategory->isDeletable()) {
            echo '<a onclick="return confirmChoice(\'product categorie ' . _e($oProductCategory->getTranslations('auto-admin')->name) . '\');" class="action_icon delete_icon" href="' . ADMIN_FOLDER . '/' . http_get(
                    'controller'
                ) . '/verwijderen/' . $oProductCategory->catalogProductCategoryId . '?'. CSRFSynchronizerToken::query() .'"></a>';
        } else {
            echo '<a class="action_icon delete_icon grey" href="#" title="' . sysTranslations::get('catalog_category_delete') . '"></a>';
        }

        echo '</div>';
        echo '</div>';
        makeListTree($oProductCategory->getSubCategories('all'), $iLevel + 1, $iMaxLevels); //call function recursive
        echo '</li>';
        $iT++;
    }
    if (count($aProductCategories) > 0) {
        echo '</ol>';
    }
}

if (count($aLevel1ProductCategories) == 0) {
    echo '<div class="likeSorterTr"><i>' . sysTranslations::get('catalog_no_category') . '</i></div>';
}
# start recursive displaying categories
makeListTree($aLevel1ProductCategories, 1, $iMaxLevels);

# create necessary javascript
$sController         = http_get('controller');
$sCategoryOffline    = sysTranslations::get('catalog_category_status_offline');
$sCategoryOnline     = sysTranslations::get('catalog_category_status_online');
$sCategoryNotChanged = sysTranslations::get('catalog_category_status_not_changed');
$sBottomJavascript   = <<<EOT
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
                    $("#productCategory_"+dataObj.catalogProductCategoryId+"_online_0").hide(); // hide offline button
                    $("#productCategory_"+dataObj.catalogProductCategoryId+"_online_1").hide(); // hide online button
                    $("#productCategory_"+dataObj.catalogProductCategoryId+"_online_"+dataObj.online).css('display', 'inline-block'); // show button based on online value
                    if(dataObj.online == 0)    
                        showStatusUpdate("$sCategoryOffline");
                    if(dataObj.online == 1)    
                        showStatusUpdate("$sCategoryOnline");
                } else {
                    showStatusUpdate("$sCategoryNotChanged");
                }
            }
        });
        e.preventDefault();
    });
</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);
?>
