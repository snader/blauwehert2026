<table class="sorted withActionIcons">
    <thead>
    <tr class="topRow">
        <td colspan="5"><h2><?= sysTranslations::get('all_reviews') ?></h2>
            <div class="right">
                <a class="addBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/toevoegen" title="<?= sysTranslations::get('review_add') ?>" alt="<?= sysTranslations::get('review_add') ?>"><?= sysTranslations::get(
                        'review_add'
                    ) ?></a><br/>
                <a class="changeOrderBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/volgorde-wijzigen&lang=1" title="<?= sysTranslations::get('global_change_order') ?>"
                   alt="<?= sysTranslations::get('global_change_order') ?>"><?= sysTranslations::get('global_change_order') ?></a>
            </div>
        </td>
    </tr>
    <tr>
        <th class="{sorter:false} nonSorted onlineOffline"></th>
        <th><?= sysTranslations::get('review_rating') ?></th>
        <th><?= sysTranslations::get('global_title') ?></th>
        <th><?= sysTranslations::get('review_author') ?></th>
        <th class="{sorter:false} nonSorted" style="width: 60px;">&nbsp;</th>
    </tr>
    </thead>
    <tbody id="tableContents">
    <?php

    foreach ($aAllReviews AS $oReview) {
        echo '<tr data-id="' . $oReview->reviewId . '" class="movable">';
        echo '<td>';
        # online offline button
        echo '<a id="review_' . $oReview->reviewId . '_online_1" title="' . sysTranslations::get('review_set_offline') . '" class="action_icon ' . ($oReview->online ? '' : 'hide') . ' online_icon" href="' . ADMIN_FOLDER . '/' . http_get(
                'controller'
            ) . '/ajax-setOnline/' . $oReview->reviewId . '/?online=0&'. CSRFSynchronizerToken::query() .'"></a>';
        echo '<a id="review_' . $oReview->reviewId . '_online_0" title="' . sysTranslations::get('review_set_online') . '" class="action_icon ' . ($oReview->online ? 'hide' : '') . ' offline_icon" href="' . ADMIN_FOLDER . '/' . http_get(
                'controller'
            ) . '/ajax-setOnline/' . $oReview->reviewId . '/?online=1&'. CSRFSynchronizerToken::query() .'"></a>';
        echo '</td>';
        echo '<td>' . $oReview->rating . '</td>';
        echo '<td>' . $oReview->title . '</td>';
        echo '<td>' . $oReview->author . '</td>';
        echo '<td>';
        echo '<a class="action_icon edit_icon" title="' . sysTranslations::get('review_edit') . '" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oReview->reviewId . '"></a>';
        echo '<a class="action_icon delete_icon" title="' . sysTranslations::get('review_delete') . '" onclick="return confirmChoice(\'' . $oReview->getName() . '\');" href="' . ADMIN_FOLDER . '/' . http_get(
                'controller'
            ) . '/verwijderen/' . $oReview->reviewId . '?'. CSRFSynchronizerToken::query() .'"></a>';
        echo '</td>';
        echo '</tr>';
    }
    if (empty($aAllReviews)) {
        echo '<tr><td colspan="5"><i>' . sysTranslations::get('review_no_reviews') . '</i></td></tr>';
    }
    ?>
    </tbody>
</table>

<?php

# create necessary javascript
$sController            = http_get('controller');
$reviewOnline           = sysTranslations::get('review_online');
$reviewOffline          = sysTranslations::get('review_offline');
$reviewStatusNotChanged = sysTranslations::get('review_status_not_changed');
$sBottomJavascript      = <<<EOT
<script type="text/javascript">
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
                    $("#review_"+dataObj.reviewId+"_online_0").hide(); // hide offline button
                    $("#review_"+dataObj.reviewId+"_online_1").hide(); // hide online button
                    $("#review_"+dataObj.reviewId+"_online_"+dataObj.online).css('display', 'inline-block'); // show button based on online value
                    if(dataObj.online == 0)    
                        showStatusUpdate("$reviewOffline");
                    if(dataObj.online == 1)    
                        showStatusUpdate("$reviewOnline");                            
                } else {
                    showStatusUpdate("$reviewStatusNotChanged");
                }
            }
        });
        e.preventDefault();
    });
</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);
?>