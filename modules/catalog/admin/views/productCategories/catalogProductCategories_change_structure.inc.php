<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<h1><?= sysTranslations::get('catalog_category_change_structure') ?></h1>
<p>
    <i><?= sysTranslations::get('catalog_categories_drag') ?></i>
</p>
<div id="nestedSortableContainer">
    <?php
    # list categories in unordered list

    function makeListTree(array $aProductCategories = [], $iLevel, $iMaxLevels)
    {
        if (count($aProductCategories) > 0 && $iLevel == 0) {
            echo '<ol class="nestedSortable cursorMove level' . $iLevel . '">';
        } elseif (count($aProductCategories) > 0) {
            echo '<ol class="level' . $iLevel . '">';
        }

        $iT = 0;
        foreach ($aProductCategories AS $oProductCategory) {
            $sLiClass = '';
            $sLocked  = '';

            # main categories are locked by default
            if ($iLevel == 0) {
                //$sLiClass = 'locked';
                //$sLocked = ' (Vergrendeld)';
            }

            // lock parent if set in database
            $sPreventLevelChanging = '';
            if ($oProductCategory->lockParent()) {
                $oParent = $oProductCategory->getParent();
                if ($oParent) {
                    $sParentProductCategoryId = $oParent->catalogProductCategoryId;
                } else {
                    $sParentProductCategoryId = 'root';
                }
                $sPreventLevelChanging = 'data-parent="' . $sParentProductCategoryId . '"'; //prevent root from nesting
            }

            echo '<li class="' . $sLiClass . '" id="productCategory_' . $oProductCategory->catalogProductCategoryId . '" ' . $sPreventLevelChanging . '>';
            echo '<div class="no-action-icons ' . ($iT % 2 == 0 ? 'even' : 'odd') . '">';
            echo _e($oProductCategory->getTranslations('auto-admin')->name) . '<span class="brackedComment"> ' . $sLocked . '</span>';
            echo '</div>';

            makeListTree($oProductCategory->getSubCategories('all'), $iLevel + 1, $iMaxLevels); //call function recursive
            echo '</li>';
            $iT++;
        }
        if (count($aProductCategories) > 0) {
            echo '</ol>';
        }
    }

    # start recursive displaying categories
    makeListTree($aLevel1ProductCategories, 0, $iMaxLevels);
    ?>
</div>
<form action="" method="POST" onsubmit="return setProductCategoryStructure();" id="productCategoryStructureForm">
    <?= CSRFSynchronizerToken::field() ?>
    <input type="hidden" name="action" value="saveProductCategoryStructure"/>
    <input type="hidden" name="productCategoryStructure" id="productCategoryStructure" value=""/>
    <input type="submit" value="<?= sysTranslations::get('global_save') ?>"/> <input type="button" value="<?= sysTranslations::get('global_reset') ?>" onclick="window.location.reload(); return false;"/>
</form>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<?php

# add nested sortable javascript source code
$oPageLayout->addJavascript('<script src="' . getAdminPath('plugins/jquery-ui-nestedSortable.min.js') . '"></script>');

# add nested sortable javascript initiation code
$sNestedSortableJavascript = <<<EOT
<script>
    $('ol.nestedSortable').nestedSortable({
        disableNesting: 'no-nesting',
        errorClass: 'ui-sortable-error',
        forcePlaceholderSize: true,
        handle: 'div',
        helper: 'clone',
        listType: 'ol',
        items: 'li:not(.locked)',
        cancel: '.not-sortable',
        maxLevels: $iMaxLevels,
        opacity: .6,
        placeholder: 'placeholder',
        revert: 250,
        tabSize: 25,
        tolerance: 'pointer',
        toleranceElement: '> div',
        rootClass: 'root-item',
        rootID: 'root',
        isAllowed: function(item, parent){
                return true;
            }
        }).disableSelection();        

    function setProductCategoryStructure(){
        serialized = $('ol.nestedSortable').nestedSortable('serialize');
        $("#productCategoryStructure").val(serialized);
        return true;
    }
</script>
EOT;
$oPageLayout->addJavascript($sNestedSortableJavascript);
?>