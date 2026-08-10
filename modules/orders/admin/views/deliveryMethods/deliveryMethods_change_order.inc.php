<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<h1><?= sysTranslations::get('order_delivery_method_change_order') ?></h1>
<p>
    <i><?= sysTranslations::get('order_delivery_method_drag') ?></i>
</p>
<div id="sortableContainer">
    <?php
    # list pages in unordered list

    if (count($aDeliveryMethods) > 0) {
        echo '<ol id="deliveryMethodSorter" class="sortable cursorMove">';
    }
    $iT = 0;
    foreach ($aDeliveryMethods AS $oDeliveryMethod) {
        echo '<li data-deliverymethodid="' . $oDeliveryMethod->deliveryMethodId . '">';
        echo '<div>';
        echo _e($oDeliveryMethod->getTranslations('auto-admin')->name);
        echo '</div>';
        echo '</li>';
        $iT++;
    }
    if (count($aDeliveryMethods) > 0) {
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
            makeZebra('#deliveryMethodSorter');
        }
        }).disableSelection();

    function setOrder(){
        var order = '';
        $('#deliveryMethodSorter li').each(function(index, element){
            order += (order == '' ? '' : '|')+$(element).data('deliverymethodid');
        });
        $("#order").val(order);
        return true;
    }

    makeZebra('#deliveryMethodSorter');
</script>
EOT;
$oPageLayout->addJavascript($sSortableJavascript);
?>