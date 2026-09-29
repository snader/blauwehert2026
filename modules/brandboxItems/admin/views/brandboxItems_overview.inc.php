<table class="sorted withActionIcons">
    <thead>
    <tr class="topRow">
        <td colspan="6">
            <?php include_once getAdminSnippet('localeSelect'); ?>
        </td>
    </tr>
    <tr class="topRow">
        <td colspan="3"><h2><?= sysTranslations::get('brandbox_all_items') ?></h2>
            <div class="right">
                <a class="addBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/toevoegen" title="<?= sysTranslations::get('brandbox_add') ?>" alt="<?= sysTranslations::get('brandbox_add') ?>"><?= sysTranslations::get(
                        'brandbox_add'
                    ) ?></a><br/>
                <a class="changeOrderBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/volgorde-wijzigen" title="<?= sysTranslations::get('global_change_order') ?>"
                   alt="<?= sysTranslations::get('global_change_order') ?>"><?= sysTranslations::get('global_change_order') ?></a>
            </div>
        </td>
    </tr>
    <tr>
        <th class="{sorter:false} nonSorted onlineOffline"></th>
        <th><?= sysTranslations::get('global_name') ?></th>
        <th class="{sorter:false} nonSorted" style="width: 60px;">&nbsp;</th>
    </tr>
    </thead>
    <tbody id="tableContents">
    <?php

    foreach ($aAllBrandboxItems AS $oBrandboxItem) {
        echo '<tr data-id="' . $oBrandboxItem->brandboxItemId . '" class="movable">';
        echo '<td>';
        # online offline button
        echo '<a id="brandboxItem_' . $oBrandboxItem->brandboxItemId . '_online_1" title="' . sysTranslations::get(
                'brandbox_set_offline'
            ) . '" class="action_icon ' . ($oBrandboxItem->online ? '' : 'hide') . ' online_icon" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/ajax-setOnline/' . $oBrandboxItem->brandboxItemId . '/?online=0&'. CSRFSynchronizerToken::query() .'"></a>';
        echo '<a id="brandboxItem_' . $oBrandboxItem->brandboxItemId . '_online_0" title="' . sysTranslations::get(
                'brandbox_set_online'
            ) . '" class="action_icon ' . ($oBrandboxItem->online ? 'hide' : '') . ' offline_icon" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/ajax-setOnline/' . $oBrandboxItem->brandboxItemId . '/?online=1&'. CSRFSynchronizerToken::query() .'"></a>';
        echo '</td>';
        echo '<td>' . $oBrandboxItem->name . '</td>';
        echo '<td>';
        echo '<a class="action_icon edit_icon" title="' . sysTranslations::get('brandbox_edit') . '" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oBrandboxItem->brandboxItemId . '"></a>';
        echo '<a class="action_icon delete_icon" title="' . sysTranslations::get('brandbox_delete') . '" onclick="return confirmChoice(\'' . $oBrandboxItem->getName() . '\');" href="' . ADMIN_FOLDER . '/' . http_get(
                'controller'
            ) . '/verwijderen/' . $oBrandboxItem->brandboxItemId . '?'. CSRFSynchronizerToken::query() .'"></a>';
        echo '</td>';
        echo '</tr>';
    }
    if (empty($aAllBrandboxItems)) {
        echo '<tr><td colspan="3"><i>' . sysTranslations::get('brandbox_no_items') . '</i></td></tr>';
    }
    ?>
    </tbody>
</table>

<?php

# create necessary javascript
$sController              = http_get('controller');
$brandboxOnline           = sysTranslations::get('brandbox_online');
$brandboxOffline          = sysTranslations::get('brandbox_offline');
$brandboxStatusNotChanged = sysTranslations::get('brandbox_status_not_changed');
$sBottomJavascript        = <<<EOT
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
                    $("#brandboxItem_"+dataObj.brandboxItemId+"_online_0").hide(); // hide offline button
                    $("#brandboxItem_"+dataObj.brandboxItemId+"_online_1").hide(); // hide online button
                    $("#brandboxItem_"+dataObj.brandboxItemId+"_online_"+dataObj.online).css('display', 'inline-block'); // show button based on online value
                    if(dataObj.online == 0)    
                        showStatusUpdate("$brandboxOffline");
                    if(dataObj.online == 1)    
                        showStatusUpdate("$brandboxOnline");                            
                } else {
                    showStatusUpdate("$brandboxStatusNotChanged");
                }
            }
        });
        e.preventDefault();
    });
</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);
?>