<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<h1><?= sysTranslations::get('catalog_color_change_order') ?></h1>
<p>
    <i><?= sysTranslations::get('catalog_color_drag') ?></i>
</p>
<div id="sortableContainer">
    <?php
    # list pages in unordered list

    if (count($aProductColors) > 0) {
        echo '<ol id="productColorSorter" class="sortable cursorMove">';
    }
    $iT = 0;
    foreach ($aProductColors AS $oProductColor) {
        echo '<li data-productcolorid="' . $oProductColor->catalogProductColorId . '">';
        echo '<div>';
        echo _e($oProductColor->getTranslations('auto-admin')->name);
        echo '</div>';
        echo '</li>';
        $iT++;
    }
    if (count($aProductColors) > 0) {
        echo '</ol>';
    }
    ?>
</div>
<form action="" method="POST" onsubmit="return setOrder();" id="changeOrderForm">
    <?= CSRFSynchronizerToken::field() ?>
    <input type="hidden" name="action" value="saveOrder"/>
    <input type="hidden" name="order" id="order" value=""/>
    <input type="submit" value="<?= sysTranslations::get('global_save') ?>"/> <input type="button" value="<?= sysTranslations::get('global_reset') ?>" onclick="window.location.reload();
            return false;"/></a>
</form>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<?php

# add nested sortable javascript initiation code
$sSortableJavascript = <<<EOT
<script>
    $('ol.sortable').sortable({
        axis: 'y',
        forcePlaceholderSize: true,
        helper: 'clone',
        items: 'li',
        cancel: '.not-sortable',
        opacity: .6,
        placeholder: 'placeholder',
        revert: 250,
        tolerance: 'pointer',
        stop: function(){
            makeZebra('#productColorSorter');
        }
        }).disableSelection();

    function setOrder(){
        var order = '';
        $('#productColorSorter li').each(function(index, element){
            order += (order == '' ? '' : '|')+$(element).data('productcolorid');
        });
        $("#order").val(order);
        return true;
    }

    makeZebra('#productColorSorter');
</script>
EOT;
$oPageLayout->addJavascript($sSortableJavascript);
?>