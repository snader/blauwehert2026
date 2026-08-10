<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<h1><?= sysTranslations::get('review_change_order') ?></h1>
<p>
    <i><?= sysTranslations::get('review_drag') ?></i>
</p>
<div id="sortableContainer">
    <?php
    # list pages in unordered list

    if (count($aAllReviews) > 0) {
        echo '<ol id="reviewSorter" class="sortable cursorMove">';
    }
    $iT = 0;
    foreach ($aAllReviews AS $oReview) {
        echo '<li data-reviewid="' . $oReview->reviewId . '">';
        echo '<div>';
        echo $oReview->title;
        echo '</div>';
        echo '</li>';
        $iT++;
    }
    if (count($aAllReviews) > 0) {
        echo '</ol>';
    }
    ?>
</div>
<form action="" method="POST" onsubmit="return setOrder();" id="changeOrderForm">
    <?= CSRFSynchronizerToken::field() ?>
    <input type="hidden" name="action" value="saveOrder"/>
    <input type="hidden" name="order" id="order" value=""/>
    <input type="submit" value="<?= sysTranslations::get('global_save') ?>"/> <input type="button" value="<?= sysTranslations::get('global_reset') ?>" onclick="window.location.reload(); return false;"/></a>
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
            makeZebra('#reviewSorter');
        }
        }).disableSelection();

    function setOrder(){
        var order = '';
        $('#reviewSorter li').each(function(index, element){
            order += (order == '' ? '' : '|')+$(element).data('reviewid');
        });
        $("#order").val(order);
        return true;
    }

    makeZebra('#reviewSorter');
</script>
EOT;
$oPageLayout->addJavascript($sSortableJavascript);
?>
