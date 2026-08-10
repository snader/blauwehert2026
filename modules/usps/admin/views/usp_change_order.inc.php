<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= Request::getControllerSegment() ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<h1><?= sysTranslations::get('usp_change_order') ?></h1>
<p>
    <i><?= sysTranslations::get('usp_drag') ?></i>
</p>
<div id="sortableContainer">
    <?php
    # list pages in unordered list

    if (count($aAllUsps) > 0) { ?>
    <ol id="cardSorter" class="sortable cursorMove">
        <?php
        }
        $iT = 0;
        foreach ($aAllUsps AS $oUsp) { ?>
            <li data-cardid="<?= $oUsp->uspId ?>">
                <div>
                    <?= $oUsp->name ?>
                </div>
            </li>
            <?php

            $iT++;
        }
        if (count($aAllUsps) > 0) { ?>
    </ol>
<?php } ?>
</div>
<form action="" method="POST" onsubmit="return setOrder();" id="changeOrderForm">
    <?= CSRFSynchronizerToken::field() ?>
    <input type="hidden" name="action" value="saveOrder"/>
    <input type="hidden" name="order" id="order" value=""/>
    <input type="submit" value="<?= sysTranslations::get('global_save') ?>"/> <input type="button" value="<?= sysTranslations::get('global_reset') ?>" onclick="window.location.reload(); return false;"/></a>
</form>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= Request::getControllerSegment() ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
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
            makeZebra('#cardSorter');
        }
        }).disableSelection();

    function setOrder(){
        var order = '';
        $('#cardSorter li').each(function(index, element){
            order += (order == '' ? '' : '|')+$(element).data('cardid');
        });
        $("#order").val(order);
        return true;
    }

    makeZebra('#cardSorter');
</script>
EOT;
$oPageLayout->addJavascript($sSortableJavascript);
?>
