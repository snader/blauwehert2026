<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>">Terug naar overzicht</a><span class="backBtnInfo"> (zonder opslaan)</span>
</div>
<h1>Kleurenen volgorde wijzigen</h1>
<p>
    <i>Sleep de maten om de volgorde te veranderen</i>
</p>
<div id="sortableContainer">
    <?php
    # list pages in unordered list

    if (count($aProductSizes) > 0) {
        echo '<ol id="productSizeSorter" class="sortable cursorMove">';
    }
    $iT = 0;
    foreach ($aProductSizes AS $oProductSize) {
        $aTranslations = $oProductSize->getTranslations();
        echo '<li data-productsizeid="' . $oProductSize->catalogProductSizeId . '">';
        echo '<div>';
        echo _e($oProductSize->getTranslations('auto-admin')->name);
        echo '</div>';
        echo '</li>';
        $iT++;
    }
    if (count($aProductSizes) > 0) {
        echo '</ol>';
    }
    ?>
</div>
<form action="" method="POST" onsubmit="return setOrder();" id="changeOrderForm">
    <?= CSRFSynchronizerToken::field() ?>
    <input type="hidden" name="action" value="saveOrder"/>
    <input type="hidden" name="order" id="order" value=""/>
    <input type="submit" value="Opslaan"/> <input type="button" value="Reset" onclick="window.location.reload();
            return false;"/></a>
</form>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>">Terug naar overzicht</a><span class="backBtnInfo"> (zonder opslaan)</span>
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
            makeZebra('#productSizeSorter');
        }
        }).disableSelection();

    function setOrder(){
        var order = '';
        $('#productSizeSorter li').each(function(index, element){
            order += (order == '' ? '' : '|')+$(element).data('productsizeid');
        });
        $("#order").val(order);
        return true;
    }

    makeZebra('#productSizeSorter');
</script>
EOT;
$oPageLayout->addJavascript($sSortableJavascript);
?>